<?php

use App\Http\Controllers\Api\PhonePeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PagesController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\PreferenceController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\InterestController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\PackageController;
use App\Http\Controllers\Api\SupportController;


// Authentication Routes
Route::post('user_register', [AuthController::class, 'Register']);
Route::get('get-register-personal', [AuthController::class, 'GetRegisterPersonal']);
Route::get('get-register-professional', [AuthController::class, 'GetRegisterProfessional']);
Route::get('get-register-family', [AuthController::class, 'GetRegisterFamily']);
Route::get('get-register-horoscope', [AuthController::class, 'GetRegisterHoroscope']);
Route::post('register-personal-details', [AuthController::class, 'RegisterPersonalDetails']);
Route::post('register-physical-details', [AuthController::class, 'RegisterPhysicalDetails']);
Route::post('register-habitual-details', [AuthController::class, 'RegisterHabitualDetails']);
Route::post('register-professional-details', [AuthController::class, 'RegisterProfessionalDetails']);
Route::post('register-family-details', [AuthController::class, 'RegisterFamilyDetails']);
Route::post('register-horoscope-details', [AuthController::class, 'RegisterHoroscopeDetails']);
Route::post('register-address-details', [AuthController::class, 'RegisterAddressDetails']);
Route::post('verify-otp', [AuthController::class, 'VerifyOtp']);
Route::post('sendEmailOtp', [AuthController::class, 'sendEmailOtp']);
Route::post('verifyEmailOtp', [AuthController::class, 'verifyEmailOtp']);
Route::get('registration-details', [AuthController::class, 'RegistrationDetails']);
Route::post('store-registration-details', [AuthController::class, 'StoreRegistrationDetails']);
Route::post('login', [AuthController::class, 'Login']);
Route::post('logout', [AuthController::class, 'Logout']);
Route::post('forgotPassword', [AuthController::class, 'ForgotPassword']);
Route::post('resetPasswordVerifyOtp', [AuthController::class, 'ResetPasswordVerifyOtp']);
Route::post('resetChangePassword', [AuthController::class, 'ResetChangePassword']);
Route::post('otpSendLogin', [AuthController::class, 'SendOtpLogin']);
Route::post('verifyOtpLogin', [AuthController::class, 'VerifyOtpLogin']);
Route::post('delete-account', [AuthController::class, 'deactivateUserAccount']);
Route::post('admin_login', [AuthController::class, 'adminLogin']);
Route::get('getVerificationStatus', [AuthController::class, 'getVerificationStatus']);
Route::post('photoVerification', [AuthController::class, 'photoVerification']);
Route::post('edit-limit', [AuthController::class, 'getUpdateLimit']);


// Dependant Dropdown
Route::get('get-register-countries', [AuthController::class, 'GetRegisterCountries']);
Route::post('get-states', [AuthController::class, 'getStates']);
Route::post('get-cities', [AuthController::class, 'getCities']);
Route::post('get-occupations', [AuthController::class, 'getOccupations']);
Route::post('get-education', [AuthController::class, 'getEducation']);
Route::post('get-subCastes', [AuthController::class, 'getSubCastes']);
Route::post('get-all-occupations', [AuthController::class, 'getAllOccupations']);
Route::post('get-all-education', [AuthController::class, 'getAllEducation']);

// Pages controller
Route::get('dashboard', [PagesController::class, 'Dashboard']);
Route::get('showUserSettings', [PagesController::class, 'showUserSettings']);
Route::post('updateUserSettings', [PagesController::class, 'updateUserSettings']);
Route::get('highlightedProfiles', [PagesController::class, 'highlightedProfiles']);
Route::post('addSliderImage', [PagesController::class, 'StoreSliderImage']);
Route::get('getSliderImages', [PagesController::class, 'GetSliderImage']);
Route::get('getAdsImages', [PagesController::class, 'GetAddsImage']);
Route::post('addEnquiry', [PagesController::class, 'StoreEnquiry']);
Route::post('chat-details', [PagesController::class, 'ChatDetails']);
Route::post('send-chat', [PagesController::class, 'ChatSend']);
Route::post('getChatRoom', [PagesController::class, 'getChatRoom']);

