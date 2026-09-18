<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use ZipArchive;

class BackupController extends Controller
{
    public function createBackup(): BinaryFileResponse|RedirectResponse
    {
        $backupDir = storage_path('app/backups');
        if (!File::exists($backupDir)) {
            File::makeDirectory($backupDir, 0755, true);
        }

        $timestamp = now()->format('Ymd_His');
        $backupFileName = "backup_{$timestamp}.sql";
        $backupFilePath = "{$backupDir}/{$backupFileName}";

        $databaseName = config('database.connections.mysql.database', env('DB_DATABASE'));
        $username = config('database.connections.mysql.username', env('DB_USERNAME'));
        $password = config('database.connections.mysql.password', env('DB_PASSWORD'));
        $host = config('database.connections.mysql.host', env('DB_HOST', '127.0.0.1'));
        $port = config('database.connections.mysql.port', env('DB_PORT', '3306'));

        // Try mysqldump executable first if exec() is enabled
        $mysqldumpExec = null;
        if (function_exists('exec')) {
            $possiblePaths = [
                'mysqldump',
                'C:\\xampp\\mysql\\bin\\mysqldump.exe',
                'D:\\xampp\\mysql\\bin\\mysqldump.exe',
                '/usr/bin/mysqldump',
                '/usr/local/bin/mysqldump',
            ];

            foreach ($possiblePaths as $path) {
                if ($path === 'mysqldump' || file_exists($path)) {
                    $mysqldumpExec = $path;
                    break;
                }
            }
        }

        $success = false;
        if ($mysqldumpExec && function_exists('exec')) {
            $passwordPart = !empty($password) ? "--password=" . escapeshellarg($password) : "";
            $command = escapeshellcmd($mysqldumpExec) . " --host=" . escapeshellarg($host) . " --port=" . escapeshellarg($port) . " --user=" . escapeshellarg($username) . " {$passwordPart} --databases " . escapeshellarg($databaseName) . " --skip-comments --skip-triggers > " . escapeshellarg($backupFilePath) . " 2>&1";
            @\exec($command);

            if (file_exists($backupFilePath) && filesize($backupFilePath) > 100) {
                $firstLine = fgets(fopen($backupFilePath, 'r'));
                if (!str_contains(strtolower($firstLine), 'error') && !str_contains(strtolower($firstLine), 'access denied')) {
                    $success = true;
                }
            }
        }

        // If mysqldump failed or produced empty file, fallback to pure PHP PDO export
        if (!$success) {
            $success = $this->exportDatabaseUsingPdo($backupFilePath);
        }

        if ($success && file_exists($backupFilePath)) {
            return response()->download($backupFilePath, $backupFileName, [
                'Content-Type' => 'application/sql',
            ])->deleteFileAfterSend(true);
        }

        return redirect()->back()->with('error', 'Backup failed to generate.');
    }

