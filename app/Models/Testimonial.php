<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Testimonial extends Model
{
    use HasFactory;

    protected string $happy_stories = 'happy_stories';

    public function storeHappyStories($store): int
    {
        return DB::table($this->happy_stories)->insertGetId($store);
    }

    protected string $highlighted_profiles = 'highlighted_profiles';

    public function storeHighlightedProfiles($store): int
    {
        return DB::table($this->highlighted_profiles)->insertGetId($store);
    }



}
