<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Api\ApiHelperController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Helpers\DataController;
use App\Http\Controllers\Helpers\DataSharedController;
use App\Http\Controllers\WebController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\RedirectResponse;

class InterestController extends Controller
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

        // Get user's package details
        $userPackage = DB::table('receipts')
            ->where('user_id', $userId)
            ->where('status', 'paid')
            ->where(function ($query) {
                $query->whereNull('expiry_date')
                      ->orWhere('expiry_date', '>=', now());
            })
            ->orderByDesc('id')
            ->first();

        $userPackageValue = $userPackage->package ?? '';
        $isPackageValid = !empty($userPackage);

        // Helper to get user info with privacy
        $getUserData = function ($targetUserId) use ($userPackageValue) {
            $user = DB::table('users')
                ->join('user_details', 'users.id', '=', 'user_details.user_id')
                ->where('users.id', $targetUserId)
                ->select('users.id', 'users.name', 'user_details.gender', 'user_details.profile_image', 'user_details.dob', 'user_details.marital_status', 'user_details.caste', 'user_details.employed_in', 'user_details.height', 'user_details.city', 'user_details.religion', 'user_details.occupation')
                ->first();

            if (!$user) return null;

            $package = DB::table('receipts')->where('user_id', $targetUserId)->value('package') ?? 'No Package';

            $visibility = DB::table('settings')
                ->where('user_id', $targetUserId)
                ->select('profile_picture_visibility', 'name_visibility', 'date_of_birth_visibility')
                ->first();

            $profileImage = ApiHelperController::ImageUrl($user->profile_image, $visibility->profile_picture_visibility ?? null, $userPackageValue, $user->gender);
            $name = ApiHelperController::privacyData($user->name, $visibility->name_visibility ?? null, $userPackageValue);
            $dob = ApiHelperController::privacyData($user->dob, $visibility->date_of_birth_visibility ?? null, $userPackageValue);

            return [
                'id' => $user->id,
                'name' => $name,
                'gender' => $user->gender,
                'profile_image' => $profileImage,
                'age' => now()->year - date('Y', strtotime($user->dob)),
                'marital_status' => $user->marital_status,
                'caste' => $user->caste,
                'employed_in' => $user->employed_in,
                'height' => $user->height,
                'dob' => $dob,
                'city' => $user->city,
                'religion' => $user->religion,
                'occupation' => $user->occupation,
                'package' => $package,
                'profile_picture_visibility' => $visibility->profile_picture_visibility ?? null,
            ];
        };

        // Interest categories
        $newInterest = DB::table('interests')
            ->where('receiver_id', $userId)
            ->where('status', 'pending')
            ->get()
            ->map(fn($i) => ($d = $getUserData($i->sender_id)) ? array_merge($d, ['interest_id' => $i->id]) : null)
            ->filter()->values();

        $sentByMe = DB::table('interests')
            ->where('sender_id', $userId)
            ->where('status', 'pending')
            ->get()
            ->map(fn($i) => ($d = $getUserData($i->receiver_id)) ? array_merge($d, ['interest_id' => $i->id]) : null)
            ->filter()->values();

        $acceptedByMe = DB::table('interests')
            ->where('receiver_id', $userId)
            ->where('status', 'accepted')
            ->get()
            ->map(fn($i) => ($d = $getUserData($i->sender_id)) ? array_merge($d, ['interest_id' => $i->id]) : null)
            ->filter()->values();

        $acceptedMyInterest = DB::table('interests')
            ->where('sender_id', $userId)
            ->where('status', 'accepted')
            ->get()
            ->map(fn($i) => ($d = $getUserData($i->receiver_id)) ? array_merge($d, ['interest_id' => $i->id]) : null)
            ->filter()->values();

        $deniedByMe = DB::table('interests')
            ->where('receiver_id', $userId)
            ->where('status', 'denied')
            ->get()
            ->map(fn($i) => ($d = $getUserData($i->sender_id)) ? array_merge($d, ['interest_id' => $i->id]) : null)
            ->filter()->values();

        $deniedMyInterest = DB::table('interests')
            ->where('sender_id', $userId)
            ->where('status', 'denied')
            ->get()
            ->map(fn($i) => ($d = $getUserData($i->receiver_id)) ? array_merge($d, ['interest_id' => $i->id]) : null)
            ->filter()->values();

        $metaTags = DataSharedController::MetaData('interests');
        $db = DataSharedController::getDatabases();

        return view('web.interests', compact(
            'userAndUserDetails', 'isPackageValid', 'userId', 'userPackageValue', 'metaTags', 'db',
            'newInterest', 'sentByMe', 'acceptedByMe', 'acceptedMyInterest', 'deniedByMe', 'deniedMyInterest'
        ));
    }

    /**
     * Send Interest to the specified the user
     */
    public function sendInterest(Request $request): RedirectResponse
    {
        $request->validate([
            'user_id'     => 'required|exists:users,id',
            'profile_id'  => 'required|exists:users,id',
            'permissions' => 'array'
        ]);

        $userId    = $request->input('user_id');
        $profileId = $request->input('profile_id');

        $sender = DB::table('users')->where('id', $userId)->first();
        if (!$sender) {
            return redirect()->back()->with('error', 'Sender not found.');
        }

        $receiver = DB::table('users')->where('id', $profileId)->first();
        if (!$receiver) {
            return redirect()->back()->with('error', 'Receiver not found.');
        }

        $receipt = DB::table('receipts')
            ->where('user_id', $userId)
            ->where('status', 'paid')
            ->where(function ($query) {
                $query->whereNull('expiry_date')
                      ->orWhere('expiry_date', '>=', now());
            })
            ->orderByDesc('id')
            ->first();

        if (!$receipt) {
            return redirect()->back()->with('error', 'No active package found. Please upgrade your package.');
        }

        $balance = $receipt->balance_interests ?? 0;
        if ($balance <= 0) {
            return redirect()->back()->with('error', 'Insufficient balance to send interests. Please upgrade your package.');
        }

        // Check if interest already exists and is not denied
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
            return redirect()->back()->with('info', 'You have already sent or received an interest to/from this user.');
        }

        $inserted = DB::table('interests')->insert([
            'sender_id'   => $userId,
            'receiver_id' => $profileId,
            'status'      => 'pending',
        ]);

        if (!$inserted) {
            return redirect()->back()->with('error', 'Failed to send interest. Please try again.');
        }

        // Update receipt balance
        DB::table('receipts')
            ->where('id', $receipt->id)
            ->update([
                'balance_interests' => $balance - 1,
                'viewed_interests'  => $receipt->viewed_interests + 1,
            ]);

        return redirect()->back()->with('success', 'Interest sent successfully!');
    }


    /**
     * Accept the specified resource.
     */
    public function accept(Request $request, $interestId): RedirectResponse
    {
        $interest = DB::table('interests')->where('id', $interestId)->first();

        if (!$interest) {
            return redirect()->back()->with('error', 'Interest not found.');
        }

        DB::table('interests')
            ->where('id', $interestId)
            ->update(['status' => 'accepted']);

        return redirect()->back()->with('success', 'Request accepted successfully.');
    }

    /**
     * Deny the specified resource.
     */
    public function deny(Request $request, $interestId): RedirectResponse
    {
        $interest = DB::table('interests')->where('id', $interestId)->first();

        if (!$interest) {
            return redirect()->back()->with('error', 'Interest not found.');
        }

        DB::table('interests')
            ->where('id', $interestId)
            ->update(['status' => 'denied']);

        return redirect()->back()->with('success', 'Request denied successfully.');
    }


}
