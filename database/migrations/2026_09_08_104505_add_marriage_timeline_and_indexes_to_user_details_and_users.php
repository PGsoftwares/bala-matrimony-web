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
            if (!Schema::hasColumn('user_details', 'marriage_timeline')) {
                $table->string('marriage_timeline')->nullable()->after('property_details');
            }
        });

        // Add indexes if they do not exist
        Schema::table('user_details', function (Blueprint $table) {
            $table->index('city', 'idx_user_details_city');
            $table->index('dob', 'idx_user_details_dob');
            $table->index('created_at', 'idx_user_details_created_at');
            $table->index('marital_status', 'idx_user_details_marital_status');
            $table->index('gender', 'idx_user_details_gender');
            $table->index('caste', 'idx_user_details_caste');
            $table->index('religion', 'idx_user_details_religion');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index('email_verified_at', 'idx_users_email_verified_at');
            $table->index('status', 'idx_users_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_details', function (Blueprint $table) {
            if (Schema::hasColumn('user_details', 'marriage_timeline')) {
                $table->dropColumn('marriage_timeline');
            }
            $table->dropIndex('idx_user_details_city');
            $table->dropIndex('idx_user_details_dob');
            $table->dropIndex('idx_user_details_created_at');
            $table->dropIndex('idx_user_details_marital_status');
            $table->dropIndex('idx_user_details_gender');
            $table->dropIndex('idx_user_details_caste');
            $table->dropIndex('idx_user_details_religion');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_email_verified_at');
            $table->dropIndex('idx_users_status');
        });
    }
};
