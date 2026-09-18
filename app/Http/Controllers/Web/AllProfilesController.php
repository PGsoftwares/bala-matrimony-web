<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Api\ApiHelperController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Helpers\DataController;
use App\Http\Controllers\Helpers\DataSharedController;
use App\Mail\SendInterest;
use App\Mail\TrackProfile;
use App\Models\User;
use App\Notifications\InterestNotification;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class AllProfilesController extends Controller
{
    public function __construct()
    {
        //
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $userId = Auth::id();

        $packageDetails = DataController::getUserPackageDetails($userId);
        $userPackageValue = $packageDetails['package'];
        $isPackageValid = $packageDetails['is_active'];

        $userAndUserDetails = DataController::getUserDetails($userId);
        if (!$userAndUserDetails || !$userAndUserDetails->gender) {
            return view('web.all-profiles', ['message' => 'User details not found.']);
        }

        $oppositeGender = DataController::getOppositeGender($userAndUserDetails->gender);
        [$profiles, $appliedFilters] = DataSharedController::getFilteredProfiles($request, $oppositeGender);

        $metaTags = DataSharedController::MetaData('all-profiles');
        $db = DataSharedController::getDatabases();

        return view('web.all-profiles', array_merge([
            'userAndUserDetails' => $userAndUserDetails,
            'profiles' => $profiles,
            'profilesCount' => $profiles->total(),
            'appliedFilters' => $appliedFilters,
            'metaTags' => $metaTags,
            'userPackageValue' => $userPackageValue,
            'isPackageValid' => $isPackageValid,
            'db' => $db,
        ], $profiles->total() === 0 ? ['message' => 'No profiles found for the selected criteria.'] : []));
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): RedirectResponse|View
    {
        $authUser = Auth::id();
        $userAndUserDetails = DataController::getUserDetails($authUser);

        // Fetch user details and user details
        $profile = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->leftJoin('settings', 'settings.user_id', '=', 'users.id')
            ->where('users.id', $id)
            ->select('users.*', 'user_details.*', 'settings.profile_picture_visibility', 'settings.name_visibility', 'settings.date_of_birth_visibility', 'settings.horoscope_picture_visibility', 'settings.email_visibility', 'settings.mobile_number_visibility')
            ->first();

        // Check if user exists
        if (!$profile) {
            return redirect()->back()->with('error', 'User not found.');
        }
        $oppositeGender = DataController::getOppositeGender($profile->gender);

        // Calculate for the profile
        $profile->age = Carbon::parse($profile->dob)->age;
        $profile->profile_image = ApiHelperController::ImageUrl($profile->profile_image, $profile->profile_picture_visibility, null, $profile->gender);
        $profile->horoscope_image = ApiHelperController::HoroscopeImageUrl($profile->horoscope_image, $profile->profile_picture_visibility, null);
        $profile->name = ApiHelperController::privacyData($profile->name, $profile->name_visibility, null);
        $profile->email = ApiHelperController::privacyData($profile->email, $profile->email_visibility, null);
        $profile->mobile = ApiHelperController::privacyData($profile->mobile, $profile->mobile_number_visibility, null);
        $rawDob = $profile->dob;
        $visibleDob = ApiHelperController::privacyData($rawDob, $profile->date_of_birth_visibility, null);
        if (strtotime($visibleDob)) {
            $profile->date_of_birth = Carbon::parse($visibleDob)->format('d-m-Y');
        } else {
            $profile->date_of_birth = $visibleDob;
        }

        $relatedProfiles = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('city', $profile->city)
            ->where('users.id', '!=', $id)
            ->where('user_details.gender',  $oppositeGender)
            ->select('users.*', 'user_details.*')
            ->take(5)->get();

        // Calculate age for each related profile
        foreach ($relatedProfiles as $relatedProfile) {
            $relatedProfile->age = Carbon::parse($relatedProfile->dob)->age;
        }

        $receipts = DataController::otherProfileReceipts($id);
        $metaTags = DataSharedController::MetaData('profile-details');
        $db = DataSharedController::getDatabases();

        /* ---Matched‑preferences logic---*/
        $matchedPreferences = [];
        $preference = DB::table('set_preferences')
            ->where('user_id', $profile->user_id)
            ->first();

        if (!$preference) {
            $totalPreferences = 0;
            $matchedCount     = 0;
        } else {
            $candidate = $userAndUserDetails;
            /* --------- field‑by‑field matching --------- */
            $fieldsToCheck = [
                'mother_tongue','marital_status','drinking_habit','smoking_habit',
                'eating_habit','physical_status','religion','caste',
                'qualification','education','occupation_type','occupation',
                'employed_in','rashi','nakshatra','lagnam','dosham',
                'country','state','city',
            ];

            foreach ($fieldsToCheck as $field) {
                $want   = $preference->$field ?? null;
                $has    = $candidate->$field ?? null;

                if (empty($want)) {
                    $matchedPreferences[] = [
                        'preference_name' => $field,
                        'value'           => 'Any',
                        'match'           => true,
                    ];
                    continue;
                }

                if (is_null($has) || $has === '') {
                    $matchedPreferences[] = [
                        'preference_name' => $field,
                        'value'           => $want,
                        'match'           => false,
                    ];
                    continue;
                }

                $wantList = array_map('trim', explode(',', $want));
                $match    = in_array($has, $wantList);

                $matchedPreferences[] = [
                    'preference_name' => $field,
                    'value'           => $want,
                    'match'           => $match,
                ];
            }

            /* ------------- age / height / income ------------- */
            if (!empty($candidate->dob)) {
                $age = Carbon::parse($candidate->dob)->age;
                if ($preference->min_age && $preference->max_age) {
                    $matchedPreferences[] = [
                        'preference_name' => 'age',
                        'value'           => "{$preference->min_age} - {$preference->max_age}",
                        'match'           => $age >= $preference->min_age && $age <= $preference->max_age,
                    ];
                } else {
                    $matchedPreferences[] = [
                        'preference_name' => 'age',
                        'value'           => 'Any',
                        'match'           => true,
                    ];
                }
            }

            if (!empty($candidate->height)) {
                if ($preference->height_from && $preference->height_to) {
                    $matchedPreferences[] = [
                        'preference_name' => 'height',
                        'value'           => "{$preference->height_from} - {$preference->height_to}",
                        'match'           => $candidate->height >= $preference->height_from &&
                            $candidate->height <= $preference->height_to,
                    ];
                } else {
                    $matchedPreferences[] = [
                        'preference_name' => 'height',
                        'value'           => 'Any',
                        'match'           => true,
                    ];
                }
            }

            if (!empty($candidate->monthly_income)) {
                if (!empty($preference->monthly_income_from) && !empty($preference->monthly_income_to)) {
                    $matchedPreferences[] = [
                        'preference_name' => 'monthly_income',
                        'value'           => "{$preference->monthly_income_from} - {$preference->monthly_income_to}",
                        'match'           => $candidate->monthly_income >= $preference->monthly_income_from &&
                            $candidate->monthly_income <= $preference->monthly_income_to,
                    ];
                } else {
                    $matchedPreferences[] = [
                        'preference_name' => 'monthly_income',
                        'value'           => 'Any',
                        'match'           => true,
                    ];
                }
            }

            $totalPreferences = count($matchedPreferences);
            $matchedCount     = collect($matchedPreferences)->where('match', true)->count();
        }

        $contactRequested = DB::table('contact_requests')
            ->where('user_id', $authUser)
            ->where('profile_id', $profile->user_id)
            ->exists();

        return view('web.profile-details', compact('profile','authUser','receipts', 'relatedProfiles', 'metaTags','userAndUserDetails', 'db', 'matchedPreferences', 'totalPreferences', 'matchedCount', 'contactRequested'));
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function profileLists(Request $request): string
    {
        $userId = Auth::id();
        $packageDetails = DataController::getUserPackageDetails($userId);
        $userPackageValue = $packageDetails['package'];
        $isPackageValid = $packageDetails['is_active'];

        $userAndUserDetails = DataController::getUserDetails($userId);
        if (!$userAndUserDetails || !$userAndUserDetails->gender) {
            return view('web.all-profiles', ['message' => 'User details not found.']);
        }

        $oppositeGender = DataController::getOppositeGender($userAndUserDetails->gender);
        [$profiles, $appliedFilters] = DataSharedController::getFilteredProfiles($request, $oppositeGender);

        if ($request->ajax()) {
            return view('vendor.pagination.bootstrap-5', compact('profiles'))->render();
        }

        $metaTags = DataSharedController::MetaData('profile_lists');
        $db = DataSharedController::getDatabases();

        return view('web.profile_lists', array_merge([
            'userAndUserDetails' => $userAndUserDetails,
            'profiles' => $profiles,
            'profilesCount' => $profiles->total(),
            'appliedFilters' => $appliedFilters,
            'metaTags' => $metaTags,
            'userPackageValue' => $userPackageValue,
            'isPackageValid' => $isPackageValid,
            'db' => $db,
        ], $profiles->total() === 0 ? ['message' => 'No profiles found for the selected criteria.'] : []));
    }

    public function HomeSearch(Request $request): View
    {
        $userId = Auth::id();
        $userGender = null;

        if ($userId) {
            $userGender = DB::table('user_details')
                ->where('user_id', $userId)
                ->value('gender');
        }
        // Define opposite gender
        $oppositeGender = ($userGender === 'Male') ? 'Female' : (($userGender === 'Female') ? 'Male' : null);

        // Fetch user and user details
        $userAndUserDetails = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('users.id', $userId)
            ->select('users.*', 'user_details.*')
            ->first();

        // Fetch options for filter dropdowns
        $db = DataSharedController::getDatabases();

        // Build the base query
        $profilesQuery = DB::table('user_details')
            ->join('users', 'user_details.user_id', '=', 'users.id')
            ->orderBy('user_details.created_at', 'desc')
            ->select('user_details.*', 'users.*');

        // If user is logged in, show only opposite gender profiles
        if ($userId && $oppositeGender) {
            $profilesQuery->where('user_details.gender', $oppositeGender);
        }
        // If user is not logged in, apply gender filter from the search form
        elseif ($request->filled('gender')) {
            $profilesQuery->where('user_details.gender', $request->input('gender'));
        }

        // Apply filters
        $filters = [
            'mother_tongue', 'gender', 'marital_status', 'skin_tone', 'height',
            'body_type', 'drinking_habit', 'smoking_habit', 'religion', 'caste',
            'sub_caste', 'qualification', 'education', 'occupation_type',
            'occupation', 'country', 'state', 'city', 'rashi', 'nakshatra',
            'lagnam', 'padam', 'kulam', 'gothram', 'dosham'
        ];

        foreach ($filters as $filter) {
            if ($request->filled($filter)) {
                $profilesQuery->where('user_details.' . $filter, $request->input($filter));
            }
        }

        // Apply age filter
        $minAge = $request->input('min_age');
        $maxAge = $request->input('max_age');

        if ($minAge || $maxAge) {
            $profilesQuery->whereNotNull('user_details.dob');

            if ($minAge && $maxAge) {
                $profilesQuery->whereRaw("TIMESTAMPDIFF(YEAR, user_details.dob, CURDATE()) BETWEEN ? AND ?", [$minAge, $maxAge]);
            } elseif ($minAge) {
                $profilesQuery->whereRaw("TIMESTAMPDIFF(YEAR, user_details.dob, CURDATE()) = ?", [$minAge]);
            } elseif ($maxAge) {
                $profilesQuery->whereRaw("TIMESTAMPDIFF(YEAR, user_details.dob, CURDATE()) <= ?", [$maxAge]);
            }
        }

        // Fetch profiles with pagination
        $profiles = $profilesQuery->paginate(10);

        // If no profiles found, show message
        if ($profiles->isEmpty()) {
            $message = ($userGender === 'Male') ? 'You can only search for female users.' : 'You can only search for male users.';
        } else {
            $message = null;
        }

        // Calculate age for each profile
        $profiles->each(function ($profile) {
            $profile->age = Carbon::parse($profile->dob)->age;
        });

//        $recentUserDetailsData = $this->webController->getRecentUserDetails();
//        $recentUserDetails = $recentUserDetailsData['recentUserDetails'] ?? [];

        return view('web.home-search', array_merge(
            [
                'profiles' => $profiles,
                'userAndUserDetails' => $userAndUserDetails,
//                'recentUserDetails' => $recentUserDetails,
                'message' => $message,
                'db' => $db
            ]
        ));
    }


    public function contact(Request $request): RedirectResponse
    {

        $authUserId = $request->input('user_id');
        $profileUserId = $request->input('profile_id');

        if (empty($profileUserId)) {
            return Redirect::back()->with('error', 'Invalid profile user ID.');
        }

        // Fetch the most recent 'paid' receipt for the authenticated user
        $authUserReceipt = DB::table('receipts')
            ->where('user_id', $authUserId)
            ->where('status', 'paid')
            ->orderBy('expiry_date', 'desc')
            ->first();

        if (!$authUserReceipt) {
            return Redirect::back()->with('error', 'No active package found. Please upgrade.');
        }

        // Check if a contact request already exists for the same user and profile
        $contactRequestExists = DB::table('contact_requests')
            ->where('user_id', $authUserId)
            ->where('profile_id', $profileUserId)
            ->exists();

        if ($contactRequestExists) {
            return Redirect::back()->with('info', 'A contact request has already been made. Please view the available contacts.');
        }

        // Check if the authenticated user's package has sufficient balance
        $currentBalance = $authUserReceipt->balance ?? 0;

        if ($currentBalance <= 0) {
            return Redirect::back()->with('error', 'Your balance is zero. Please upgrade your package to view contacts.');
        }

        // Update the number of viewed contacts and decrement the balance for the user
        $updatedRows = DB::table('receipts')
            ->where('user_id', $authUserId)
            ->where('id', $authUserReceipt->id)
            ->update([
                'no_of_viewed' => $authUserReceipt->no_of_viewed + 1,
                'balance' => $currentBalance - 1,
            ]);

        if ($updatedRows === 0) {
            return Redirect::back()->with('error', 'Failed to update the package.');
        }

        // Fetch the updated receipt data
        $updatedAuthReceipt = DB::table('receipts')->where('id', $authUserReceipt->id)->first();

        // Calculate new balance based on no_of_contact and no_of_viewed
        $newBalance = $updatedAuthReceipt->no_of_contact - $updatedAuthReceipt->no_of_viewed;

        // If the new balance reaches 0, close the receipt
        if ($newBalance <= 0) {
            $newBalance = 0; // Ensure balance is not negative
            DB::table('receipts')
                ->where('id', $authUserReceipt->id)
                ->update([
                    'balance' => $newBalance,
                    'status' => 'closed',
                ]);
        } else {
            // Update the balance without closing the receipt
            DB::table('receipts')
                ->where('id', $authUserReceipt->id)
                ->update(['balance' => $newBalance]);
        }

        // Store the contact request in the contact_requests table
        DB::table('contact_requests')->insert([
            'user_id' => $authUserId,
            'profile_id' => $profileUserId,
        ]);

        return Redirect::back()->with('success', 'Contact is now available.');
    }


    public function viewedProfiles(Request $request): RedirectResponse
    {

        $existingView = DB::table('profile_views')
            ->where('viewer_id', $request->input('viewer_id'))
            ->where('viewed_id', $request->input('viewed_id'))
            ->first();

        if (!$existingView) {
            DB::table('profile_views')->insert([
                'viewer_id' => $request->input('viewer_id'),
                'viewed_id' => $request->input('viewed_id'),
                'last_viewed_at' => now(),
                'viewed_count' => 1,
            ]);
        } else {
            $existingView = (object) $existingView;

            $allowedContacts = DB::table('track_broker')->value('no_of_profile') ?? 20;

            $today = now()->startOfDay();
            $lastViewedDate = $existingView->last_viewed_at ? Carbon::parse($existingView->last_viewed_at)->startOfDay() : null;

            if ($lastViewedDate && $lastViewedDate != $today) {
                $existingView->viewed_count = 1;
            } else {
                $existingView->viewed_count++;
            }

            DB::table('profile_views')
                ->where('viewer_id', $request->input('viewer_id'))
                ->where('viewed_id', $request->input('viewed_id'))
                ->update([
                    'viewed_count' => $existingView->viewed_count,
                    'last_viewed_at' => now(),
                ]);

            // Profile tracking check - email disabled to prevent automated spam emails
            /*
            if ($existingView->viewed_count > $allowedContacts) {
                $viewed = User::find($request->input('viewed_id'));
                if ($viewed) {
                    try {
                        Mail::to('info@pgmatrimony.in')->send(new TrackProfile($viewed, $existingView->viewed_count));
                    } catch (\Exception $e) {
                        Log::error('Failed to send email: ' . $e->getMessage());
                    }
                }
            }
            */
        }

        return redirect()->route('all-profiles.show', $request->input('viewer_id'));
    }

    public static function showNotifications(Request $request): View
    {
        $user_id = $request->user()->id;

        // Fetch notifications for the user
        $notifications = DB::table('notifications')
            ->where('notifiable_id', $user_id)
            ->where('notifiable_type', 'App\\Models\\User')
            ->orderBy('created_at', 'desc')
            ->get();

        // Optional: Remove duplicates based on message or data content
        $notifications = $notifications->unique(function ($item) {
            return $item->data;
        });

        // Transform notifications
        $transformedNotifications = $notifications->map(function ($notification) {
            $data = json_decode($notification->data, true);
            return [
                'id' => $notification->id,
                'notifiable_id' => $notification->notifiable_id,
                'title' => $data['title'] ?? 'No title',
                'message' => $data['message'] ?? 'No message',
                'datetime' => Carbon::parse($notification->created_at)->format('d-m-Y H:i:s'),
                'status' => $notification->read_at ? 'read' : 'unread',
                'read_at' => $notification->read_at,
            ];
        });

        $unreadCount = $notifications->whereNull('read_at')->count();

        return view('web.notifications', [
            'notifications' => $transformedNotifications,
            'unreadCount' => $unreadCount
        ]);
    }


}
