<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserProfile extends Model
{
    use HasFactory;


    protected string $settings = 'settings';

    public function storeSettings($store): int
    {
        return DB::table($this->settings)->insertGetId($store);
    }


//    IF preference exists
    protected string $set_preferences = 'set_preferences';


    public function preferencesExist($userId): bool
    {
        return DB::table($this->set_preferences)->where('user_id', $userId)->exists();
    }

    public function storePreferences($store): int
    {
        return DB::table($this->set_preferences)->insertGetId($store);
    }
}
