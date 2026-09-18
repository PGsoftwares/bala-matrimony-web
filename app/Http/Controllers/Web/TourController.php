<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helpers\DataController;
use App\Http\Controllers\Helpers\DataSharedController;
use App\Http\Controllers\Helpers\DropdownController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TourController extends Controller
{
    public function PartnerPreference(): View
    {
        $userId = Auth::id();
        $userAndUserDetails = DataController::getUserDetails($userId);

        $userPackage = DataController::getUserPackageDetails($userId);
        $isPackageValid = $userPackage['is_active'];

        $preference = DB::table('set_preferences')
            ->where('user_id', $userId)
            ->select('set_preferences.*')
            ->first();

        $locationData = DropdownController::getCombinedLocationData($preference);
        $dropdownData = DropdownController::getDropdownData(
            $preference?->qualification, $preference?->education,
            $preference?->occupation_type, $preference?->occupation,
            $preference?->caste, $preference?->sub_caste
        );

        $metaTags = DataSharedController::MetaData('set-preference');
        $db = DataSharedController::getDatabases();

        return view('web.tour.partner-preference', array_merge(
            [
                'userAndUserDetails' => $userAndUserDetails, 'metaTags' => $metaTags, 'preference' => $preference,
                'isPackageValid' => $isPackageValid, 'db' => $db,
                'locationData' => $locationData, 'dropdownData' => $dropdownData
            ]
        ));
    }

    public function PrivacySettings(): View
    {
        $userId = Auth::id();
        $userAndUserDetails = DataController::getUserDetails($userId);
        $setting = DB::table('settings')->where('user_id', $userId)->first();

        if (!$setting) {
            $setting = (object) [
                'user_id' => $userId,
                'name_visibility' => '',
                'date_of_birth_visibility' => '',
                'profile_picture_visibility' => '',
                'horoscope_picture_visibility' => '',
                'mobile_number_visibility' => '',
                'email_visibility' => '',
            ];
        }

        $metaTags = DataSharedController::MetaData('settings');
        $db = DataSharedController::getDatabases();
        return view('web.tour.privacy-settings', compact('userAndUserDetails', 'setting', 'metaTags', 'db'));
    }

    public function PaymentService(): View
    {
        $userId = Auth::id();
        $userAndUserDetails = DataController::getUserDetails($userId);
        $userPackage = DataController::getUserPackageDetails($userId);

        $db = DataSharedController::getDatabases();
        $metaTags = DataSharedController::MetaData('payment-plan');

        return view('web.tour.payment-service', compact('db', 'metaTags', 'userAndUserDetails', 'userPackage'));
    }

    public function TourComplete(Request $request): RedirectResponse
    {
        $userId = $request->get('user_id');
        DB::table('users')->where('id', $userId)->update(['show_tour' => false]);
        return redirect()->route('dashboard');
    }
}
