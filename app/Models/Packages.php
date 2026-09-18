<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Packages extends Model
{
    use HasFactory;

    protected string $packages = 'packages';

    public function storePackages($store): int
    {
        return DB::table($this->packages)->insertGetId($store);
    }

    protected string $packageSettings = 'package_settings';
    public function storePackageSettings($store): int
    {
        return DB::table($this->packageSettings)->insertGetId($store);
    }
}
