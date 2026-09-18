<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helpers\DataController;
use App\Http\Controllers\Helpers\DataSharedController;
use App\Http\Controllers\WebController;
use App\Models\UserProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SettingsController extends Controller
{

    protected  $webController;

    public function __construct(WebController $webController)
    {
        $this->webController = $webController;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $userId = Auth::id();
        $userAndUserDetails = DataController::getUserDetails($userId);

        $setting = DB::table('settings')->where('user_id', $userId)->first();

        // If no settings found, initialize empty settings object
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

        return view('web.settings', compact('userAndUserDetails', 'setting', 'metaTags', 'db'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $userId = Auth::id();
        $insertData = [
            'user_id' => $userId,
            'name_visibility' => $request->input('name_visibility'),
            'date_of_birth_visibility' => $request->input('date_of_birth_visibility'),
            'profile_picture_visibility' => $request->input('profile_picture_visibility'),
            'horoscope_picture_visibility' => $request->input('horoscope_picture_visibility'),
            'mobile_number_visibility' => $request->input('mobile_number_visibility'),
            'email_visibility' => $request->input('email_visibility'),
        ];

        $userProfile = new UserProfile();
        $userProfile->storeSettings($insertData);
        return redirect('settings')->with('success', 'Privacy added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $userId): RedirectResponse
    {
        $update = [
            'user_id' => $userId,
            'name_visibility' => $request->input('name_visibility'),
            'date_of_birth_visibility' => $request->input('date_of_birth_visibility'),
            'profile_picture_visibility' => $request->input('profile_picture_visibility'),
            'horoscope_picture_visibility' => $request->input('horoscope_picture_visibility'),
            'mobile_number_visibility' => $request->input('mobile_number_visibility'),
            'email_visibility' => $request->input('email_visibility'),
        ];

        $existingSetting = DB::table('settings')->where('user_id', $userId)->first();

        if ($existingSetting) {
            DB::table('settings')->where('user_id', $userId)->update($update);
        } else {
            DB::table('settings')->insert($update);
        }

        return redirect()->back()->with('success', 'Privacy updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
