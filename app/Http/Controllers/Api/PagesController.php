<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helpers\DataController;
use App\Models\User;
use App\Notifications\ChatNotification;
use App\Http\Controllers\Api\FCMController;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PagesController extends Controller
{
    protected FCMController $fcmController;
    public function __construct(FCMController $fcmController)
    {
        $this->fcmController = $fcmController;
    }

    public function Dashboard(Request $request): JsonResponse
    {
        $userId = $request->input('user_id');

        $package = DataController::getUserPackageDetails($userId);
        $receipt = $package['receipt'];
        $activePackage = $package['is_active'];

        $date30DaysAgo = Carbon::now()->subDays(30);

        // Fetch notifications
        $notifications = DB::table('notifications')
            ->where('notifiable_id', $userId)
            ->where('notifiable_type', 'App\Models\User')
            ->where('status', 'pending')
            ->where('created_at', '>=', $date30DaysAgo)
            ->limit(10)
            ->get();

        $interestsData = [];
        foreach ($notifications as $notification) {
            $data = json_decode($notification->data, true);
            $senderId = $data['sender_id'] ?? null;
            $sender = null;

            if ($senderId) {
                $sender = DB::table('users')
                    ->join('user_details', 'users.id', '=', 'user_details.user_id')
                    ->where('users.id', $senderId)
                    ->first();
            }

            if ($sender) {
                $package = DB::table('receipts')
                    ->where('user_id', $senderId)
                    ->value('package') ?? 'No Package';

                $visibilitySettings = DB::table('settings')
                    ->where('user_id', $senderId)
                    ->select('profile_picture_visibility', 'name_visibility')
                    ->first();
                $profilePictureVisibility = $visibilitySettings->profile_picture_visibility ?? null;
                $nameVisibility = $visibilitySettings->name_visibility ?? null;

                $sender->profile_image = ApiHelperController::ImageUrl($sender->profile_image,$profilePictureVisibility, $activePackage, $sender->gender);
                $sender->name = ApiHelperController::privacyData($sender->name, $nameVisibility, $activePackage);

                $interestsData[] = [
                    'notification_id' => $notification->id,
                    'sender_name' => $sender->name,
                    'gender' => $sender->gender,
                    'sender_id' => $senderId,
                    'profile_image' => $sender->profile_image,
                    'city' => $sender->city,
                    'latitude' => $sender->latitude ?? null,
                    'longitude' => $sender->longitude ?? null,
                    'age' => (!empty($sender->dob) && $sender->dob !== '0000-00-00') ? Carbon::parse($sender->dob)->age : null,
                    'height' => $sender->height,
                    'occupation' => $sender->occupation,
                    'package' => $package,
                    'request_time' => Carbon::parse($notification->created_at)->format('h:i A, d F Y'),
                ];
            }
        }

        // Fetch user gender and coordinates to find opposite gender profiles
        $currentUser = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('users.id', $userId)
            ->select('user_details.gender', 'user_details.latitude', 'user_details.longitude')
            ->first();
        $userGender = $currentUser->gender ?? null;
        $oppositeGender = strtolower($userGender) === 'male' ? 'female' : (strtolower($userGender) === 'female' ? 'male' : null);


        // Fetch recent user details
        $base = DB::table('user_details')
            ->join('users',    'user_details.user_id', '=', 'users.id')
            ->leftJoin('settings', 'settings.user_id', '=', 'user_details.user_id')
            ->where('user_details.gender',   $oppositeGender)
            ->where('users.status',          'active')
            ->where('user_details.created_at', '>=', $date30DaysAgo)
            ->select([
                'users.id',
                'users.name',
                'user_details.gender',
                'user_details.profile_image',
                'user_details.height',
                'user_details.city',
                'user_details.latitude',
                'user_details.longitude',
                'user_details.dob',
                'settings.profile_picture_visibility',
                'settings.name_visibility',
                'user_details.created_at',
            ]);

        /** list of recent user data */
        $recentUserDetails = (clone $base)
            ->orderByDesc('user_details.created_at')
            ->limit(10)
            ->get();

        /** total count for recent user data */
        $totalRecentCount = (clone $base)
            ->distinct('user_details.user_id')
            ->count('user_details.user_id');

        /** post‑processing recent user data */
        foreach ($recentUserDetails as $profile) {
            $profile->package = DataController::getUserPackageDetails($profile->id)['package'];
            $profile->age = Carbon::parse($profile->dob)->age;
            $profile->name = ApiHelperController::privacyData(
                $profile->name,
                $profile->name_visibility,
                $activePackage
            );
            $profile->profile_image = ApiHelperController::ImageUrl(
                $profile->profile_image,
                $profile->profile_picture_visibility,
                $activePackage,
                $profile->gender
            );
        }

        //Preference-Based Profiles
        $userPreferences = DB::table('set_preferences')->where('user_id', $userId)->first();
        $filteredProfiles = collect();

        if ($userPreferences) {
            $relatedProfilesQuery = DB::table('user_details')
                ->join('users', 'user_details.user_id', '=', 'users.id')
                ->leftJoin('settings', 'user_details.user_id', '=', 'settings.user_id')
                ->where('user_details.gender', $oppositeGender)
                ->select(
                    'users.id as user_id',
                    'users.name',
                    'users.email_verified_at',
                    'user_details.gender',
                    'user_details.profile_image',
                    'user_details.marital_status',
                    'user_details.caste',
                    'user_details.employed_in',
                    'user_details.height',
                    'user_details.city',
                    'user_details.dob',
                    'settings.profile_picture_visibility', 'settings.name_visibility',
                );

            $userDetailColumns = Schema::getColumnListing('user_details');
            foreach ($userDetailColumns as $column) {
                $relatedProfilesQuery->addSelect("user_details.$column");
            }

            $filters = [
                'profile_for' => '=',
                'min_age' => '>=',
                'max_age' => '<=',
                'country' => '=',
                'state' => '=',
                'city' => '=',
                'mother_tongue' => '=',
                'marital_status' => '=',
                'skin_tone' => '=',
                'height_from' => '>=',
                'height_to' => '<=',
                'body_type' => '=',
                'drinking_habit' => '=',
                'smoking_habit' => '=',
                'eating_habit' => '=',
                'physical_status' => '=',
                'religion' => '=',
                'caste' => '=',
                'sub_caste' => '=',
                'qualification' => '=',
                'education' => '=',
                'occupation_type' => '=',
                'occupation' => '=',
                'employed_in' => '=',
                'monthly_income_from' => '>=',
                'monthly_income_to' => '<=',
                'rashi' => '=',
                'nakshatra' => '=',
                'lagnam' => '=',
                'padam' => '=',
                'kulam' => '=',
                'gothram' => '=',
                'dosham' => '='
            ];

            foreach ($filters as $key => $operator) {
                if (!empty($userPreferences->$key)) {
                    if (in_array($key, ['min_age', 'max_age'])) {
                        $relatedProfilesQuery->whereNotNull('user_details.dob')
                            ->whereRaw(
                                "TIMESTAMPDIFF(YEAR, user_details.dob, CURDATE()) " . $operator . " ?",
                                [$userPreferences->$key]
                            );
                    } elseif (in_array($key, ['height_from', 'height_to'])) {
                        $relatedProfilesQuery->where('user_details.height', $operator, $userPreferences->$key);
                    } elseif (in_array($key, ['monthly_income_from', 'monthly_income_to'])) {
                        $relatedProfilesQuery->where('user_details.monthly_income', $operator, $userPreferences->$key);
                    } else {
                        $relatedProfilesQuery->whereIn('user_details.' . $key, explode(',', $userPreferences->$key));
                    }
                }
            }

            $groupByFields = [
                'users.id', 'users.name', 'users.email_verified_at',
                'user_details.gender', 'user_details.profile_image',
                'user_details.marital_status', 'user_details.caste',
                'user_details.employed_in', 'user_details.height',
                'user_details.city', 'user_details.dob',
                'settings.profile_picture_visibility', 'settings.name_visibility',
            ];
            foreach ($userDetailColumns as $column) {
                $groupByFields[] = "user_details.$column";
            }

            $relatedProfiles = $relatedProfilesQuery
                ->groupBy(...$groupByFields)
                ->limit(10)
                ->get();

            // Post-processing and filtering fields
            $filteredProfiles = $relatedProfiles->map(function ($profile) use ($activePackage) {
                $package = DataController::getUserPackageDetails($profile->id)['package'];
                $imageUrl = ApiHelperController::ImageUrl($profile->profile_image, $profile->profile_picture_visibility, $activePackage, $profile->gender);
                $name = ApiHelperController::privacyData($profile->name, $profile->name_visibility, $activePackage);
                $isVerified = ApiHelperController::isVerified($profile->email_verified_at);
                return [
                    'id' => $profile->user_id,
                    'name' => $name,
                    'gender' => $profile->gender,
                    'age' => Carbon::parse($profile->dob)->age,
                    'profile_image' => $imageUrl,
                    'height' => $profile->height,
                    'city' => $profile->city,
                    'latitude' => $profile->latitude ?? null,
                    'longitude' => $profile->longitude ?? null,
                    'marital_status' => $profile->marital_status,
                    'caste' => $profile->caste,
                    'employed_in' => $profile->employed_in,
                    'package' => $package,
                    'isVerified' => $isVerified
                ];
            });
        }


        // Fetch the logged-in user's preferred city from set_preferences
        $userLocation = DB::table('set_preferences')
            ->where('user_id', $userId)
            ->value('city');

        $locationBasedMatches = DB::table('user_details')
            ->join('users', 'user_details.user_id', '=', 'users.id')
            ->where('user_details.gender', $oppositeGender)
            ->where('user_details.city', $userLocation)
            ->limit(10)
            ->get();

        $locationBasedMatchesCount = $locationBasedMatches->count();

        // Fetch education-based matches from user_details
        $userEducation = DB::table('set_preferences')
            ->where('user_id', $userId)
            ->value('education');

        $educationBasedMatches = DB::table('user_details')
            ->join('users', 'user_details.user_id', '=', 'users.id')
            ->where('user_details.gender', $oppositeGender)
            ->where('user_details.education', $userEducation)
            ->limit(10)
            ->get();

        $educationBasedMatchesCount = $educationBasedMatches->count();

        // Fetch occupation-based matches from user_details
        $userOccupation = DB::table('set_preferences')
            ->where('user_id', $userId)
            ->value('occupation');

        $occupationBasedMatches = DB::table('user_details')
            ->join('users', 'user_details.user_id', '=', 'users.id')
            ->where('user_details.gender', $oppositeGender)
            ->where('user_details.occupation', $userOccupation)
            ->limit(10)
            ->get();

        $occupationBasedMatchesCount = $occupationBasedMatches->count();

        $viewedByMeProfiles = $this->fetchProfiles('viewed_id', 'viewer_id', $userId);
        $viewedMyProfiles = $this->fetchProfiles('viewer_id', 'viewed_id', $userId);

        // Fetch notifications for the user
        $notifications = DB::table('notifications')
            ->where('notifiable_id', $userId)
            ->where('notifiable_type', 'App\\Models\\User')
            ->orderBy('created_at', 'desc')
            ->get();

        // Count unread notifications
        $unreadCount = $notifications->whereNull('read_at')->count();

        // Get highlighted profiles
        $highlighted = DB::table('highlighted_profiles')
            ->join('users',        'users.id',           '=', 'highlighted_profiles.user_id')
            ->join('user_details', 'user_details.user_id', '=', 'users.id')
            ->leftJoin('settings', 'settings.user_id',   '=', 'users.id')
            ->where('users.status',  'active')
            ->where('user_details.gender', $oppositeGender);

        $highlightedProfile = (clone $highlighted)
            ->select([
                'users.id',
                'users.name',
                'user_details.gender',
                'user_details.profile_image',
                'user_details.height',
                'user_details.city',
                'user_details.latitude',
                'user_details.longitude',
                'user_details.dob',
                'user_details.monthly_income',
                'settings.profile_picture_visibility',
                'settings.name_visibility',
                'settings.date_of_birth_visibility',
            ])
            ->orderByDesc('highlighted_profiles.id')
            ->limit(10)
            ->get();

        $highlightedProfileCount = (clone $highlighted)->distinct('users.id')->count('users.id');

        foreach ($highlightedProfile as $p) {
            $p->package = DataController::getUserPackageDetails($p->id)['package'];
            $p->age  = Carbon::parse($p->dob)->age;
            $p->name = ApiHelperController::privacyData(
                $p->name,
                $p->name_visibility,
                $activePackage
            );
            $p->dob  = ApiHelperController::privacyData(
                $p->dob,
                $p->date_of_birth_visibility,
                $activePackage
            );
            $p->profile_image = ApiHelperController::ImageUrl(
                $p->profile_image,
                $p->profile_picture_visibility,
                $activePackage,
                $p->gender
            );
        }

        //Testimonials Start
        $testimonials = DB::table('testimonials')->get();

        $testimonials = $testimonials->map(function ($testimonial) {
            if ($testimonial->image) {
                $testimonial->image = asset('TestimonialImage/' . $testimonial->image);
            }
            return $testimonial;
        });
        //End: Testimonials

        return response()->json([
            'latitude' => $currentUser->latitude ?? null,
            'longitude' => $currentUser->longitude ?? null,
            'interests' => [
                'interest_list' => $interestsData,
                'interest_count' => count($interestsData),
            ],
            'recent_users' => [
                'recent_user_list' => $recentUserDetails,
                'recent_user_count' => $totalRecentCount,
            ],
            'related_profiles' => [
                'related_profile_list' => $filteredProfiles,
                'related_profile_count' => $filteredProfiles->count(),
            ],
            'location_based' => [
                'matches' => $locationBasedMatchesCount,
                'name' => 'location'
            ],
            'education_based' => [
                'matches' => $educationBasedMatchesCount,
                'name' => 'education'
            ],
            'occupation_based' => [
                'matches' => $occupationBasedMatchesCount,
                'name' => 'occupation'
            ],
            'viewed_profiles' => [
                'viewed_profile_list' => $viewedMyProfiles['list'],
                'viewed_profile_count' => $viewedMyProfiles['count'],
            ],
            'viewed_by_me_profiles' => [
                'viewed_by_me_profile_list' => $viewedByMeProfiles['list'],
                'viewed_by_me_profile_count' => $viewedByMeProfiles['count'],
            ],
            'highlighted_profiles' => [
                'highlighted_profile_list' => $highlightedProfile,
                'highlighted_profile_count' => $highlightedProfileCount,
            ],
            'testimonials' => [
                'testimonials_list' => $testimonials,
                'testimonials_count' => count($testimonials),
            ],
            'notification_count' => $unreadCount,
            'package' => $receipt->package ?? '',
        ]);
    }

    private function fetchProfiles(string $columnFrom, string $columnTo, int $userId): array
    {
        $authUserPackage = DataController::getUserPackageDetails($userId)['is_active'];
        $base = DB::table('profile_views')
            ->join('user_details', "profile_views.$columnTo", '=', 'user_details.user_id')
            ->join('users',         'users.id',               '=', 'user_details.user_id')
            ->leftJoin('settings',  'settings.user_id',       '=', 'user_details.user_id')
            ->where("profile_views.$columnFrom", $userId)
            ->where('users.status', 'active')
            ->select([
                'users.id',
                'users.name',
                'user_details.gender',
                'user_details.profile_image',
                'user_details.dob',
                'user_details.height',
                'user_details.city',
                'user_details.latitude',
                'user_details.longitude',
                'settings.profile_picture_visibility',
                'settings.name_visibility',
                'settings.date_of_birth_visibility',
            ]);
        $list = (clone $base)
            ->orderByDesc('profile_views.id')
            ->limit(10)
            ->get();

        $count = (clone $base)
            ->distinct('user_details.user_id')
            ->count('user_details.user_id');

        foreach ($list as $profile) {
            $profile->package = DataController::getUserPackageDetails($profile->id)['package'];
            $profile->age = Carbon::parse($profile->dob)->age;
            $profile->name = ApiHelperController::privacyData(
                $profile->name,
                $profile->name_visibility,
                $authUserPackage
            );
            $profile->dob = ApiHelperController::privacyData(
                $profile->dob,
                $profile->date_of_birth_visibility,
                $authUserPackage
            );
            $profile->profile_image = ApiHelperController::ImageUrl(
                $profile->profile_image,
                $profile->profile_picture_visibility,
                $authUserPackage,
                $profile->gender
            );
        }

        return [
            'list'  => $list,
            'count' => $count,
        ];
    }
    
    public function downloadSliderImage($image): BinaryFileResponse|JsonResponse
    {
        $filePath = public_path('web/SliderImage/' . $image);

        if (file_exists($filePath)) {
            return response()->download($filePath);
        } else {
            return response()->json(['error' => 'File not found.'], 404);
        }
    }


    public function showUserSettings(Request $request): JsonResponse
    {
        $userId = $request->input('user_id');
        $userSettings = DB::table('settings')
            ->where('user_id', $userId)
            ->select(
                'profile_picture_visibility', 'horoscope_picture_visibility',
                'mobile_number_visibility', 'email_visibility', 'name_visibility', 'date_of_birth_visibility'
            )
            ->first();

        if ($userSettings) {
            return response()->json([
                'success' => true,
                'show_settings' => $userSettings,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User settings not found',
            ], 404);
        }
    }


    public function updateUserSettings(Request $request): JsonResponse
    {
        $userId = $request->input('user_id');

        $updateUserSettings = [
            'user_id' => $userId,
            'profile_picture_visibility' => $request->input('profile_picture_visibility'),
            'horoscope_picture_visibility' => $request->input('horoscope_picture_visibility'),
            'mobile_number_visibility' => $request->input('mobile_number_visibility'),
            'email_visibility' => $request->input('email_visibility'),
            'name_visibility' => $request->input('name_visibility'),
            'date_of_birth_visibility' => $request->input('date_of_birth_visibility'),
        ];

        $existing = DB::table('settings')->where('user_id', $userId)->exists();

        if ($existing) {
            DB::table('settings')
                ->where('user_id', $userId)
                ->update($updateUserSettings);
            $message = 'Settings updated successfully';
        } else {
            DB::table('settings')->insert($updateUserSettings);
            $message = 'Settings created successfully';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }
    
    public function highlightedProfiles(Request $request): JsonResponse
    {
        $userId = $request->input('user_id');
        $authPackage = DataController::getUserPackageDetails($userId)['is_active'];

        $userGender = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('users.id', $userId)
            ->value('user_details.gender');

        $oppositeGender = strtolower($userGender) === 'male' ? 'female' : 'male';
        // Get highlighted profiles
        $highlightedProfiles = DB::table('highlighted_profiles')
            ->join('users', 'users.id', '=', 'highlighted_profiles.user_id')
            ->join('user_details', 'user_details.user_id', '=', 'users.id')
            ->leftJoin('settings', 'user_details.user_id', '=', 'settings.user_id')
            ->where('user_details.gender', $oppositeGender);

        $highlightedProfile = $highlightedProfiles->select(
            'users.id',
            'users.name',
            'user_details.gender',
            'user_details.profile_image',
            'user_details.height',
            'user_details.city',
            'user_details.dob',
            'user_details.monthly_income',
            'user_details.occupation_type',
            'settings.profile_picture_visibility', 'settings.name_visibility', 'settings.date_of_birth_visibility'
        )->limit(10)->get();

        // Calculate age for each related profile
        foreach ($highlightedProfile as $profile) {
            $profile->package = DataController::getUserPackageDetails($profile->id)['package'];
            $profile->age = Carbon::parse($profile->dob)->age;
            $profile->name = ApiHelperController::privacyData($profile->name, $profile->name_visibility, $authPackage);
            $profile->dob = ApiHelperController::privacyData($profile->dob, $profile->date_of_birth_visibility, $authPackage);
            $profile->profile_image = ApiHelperController::ImageUrl($profile->profile_image, $profile->profile_picture_visibility, $authPackage, $profile->gender);
        }

        $highlightedProfileCount = $highlightedProfile->count();
        return response()->json([
            'all_highlighted_profiles' => [
                'all_highlighted_profile_list' => $highlightedProfile,
            ],
            'all_highlighted_profile_count' => $highlightedProfileCount,
        ]);
    }

    public function StoreSliderImage(Request $request): JsonResponse
    {
        if ($request->hasFile('image')) {
            $sliderImage = $request->file('image');
            $sliderImageName = time() . '.' . $sliderImage->getClientOriginalExtension();
            $sliderImage->move(public_path('web/SliderImage'), $sliderImageName);

            $slider = DB::table('slider_images')->insertGetId([
                'image' => $sliderImageName,
            ]);

            return response()->json(['success' => 'Image uploaded successfully!', 'image' => $sliderImageName,]);
        } else {
            return response()->json(['error' => 'No file uploaded'], 400);
        }
    }

    public function GetSliderImage(): JsonResponse
    {
        try {
            $sliderImages = DB::table('slider_images')->pluck('image');

            $sliderImageUrls = $sliderImages->map(function ($image) {
                return asset('web/SliderImage/' . $image);
            });

            return response()->json([
                'success' => true,
                'images' => $sliderImageUrls
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }

    }

    public function GetAddsImage(): JsonResponse
    {
        try {
            $image = DB::table('mobile_adds')->value('image');

            if (!$image) {
                return response()->json([
                    'success' => false,
                    'error' => 'No image found.'
                ]);
            }

            $imageUrl = asset('Advertisement/' . $image);

            return response()->json([
                'success' => true,
                'image' => $imageUrl
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }


    public function StoreEnquiry(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'mobile' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation errors',
                'messages' => $validator->errors(),
            ], 400);
        }

        $attachmentName = null;
        if ($request->hasFile('attachment')) {
            $attachment = $request->file('attachment');
            $attachmentName = date('dmYHis') . '.' . $attachment->getClientOriginalExtension();
            $attachment->move(public_path('Attachment'), $attachmentName);
        }

        DB::table('enquiry')->insert([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'mobile' => $request->input('mobile'),
            'subject' => $request->input('subject'),
            'message' => $request->input('message'),
            'attachment' => $attachmentName,
        ]);

        return response()->json([
            'success' => 'Your enquiry has been sent.',
        ]);
    }


    public function ChatSend(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'chat_user_id' => 'required|integer|exists:users,id',
            'message'      => 'required|string',
            'user_id'      => 'required|integer|exists:users,id',
        ]);

        $authUserId = $validated['user_id'];
        $chatUserId = $validated['chat_user_id'];
        $message    = $validated['message'];

        $authUser = DB::table('users')->where('id', $authUserId)->first();
        if (!$authUser) {
            return response()->json(['error' => 'Authenticated user not found.'], 404);
        }

        $chatUser = DB::table('users')->where('id', $chatUserId)->first();
        if (!$chatUser) {
            return response()->json(['error' => 'Chat user not found.'], 404);
        }

        $receipt = DataController::getUserPackageDetails($authUserId)['receipt'];

        if (!$receipt) {
            return response()->json(['error' => 'No package found. Please upgrade your package.'], 200);
        }

        $balance = $receipt->balance_chats ?? 0;
        if ($balance <= 0) {
            return response()->json(['error' => 'Balance is zero. Please upgrade your package to continue chatting.'], 200);
        }

        // Insert the chat message
        DB::table('chat_details')->insert([
            'chat_user_id' => $chatUserId,
            'user_id'      => $authUserId,
            'messages'     => $message,
        ]);

        // Update balance and viewed count
        DB::table('receipts')
            ->where('id', $receipt->id)
            ->update([
                'viewed_chats'  => $receipt->viewed_chats + 1,
                'balance_chats' => $balance - 1,
            ]);

        // Send Firebase Notification
        if (!empty($chatUser->device_token)) {
            $this->fcmController->sendFcmNotificationHelper(
                $chatUser->device_token,
                "New message from {$authUser->name}",
                "Tap to read the message",
            );
        }

        return response()->json([
            'message' => 'Message sent successfully!',
            'data'    => [
                'chat_user_id' => $authUserId,
                'message'      => $message,
            ],
        ], 200);
    }


    public function ChatDetails(Request $request): JsonResponse
    {
        $userId = $request->input('user_id');
        $chatUserId = $request->input('chat_user_id');

        $authUserReceipt = DataController::getUserPackageDetails($userId)['receipt'];
        $packageStatus = $authUserReceipt ? 'Package Found' : 'Package Not Found';

        if (!$authUserReceipt) {
            return response()->json([
                'status' => 'success',
                'package_status' => $packageStatus,
                'chat_messages' => ['message' => []],
            ]);
        }

        // 2. Mark messages as read
        DB::table('chat_details')
            ->where('user_id', $userId)
            ->where('chat_user_id', $chatUserId)
            ->where('is_read', 0)
            ->update(['is_read' => 1]);

        // 3. Retrieve all messages between both users
        $chatDetails = DB::table('chat_details')
            ->join('users as sender', 'chat_details.user_id', '=', 'sender.id')
            ->where(function ($query) use ($userId, $chatUserId) {
                $query->where('chat_details.user_id', $userId)
                    ->where('chat_details.chat_user_id', $chatUserId);
            })
            ->orWhere(function ($query) use ($userId, $chatUserId) {
                $query->where('chat_details.user_id', $chatUserId)
                    ->where('chat_details.chat_user_id', $userId);
            })
            ->select(
                'chat_details.*',
                'sender.name as chat_user'
            )
            ->orderBy('chat_details.created_at')
            ->get();

        // 4. Format chat messages
        $formattedMessages = $chatDetails->map(function ($chat) {
            return [
                'msg' => $chat->messages,
                'time' => $chat->created_at,
                'chat_user_id' => $chat->user_id,
                'chat_user' => $chat->chat_user,
                'is_read' => $chat->is_read,
            ];
        });

        return response()->json([
            'status' => 'success',
            'package_status' => $packageStatus,
            'chat_messages' => [
                'message' => $formattedMessages,
            ],
        ]);
    }


    public function getChatRoom(Request $request): JsonResponse
    {
        $userId = $request->input('user_id');

        // Get Package status
        $package = DataController::getUserPackageDetails($userId);
        $activePackage = $package['is_active'];

        $chatPartners = DB::table('chat_details')
            ->where('user_id', $userId)
            ->orWhere('chat_user_id', $userId)
            ->orderByDesc('created_at')
            ->get();

        $chatRooms = [];
        $seenUserIds = [];

        foreach ($chatPartners as $chat) {
            $otherUserId = $chat->user_id == $userId ? $chat->chat_user_id : $chat->user_id;

            if (in_array($otherUserId, $seenUserIds) || $otherUserId == $userId) {
                continue;
            }

            $lastMessage = DB::table('chat_details')
                ->where(function ($query) use ($userId, $otherUserId) {
                    $query->where('user_id', $userId)->where('chat_user_id', $otherUserId);
                })
                ->orWhere(function ($query) use ($userId, $otherUserId) {
                    $query->where('user_id', $otherUserId)->where('chat_user_id', $userId);
                })
                ->orderByDesc('created_at')
                ->first();

            $userDetail = DB::table('user_details')
                ->join('users', 'user_details.user_id', '=', 'users.id')
                ->leftJoin('settings', 'user_details.user_id', '=', 'settings.user_id')
                ->where('user_details.user_id', $otherUserId)
                ->where('users.status', 'active')
                ->select('users.name', 'user_details.user_id', 'user_details.dob', 'user_details.gender', 'user_details.profile_image', 'settings.profile_picture_visibility',
                    'settings.name_visibility')
                ->first();

            if ($lastMessage && $userDetail) {
                $chatRooms[] = [
                    'chat_user_id'       => $otherUserId,
                    'chat_user_name'     => ApiHelperController::privacyData($userDetail->name, $userDetail->name_visibility, $activePackage),
                    'chat_profile_image' => ApiHelperController::ImageUrl($userDetail->profile_image, $userDetail->profile_picture_visibility, $activePackage, $userDetail->gender),
                    'is_read'            => (bool) $lastMessage->is_read,
                    'last_message'       => $lastMessage->messages,
                    'chat_time'          => $lastMessage->created_at,
                ];

                $seenUserIds[] = $otherUserId;
            }
        }
        return response()->json(['chat_room' => $chatRooms]);
    }


}