    private function exportDatabaseUsingPdo(string $filePath): bool
    {
        try {
            $pdo = DB::connection()->getPdo();
            $tables = $pdo->query('SHOW TABLES')->fetchAll(\PDO::FETCH_COLUMN);

            $handle = fopen($filePath, 'w');
            if (!$handle) {
                return false;
            }

            fwrite($handle, "-- BMB Database Backup\n");
            fwrite($handle, "-- Generated: " . date('Y-m-d H:i:s') . "\n");
            fwrite($handle, "SET FOREIGN_KEY_CHECKS=0;\n\n");

            foreach ($tables as $table) {
                // Get CREATE TABLE
                $createTableStmt = $pdo->query("SHOW CREATE TABLE `{$table}`")->fetch(\PDO::FETCH_ASSOC);
                $createTableSql = $createTableStmt['Create Table'] ?? null;
                if ($createTableSql) {
                    fwrite($handle, "DROP TABLE IF EXISTS `{$table}`;\n");
                    fwrite($handle, $createTableSql . ";\n\n");
                }

                // Dump table rows
                $rows = $pdo->query("SELECT * FROM `{$table}`");
                while ($row = $rows->fetch(\PDO::FETCH_ASSOC)) {
                    $columns = array_map(function ($col) {
                        return "`" . str_replace("`", "``", $col) . "`";
                    }, array_keys($row));

                    $values = array_map(function ($val) use ($pdo) {
                        if ($val === null) {
                            return 'NULL';
                        }
                        return $pdo->quote((string)$val);
                    }, array_values($row));

                    $insertSql = "INSERT INTO `{$table}` (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $values) . ");\n";
                    fwrite($handle, $insertSql);
                }
                fwrite($handle, "\n");
            }

            fwrite($handle, "SET FOREIGN_KEY_CHECKS=1;\n");
            fclose($handle);

            return file_exists($filePath) && filesize($filePath) > 0;
        } catch (\Throwable $e) {
            \Log::error('PDO Backup Error: ' . $e->getMessage());
            return false;
        }
    }

    public function backupImages(): BinaryFileResponse|RedirectResponse
    {
        return $this->createAndDownloadZip('Profile Image', 'profile_images.zip');
    }

    public function horoscopeImages(): BinaryFileResponse|RedirectResponse
    {
        return $this->createAndDownloadZip('Horoscope Image', 'horoscope_images.zip');
    }

    private function createAndDownloadZip(string $folderName, string $zipFileName): BinaryFileResponse|RedirectResponse
    {
        $folderPath = public_path($folderName);

        // Ensure the folder exists
        if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, 0755, true, true);
        }

        // Store temp zip in storage/app/backups
        $backupDir = storage_path('app/backups');
        if (!File::exists($backupDir)) {
            File::makeDirectory($backupDir, 0755, true, true);
        }
        $zipFilePath = $backupDir . DIRECTORY_SEPARATOR . $zipFileName;
        if (File::exists($zipFilePath)) {
            @unlink($zipFilePath);
        }

        $success = false;

        // Method 1: PHP ZipArchive extension
        if (class_exists(\ZipArchive::class)) {
            try {
                $zip = new \ZipArchive();
                if ($zip->open($zipFilePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
                    $files = File::allFiles($folderPath);
                    if (empty($files)) {
                        $zip->addFromString('.empty', 'Folder is empty.');
                    } else {
                        foreach ($files as $file) {
                            $relativePath = $file->getRelativePathname();
                            $zip->addFile($file->getRealPath(), $relativePath);
                        }
                    }
                    $zip->close();
                    $success = file_exists($zipFilePath) && filesize($zipFilePath) > 0;
                }
            } catch (\Throwable $e) {
                \Log::warning('ZipArchive backup failed: ' . $e->getMessage());
            }
        }

        // Method 2: Fallback using OS tools (tar.exe, powershell Compress-Archive, or zip)
        if (!$success && function_exists('exec')) {
            $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
            if ($isWindows) {
                // Try tar -a (built into Windows 10/11)
                $tarCmd = sprintf('tar -a -c -f "%s" -C "%s" . 2>&1', $zipFilePath, $folderPath);
                @\exec($tarCmd, $output, $returnCode);
                if ($returnCode === 0 && file_exists($zipFilePath) && filesize($zipFilePath) > 0) {
                    $success = true;
                } else {
                    // Try PowerShell Compress-Archive
                    $psCmd = sprintf(
                        'powershell -NoProfile -Command "Compress-Archive -Path \'%s\\*\' -DestinationPath \'%s\' -Force"',
                        rtrim($folderPath, '\\/'),
                        $zipFilePath
                    );
                    @\exec($psCmd, $output, $returnCode);
                    if ($returnCode === 0 && file_exists($zipFilePath) && filesize($zipFilePath) > 0) {
                        $success = true;
                    }
                }
            } else {
                // Linux: zip command
                $zipCmd = sprintf('cd "%s" && zip -r "%s" . 2>&1', $folderPath, $zipFilePath);
                @\exec($zipCmd, $output, $returnCode);
                if ($returnCode === 0 && file_exists($zipFilePath) && filesize($zipFilePath) > 0) {
                    $success = true;
                }
            }
        }

        if (!$success || !file_exists($zipFilePath)) {
            return back()->with('error', "Failed to create {$zipFileName} backup.");
        }

        return response()->download($zipFilePath, $zipFileName)->deleteFileAfterSend(true);
    }

    public function Export(Request $request): View
    {
        $from = $request->input('from_date');
        $to = $request->input('to_date');
        $gender = $request->input('gender');

        $users = collect();

        if ($from || $to || $gender) {
            $query = DB::table('users')
                ->join('user_details', 'users.id', '=', 'user_details.user_id')
                ->select('users.id', 'users.name', 'users.email', 'users.mobile', 'user_details.*' );

            if ($from && $to) {
                $query->whereBetween('user_details.created_at', [$from, $to]);
            }

            if (!empty($gender)) {
                $query->where('user_details.gender', $gender);
            }

            $users = $query->get();
        }

        return view('admin.securedSettings.export', compact('users', 'from', 'to', 'gender'));
    }


}
