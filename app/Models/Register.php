<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Register extends Model
{
    use HasFactory;


    protected string $user_details = 'user_details';
    public function storeUserDetails($store): int
    {
        return DB::table($this->user_details)->insertGetId($store);
    }
}
