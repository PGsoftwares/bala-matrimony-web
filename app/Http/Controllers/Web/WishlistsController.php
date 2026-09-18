<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Api\ApiHelperController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Helpers\DataController;
use App\Http\Controllers\Helpers\DataSharedController;
use App\Http\Controllers\WebController;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class WishlistsController extends Controller
{

    protected  $webController;

    public function __construct(WebController $webController)
    {
        $this->webController = $webController;
    }

    public function WishLists(): View
    {
        $userId = Auth::id();
        $userAndUserDetails = DataController::getUserDetails($userId);

        $userPackage = DataController::getUserPackageDetails($userId);
        $isPackageValid = $userPackage['is_active'];
        $userPackageValue = $userPackage['package'];

        $wishlistProfiles = DB::table('wishlists')
            ->join('users', 'wishlists.profile_id', '=', 'users.id')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->leftJoin('settings', 'settings.user_id', '=', 'user_details.user_id')
            ->where('wishlists.user_id', $userId)
            ->where('users.status', '=', 'active')
            ->orderBy('wishlists.id', 'desc')
            ->select('users.id as user_id', 'wishlists.profile_id', 'users.*', 'user_details.*', 'settings.*')
            ->get();

        foreach ($wishlistProfiles as $profile) {
            $profile->age = Carbon::parse($profile->dob)->age;
            $profile->profile_image = ApiHelperController::ImageUrl($profile->profile_image, $profile->profile_picture_visibility, null, $profile->gender);
            $profile->name = ApiHelperController::privacyData($profile->name, $profile->name_visibility, null);
        }

        $db = DataSharedController::getDatabases();
        return view('web.wishlists', compact('userAndUserDetails', 'wishlistProfiles', 'isPackageValid','userPackageValue', 'db'));
    }

    public function AddWishlist(Request $request): RedirectResponse
    {
        $userId = $request->input('user_id');
        $profileId = $request->input('profile_id');

        $user = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('users.id', $userId)
            ->first();

        if (!$user) {
            return redirect()->back()->with('error', 'User not found!');
        }

        $profile = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('users.id', $profileId)
            ->first();

        if (!$profile) {
            return redirect()->back()->with('error', 'This profile not found!');
        }

        $existingWishlist = DB::table('wishlists')
            ->where('user_id', $userId)
            ->where('profile_id', $profileId)
            ->first();

        if ($existingWishlist) {
            return redirect()->back()->with('success', 'This profile is already in your wishlist!');
        }

        DB::table('wishlists')->insert([
            'user_id' => $userId,
            'profile_id' => $profileId,
        ]);

        return redirect('wishlists')->with('success', 'Profile added to your wishlist successfully!');

    }

    public function RemoveWishlist(Request $request): RedirectResponse
    {
        $userId = Auth::id();
        $profileId = $request->input('profile_id');

        $existingWishlist = DB::table('wishlists')
            ->where('user_id', $userId)
            ->where('profile_id', $profileId)
            ->first();

        if (!$existingWishlist) {
            return redirect('wishlists')->with('error', 'Profile not found in your wishlist!');
        }

        DB::table('wishlists')
            ->where('user_id', $userId)
            ->where('profile_id', $profileId)
            ->delete();

        return redirect('wishlists')->with('success', 'Profile removed from your wishlist successfully!');
    }
}
