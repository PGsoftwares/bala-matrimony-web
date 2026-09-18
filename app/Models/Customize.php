<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Customize extends Model
{
    use HasFactory;

    protected string $tableApprovals = 'table_approvals';

    public function storeTableApproval($store): int
    {
        return DB::table($this->tableApprovals)->insertGetId($store);
    }

    protected string $languages = 'languages';

    public function storeLanguages($store): int
    {
        return DB::table($this->languages)->insertGetId($store);
    }

    protected string $maritalStatus = 'marital_status';

    public function storeMaritalStatus($store): int
    {
        return DB::table($this->maritalStatus)->insertGetId($store);
    }

    protected string $skinTone = 'skin_tone';
    public function storeSkinTone($store): int
    {
        return DB::table($this->skinTone)->insertGetId($store);
    }

    protected string $height = 'height';
    public function storeHeight($store): int
    {
        return DB::table($this->height)->insertGetId($store);
    }


    protected string $bodyType = 'body_type';
    public function storeBodyType($store): int
    {
        return DB::table($this->bodyType)->insertGetId($store);
    }

    protected string $drinkingHabits = 'drinking_habits';
    public function storeDrinkingHabits($store): int
    {
        return DB::table($this->drinkingHabits)->insertGetId($store);
    }

    protected string $employedIn = 'employed_in';
    public function storeEmployedIn($store): int
    {
        return DB::table($this->employedIn)->insertGetId($store);
    }

    protected string $salary = 'salary';
    public function storeSalary($store): int
    {
        return DB::table($this->salary)->insertGetId($store);
    }
    protected string $smokingHabits = 'smoking_habits';
    public function storeSmokingHabits($store): int
    {
        return DB::table($this->smokingHabits)->insertGetId($store);
    }

    protected string $eatingHabits = 'eating_habits';
    public function storeEatingHabits($store): int
    {
        return DB::table($this->eatingHabits)->insertGetId($store);
    }

    protected string $castes = 'castes';

    public function storeCastes($store): int
    {
        return DB::table($this->castes)->insertGetId($store);
    }

    protected string $subCastes = 'sub_castes';

    public function storeSubCastes($store): int
    {
        return DB::table($this->subCastes)->insertGetId($store);
    }

    protected string $kulam = 'kulam';

    public function storeKulam($store): int
    {
        return DB::table($this->kulam)->insertGetId($store);
    }

    protected string $gothram = 'gothram';

    public function storeGothram($store): int
    {
        return DB::table($this->gothram)->insertGetId($store);
    }

    protected string $stars = 'stars';

    public function storeStars($store): int
    {
        return DB::table($this->stars)->insertGetId($store);
    }

    protected string $dosham = 'dosham';

    public function storeDosham($store): int
    {
        return DB::table($this->dosham)->insertGetId($store);
    }

    protected string $rashi = 'rashi';

    public function storeRashi($store): int
    {
        return DB::table($this->rashi)->insertGetId($store);
    }

    protected string $lagnam = 'lagnam';
    public function storeLagnam($store): int
    {
        return DB::table($this->lagnam)->insertGetId($store);
    }

    protected string $padam = 'padam';

    public function storePadam($store): int
    {
        return DB::table($this->padam)->insertGetId($store);
    }

    protected string $religion = 'religion';

    public function storeReligion($store): int
    {
        return DB::table($this->religion)->insertGetId($store);
    }

    protected string $family_god = 'family_god';

    public function storeFamilyGod($store): int
    {
        return  DB::table($this->family_god)->insertGetId($store);
    }

    protected string $education = 'education';

    public function storeEducation($store): int
    {
        return DB::table($this->education)->insertGetId($store);
    }

    protected string $occupation = 'occupation';
    public function storeOccupation($store): int
    {
        return DB::table($this->occupation)->insertGetId($store);
    }



}
