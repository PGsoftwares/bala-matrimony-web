<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helpers\DataController;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    protected PagesController $pagesController;
    protected ApiHelperController $apiHelper;

    public function __construct(PagesController $pagesController, ApiHelperController $apiHelper) {
        $this->pagesController = $pagesController;
        $this->apiHelper = $apiHelper;
    }

    public function getUserProfileData(Request $request): JsonResponse
    {
        $userId = $request->input('user_id');
        $profileId = $request->input('profile_id');

        $authPackage = DataController::getUserPackageDetails($userId)['package'] ?? '';
        $profilePackage = $profileId ? DataController::getUserPackageDetails($profileId)['package'] : '';

        // Fetch user details
        $fetchUserDetails = function ($id) {
            return DB::table('users')
                ->join('user_details', 'users.id', '=', 'user_details.user_id')
                ->leftJoin('settings', 'user_details.user_id', '=', 'settings.user_id')
                ->where('users.id', $id)
                ->where('users.status', 'active')
                ->select(
                    'users.*', 'user_details.*',
                    'settings.profile_picture_visibility', 'settings.horoscope_picture_visibility', 'settings.mobile_number_visibility', 'settings.email_visibility', 'settings.name_visibility', 'settings.date_of_birth_visibility',
                    DB::raw('CASE WHEN users.email_verified_at IS NOT NULL THEN "Verified" ELSE "Not Verified" END as EmailVerifiedStatus')
                )
                ->first();
        };

        $userAndUserDetails = $fetchUserDetails($userId);
        $ProfileUserDetails = $profileId ? $fetchUserDetails($profileId) : null;

        if (!$userAndUserDetails) {
            return response()->json(['status' => false, 'message' => 'User not found']);
        }

        // Assign correct package values
        $userAndUserDetails->package = $authPackage;
        if ($ProfileUserDetails) {
            $ProfileUserDetails->package = $profilePackage;
        }

        // Format birth time
        $formatBirthTime = function ($details) {
            if (!empty($details->birth_time)) {
                $details->birth_time = Carbon::parse($details->birth_time)->format('h:i A');
            }
        };

        $formatBirthTime($userAndUserDetails);
        if ($ProfileUserDetails) {
            $formatBirthTime($ProfileUserDetails);
        }

        // Set image paths
        $setImagePaths = function (&$details, $isOwnProfile, $authPackage) {
            $details->age = Carbon::parse($details->dob)->age;

            $profileImages = [];
            if (!empty($details->profile_image)) {
                $profileImages[] = [
                    'image_id' => $details->user_id,
                    'image_type' => 'profile_image',
                    'image_url' => $isOwnProfile
                        ? asset('Profile Image/' . $details->profile_image)
                        : ApiHelperController::ImageUrl(
                            $details->profile_image,
                            $details->profile_picture_visibility,
                            $authPackage,
                            $details->gender
                        ),
                ];
            }

            $galleryImages = DB::table('gallery')
                ->where('user_id', $details->user_id)
                ->get()
                ->map(function ($gallery) {
                    return [
                        'image_id' => $gallery->id,
                        'image_type' => 'gallery_image',
                        'image_url' => file_exists(public_path('GalleryImage/' . $gallery->image))
                            ? asset('GalleryImage/' . $gallery->image)
                            : ''
                    ];
                })
                ->filter(fn($img) => !empty($img['image_url']))
                ->values();

            $details->profile_image = array_merge($profileImages, $galleryImages->toArray());

            $details->horoscope_image = $isOwnProfile
                ? asset('Horoscope Image/' . $details->horoscope_image)
                : ApiHelperController::HoroscopeImageUrl(
                    $details->horoscope_image,
                    $details->horoscope_picture_visibility,
                    $authPackage
                );
        };

        // Apply privacy settings only when viewing another profile
        $mapVisibilitySettings = function (&$details, $isOwnProfile, $authPackage) {
            if (!$isOwnProfile) {
                $details->raw_dob = $details->dob;
            }
            if (!$isOwnProfile) {
                $details->raw_dob = $details->dob;

                $details->name = ApiHelperController::privacyData(
                    $details->name,
                    $details->name_visibility,
                    $authPackage
                );

                $details->mobile = ApiHelperController::privacyData(
                    $details->mobile,
                    $details->mobile_number_visibility,
                    $authPackage
                );

                $details->email = ApiHelperController::privacyData(
                    $details->email,
                    $details->email_visibility,
                    $authPackage
                );

                $details->dob = ApiHelperController::privacyData(
                    $details->dob,
                    $details->date_of_birth_visibility,
                    $authPackage
                );
            }
        };

        $isOwnProfile = empty($profileId) || $userId == $profileId;

        $setImagePaths($userAndUserDetails, $isOwnProfile, $authPackage);;
        $mapVisibilitySettings($userAndUserDetails, $isOwnProfile, $authPackage);;

        if ($ProfileUserDetails) {
            $setImagePaths($ProfileUserDetails, false, $authPackage);
            $mapVisibilitySettings($ProfileUserDetails, false, $authPackage);
        }

        $profileCompletionPercentage = $this->apiHelper->getProfileCompletion($userId);

        // Return for own profile
        if (empty($profileId)) {
            return response()->json([
                'status' => true,
                'message' => 'User details retrieved successfully',
                'user_details' => $userAndUserDetails,
                'profile_completion_percentage' => $profileCompletionPercentage,
            ]);
        }

        // ================= Preference Matching =================
        $matchedPreferences = [];
        $preference = DB::table('set_preferences')->where('user_id', $profileId)->first();

        if ($preference && $userAndUserDetails) {
            $fieldsToCheck = [
                'mother_tongue', 'marital_status', 'drinking_habit', 'smoking_habit',
                'eating_habit', 'physical_status', 'religion', 'caste',
                'qualification', 'education', 'occupation_type', 'occupation',
                'employed_in', 'rashi', 'nakshatra', 'lagnam', 'dosham', 'country', 'state', 'city',
                'work_country', 'visa_status', 'ethnicity', 'nationality'
            ];

            foreach ($fieldsToCheck as $field) {
                $preferenceValue = $preference->$field ?? null;
                $profileValue = $userAndUserDetails->$field ?? null;

                $matchedPreferences[] = [
                    'preference_name' => $field,
                    'value' => $preferenceValue ?: 'Any',
                    'match' => empty($preferenceValue) || in_array($profileValue, array_map('trim', explode(',', $preferenceValue)))
                ];
            }

            // Age check
            if (!empty($userAndUserDetails->raw_dob)) {
                $age = Carbon::parse($userAndUserDetails->raw_dob)->age;
                $matchedPreferences[] = [
                    'preference_name' => 'age',
                    'value' => ($preference->min_age && $preference->max_age)
                        ? "{$preference->min_age} - {$preference->max_age}"
                        : 'Any',
                    'match' => !($preference->min_age && $preference->max_age) || $age >= $preference->min_age && $age <= $preference->max_age
                ];
            }

            // Height check
            if (!empty($userAndUserDetails->height)) {
                $height = $userAndUserDetails->height;
                $matchedPreferences[] = [
                    'preference_name' => 'height',
                    'value' => ($preference->height_from && $preference->height_to)
                        ? "{$preference->height_from} - {$preference->height_to}"
                        : 'Any',
                    'match' => !($preference->height_from && $preference->height_to) || $height >= $preference->height_from && $height <= $preference->height_to
                ];
            }

            // Monthly Income check
            if (!empty($userAndUserDetails->monthly_income)) {
                $income = $userAndUserDetails->monthly_income;
                $matchedPreferences[] = [
                    'preference_name' => 'monthly_income',
                    'value' => ($preference->monthly_income_from && $preference->monthly_income_to)
                        ? "{$preference->monthly_income_from} - {$preference->monthly_income_to}"
                        : 'Any',
                    'match' => !($preference->monthly_income_from && $preference->monthly_income_to) || $income >= $preference->monthly_income_from && $income <= $preference->monthly_income_to
                ];
            }

            // Weight check
            if (!empty($userAndUserDetails->weight)) {
                $weight = $userAndUserDetails->weight;
                $matchedPreferences[] = [
                    'preference_name' => 'weight',
                    'value' => ($preference->weight_from && $preference->weight_to)
                        ? "{$preference->weight_from} - {$preference->weight_to}"
                        : 'Any',
                    'match' => !($preference->weight_from && $preference->weight_to) || $weight >= $preference->weight_from && $weight <= $preference->weight_to
                ];
            }
        }

        // ================== View Tracking & Wishlist ====================
        $existingProfileView = DB::table('profile_views')
            ->where('viewer_id', $profileId)
            ->where('viewed_id', $userId)
            ->first();

        $wishlistStatus = DB::table('wishlists')
            ->where('user_id', $userId)
            ->where('profile_id', $profileId)
            ->exists();

        $viewContacts = DB::table('contact_requests')
            ->where('user_id', $userId)
            ->where('profile_id', $profileId)
            ->exists();

        $interestStatus = DB::table('interests')
            ->where(function ($query) use ($userId, $profileId) {
                $query->where('sender_id', $userId)->where('receiver_id', $profileId);
            })
            ->orWhere(function ($query) use ($userId, $profileId) {
                $query->where('sender_id', $profileId)->where('receiver_id', $userId);
            })
            ->orderBy('created_at', 'desc')
            ->first();

        $interest = '';
        if ($interestStatus) {
            $interest = match ($interestStatus->status) {
                'accepted', 'denied' => $interestStatus->status,
                'pending' => ($interestStatus->sender_id == $userId) ? 'Requested' : 'Interest Received',
                default => '',
            };
        }

        if (!$existingProfileView) {
            DB::table('profile_views')->insert([
                'viewer_id' => $profileId,
                'viewed_id' => $userId,
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'User details retrieved successfully',
            'user_details' => $ProfileUserDetails,
            'matched_preference' => $matchedPreferences,
            'wishlist' => $wishlistStatus,
            'view_contact' => $viewContacts,
            'interest' => $interest,
        ]);
    }

    public function editProfileImage(Request $request): JsonResponse
    {
        $userId = $request->input('user_id');
        // Update Profile Image
        if ($request->hasFile('profile_image')) {
            $profileImage = $request->file('profile_image');
            $profileImageName = time() . '.' . $profileImage->getClientOriginalExtension();

            $profileImageDirectory = public_path('Profile Image');

            $currentProfileImage = DB::table('user_details')->where('user_id', $userId)->value('profile_image');

            if ($currentProfileImage && file_exists($profileImageDirectory . '/' . basename($currentProfileImage))) {
                unlink($profileImageDirectory . '/' . basename($currentProfileImage));
            }

            $profileImage->move($profileImageDirectory, $profileImageName);

            DB::table('user_details')->where('user_id', $userId)->update(['profile_image' =>  $profileImageName]);
        }

        // Update Gallery Image
        if ($request->hasFile('gallery_image') && $request->has('gallery_image_id')) {
            $galleryImage = $request->file('gallery_image');
            $galleryImageName = time() . '.' . $galleryImage->getClientOriginalExtension();

            $galleryImageRecord = DB::table('gallery')->where('id', $request->input('gallery_image_id'))->where('user_id', $userId)->first(['image']);

            if (!$galleryImageRecord) {
                return response()->json(['status' => false, 'message' => 'Gallery image not found'], 404);
            }

            $galleryImageDirectory = public_path('GalleryImage');

            if ($galleryImageRecord->image && file_exists($galleryImageDirectory . '/' . basename($galleryImageRecord->image))) {
                unlink($galleryImageDirectory . '/' . basename($galleryImageRecord->image));
            }

            $galleryImage->move($galleryImageDirectory, $galleryImageName);
            DB::table('gallery')->where('id', $request->input('gallery_image_id'))->update(['image' => $galleryImageName]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Images updated successfully'
        ]);
    }


    public function deleteProfileImage(Request $request): JsonResponse
    {

        $userId = $request->input('user_id');
        $imageType = $request->input('image_type');

        if ($imageType === 'profile_image') {
            // Delete Profile Image
            $currentProfileImage = DB::table('user_details')->where('user_id', $userId)->value('profile_image');
            $profileImageDirectory = public_path('Profile Image');

            if ($currentProfileImage && file_exists($profileImageDirectory . '/' . basename($currentProfileImage))) {
                unlink($profileImageDirectory . '/' . basename($currentProfileImage));

                DB::table('user_details')->where('user_id', $userId)->update(['profile_image' => null]);

                return response()->json(['status' => true, 'message' => 'Profile image deleted successfully']);
            }
            return response()->json(['status' => false, 'message' => 'Profile image not found'], 404);

        } elseif ($imageType === 'gallery_image') {
            // Delete Gallery Image
            $galleryImageId = $request->input('gallery_image_id');
            $galleryImageRecord = DB::table('gallery')->where('id', $galleryImageId)->where('user_id', $userId)->first(['image']);

            if (!$galleryImageRecord) {
                return response()->json(['status' => false, 'message' => 'Gallery image not found'], 404);
            }

            $galleryImageDirectory = public_path('GalleryImage');

            if ($galleryImageRecord->image && file_exists($galleryImageDirectory . '/' . basename($galleryImageRecord->image))) {
                unlink($galleryImageDirectory . '/' . basename($galleryImageRecord->image));
                DB::table('gallery')->where('id', $galleryImageId)->delete();
                return response()->json(['status' => true, 'message' => 'Gallery image deleted successfully']);
            }

            return response()->json(['status' => false, 'message' => 'Gallery image not found'], 404);
        }

        return response()->json(['status' => false, 'message' => 'Invalid image type'], 400);
    }

    public function updateUserProfileData(Request $request): JsonResponse
    {
        $userId = $request->input('user_id') ?? $request->input('id');

        if (!$userId) {
            return response()->json(['message' => 'User ID is required.'], 400);
        }

        DB::beginTransaction();
        try {
            $hasUpdated = false;

            if ($request->hasAny(['name', 'password'])) {
                $this->updateUserProfile($request, $userId);
                $hasUpdated = true;
            }

            if ($request->hasAny(['profile_for', 'gender', 'dob', 'birth_time', 'hour', 'minute', 'ampm', 'birth_hour', 'birth_minute', 'birth_ampm', 'birth_country', 'birth_state', 'birth_city', 'mother_tongue', 'marital_status', 'religion', 'caste', 'sub_caste', 'new_community', 'listed_community', 'ethnicity', 'nationality', 'profile_image'])) {
                $this->updatePersonalInfo($request, $userId);
                $hasUpdated = true;
            }

            if ($request->hasAny(['physical_status', 'skin_tone', 'height', 'weight', 'body_type'])) {
                $this->updatePhysicalInfo($request, $userId);
                $hasUpdated = true;
            }

            if ($request->hasAny(['eating_habit', 'drinking_habit', 'smoking_habit'])) {
                $this->updateHabitualInfo($request, $userId);
                $hasUpdated = true;
            }

            if ($request->hasAny(['qualification', 'education', 'occupation', 'occupation_type', 'employed_in', 'monthly_income', 'work_country', 'visa_status', 'about_me'])) {
                $this->updateEducationInfo($request, $userId);
                $hasUpdated = true;
            }

            if ($request->hasAny(['father_name', 'father_profession', 'mother_name', 'mother_profession', 'family_type', 'family_status', 'family_values', 'family_god', 'no_of_brother', 'elder_brother', 'younger_brother', 'elder_married_brother', 'younger_married_brother', 'no_of_sister', 'elder_sister', 'younger_sister', 'elder_married_sister', 'younger_married_sister', 'property_details', 'property_info', 'about_family', 'marriage_timeline'])) {
                $this->updateFamilyInfo($request, $userId);
                $hasUpdated = true;
            }

            if ($request->hasAny(['rashi', 'nakshatra', 'lagnam', 'padam', 'kulam', 'gothram', 'dosham', 'horoscope_image'])) {
                $this->updateHoroscopeInfo($request, $userId);
                $hasUpdated = true;
            }

            if ($request->hasAny(['country', 'state', 'listed_state', 'new_state', 'city', 'listed_city', 'new_city', 'address', 'pin_code', 'latitude', 'longitude'])) {
                $this->updateAddressInfo($request, $userId);
                $hasUpdated = true;
            }

            // If any data was updated, increment the update count
            if ($hasUpdated) {
                DB::table('users')->where('id', $userId)->increment('profile_update_count');
            }

            DB::commit();
            return response()->json(['message' => 'Profile Updated Successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to Update Profile', 'error' => $e->getMessage()], 500);
        }
    }

    private function updateUserProfile(Request $request, $id): void
    {
        $updateData = [];
        if ($request->has('name') && $request->filled('name')) {
            $updateData['name'] = trim($request->input('name'));
        }
        if ($request->has('password') && $request->filled('password')) {
            $updateData['password'] = Hash::make($request->input('password'));
        }
        if (!empty($updateData) && !empty($id)) {
            DB::table('users')->where('id', $id)->update($updateData);
        }
    }

    private function updatePersonalInfo(Request $request, $userId): void
    {
        $birthTime = null;
        $hour = $request->input('hour') ?? $request->input('birth_hour');
        $minute = $request->input('minute') ?? $request->input('birth_minute') ?? '00';
        $ampm = $request->input('ampm') ?? $request->input('birth_ampm');

        if (!empty($hour) && !empty($ampm)) {
            $birthTime = date('H:i:s', strtotime("{$hour}:{$minute} {$ampm}"));
        } elseif ($request->filled('birth_time')) {
            try {
                $birthTime = Carbon::createFromFormat('h:i A', $request->input('birth_time'))->format('H:i:s');
            } catch (\Exception $ex) {
                $birthTime = date('H:i:s', strtotime($request->input('birth_time')));
            }
        }

        $updateData = $request->only([
            'profile_for', 'gender', 'dob', 'birth_country', 'birth_state', 'birth_city',
            'mother_tongue', 'marital_status', 'religion', 'caste', 'sub_caste',
            'new_community', 'listed_community', 'ethnicity', 'nationality'
        ]);

        if ($birthTime) {
            $updateData['birth_time'] = $birthTime;
        }

        if ($request->hasFile('profile_image')) {
            $currentImage = DB::table('user_details')->where('user_id', $userId)->value('profile_image');
            $updateData['profile_image'] = $this->handleFileUpload($request->file('profile_image'), 'Profile Image', $currentImage);
        } elseif ($request->filled('profile_image') && strpos($request->input('profile_image'), 'base64') !== false) {
            $image_parts = explode(";base64,", $request->input('profile_image'));
            if (isset($image_parts[1])) {
                $image_base64 = base64_decode($image_parts[1]);
                $profileImageName = date('dmY_His') . '.jpg';
                file_put_contents(public_path('Profile Image/' . $profileImageName), $image_base64);
                $updateData['profile_image'] = $profileImageName;
            }
        }

        DB::table('user_details')->where('user_id', $userId)->update($updateData);
    }

    private function updatePhysicalInfo(Request $request, $userId): void
    {
        $updateData = $request->only([
            'physical_status', 'skin_tone', 'height',
            'weight', 'body_type',
        ]);

        DB::table('user_details')->where('user_id', $userId)->update($updateData);
    }

    private function updateHabitualInfo(Request $request, $userId): void
    {
        $updateData = $request->only([
            'eating_habit', 'drinking_habit', 'smoking_habit',
        ]);

        DB::table('user_details')->where('user_id', $userId)->update($updateData);
    }

    private function updateEducationInfo(Request $request, $userId): void
    {
        $updateData = $request->only([
            'qualification', 'education', 'employed_in', 'occupation_type',
            'occupation', 'monthly_income', 'work_country', 'visa_status', 'about_me',
        ]);

        DB::table('user_details')->where('user_id', $userId)->update($updateData);
    }

    private function updateFamilyInfo(Request $request, $userId): void
    {
        $updateData = $request->only([
            'father_name', 'father_profession', 'mother_name', 'mother_profession', 'family_type', 'family_status',
            'family_values', 'family_god', 'no_of_brother',
            'elder_brother', 'younger_brother', 'elder_married_brother', 'younger_married_brother',
            'no_of_sister', 'elder_sister', 'younger_sister', 'elder_married_sister', 'younger_married_sister',
            'property_info', 'about_family', 'marriage_timeline'
        ]);

        if ($request->has('property_details')) {
            $updateData['property_details'] = DataController::formatPropertyDetails($request->input('property_details'));
        }

        DB::table('user_details')->where('user_id', $userId)->update($updateData);
    }

    private function updateHoroscopeInfo(Request $request, $userId): void
    {
        $updateData = $request->only([
            'rashi', 'nakshatra', 'lagnam', 'padam', 'kulam', 'gothram', 'dosham'
        ]);

        if ($request->hasFile('horoscope_image')) {
            // Get current image name from database
            $currentImage = DB::table('user_details')->where('user_id', $userId)->value('horoscope_image');

            // Upload new image and delete old one
            $updateData['horoscope_image'] = $this->handleFileUpload($request->file('horoscope_image'), 'Horoscope Image', $currentImage);
        } elseif ($request->filled('horoscope_image') && strpos($request->input('horoscope_image'), 'base64') !== false) {
            $image_parts = explode(";base64,", $request->input('horoscope_image'));
            if (isset($image_parts[1])) {
                $image_base64 = base64_decode($image_parts[1]);
                $horoscopeImageName = date('dmY_His') . '.jpg';
                file_put_contents(public_path('Horoscope Image/' . $horoscopeImageName), $image_base64);
                $updateData['horoscope_image'] = $horoscopeImageName;
            }
        }

        DB::table('user_details')->where('user_id', $userId)->update($updateData);
    }


    private function updateAddressInfo(Request $request, $userId): void
    {
        $updateData = $request->only([
            'country', 'state', 'listed_state', 'new_state', 'city', 'listed_city', 'new_city',
            'address', 'pin_code', 'latitude', 'longitude'
        ]);
        DB::table('user_details')->where('user_id', $userId)->update($updateData);
    }

    private function handleFileUpload($file, $folder, $currentImage = null): string
    {
        // Delete the old image if it exists
        if ($currentImage && file_exists(public_path($folder . '/' . $currentImage))) {
            unlink(public_path($folder . '/' . $currentImage));
        }

        // Upload the new image
        $imageName = date('dmY_His') . '.' . $file->getClientOriginalExtension();
        $file->move(public_path($folder), $imageName);

        return $imageName;
    }

    public function allUserProfiles(Request $request): JsonResponse
    {
        $userId = (int) $request->input('user_id');
        $page = (int) $request->input('page_no', 1);
        $perPage = 30;

        // Get user gender
        $userGender = DB::table('user_details')
            ->where('user_id', $userId)
            ->value('gender');

        $oppositeGender = match (strtolower($userGender)) {
            'male' => 'female',
            'female' => 'male',
            default => null,
        };

        if (!$oppositeGender) {
            return response()->json([
                'message' => 'Invalid gender or user not found.',
                'all_profiles' => [
                    'all_profile_list' => [],
                    'all_profile_list_count' => 0,
                    'total_pages' => 0,
                    'current_page' => $page,
                ],
            ]);
        }

        // Get package status for privacy checks
        $authPackage = DataController::getUserPackageDetails($userId)['is_active'] ?? '';

        $baseQuery = DB::table('user_details')
            ->join('users', 'user_details.user_id', '=', 'users.id')
            ->leftJoin('settings', 'user_details.user_id', '=', 'settings.user_id')
            ->where('users.status', 'active')
            ->where('user_details.gender', $oppositeGender);

        $totalCount = (clone $baseQuery)->count();

        // Fetch paginated profiles
        $profiles = $baseQuery
            ->select(
                'users.id',
                'users.name',
                'users.email_verified_at',
                'user_details.gender',
                'user_details.profile_image',
                'user_details.marital_status',
                'user_details.caste',
                'user_details.employed_in',
                'user_details.height',
                'user_details.city',
                'user_details.latitude',
                'user_details.longitude',
                'user_details.dob',
                'settings.profile_picture_visibility',
                'settings.name_visibility',
                'settings.date_of_birth_visibility',
                'user_details.created_at'
            )
            ->orderBy('user_details.created_at', 'desc')
            ->offset(($page - 1) * $perPage)
            ->limit($perPage)
            ->get();

        // Apply privacy
        foreach ($profiles as $profile) {
            $profile->package = DataController::getUserPackageDetails($profile->id)['package'] ?? '';
            $profile->age = $profile->dob ? Carbon::parse($profile->dob)->age : null;

            $profile->name = ApiHelperController::privacyData(
                $profile->name, $profile->name_visibility, $authPackage
            );

            $profile->dob = ApiHelperController::privacyData(
                $profile->dob, $profile->date_of_birth_visibility, $authPackage
            );

            $profile->profile_image = ApiHelperController::ImageUrl(
                $profile->profile_image, $profile->profile_picture_visibility,
                $authPackage, $profile->gender
            );

            $profile->isVerified = ApiHelperController::isVerified($profile->email_verified_at);
        }

        return response()->json([
            'all_profiles' => [
                'all_profile_list' => $profiles,
                'all_profile_list_count' => count($profiles),
                'total_pages' => ceil($totalCount / $perPage),
                'current_page' => $page,
            ],
        ]);
    }

    public function locationBasedProfiles(Request $request): JsonResponse
    {
        $userId = (int) $request->input('user_id');
        $authPackage = DataController::getUserPackageDetails($userId)['is_active'] ?? '';

        $userGender = DB::table('user_details')->where('user_id', $userId)->value('gender');

        $oppositeGender = match (strtolower($userGender)) {
            'male' => 'female',
            'female' => 'male',
            default => null,
        };

        if (!$oppositeGender) {
            return response()->json([
                'message' => 'Invalid or undefined user found.',
                'all_location_based_profiles' => [
                    'all_location_based_profile_list' => [],
                    'all_location_based_profile_count' => 0,
                ],
            ]);
        }

        // Get preferred location (city)
        $userCity = DB::table('set_preferences')->where('user_id', $userId)->value('city');

        if (!$userCity) {
            return response()->json([
                'message' => 'Preferred location not set.',
                'all_location_based_profiles' => [
                    'all_location_based_profile_list' => [],
                    'all_location_based_profile_count' => 0,
                ],
            ]);
        }

        $preferredCities = array_map('trim', explode(',', $userCity));

        // Fetch matches
        $profiles = DB::table('user_details')
            ->join('users', 'user_details.user_id', '=', 'users.id')
            ->leftJoin('settings', 'user_details.user_id', '=', 'settings.user_id')
            ->where('user_details.gender', $oppositeGender)
            ->where('user_details.city', $preferredCities)
            ->where('users.status', 'active')
            ->orderBy('user_details.created_at', 'desc')
            ->select(
                'users.id',
                'users.name',
                'user_details.gender',
                'user_details.profile_image',
                'user_details.dob',
                'user_details.height',
                'user_details.city',
                'settings.profile_picture_visibility',
                'settings.name_visibility',
                'settings.date_of_birth_visibility'
            )
            ->get();

        foreach ($profiles as $profile) {
            $profilePackage = DataController::getUserPackageDetails($profile->id)['package'] ?? '';
            $profile->package = $profilePackage;
            $profile->age = $profile->dob ? Carbon::parse($profile->dob)->age : '';

            $profile->name = ApiHelperController::privacyData(
                $profile->name, $profile->name_visibility, $authPackage
            );

            $profile->dob = ApiHelperController::privacyData(
                $profile->dob, $profile->date_of_birth_visibility, $authPackage
            );

            $profile->profile_image = ApiHelperController::ImageUrl(
                $profile->profile_image, $profile->profile_picture_visibility,
                $authPackage, $profile->gender
            );
        }

        return response()->json([
            'all_location_based_profiles' => [
                'all_location_based_profile_list' => $profiles,
                'all_location_based_profile_count' => $profiles->count(),
            ],
        ]);
    }


    public function educationBasedProfiles(Request $request): JsonResponse
    {
        $userId = (int) $request->input('user_id');
        $authPackage = DataController::getUserPackageDetails($userId)['is_active'] ?? '';

        $userGender = DB::table('user_details')->where('user_id', $userId)->value('gender');
        $oppositeGender = match (strtolower($userGender)) {
            'male' => 'female',
            'female' => 'male',
            default => null,
        };

        if (!$oppositeGender) {
            return response()->json([
                'message' => 'Invalid or undefined user found.',
                'all_education_based_profiles' => [
                    'all_education_based_profile_list' => [],
                    'all_education_based_profile_count' => 0,
                ],
            ]);
        }

        $preferredEducationRaw = DB::table('set_preferences')->where('user_id', $userId)->value('education');

        if (!$preferredEducationRaw) {
            return response()->json([
                'message' => 'Preferred education not set.',
                'all_education_based_profiles' => [
                    'all_education_based_profile_list' => [],
                    'all_education_based_profile_count' => 0,
                ],
            ]);
        }

        $preferredEducations = array_map('trim', explode(',', $preferredEducationRaw));

        // Fetch education-based matches
        $profiles = DB::table('user_details')
            ->join('users', 'user_details.user_id', '=', 'users.id')
            ->leftJoin('settings', 'user_details.user_id', '=', 'settings.user_id')
            ->where('user_details.gender', $oppositeGender)
            ->whereIn('user_details.education', $preferredEducations)
            ->where('users.status', 'active')
            ->orderBy('user_details.created_at', 'desc')
            ->select(
                'users.id',
                'users.name',
                'user_details.gender',
                'user_details.profile_image',
                'user_details.dob',
                'user_details.height',
                'user_details.city',
                'settings.profile_picture_visibility',
                'settings.name_visibility',
                'settings.date_of_birth_visibility'
            )
            ->get();

        foreach ($profiles as $profile) {
            $profilePackage = DataController::getUserPackageDetails($profile->id)['package'] ?? '';
            $profile->package = $profilePackage;
            $profile->age = $profile->dob ? Carbon::parse($profile->dob)->age : '';
            $profile->name = ApiHelperController::privacyData($profile->name, $profile->name_visibility, $authPackage);
            $profile->dob = ApiHelperController::privacyData($profile->dob, $profile->date_of_birth_visibility, $authPackage);
            $profile->profile_image = ApiHelperController::ImageUrl($profile->profile_image, $profile->profile_picture_visibility, $authPackage, $profile->gender);
        }

        return response()->json([
            'all_education_based_profiles' => [
                'all_education_based_profile_list' => $profiles,
                'all_education_based_profile_count' => $profiles->count(),
            ],
        ]);
    }

    public function occupationBasedProfiles(Request $request): JsonResponse
    {
        $userId = (int) $request->input('user_id');
        $authPackage = DataController::getUserPackageDetails($userId)['is_active'] ?? '';

        // Get user's gender
        $userGender = DB::table('user_details')->where('user_id', $userId)->value('gender');
        $oppositeGender = match (strtolower($userGender)) {
            'male' => 'female',
            'female' => 'male',
            default => null,
        };

        if (!$oppositeGender) {
            return response()->json([
                'message' => 'Invalid or undefined user found.',
                'all_occupation_based_profiles' => [
                    'all_occupation_based_profile_list' => [],
                    'all_occupation_based_profile_count' => 0,
                ],
            ]);
        }

        $preferredOccupationRaw = DB::table('set_preferences')->where('user_id', $userId)->value('occupation');

        if (!$preferredOccupationRaw) {
            return response()->json([
                'message' => 'Preferred occupation not set.',
                'all_occupation_based_profiles' => [
                    'all_occupation_based_profile_list' => [],
                    'all_occupation_based_profile_count' => 0,
                ],
            ]);
        }

        $preferredOccupations = array_map('trim', explode(',', $preferredOccupationRaw));

        // Query matching profiles
        $profiles = DB::table('user_details')
            ->join('users', 'user_details.user_id', '=', 'users.id')
            ->leftJoin('settings', 'user_details.user_id', '=', 'settings.user_id')
            ->where('user_details.gender', $oppositeGender)
            ->whereIn('user_details.occupation', $preferredOccupations)
            ->where('users.status', 'active')
            ->orderBy('user_details.created_at', 'desc')
            ->select(
                'users.id',
                'users.name',
                'user_details.gender',
                'user_details.profile_image',
                'user_details.dob',
                'user_details.height',
                'user_details.city',
                'settings.profile_picture_visibility',
                'settings.name_visibility',
                'settings.date_of_birth_visibility'
            )
            ->get();

        foreach ($profiles as $profile) {
            $profilePackage = DataController::getUserPackageDetails($profile->id)['package'] ?? '';
            $profile->package = $profilePackage;
            $profile->age = $profile->dob ? Carbon::parse($profile->dob)->age : '';
            $profile->name = ApiHelperController::privacyData($profile->name, $profile->name_visibility, $authPackage);
            $profile->dob = ApiHelperController::privacyData($profile->dob, $profile->date_of_birth_visibility, $authPackage);
            $profile->profile_image = ApiHelperController::ImageUrl(
                $profile->profile_image, $profile->profile_picture_visibility,
                $authPackage, $profile->gender
            );
        }

        return response()->json([
            'all_occupation_based_profiles' => [
                'all_occupation_based_profile_list' => $profiles,
                'all_occupation_based_profile_count' => $profiles->count(),
            ],
        ]);
    }


    public function preferenceBasedProfiles(Request $request): JsonResponse
    {
        try {
            $userId = $request->input('user_id');
            $authPackage = DataController::getUserPackageDetails($userId)['is_active'] ?? '';

            $userGender = DB::table('user_details')->where('user_id', $userId)->value('gender');

            if (!$userGender) {
                return response()->json(['error' => 'Unauthorized User'], 400);
            }

            $oppositeGender = strtolower($userGender) === 'male' ? 'female' : 'male';

            // Get user preferences
            $preferences = DB::table('set_preferences')->where('user_id', $userId)->first();

            if (!$preferences) {
                return response()->json([
                    'all_preference_based_profiles' => [
                        'all_preference_based_profile_list' => [],
                        'all_preference_based_profile_count' => 0
                    ]
                ]);
            }

            $query = DB::table('user_details')
                ->join('users', 'user_details.user_id', '=', 'users.id')
                ->leftJoin('settings', 'user_details.user_id', '=', 'settings.user_id')
                ->where('user_details.gender', $oppositeGender)
                ->select(
                    'users.id',
                    'users.name',
                    'user_details.gender',
                    'user_details.profile_image',
                    'user_details.height',
                    'user_details.city',
                    'user_details.dob',
                    'user_details.marital_status',
                    'user_details.caste',
                    'user_details.employed_in',
                    'users.email_verified_at',
                    'settings.profile_picture_visibility',
                    'settings.name_visibility',
                    'settings.date_of_birth_visibility'
                );

            // Filter fields map
            $filters = [
                'min_age' => '>=',
                'max_age' => '<=',
                'country', 'state', 'city', 'mother_tongue',
                'marital_status', 'skin_tone', 'body_type',
                'drinking_habit', 'smoking_habit', 'religion',
                'caste', 'sub_caste', 'qualification', 'education',
                'occupation_type', 'occupation', 'employed_in',
                'rashi', 'nakshatra', 'lagnam', 'padam', 'kulam',
                'gothram', 'dosham', 'ethnicity', 'nationality',
                'work_country', 'visa_status'
            ];

            foreach ($filters as $key => $value) {
                $field = is_string($key) ? $key : $value;
                $operator = is_string($key) ? $value : '=';

                if (!empty($preferences->$field)) {
                    $filterVal = $preferences->$field;
                    if (in_array($field, ['min_age', 'max_age'])) {
                        $query->whereNotNull('user_details.dob')
                            ->whereRaw(
                                "TIMESTAMPDIFF(YEAR, user_details.dob, CURDATE()) " . $operator . " ?",
                                [$filterVal]
                            );
                    } else {
                        $values = explode(',', $filterVal);
                        $query->whereIn('user_details.' . $field, $values);
                    }
                }
            }

            // Range filters
            $rangeFilters = [
                'height' => ['height_from', 'height_to'],
                'monthly_income' => ['monthly_income_from', 'monthly_income_to'],
                'weight' => ['weight_from', 'weight_to'],
            ];

            foreach ($rangeFilters as $column => [$fromKey, $toKey]) {
                if (!empty($preferences->$fromKey)) {
                    $query->where("user_details.$column", '>=', $preferences->$fromKey);
                }
                if (!empty($preferences->$toKey)) {
                    $query->where("user_details.$column", '<=', $preferences->$toKey);
                }
            }

            $profiles = $query
                ->groupBy(
                    'users.id',
                    'users.name',
                    'user_details.gender',
                    'user_details.profile_image',
                    'user_details.height',
                    'user_details.city',
                    'user_details.dob',
                    'user_details.marital_status',
                    'user_details.caste',
                    'user_details.employed_in',
                    'users.email_verified_at',
                    'settings.profile_picture_visibility',
                    'settings.name_visibility',
                    'settings.date_of_birth_visibility'
                )
                ->get();

            foreach ($profiles as $profile) {
                $profilePackage = DataController::getUserPackageDetails($profile->id)['package'] ?? '';
                $profile->package = $profilePackage;
                $profile->age = Carbon::parse($profile->dob)->age ?? '';
                $profile->name = ApiHelperController::privacyData($profile->name, $profile->name_visibility, $authPackage);
                $profile->dob = ApiHelperController::privacyData($profile->dob, $profile->date_of_birth_visibility, $authPackage);
                $profile->profile_image = ApiHelperController::ImageUrl($profile->profile_image, $profile->profile_picture_visibility, $authPackage, $profile->gender);
                $profile->isVerified = ApiHelperController::isVerified($profile->email_verified_at);
            }

            return response()->json([
                'all_preference_based_profiles' => [
                    'all_preference_based_profile_list' => $profiles,
                    'all_preference_based_profile_count' => $profiles->count()
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong while fetching profiles.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function allViewedMyProfiles(Request $request): JsonResponse
    {
        $userId = (int) $request->input('user_id');
        $authPackage = DataController::getUserPackageDetails($userId)['is_active'] ?? '';

        $base = DB::table('profile_views as pv')
            ->join('users as u',          'u.id',      '=', 'pv.viewed_id')
            ->join('user_details as ud',  'ud.user_id','=', 'u.id')
            ->leftJoin('settings as s',   's.user_id', '=', 'u.id')
            ->where('pv.viewer_id', $userId)
            ->where('u.status', 'active');

        $viewed = (clone $base)
            ->select([
                'u.id',
                'u.name',
                'ud.gender',
                'ud.profile_image',
                'ud.dob',
                'ud.height',
                'ud.city',
                's.profile_picture_visibility',
                's.name_visibility',
                's.date_of_birth_visibility',
                'pv.created_at as viewed_at',
            ])
            ->orderByDesc('pv.created_at')
            ->get();

        foreach ($viewed as $p) {
            $p->package = DataController::getUserPackageDetails($p->id)['package'] ?? '';
            $p->age = $p->dob ? Carbon::parse($p->dob)->age : '';
            $p->name = ApiHelperController::privacyData(
                $p->name, $p->name_visibility, $authPackage
            );
            $p->dob = ApiHelperController::privacyData(
                $p->dob, $p->date_of_birth_visibility, $authPackage
            );
            $p->profile_image = ApiHelperController::ImageUrl(
                $p->profile_image, $p->profile_picture_visibility,
                $authPackage, $p->gender
            );
        }
        $count = (clone $base)->distinct('ud.user_id')->count('ud.user_id');

        return response()->json([
            'all_viewed_my_profiles' => [
                'all_viewed_my_profile_list'  => $viewed,
                'all_viewed_my_profile_count' => $count,
            ],
        ]);
    }

    public function AddGalleryImage(Request $request): JsonResponse
    {
        $userId = $request->input('user_id');

        $validator = Validator::make($request->all(), [
            'image.*' => 'required|image|max:500',
        ], [
            'image.required' => 'You must upload at least one image.',
            'image.*.image' => 'Each file must be a valid image.',
            'image.*.max' => 'Each image must be less than 500KB.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Images upload failed.',
                'errors' => $validator->messages(),
            ], 400);
        }

        // Check if there are images in the request
        if ($request->hasFile('image')) {
            $uploadedImages = $request->file('image');
            $uploadedImageNames = [];

            foreach ($uploadedImages as $galleryImage) {
                $galleryImageName = time() . '_' . uniqid() . '.' . $galleryImage->getClientOriginalExtension();
                $galleryImage->move(public_path('GalleryImage'), $galleryImageName);

                DB::table('gallery')->insert([
                    'user_id' => $userId,
                    'image' => $galleryImageName,
                ]);

                $uploadedImageNames[] = $galleryImageName;
            }

            return response()->json([
                'message' => 'Images uploaded successfully!',
                'images' => $uploadedImageNames,
            ]);
        } else {
            return response()->json(['message' => 'No files uploaded'], 400);
        }
    }

    public function contactRequest(Request $request): JsonResponse
    {
        $authUserId = (int) $request->input('user_id');
        $profileUserId = (int) $request->input('profile_id');

        if (!$profileUserId) {
            return response()->json(['message' => 'Invalid profile user ID.'], 400);
        }

        $profileUser = DB::table('users')
            ->join('user_details', 'user_details.user_id', '=', 'users.id')
            ->where('users.id', $profileUserId)
            ->select('users.email', 'users.mobile')
            ->first();

        if (!$profileUser) {
            return response()->json(['message' => 'Profile user not found.'], 404);
        }

        // Get active receipt
        $authReceipt = DataController::getUserPackageDetails($authUserId)['receipt'] ?? '';

        if (!$authReceipt) {
            return response()->json(['message' => 'No active package found. Please upgrade.'], 403);
        }

        // Limit daily requests
        $requestsToday = DB::table('contact_requests')
            ->where('user_id', $authUserId)
            ->whereDate('created_at', today())
            ->count();

        if ($requestsToday >= 10) {
            return response()->json(['message' => 'You have reached the limit of 10 contact requests for today.'], 403);
        }

        // If already requested, just return the contact again
        $existing = DB::table('contact_requests')
            ->where('user_id', $authUserId)
            ->where('profile_id', $profileUserId)
            ->exists();

        if ($existing) {
            return response()->json([
                'message' => 'A contact request has already been made. Please view the available contacts.',
                'mobile'  => $profileUser->mobile,
                'email'   => $profileUser->email,
            ], 200);
        }

        // Check balance before allowing view
        $currentBalance = $authReceipt->balance ?? 0;
        $noOfViewed = $authReceipt->no_of_viewed ?? 0;
        $noOfContact = $authReceipt->no_of_contact ?? 0;

        if ($currentBalance <= 0) {
            return response()->json(['message' => 'Your balance is zero. Please upgrade your package to view contacts.'], 403);
        }

        DB::beginTransaction();
        try {
            DB::table('receipts')->where('id', $authReceipt->id)->update([
                'no_of_viewed' => $noOfViewed + 1,
                'balance'      => max($noOfContact - ($noOfViewed + 1), 0),
            ]);

            DB::table('contact_requests')->insert([
                'user_id'    => $authUserId,
                'profile_id' => $profileUserId,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Contact is now available.',
                'mobile'  => $profileUser->mobile,
                'email'   => $profileUser->email,
            ], 200);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['message' => 'Something went wrong. Please try again later.'], 500);
        }
    }


    public function viewedContacts(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid user.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $userId = $request->input('user_id');
        $package = DataController::getUserPackageDetails($userId)['is_active'] ?? '';

        $contacts = ApiHelperController::getContactUsers('user_id', $userId);

        $viewedContacts = $contacts->map(function ($profile) use ($package) {
            $profile->name = ApiHelperController::privacyData($profile->name, $profile->name_visibility, $package);
            $profile->email = ApiHelperController::privacyData($profile->email, $profile->email_visibility, $package);
            $profile->mobile = ApiHelperController::privacyData($profile->mobile, $profile->mobile_number_visibility, $package);
            $profile->profile_image = ApiHelperController::ImageUrl(
                $profile->profile_image, $profile->profile_picture_visibility,
                $package, $profile->gender
            );
            return $profile;
        });

        return response()->json([
            'status' => true,
            'message' => 'Viewed contacts fetched successfully',
            'viewed_contacts' => $viewedContacts,
        ]);
    }

    public function viewerContacts(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid user.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $userId = $request->input('user_id');

        $package = DataController::getUserPackageDetails($userId)['is_active'] ?? '';
        $data = ApiHelperController::getContactUsers('profile_id', $userId);

        $viewerContacts = $data->map(function ($profile) use ($package) {
            $profile->name = ApiHelperController::privacyData($profile->name, $profile->name_visibility, $package);
            $profile->email = ApiHelperController::privacyData($profile->email, $profile->email_visibility, $package);
            $profile->mobile = ApiHelperController::privacyData($profile->mobile, $profile->mobile_number_visibility, $package);
            $profile->profile_image = ApiHelperController::ImageUrl(
                $profile->profile_image, $profile->profile_picture_visibility,
                $package, $profile->gender
            );
            return $profile;
        });

        return response()->json([
            'status' => true,
            'message' => 'Viewers fetched successfully',
            'viewer_contacts' => $viewerContacts,
        ]);
    }


}
