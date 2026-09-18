<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_details', function (Blueprint $table) {
            if (!Schema::hasColumn('user_details', 'elder_married_brother')) {
                $table->string('elder_married_brother', 50)->nullable()->after('younger_brother');
            }
            if (!Schema::hasColumn('user_details', 'younger_married_brother')) {
                $table->string('younger_married_brother', 50)->nullable()->after('elder_married_brother');
            }
            if (!Schema::hasColumn('user_details', 'elder_married_sister')) {
                $table->string('elder_married_sister', 50)->nullable()->after('younger_sister');
            }
            if (!Schema::hasColumn('user_details', 'younger_married_sister')) {
                $table->string('younger_married_sister', 50)->nullable()->after('elder_married_sister');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_details', function (Blueprint $table) {
            $table->dropColumn([
                'elder_married_brother',
                'younger_married_brother',
                'elder_married_sister',
                'younger_married_sister',
            ]);
        });
    }
};
