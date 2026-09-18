<?php

namespace App\Http\Controllers;

use App\Models\Register;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function Index() : View
    {
        // Total counts
        $totalUsers = DB::table('users')->where('role', '!=', 'admin')->count();

        // Standard users
        $totalStandard = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('role', '=', 'standard')
            ->count();
        $totalStandardMale = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('users.role', '=', 'standard')
            ->where('user_details.gender', '=', 'Male')
            ->count();
        $totalStandardFemale = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('users.role', '=', 'standard')
            ->where('user_details.gender', '=', 'Female')
            ->count();

        // Professional users
        $totalProfessional = DB::table('users')->where('role', '=', 'professional')->count();
        $totalProfessionalMale = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('users.role', '=', 'professional')
            ->where('user_details.gender', '=', 'Male')
            ->count();
        $totalProfessionalFemale = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('users.role', '=', 'professional')
            ->where('user_details.gender', '=', 'Female')
            ->count();

        // Other counts
        $totalMaleUser = DB::table('user_details')->where('gender', '=', 'Male')->count();
        $totalFemaleUser = DB::table('user_details')->where('gender', '=', 'Female')->count();
        $todayUser = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('users.role', '!=', 'admin')
            ->whereDate('users.created_at', Carbon::today())
            ->count();
        $thisMonth = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('users.role', '!=', 'admin')
            ->whereYear('users.created_at', '=', now()->year)
            ->whereMonth('users.created_at', '=', now()->month)
            ->count();
        $thisYear = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('users.role', '!=', 'admin')
            ->whereYear('users.created_at', now()->year)
            ->count();

        //Membership counts
        $membershipFree = DB::table('receipts')
            ->where('package', '=', 'Free')
            ->count();

        $packages = DB::table('packages')->get();
        function getMembershipCount($package): int
        {
            return DB::table('users')
                ->join('user_details', 'users.id', '=', 'user_details.user_id')
                ->leftJoin(
                    DB::raw('(
                SELECT user_id, MAX(package) as package, MAX(expiry_date) as expiry_date, MAX(status) as status
                FROM receipts
                WHERE status IN ("paid", "closed")
                GROUP BY user_id
            ) as r'),
                    'user_details.user_id', '=', 'r.user_id'
                )
                ->where('users.role', '!=', 'admin')
                ->whereRaw('LOWER(
            CASE
                WHEN r.status = "paid" AND (r.expiry_date IS NULL OR r.expiry_date > NOW()) THEN r.package
                ELSE "closed"
            END
        ) = ?', [strtolower($package)])
                ->count();
        }

        // Get the count for memberships
        $membershipCounts = [];
        foreach ($packages as $package) {
            $membershipCounts[$package->name] = getMembershipCount($package->name);
        }

//        $membershipCopper = getMembershipCount('1st Marriage - Plan 1');
//        $membershipSilver = getMembershipCount('1st Marriage - Plan 2');
//        $membershipGold = getMembershipCount('2nd Marriage - Plan 1');
//        $membershipPlatinum = getMembershipCount('2nd Marriage - Plan 2');
//        $membershipDiamond = getMembershipCount('Existing');


        // Total Standard status counts
        $totalActive = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('users.role', '=', 'standard')
            ->where('users.status', '=', 'active')
            ->count();
        $totalPending = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('users.role', '=', 'standard')
            ->where('users.status', '=', 'pending')
            ->count();
        $totalInactive = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('users.role', '=', 'standard')
            ->whereIn('users.status', ['deactivated', 'inactive'])
            ->count();

        // Male Standard status counts
        $totalMaleActive = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('users.role', '=', 'standard')
            ->where('user_details.gender', '=', 'Male')
            ->where('users.status', '=', 'active')
            ->count();
        $totalMalePending = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('users.role', '=', 'standard')
            ->where('user_details.gender', '=', 'Male')
            ->where('users.status', '=', 'pending')
            ->count();
        $totalMaleInactive = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('users.role', '=', 'standard')
            ->where('user_details.gender', '=', 'Male')
            ->whereIn('users.status', ['deactivated', 'inactive'])
            ->count();

        // Female Standard status counts
        $totalFemaleActive = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('users.role', '=', 'standard')
            ->where('user_details.gender', '=', 'Female')
            ->where('users.status', '=', 'active')
            ->count();
        $totalFemalePending = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('users.role', '=', 'standard')
            ->where('user_details.gender', '=', 'Female')
            ->where('users.status', '=', 'pending')
            ->count();
        $totalFemaleInactive = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('users.role', '=', 'standard')
            ->where('user_details.gender', '=', 'Female')
            ->whereIn('users.status', ['deactivated', 'inactive'])
            ->count();

        $couponCounts = DB::table('user_details')->whereNotNull('coupon_code')->groupBy('coupon_code')->count();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalStandard', 'totalProfessional',
            'todayUser', 'thisMonth', 'thisYear',
            'totalMaleUser', 'totalFemaleUser',
            'totalStandardMale', 'totalStandardFemale',
            'totalProfessionalMale', 'totalProfessionalFemale',
            'totalActive', 'totalPending', 'totalInactive',
            'totalMaleActive', 'totalMalePending', 'totalMaleInactive',
            'totalFemaleActive', 'totalFemalePending', 'totalFemaleInactive',
            'membershipFree', 'packages', 'membershipCounts', 'couponCounts'
        ));
    }

    public function Coupons(): View
    {
        $coupons = DB::table('user_details')
            ->select(
                'coupon_code',
                DB::raw("COUNT(*) as total"),
                DB::raw("SUM(CASE WHEN gender = 'male' THEN 1 ELSE 0 END) as male_count"),
                DB::raw("SUM(CASE WHEN gender = 'female' THEN 1 ELSE 0 END) as female_count")
            )
            ->whereNotNull('coupon_code')
            ->groupBy('coupon_code')
            ->get();

        return view('admin.coupons', compact('coupons'));
    }


    public function Register() : View
    {
        return view('admin.register');
    }

    public function Registration(Request $request) : RedirectResponse
    {
        $validatedData = $request->validate([
            'role' => 'required|string',
            'profile_for' => 'required|string',
            'gender' => 'required|string',
            'name' => 'required|string|regex:/^[A-Za-z]+(?:\s[A-Za-z]+)*$/',
            'email' => 'required|string|email|max:255|unique:users',
            'mobile' => 'required|numeric|digits:10|unique:users',
            'country_code' => 'nullable',
            'password' => 'required|min:6|regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d@#$%^&+=!]{6,}$/'
        ], [
            'name.regex' => 'Name must start with an uppercase letter followed by lowercase letters',
            'mobile.numeric' => 'Mobile number must contain only numbers',
            'password.regex' => 'Password must be at least 6 characters and include both letters and numbers'
        ]);

        $user = new User();
        $user->role = $validatedData['role'];
        $user->name = Str::title(strtolower($validatedData['name']));
        $user->email = $validatedData['email'];
        $user->mobile = $validatedData['mobile'];
        $user->country_code = $validatedData['country_code'];
        $user->password = Hash::make($validatedData['password']);
        $user->otp_verified_at = Carbon::now();
        $user->email_verified_at = Carbon::now();
        $user->status = 'pending';
        $user->register_step = 0;
        $user->save();

        $userDetail = new Register();
        $userDetail->user_id = $user->id;
        $userDetail->storeUserDetails(['user_id' => $user->id, 'profile_for' => $validatedData['profile_for'], 'gender' => $validatedData['gender']]);

        return redirect()->route('register-details.create', ['user_id' => $user->id])->with('success','Registered Successfully');
    }

    public function userVerification(): View
    {
        $userVerifications = DB::table('users')
            ->where('role', '!=', 'admin')
            ->select('id', 'name', 'mobile', 'photo', 'email', 'email_verified_at', 'otp_verified_at', 'photo_verified_at')
            ->get();
        return view('admin.securedSettings.user-verification', compact('userVerifications'));
    }

    public function userContacts(): View
    {
        $userContacts = DB::table('contact_requests as cr')
            ->join('users as u', 'u.id', '=', 'cr.user_id')
            ->select(
                'cr.user_id',
                'u.name',
                'u.mobile',
                'u.email',
                DB::raw('MAX(cr.created_at) as created_at')
            )
            ->groupBy('cr.user_id', 'u.name', 'u.mobile', 'u.email')
            ->orderByDesc('created_at')
            ->get();
        return view('admin.securedSettings.user-contacts', compact('userContacts'));
    }

    public function contactViewsData($id): View
    {
        // Contacts this user has viewed (sent requests to)
        $viewedContacts = DB::table('contact_requests as cr')
            ->join('users as u', 'u.id', '=', 'cr.profile_id')
            ->where('cr.user_id', $id)
            ->select('u.id', 'u.name', 'u.mobile', 'u.email')
            ->orderByDesc('cr.created_at')
            ->get();

        // Users who viewed this user's contact (received requests from)
        $viewerContacts = DB::table('contact_requests as cr')
            ->join('users as u', 'u.id', '=', 'cr.user_id')
            ->where('cr.profile_id', $id)
            ->select('u.id', 'u.name', 'u.mobile', 'u.email')
            ->orderByDesc('cr.created_at')
            ->get();

        return view('admin.securedSettings.contactViewsData', compact('viewedContacts', 'viewerContacts'));
    }


    public function complaintPortal(): View
    {
        $enquiries = DB::table('enquiry')->get();
        return view('admin.complaint_portal', compact('enquiries'));
    }

    public function updateEnquiry(Request $request, $id): RedirectResponse
    {
        DB::table('enquiry')->where('id', $id)->update([
            'status' => $request->input('status')
        ]);
        return redirect('admin/complaint_portal')->with('success', 'Enquiry Status Updated');
    }

    public function deleteEnquiry(Request $request, $id): RedirectResponse
    {
        DB::table('enquiry')->where('id', $id)->delete();
        return redirect('admin/complaint_portal')->with('success', 'Enquiry Status Deleted');
    }

    public function profileUpdation(): View
    {
        $updateCounts = DB::table('profile_update_settings')->get();
        return view('admin.securedSettings.profileUpdation', compact('updateCounts'));
    }

    public function profileUpdateStore(Request $request): RedirectResponse
    {
        $request->validate([
            'count' => 'required|integer|min:1',
        ]);

        $exists = DB::table('profile_update_settings')->exists();

        if ($exists) {
            return redirect()->back()
                ->with('error', 'Profile update count already set.');
        }

        DB::table('profile_update_settings')->insert([
            'count' => $request->input('count'),
        ]);

        return redirect()->back()->with('success', 'Profile update count saved successfully.');
    }

    public function profileEditUpdate(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'count' => 'required|integer|min:1'
        ]);

        DB::table('profile_update_settings')->where('id', $id)->update([
            'count' => $request->input('count'),
        ]);

        return redirect()->back()->with('success', 'Profile update count updated successfully.');
    }

    public function profileUpdateDelete($id): RedirectResponse
    {
        DB::table('profile_update_settings')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Profile update count deleted successfully.');
    }

    public function photoVerify(Request $request, $id): RedirectResponse
    {
        DB::table('users')->where('id', $id)->update([
            'photo_verified_at' => $request->input('photo_verified_at'),
        ]);
        return redirect()->back()->with('success', 'Photo Verification updated successfully.');
    }

    public function newDataApproval(): View
    {
        $newDatas = DB::table('users as u')
            ->join('user_details as ud', 'ud.user_id', '=', 'u.id')
            ->select(
                'u.id',
                'ud.new_community',
                'ud.new_state',
                'ud.new_city'
            )
            ->where(function ($query) {
                $query->whereNotNull('ud.new_community')
                    ->orWhereNotNull('ud.new_state')
                    ->orWhereNotNull('ud.new_city');
            })
            ->get();

        return view('admin.customize.new_data_approval', compact('newDatas'));
    }

    public function EditedProfileLists(): View
    {
        $editedProfiles = DB::table('triumph_portal')->get();
        return view('admin.securedSettings.edited_profiles', compact('editedProfiles'));
    }

    public function editedProfileStatusUpdate(Request $request, int $id): RedirectResponse
    {
        $status = $request->string('status')->value();

        /*--- Grab the log row ---*/
        $log = DB::table('triumph_portal')->where('id', $id)->first();
        if (!$log) {
            return back()->with('error', 'Change‑log row not found.');
        }

        try {
            /*--- Everything in a single transaction ---*/
            DB::transaction(function () use ($status, $log, $id) {
                /*--- update the log status ---*/
                DB::table('triumph_portal')
                    ->where('id', $id)
                    ->update([
                        'status'     => $status,
                        'changed_at' => now(),
                    ]);

                /*--- if rejected or still pending, stop here ---*/
                if ($status !== 'accepted') {
                    return;
                }

                /*--- Work out which table owns the column ---*/
                $detailCols = Schema::getColumnListing('user_details');
                $userCols   = Schema::getColumnListing('users');
                $column     = $log->field_name;

                if (in_array($column, $detailCols, true)) {
                    /*--- update user_details ---*/
                    DB::table('user_details')
                        ->where('user_id', $log->user_id)
                        ->update([
                            $column      => $log->new_value,
                            'updated_at' => now(),
                        ]);

                } elseif (in_array($column, $userCols, true)) {
                    /*-- Unique Email, mobile --*/
                    if (in_array($column, ['email', 'mobile'], true)) {
                        $exists = DB::table('users')
                            ->where($column, $log->new_value)
                            ->where('id', '!=', $log->user_id)
                            ->exists();
                        if ($exists) {
                            throw new \Exception(ucfirst($column).' is already taken.');
                        }
                    }

                    /*--- update users ---*/
                    DB::table('users')
                        ->where('id', $log->user_id)
                        ->update([
                            $column      => $log->new_value,
                            'updated_at' => now(),
                        ]);

                } else {
                    throw new \Exception('Column “'.$column.'” does not exist in users or user_details.');
                }
            });

        } catch (\Exception $e) {
            return back()->with('error', 'Update failed: '.$e->getMessage());
        }
        return back()->with('success', 'Edited profile status updated.');
    }


    public function Help(): View
    {
        return view('admin.help-guide');
    }

    /**
     * Display logged-in admin's profile
     */
    public function myProfile(): View
    {
        $user = Auth::user();
        return view('admin.profile', compact('user'));
    }

    /**
     * Update logged-in admin's profile information
     */
    public function updateMyProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'mobile' => 'required|string|max:20|unique:users,mobile,' . $user->id,
            'country_code' => 'nullable|string|max:10',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        try {
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->mobile = $request->input('mobile');
            if ($request->filled('country_code')) {
                $user->country_code = $request->input('country_code');
            }

            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $photoName = 'admin_' . $user->id . '_' . time() . '.' . $photo->getClientOriginalExtension();
                $photo->move(public_path('Profile Image'), $photoName);

                // Remove old photo if exists
                if (!empty($user->photo) && file_exists(public_path('Profile Image/' . $user->photo))) {
                    @unlink(public_path('Profile Image/' . $user->photo));
                }

                $user->photo = $photoName;
            }

            $user->save();

            return redirect()->route('admin.profile')->with('success', 'Profile details updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update profile: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Update logged-in admin's password
     */
    public function updateMyPassword(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->input('current_password'), $user->password)) {
            return redirect()->back()->with('error', 'The current password you entered is incorrect.');
        }

        try {
            $user->password = Hash::make($request->input('password'));
            $user->save();

            return redirect()->route('admin.profile')->with('success', 'Password changed successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update password: ' . $e->getMessage());
        }
    }

}
