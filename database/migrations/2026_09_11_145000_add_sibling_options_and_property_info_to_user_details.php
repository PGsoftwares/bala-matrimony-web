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
            if (!Schema::hasColumn('user_details', 'elder_brother')) {
                $table->string('elder_brother', 50)->nullable()->after('no_of_brother_married');
            }
            if (!Schema::hasColumn('user_details', 'younger_brother')) {
                $table->string('younger_brother', 50)->nullable()->after('elder_brother');
            }
            if (!Schema::hasColumn('user_details', 'elder_sister')) {
                $table->string('elder_sister', 50)->nullable()->after('no_of_sister_married');
            }
            if (!Schema::hasColumn('user_details', 'younger_sister')) {
                $table->string('younger_sister', 50)->nullable()->after('elder_sister');
            }
            if (!Schema::hasColumn('user_details', 'property_info')) {
                $table->text('property_info')->nullable()->after('property_details');
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
                'elder_brother',
                'younger_brother',
                'elder_sister',
                'younger_sister',
                'property_info',
            ]);
        });
    }
};
