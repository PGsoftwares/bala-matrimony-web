<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('receipts')) {
            DB::table('receipts')
                ->where(function ($query) {
                    $query->where('month', 0)
                          ->orWhereNull('month')
                          ->orWhereRaw('DATE(expiry_date) = DATE(recharge_date)');
                })
                ->update(['expiry_date' => null]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