// Profile Controller
Route::post('getUserProfileData', [ProfileController::class, 'getUserProfileData']);
Route::post('addGalleryImage', [ProfileController::class, 'AddGalleryImage']);
Route::post('updateUserProfileData', [ProfileController::class, 'updateUserProfileData']);
Route::get('all-user-profiles', [ProfileController::class, 'allUserProfiles']);
Route::get('allLocationBasedProfiles', [ProfileController::class, 'locationBasedProfiles']);
Route::get('allEducationBasedProfiles', [ProfileController::class, 'educationBasedProfiles']);
Route::get('allOccupationBasedProfiles', [ProfileController::class, 'occupationBasedProfiles']);
Route::get('allPreferenceBasedProfiles', [ProfileController::class, 'preferenceBasedProfiles']);
Route::get('allViewedMyProfiles', [ProfileController::class, 'allViewedMyProfiles']);
Route::post('contactRequest', [ProfileController::class, 'contactRequest']);
Route::post('editProfileImage', [ProfileController::class, 'editProfileImage']);
Route::post('deleteProfileImage', [ProfileController::class, 'deleteProfileImage']);
Route::post('viewedContacts', [ProfileController::class, 'viewedContacts']);
Route::post('viewerContacts', [ProfileController::class, 'viewerContacts']);

// Preference Controller
Route::post('set-preference', [PreferenceController::class, 'setPreference']);
Route::get('edit-preference', [PreferenceController::class, 'editPreference']);

// Search Controller
Route::post('searchProfile', [SearchController::class, 'searchProfile']);
Route::get('searchProfileById', [SearchController::class, 'searchProfileById']);
Route::post('aiSearch', [SearchController::class, 'aiSearchApi']);
Route::post('horoscopeSearch', [SearchController::class, 'horoscopeSearchApi']);

// Notification Controller
Route::get('notification_lists', [NotificationController::class, 'getNotificationLists']);
Route::post('notifications/read', [NotificationController::class, 'markNotificationAsRead']);
Route::get('seasonal-notifications', [NotificationController::class, 'getSeasonalNotifications']);
Route::get('seasonal_notifications', [NotificationController::class, 'getSeasonalNotifications']);
Route::get('fetchNotifications', [NotificationController::class, 'getSeasonalNotifications']);
Route::post('seasonal-notifications/read', [NotificationController::class, 'markSeasonalNotificationAsRead']);
Route::delete('seasonal-notifications/delete', [NotificationController::class, 'deleteSeasonalNotification']);

// Interest Controller
Route::post('send-interest', [InterestController::class, 'sendInterest']);
Route::post('accept-interest', [InterestController::class, 'acceptInterest']);
Route::get('accepted-requested-interests', [InterestController::class, 'acceptedRequestedInterests']);
Route::post('denied-interest', [InterestController::class, 'deniedInterest']);
Route::get('all-interests', [InterestController::class, 'allInterests']);
Route::post('add-wishlist', [InterestController::class, 'addWishlist']);
Route::post('remove-wishlist', [InterestController::class, 'removeWishlist']);
Route::get('show-wishlist', [InterestController::class, 'showWishlist']);

// Payment Controller
Route::get('packages', [PaymentController::class, 'getPackages']);

// Package Controller
Route::get('getUserPackageData', [PackageController::class, 'getUserPackageData']);

// Support Controller
Route::get('about-us', [SupportController::class, 'aboutUs']);
Route::get('privacy-policy', [SupportController::class, 'privacyPolicy']);
Route::get('terms-and-conditions', [SupportController::class, 'termsAndConditions']);
Route::get('refund-policy', [SupportController::class, 'refundPolicy']);
Route::get('blogs', [SupportController::class, 'getBlogs']);
Route::get('blog-details/{id}', [SupportController::class, 'BlogDetails'])->name('api/blogDetails');

// PhonePe
Route::post('fetchAuthToken', [PhonePeController::class, 'fetchAuthToken']);
Route::post('createOrder', [PhonePeController::class, 'createOrder']);
Route::post('phonePeCheckStatus/{merchantOrderId}', [PhonePeController::class, 'phonePeCheckStatus']);
Route::post('paymentHistory', [PhonePeController::class, 'paymentHistory']);
Route::post('receipts', [PhonePeController::class, 'phonePePayment']);

// Razorpay
Route::post('razorpay/createOrder', [PaymentController::class, 'createRazorpayOrder']);
Route::post('razorpay/verifyPayment', [PaymentController::class, 'verifyRazorpayPayment']);
Route::get('razorpay/paymentHistory', [PaymentController::class, 'paymentHistory']);
Route::post('razorpay/paymentHistory', [PaymentController::class, 'paymentHistory']);

