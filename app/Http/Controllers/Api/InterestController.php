<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helpers\DataController;
use App\Models\User;
use App\Notifications\InterestNotification;
use App\Http\Controllers\Api\FCMController;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InterestController extends Controller
{
    protected PagesController $pagesController;
    protected FCMController $fcmController;

    public function __construct(PagesController $pagesController, FCMController $fcmController)
    {
        $this->pagesController = $pagesController;
        $this->fcmController =  $fcmController;
    }

    public function sendInterest(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id'     => 'required|exists:users,id',
            'profile_id'  => 'required|exists:users,id',
            'permissions' => 'array'
        ]);

        $userId     = $validated['user_id'];
        $profileId  = $validated['profile_id'];

        $sender = DB::table('users')->where('id', $userId)->first();
        if (!$sender) {
            return response()->json(['status' => 'error', 'message' => 'Sender not found.'], 404);
        }

        $receiver = DB::table('users')->where('id', $profileId)->first();
        if (!$receiver) {
            return response()->json(['status' => 'error', 'message' => 'Receiver not found.'], 404);
        }

        $receipt = DataController::getUserPackageDetails($userId)['receipt'];

        if (!$receipt) {
            return response()->json([
                'status' => 'error',
                'message' => 'No active package found. Please upgrade your package.',
            ], 403);
        }

        $balance = $receipt->balance_interests ?? 0;
        if ($balance <= 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Insufficient balance to send interests. Please upgrade your package.',
            ], 403);
        }

        // Check if interest already exists and not denied
        $interestExists = DB::table('interests')
            ->where(function ($q) use ($userId, $profileId) {
                $q->where('sender_id', $userId)
                    ->where('receiver_id', $profileId);
            })
            ->orWhere(function ($q) use ($userId, $profileId) {
                $q->where('sender_id', $profileId)
                    ->where('receiver_id', $userId);
            })
            ->where('status', '!=', 'denied')
            ->exists();

        if ($interestExists) {
            return response()->json([
                'status' => 'info',
                'message' => 'You have already received or sent an interest to this user.',
            ], 200);
        }

        // Insert new interest
        $inserted = DB::table('interests')->insert([
            'sender_id'   => $userId,
            'receiver_id' => $profileId,
            'status'      => 'pending',
        ]);

        if (!$inserted) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to send interest. Please try again.',
            ], 500);
        }

        // Send Firebase notification (if receiver has device_token)
        if (!empty($receiver->device_token)) {
            $this->fcmController->sendFcmNotificationHelper(
                $receiver->device_token,
                'Someone has expressed interest in your profile.',
                "Tap to view."
            );
        }

        // Decrement balance and increment viewed
        DB::table('receipts')
            ->where('id', $receipt->id)
            ->update([
                'balance_interests' => $balance - 1,
                'viewed_interests'  => $receipt->viewed_interests + 1,
            ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Interest sent successfully!',
        ], 200);
    }


    public function acceptInterest(Request $request): JsonResponse
    {
        $request->validate([
            'interest_id' => 'required|exists:interests,id',
        ]);

        $interestId = $request->input('interest_id');
        $interest = DB::table('interests')->where('id', $interestId)->first();

        if (!$interest) {
            return response()->json([
                'status' => 'error',
                'message' => 'Interest not found.',
            ], 404);
        }

        // Only the receiver can accept the interest
        $receiverId = $interest->receiver_id;
        $senderId = $interest->sender_id;

        // Update interest status to 'accepted'
        DB::table('interests')
            ->where('id', $interestId)
            ->update(['status' => 'accepted']);

        $sender = DB::table('users')->where('id', $senderId)->first();
        $receiver = DB::table('users')->where('id', $receiverId)->first();

        // Send Firebase notification to sender
        if ($sender && !empty($sender->device_token)) {
            $title = "{$receiver->name} has accepted the interest!";
            $body = "Tap to view.";
            $this->fcmController->sendFcmNotificationHelper($sender->device_token, $title, $body);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Interest accepted successfully!',
        ], 200);
    }


    public function deniedInterest(Request $request): JsonResponse
    {
        $request->validate([
            'interest_id' => 'required|exists:interests,id',
        ]);

        $interestId = $request->input('interest_id');
        $interest = DB::table('interests')->where('id', $interestId)->first();

        if (!$interest) {
            return response()->json([
                'status' => 'error',
                'message' => 'Interest not found.',
            ], 404);
        }

        $receiverId = $interest->receiver_id;
        $senderId = $interest->sender_id;

        // Update interest status to 'denied'
        DB::table('interests')
            ->where('id', $interestId)
            ->update([
                'status' => 'denied',
                'updated_at' => now(),
            ]);

        // Fetch users (sender and receiver)
        $sender = DB::table('users')->where('id', $senderId)->first();
        $receiver = DB::table('users')->where('id', $receiverId)->first();

        if ($sender && !empty($sender->device_token)) {
            $title = "{$receiver->name} has denied the interest.";
            $body = "Tap to view.";
            $this->fcmController->sendFcmNotificationHelper($sender->device_token, $title, $body);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Interest denied successfully!',
        ], 200);
    }


    public function allInterests(Request $request): JsonResponse
    {
        $userId = $request->input('user_id');

        // Auth user package is used for applying privacy rules
        $userPackage = DataController::getUserPackageDetails($userId);
        $userPackageValue = $userPackage['is_active'];

        // Closure to fetch and format user profile using auth user's package
        $getUserData = function ($targetUserId) use ($userPackageValue) {
            $user = DB::table('users')
                ->join('user_details', 'users.id', '=', 'user_details.user_id')
                ->where('users.id', $targetUserId)
                ->select(
                    'users.id',
                    'users.name',
                    'user_details.gender',
                    'user_details.profile_image',
                    'user_details.dob',
                    'user_details.marital_status',
                    'user_details.caste',
                    'user_details.employed_in',
                    'user_details.height',
                    'user_details.city'
                )
                ->first();

            if (!$user) return null;

            $visibility = DB::table('settings')
                ->where('user_id', $targetUserId)
                ->select('profile_picture_visibility', 'name_visibility', 'date_of_birth_visibility')
                ->first();

            return [
                'id' => $user->id,
                'name' => ApiHelperController::privacyData($user->name, $visibility->name_visibility ?? null, $userPackageValue),
                'gender' => $user->gender,
                'profile_image' => ApiHelperController::ImageUrl(
                    $user->profile_image,
                    $visibility->profile_picture_visibility ?? null,
                    $userPackageValue,
                    $user->gender
                ),
                'age' => now()->year - date('Y', strtotime($user->dob)),
                'dob' => ApiHelperController::privacyData($user->dob, $visibility->date_of_birth_visibility ?? null, $userPackageValue),
                'marital_status' => $user->marital_status,
                'caste' => $user->caste,
                'employed_in' => $user->employed_in,
                'height' => $user->height,
                'city' => $user->city,
                'package' => DataController::getUserPackageDetails($targetUserId)['package'] ?? 'No Package',
            ];
        };

        // Generic function to fetch interests by condition
        $mapInterests = function ($column, $value, $status) use ($getUserData) {
            return DB::table('interests')
                ->where($column, $value)
                ->where('status', $status)
                ->get()
                ->map(function ($interest) use ($getUserData, $column) {
                    $targetId = $column === 'sender_id' ? $interest->receiver_id : $interest->sender_id;
                    $data = $getUserData($targetId);
                    if ($data) $data['interest_id'] = $interest->id;
                    return $data;
                })
                ->filter()
                ->values();
        };

        // Fetch categorized interests
        $newInterest         = $mapInterests('receiver_id', $userId, 'pending');
        $sentByMe            = $mapInterests('sender_id', $userId, 'pending');
        $acceptedByMe        = $mapInterests('receiver_id', $userId, 'accepted');
        $acceptedMyInterest  = $mapInterests('sender_id', $userId, 'accepted');
        $deniedByMe          = $mapInterests('receiver_id', $userId, 'denied');
        $deniedMyInterest    = $mapInterests('sender_id', $userId, 'denied');

        // Return structured JSON
        return response()->json([
            'new_interest' => [
                'new_interest_list' => $newInterest,
                'new_interest_count' => $newInterest->count(),
            ],
            'sent_by_me' => [
                'sent_by_me_list' => $sentByMe,
                'sent_by_me_count' => $sentByMe->count(),
            ],
            'accepted_by_me' => [
                'accepted_by_me_lists' => $acceptedByMe,
                'accepted_by_me_count' => $acceptedByMe->count(),
            ],
            'accepted_my_interest' => [
                'accepted_my_interest_lists' => $acceptedMyInterest,
                'accepted_my_interest_count' => $acceptedMyInterest->count(),
            ],
            'denied_by_me' => [
                'denied_by_me_lists' => $deniedByMe,
                'denied_by_me_count' => $deniedByMe->count(),
            ],
            'denied_my_interest' => [
                'denied_my_interest_lists' => $deniedMyInterest,
                'denied_my_interest_count' => $deniedMyInterest->count(),
            ],
        ]);
    }


    public function addWishlist(Request $request): JsonResponse
    {
        $userId = $request->input('user_id');
        $profileId = $request->input('profile_id');

        // Check if the user exists
        $user = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('users.id', $userId)
            ->first();

        if (!$user) {
            return response()->json(['message' => 'User not found!'], 404);
        }

        // Check if the profile_id corresponds to a valid user
        $profile = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('users.id', $profileId)
            ->first();

        if (!$profile) {
            return response()->json(['message' => 'This profile not found!'], 404);
        }

        // Check if the profile is already in the wishlist for this user
        $existingWishlist = DB::table('wishlists')
            ->where('user_id', $userId)
            ->where('profile_id', $profileId)
            ->first();

        if ($existingWishlist) {
            return response()->json(['message' => 'This profile is already in your wishlist!'], 200);
        }

        // Add the profile to the wishlist
        $wishlist = DB::table('wishlists')->insert([
            'user_id' => $userId,
            'profile_id' => $profileId,
        ]);

        return response()->json(['message' => 'Profile added to your wishlist successfully!'], 200);
    }


    public function removeWishlist(Request $request): JsonResponse
    {
        $userId = $request->input('user_id');
        $profileId = $request->input('profile_id');

        // Check if the user exists
        $user = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('users.id', $userId)
            ->first();

        if (!$user) {
            return response()->json(['message' => 'User not found!'], 404);
        }

        // Check if the profile exists
        $profile = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('users.id', $profileId)
            ->first();

        if (!$profile) {
            return response()->json(['message' => 'This profile not found!'], 404);
        }

        // Check if the profile is in the user's wishlist
        $wishlist = DB::table('wishlists')
            ->where('user_id', $userId)
            ->where('profile_id', $profileId)
            ->first();

        if (!$wishlist) {
            return response()->json(['message' => 'This profile is not in your wishlist!'], 404);
        }

        // Remove the profile from the wishlist
        DB::table('wishlists')
            ->where('user_id', $userId)
            ->where('profile_id', $profileId)
            ->delete();

        return response()->json(['message' => 'Profile removed from your wishlist successfully!'], 200);
    }

    public function showWishlist(Request $request): JsonResponse
    {
        $userId = $request->input('user_id');

        // Check if the user exists
        $user = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('users.id', $userId)
            ->first();

        if (!$user) {
            return response()->json(['message' => 'User not found!'], 404);
        }

        $authPackage = DataController::getUserPackageDetails($userId)['is_active'] ?? '';

        // wishlist profiles
        $wishlistProfiles = DB::table('wishlists')
            ->join('users', 'wishlists.profile_id', '=', 'users.id')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->select(
                'users.id as profile_id',
                'users.name',
                'users.email_verified_at',
                'user_details.gender',
                'user_details.marital_status',
                'user_details.caste',
                'user_details.employed_in',
                'user_details.height',
                'user_details.dob',
                'user_details.city',
                'user_details.profile_image'
            )
            ->where('wishlists.user_id', $userId)
            ->distinct()
            ->get();

        if ($wishlistProfiles->isEmpty()) {
            return response()->json(['message' => 'No profiles found in your wishlist!'], 200);
        }

        foreach ($wishlistProfiles as $profile) {

            $profile->age = Carbon::parse($profile->dob)->age;
            $profile->isVerified = ApiHelperController::isVerified($profile->email_verified_at);
            $profilePackage = DataController::getUserPackageDetails($profile->profile_id)['package'] ?? 'No Package';

            $visibilitySettings = DB::table('settings')
                ->where('user_id', $profile->profile_id)
                ->select('profile_picture_visibility', 'name_visibility', 'date_of_birth_visibility')
                ->first();

            $profilePictureVisibility = $visibilitySettings->profile_picture_visibility ?? 0;
            $nameVisibility = $visibilitySettings->name_visibility ?? 0;
            $dobVisibility = $visibilitySettings->date_of_birth_visibility ?? 0;

            $profile->profile_image = ApiHelperController::ImageUrl(
                $profile->profile_image,
                $profilePictureVisibility,
                $authPackage,
                $profile->gender
            );

            $profile->name = ApiHelperController::privacyData(
                $profile->name,
                $nameVisibility,
                $authPackage
            );

            $profile->dob = ApiHelperController::privacyData(
                $profile->dob,
                $dobVisibility,
                $authPackage
            );

            $profile->package = $profilePackage;
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Wishlist fetched successfully!',
            'wishlists' => $wishlistProfiles
        ], 200);
    }
    
    public function acceptedRequestedInterests(Request $request): JsonResponse
    {
        $userId = $request->input('user_id');

        // Check the user's package
        $userPackage = DB::table('receipts')
            ->where('user_id', $userId)
            ->first();

        // Set default package value
        $userPackageValue = $userPackage ? $userPackage->package : '';

        // Fetch notifications where the user has accepted interests (accepted_by_me)
        $notificationsSent = DB::table('notifications')
            ->where('data', 'like', '%"sender_id":' . $userId . '%')
            ->where('status', 'accepted')
            ->get();

        $acceptedByMe = [];
        foreach ($notificationsSent as $notification) {
            $receiverId = $notification->notifiable_id;

            if ($receiverId) {
                $receiver = DB::table('users')
                    ->join('user_details', 'users.id', '=', 'user_details.user_id')
                    ->where('users.id', $receiverId)
                    ->select('users.id', 'users.name', 'user_details.gender', 'user_details.profile_image', 'user_details.dob', 'user_details.city')
                    ->first();

                if ($receiver) {
                    $package = DB::table('receipts')
                        ->where('user_id', $receiverId)
                        ->value('package') ?? 'No Package';

                    $visibilitySettings = DB::table('settings')
                        ->where('user_id', $receiverId)
                        ->select('profile_picture_visibility', 'name_visibility', 'date_of_birth_visibility')
                        ->first();
                    $profilePictureVisibility = $visibilitySettings->profile_picture_visibility ?? null;
                    $nameVisibility = $visibilitySettings->name_visibility ?? null;
                    $dobVisibility = $visibilitySettings->date_of_birth_visibility ?? null;
                    $profileImage = ApiHelperController::ImageUrl($receiver->profile_image, $profilePictureVisibility, $userPackageValue, $receiver->gender);
                    $name = ApiHelperController::privacyData($receiver->name, $nameVisibility, $userPackageValue);
                    $dob = ApiHelperController::privacyData($receiver->dob, $dobVisibility, $userPackageValue);

                    $acceptedByMe[] = [
                        'id' => $receiver->id,
                        'name' => $name,
                        'gender' => $receiver->gender,
                        'profile_image' => $profileImage,
                        'age' => now()->year - date('Y', strtotime($receiver->dob)),
                        'dob' => $dob,
                        'city' => $receiver->city,
                        'package' => $package,
                        'profile_picture_visibility' => $profilePictureVisibility,
                    ];
                }
            }
        }

        // Fetch notifications where others have accepted the user's interest (accepted_my_interests)
        $notificationsReceived = DB::table('notifications')
            ->where('notifiable_id', $userId)
            ->where('notifiable_type', 'App\Models\User')
            ->where('status', 'accepted')
            ->get();

        $acceptedMyInterest = [];
        foreach ($notificationsReceived as $notification) {
            $data = json_decode($notification->data, true);
            $senderId = $data['sender_id'] ?? null;

            if ($senderId) {
                $sender = DB::table('users')
                    ->join('user_details', 'users.id', '=', 'user_details.user_id')
                    ->where('users.id', $senderId)
                    ->select('users.id', 'users.name', 'user_details.gender', 'user_details.profile_image', 'user_details.dob', 'user_details.city')
                    ->first();

                if ($sender) {
                    $package = DB::table('receipts')
                        ->where('user_id', $senderId)
                        ->value('package') ?? 'No Package';

                    $visibilitySettings = DB::table('settings')
                        ->where('user_id', $senderId)
                        ->select('profile_picture_visibility', 'name_visibility', 'date_of_birth_visibility')
                        ->first();
                    $profilePictureVisibility = $visibilitySettings->profile_picture_visibility ?? null;
                    $nameVisibility = $visibilitySettings->name_visibility ?? null;
                    $dobVisibility = $visibilitySettings->date_of_birth_visibility ?? null;
                    $profileImage = ApiHelperController::ImageUrl($sender->profile_image, $profilePictureVisibility, $userPackageValue, $sender->gender);
                    $name = ApiHelperController::privacyData($sender->name, $nameVisibility, $userPackageValue);
                    $dob = ApiHelperController::privacyData($sender->dob, $dobVisibility, $userPackageValue);

                    $acceptedMyInterest[] = [
                        'id' => $sender->id,
                        'name' => $name,
                        'gender' => $sender->gender,
                        'profile_image' => $profileImage,
                        'age' => now()->year - date('Y', strtotime($sender->dob)),
                        'dob' => $dob,
                        'city' => $sender->city,
                        'package' => $package,
                        'profile_picture_visibility' => $profilePictureVisibility,
                    ];
                }
            }
        }


        // Fetch new notifications where the user has sent interest
        $notificationsReceived = DB::table('notifications')
            ->where('notifiable_id', $userId)
            ->where('notifiable_type', 'App\Models\User')
            ->where('status', 'pending')
            ->get();

        $newInterest = [];
        foreach ($notificationsReceived as $notification) {
            $data = json_decode($notification->data, true);
            $senderId = $data['sender_id'] ?? null;


            if ($senderId) {
                $sender = DB::table('users')
                    ->join('user_details', 'users.id', '=', 'user_details.user_id')
                    ->where('users.id', $senderId)
                    ->select('users.id', 'users.name', 'user_details.gender','user_details.profile_image', 'user_details.dob','user_details.city')
                    ->first();

                if ($sender) {
                    $package = DB::table('receipts')
                        ->where('user_id', $senderId)
                        ->value('package') ?? 'No Package';

                    $visibilitySettings = DB::table('settings')
                        ->where('user_id', $senderId)
                        ->select('profile_picture_visibility', 'name_visibility', 'date_of_birth_visibility')
                        ->first();
                    $profilePictureVisibility = $visibilitySettings->profile_picture_visibility ?? null;
                    $nameVisibility = $visibilitySettings->name_visibility ?? null;
                    $dobVisibility = $visibilitySettings->date_of_birth_visibility ?? null;
                    $profileImage = ApiHelperController::ImageUrl($sender->profile_image, $profilePictureVisibility, $userPackageValue, $sender->gender);
                    $name = ApiHelperController::privacyData($sender->name, $nameVisibility, $userPackageValue);
                    $dob = ApiHelperController::privacyData($sender->dob, $dobVisibility, $userPackageValue);

                    $newInterest[] = [
                        'id' => $sender->id,
                        'name' => $name,
                        'gender' => $sender->gender ,
                        'profile_image' => $profileImage,
                        'age' => now()->year - date('Y', strtotime($sender->dob)),
                        'dob' => $dob,
                        'city' => $sender->city,
                        'package' => $package,
                        'profile_picture_visibility' => $profilePictureVisibility,
                    ];
                }
            }
        }

        // Combine both accepted lists into a single response structure
        $response = [
            'accepted' => [
                'accepted_list' => array_merge($acceptedByMe, $acceptedMyInterest),
                'accepted_count' => count($acceptedByMe) + count($acceptedMyInterest), // Total count of both lists
            ],
            'requested' => [
                'requested_list' => $newInterest,
                'requested_count' => count($newInterest),
            ]
        ];

        return response()->json($response);
    }





}
