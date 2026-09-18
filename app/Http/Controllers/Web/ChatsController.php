<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Api\ApiHelperController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Helpers\DataController;
use App\Http\Controllers\Helpers\DataSharedController;
use App\Models\User;
use App\Notifications\ChatNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ChatsController extends Controller
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
        $chatUserId = $request->get('chat_user_id');
        $searchQuery = $request->get('search', '');

        $userAndUserDetails = DataController::getUserDetails($userId);
        $userPackage = DataController::getUserPackageDetails($userId);
        $userPackageValue = $userPackage['package'];
        $oppositeGender = DataController::getOppositeGender($userAndUserDetails->gender);

        // Get chat partner IDs
        $chatPartnerIds = DB::table('chat_details')
            ->where(function ($query) use ($userId) {
                $query->where('user_id', $userId)
                    ->orWhere('chat_user_id', $userId);
            })
            ->get()
            ->flatMap(fn($chat) => [$chat->user_id == $userId ? $chat->chat_user_id : $chat->user_id])
            ->unique()
            ->values()
            ->all();

        // Include selected profile user at the top if not in the list
        if ($chatUserId && !in_array($chatUserId, $chatPartnerIds)) {
            array_unshift($chatPartnerIds, $chatUserId);
        }

        $chatUsersWithLastMessage = collect();
        foreach ($chatPartnerIds as $partnerId) {
            $lastMessage = DB::table('chat_details')
                ->where(function ($query) use ($userId, $partnerId) {
                    $query->where('user_id', $userId)->where('chat_user_id', $partnerId);
                })
                ->orWhere(function ($query) use ($userId, $partnerId) {
                    $query->where('user_id', $partnerId)->where('chat_user_id', $userId);
                })
                ->orderByDesc('created_at')
                ->first();

            $chatUser = DB::table('user_details')
                ->join('users', 'user_details.user_id', '=', 'users.id')
                ->leftJoin('settings', 'user_details.user_id', '=', 'settings.user_id')
                ->where('users.id', $partnerId)
                ->where('users.status', '=', 'active')
                ->select(
                    'users.id as user_id', 'users.name',
                    'user_details.gender', 'user_details.profile_image',
                    'settings.profile_picture_visibility', 'settings.name_visibility'
                )
                ->first();

            if ($chatUser && (!$searchQuery || stripos($chatUser->name, $searchQuery) !== false)) {
                $chatUser->last_message = ($userPackageValue !== 'Free')
                    ? ($lastMessage->messages ?? '')
                    : 'Messaged you';
                $chatUser->last_message_time = $lastMessage->created_at ?? null;
                $chatUser->profile_image = ApiHelperController::ImageUrl(
                    $chatUser->profile_image,
                    $chatUser->profile_picture_visibility,
                    $userPackage,
                    $chatUser->gender
                );
                $chatUser->name = ApiHelperController::privacyData(
                    $chatUser->name,
                    $chatUser->name_visibility,
                    $userPackage
                );
                $chatUsersWithLastMessage->push($chatUser);
            }
        }

        // Ensure selected chat user stays first
        if ($chatUserId) {
            $chatUsersWithLastMessage = $chatUsersWithLastMessage->sortByDesc(function($user) use ($chatUserId) {
                return $user->user_id == $chatUserId ? PHP_INT_MAX : strtotime($user->last_message_time ?? '1970-01-01');
            })->values();
        } else {
            $chatUsersWithLastMessage = $chatUsersWithLastMessage->sortByDesc('last_message_time')->values();
        }

        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $paginatedUsers = new LengthAwarePaginator(
            $chatUsersWithLastMessage->forPage($currentPage, $perPage),
            $chatUsersWithLastMessage->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        $unreadCounts = DB::table('chat_details')
            ->where('chat_user_id', $userId)
            ->where('is_read', false)
            ->select('user_id', DB::raw('count(*) as unread_count'))
            ->groupBy('user_id')
            ->pluck('unread_count', 'user_id')
            ->toArray();

        $metaTags = DataSharedController::MetaData('chats');
        $db = DataSharedController::getDatabases();

        return view('web.chats', [
            'userAndUserDetails' => $userAndUserDetails,
            'chatUsers' => $paginatedUsers,
            'chatUsersCount' => $chatUsersWithLastMessage->count(),
            'unreadCounts' => $unreadCounts,
            'userPackageValue' => $userPackageValue,
            'metaTags' => $metaTags,
            'db' => $db,
            'openChatUserId' => $chatUserId,
        ]);
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
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        try {
            $request->validate([
                'chat_user_id' => 'required|integer',
                'message' => 'required|string|max:1000'
            ]);
        } catch (ValidationException $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => $e->getMessage(),
                    'errors' => $e->errors(),
                ], 422);
            }

            throw $e;
        }

        $authUserId = $request->input('user_id');
        $chatUserId = $request->input('chat_user_id');

        if (empty($chatUserId)) {
            return $request->wantsJson()
                ? response()->json(['success' => false, 'error' => 'Invalid chat user ID.'], 400)
                : redirect()->back()->with('error', 'Invalid chat user ID.');
        }

        $receipt = DataController::getUserPackageDetails($authUserId);
        $authUserReceipt = $receipt['receipt'];

        if (!$authUserReceipt) {
            return $request->wantsJson()
                ? response()->json(['success' => false, 'error' => 'No package found. Please upgrade.'], 403)
                : redirect()->back()->with('error', 'No package found. Please upgrade your package.');
        }

        if ($authUserReceipt->status !== 'paid') {
            return $request->wantsJson()
                ? response()->json(['success' => false, 'error' => 'You need a paid package.'], 403)
                : redirect()->back()->with('error', 'You need a paid package to send messages.');
        }

        $currentBalance = $authUserReceipt->balance_chats ?? 0;

        if ($currentBalance <= 0) {
            return $request->wantsJson()
                ? response()->json(['success' => false, 'error' => 'Balance is zero.'], 403)
                : redirect()->back()->with('error', 'Balance is zero. Please upgrade your package.');
        }

        // Save message
        $messageId = DB::table('chat_details')->insertGetId([
            'chat_user_id' => $chatUserId,
            'user_id' => $authUserId,
            'messages' => $request->input('message'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Update receipt
        DB::table('receipts')
            ->where('user_id', $authUserId)
            ->where('id', $authUserReceipt->id)
            ->update([
                'viewed_chats' => $authUserReceipt->viewed_chats + 1,
                'balance_chats' => $currentBalance - 1,
            ]);

        // Notify
//        $chatUser = User::find($chatUserId);
//        $chatUser->notify(new ChatNotification(Auth::user(), "messaged you."));

        // Return JSON response
        if ($request->wantsJson()) {
            $message = DB::table('chat_details')->where('id', $messageId)->first();
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        }

        // Fallback for non-AJAX
        return redirect()->back()->with('success', 'Message sent successfully!');
    }

//    public function store(Request $request): RedirectResponse
//    {
//        $request->validate([
//            'chat_user_id' => 'required',
//            'message' => 'required'
//        ]);
//
//        $authUserId = $request->input('user_id');
//        $chatUserId = $request->input('chat_user_id');
//
//        if (empty($chatUserId)) {
//            return Redirect::back()->with('error', 'Invalid chat user ID.');
//        }
//
//        $receipt = DataController::getUserPackageDetails($authUserId);
//        $authUserReceipt = $receipt['receipt'];
//
//        if (!$authUserReceipt) {
//            return Redirect::back()->with('error', 'No package found. Please upgrade your package.');
//        }
//
//        if ($authUserReceipt->status === 'paid') {
//            $currentBalance = $authUserReceipt->balance_chats ?? 0;
//
//            if ($currentBalance <= 0) {
//                return Redirect::back()->with('error', 'Balance is zero. Please upgrade your package to continue chatting.');
//            }
//
//            DB::table('chat_details')->insert([
//                'chat_user_id' => $chatUserId,
//                'user_id' => $authUserId,
//                'messages' => $request->input('message'),
//            ]);
//
//            DB::table('receipts')
//                ->where('user_id', $authUserId)
//                ->where('id', $authUserReceipt->id)
//                ->update([
//                    'viewed_chats' => $authUserReceipt->viewed_chats + 1,
//                    'balance_chats' => $currentBalance - 1,
//                ]);
//
//            // Fetch the chat user and send a notification
//            $chatUser = User::find($chatUserId);
//            $chatUser->notify(new ChatNotification(Auth::user(), "messaged you."));
//
//            return redirect()->back()->with('success', 'Message sent successfully!');
//        }
//
//        return Redirect::back()->with('error', 'You need a paid package to send messages.');
//    }


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


    public function markChatNotificationsAsRead($notifiableId, $id): RedirectResponse
    {
        DB::table('notifications')
            ->where('notifiable_id', $notifiableId)
            ->where('id', $id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return redirect()->back()->with('success', 'Marked as read');
    }

    public function chatDetails($chatUserId): View
    {
        $userId = Auth::id();
        $userAndUserDetails = DataController::getUserDetails($userId);

        // Retrieve chat details between the logged-in user and the selected chat user
        $chatDetails = DB::table('chat_details')
            ->where(function ($query) use ($userId, $chatUserId) {
                $query->where('user_id', $userId)
                    ->where('chat_user_id', $chatUserId);
            })
            ->orWhere(function ($query) use ($userId, $chatUserId) {
                $query->where('user_id', $chatUserId)
                    ->where('chat_user_id', $userId);
            })
            ->orderBy('created_at')
            ->get();

        // Mark messages as read
        DB::table('chat_details')
            ->where('user_id', $chatUserId)
            ->where('chat_user_id', $userId)
            ->update(['is_read' => true]);

        $chatUser = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->leftJoin('settings', 'user_details.user_id', '=', 'settings.user_id')
            ->where('users.id', $chatUserId)
            ->select('users.*', 'user_details.*', 'settings.profile_picture_visibility', 'settings.name_visibility')
            ->first();
        $chatUser->profile_image = ApiHelperController::ImageUrl($chatUser->profile_image ,$chatUser->profile_picture_visibility, null, $chatUser->gender);
        $chatUser->name = ApiHelperController::privacyData($chatUser->name ,$chatUser->name_visibility, null);

        $metaTags = DataSharedController::MetaData('chat-details');
        $db = DataSharedController::getDatabases();

        return view('web.chat-details', compact('userAndUserDetails', 'chatUser',
            'chatDetails', 'metaTags','db'
        ));
    }

    public function chatDetailsAjax($chatUserId): JsonResponse
    {
        $userId = Auth::id();
        $authUserPackage = DataController::getUserPackageDetails($userId);
        $isValidPackage = $authUserPackage['is_active'];

        $chatDetails = DB::table('chat_details')
            ->where(function ($query) use ($userId, $chatUserId) {
                $query->where('user_id', $userId)
                    ->where('chat_user_id', $chatUserId);
            })
            ->orWhere(function ($query) use ($userId, $chatUserId) {
                $query->where('user_id', $chatUserId)
                    ->where('chat_user_id', $userId);
            })
            ->orderBy('created_at')
            ->get();

        // Mark as read
        DB::table('chat_details')
            ->where('user_id', $chatUserId)
            ->where('chat_user_id', $userId)
            ->update(['is_read' => true]);

        $chatUser = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->leftJoin('settings', 'user_details.user_id', '=', 'settings.user_id')
            ->where('users.id', $chatUserId)
            ->select('users.*', 'user_details.*', 'settings.profile_picture_visibility', 'settings.name_visibility')
            ->first();

        $chatUser->profile_image = ApiHelperController::ImageUrl($chatUser->profile_image, $chatUser->profile_picture_visibility, $isValidPackage, $chatUser->gender);
        $chatUser->name = ApiHelperController::privacyData($chatUser->name, $chatUser->name_visibility, $isValidPackage);

        return response()->json([
            'user' => $chatUser,
            'messages' => $chatDetails,
        ]);
    }



}
