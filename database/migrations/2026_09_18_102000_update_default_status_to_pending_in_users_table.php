<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('status')->default('pending')->change();
        });

        // Update any existing non-admin users that are incomplete or in registration steps to 'pending'
        DB::table('users')
            ->where('role', '!=', 'admin')
            ->where(function ($query) {
                $query->whereNull('register_step')
                      ->orWhere('register_step', '<', 7);
            })
            ->update(['status' => 'pending']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('status')->default('active')->change();
        });
    }
};
