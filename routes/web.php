<?php

use App\Http\Controllers\Customize\EthnicityController;
use App\Http\Controllers\Customize\NationalityController;
use App\Http\Controllers\Customize\PropertyDetailController;
use App\Http\Controllers\Customize\VisaStatusController;
use App\Http\Controllers\Helpers\DropdownController;
use App\Http\Controllers\PhonePeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SecuredSettings\NotificationsController;
use App\Http\Controllers\SecuredSettings\TrackBrokerController;
use App\Http\Controllers\TriumphPortalController;
use App\Http\Controllers\Admin\PaymentListController;
use App\Http\Controllers\Web\TourController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\WebController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\UserDetailsController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\Customize\LanguageController;
use App\Http\Controllers\Customize\MaritalStatusController;
use App\Http\Controllers\Customize\CasteController;
use App\Http\Controllers\Customize\SubCastesController;
use App\Http\Controllers\Customize\KulamController;
use App\Http\Controllers\Customize\GothramController;
use App\Http\Controllers\Customize\StarsController;
use App\Http\Controllers\Customize\RashiController;
use App\Http\Controllers\Customize\ReligionController;
use App\Http\Controllers\Customize\FamilyGodController;
use App\Http\Controllers\Customize\EducationController;
use App\Http\Controllers\Customize\OccupationController;
use App\Http\Controllers\Customize\SkinToneController;
use App\Http\Controllers\Customize\HeightController;
use App\Http\Controllers\Customize\BodyTypeController;
use App\Http\Controllers\Customize\DrinkingHabitController;
use App\Http\Controllers\Customize\SmokingHabitController;
use App\Http\Controllers\Customize\EatingHabitController;
use App\Http\Controllers\Customize\EmployedInController;
use App\Http\Controllers\Customize\SalaryController;
use App\Http\Controllers\Customize\DoshamController;
use App\Http\Controllers\Customize\LagnamController;
use App\Http\Controllers\Customize\PadamController;
use App\Http\Controllers\Customize\TableApprovalController;
use App\Http\Controllers\Testimonial\HappyStoriesController;
use App\Http\Controllers\Testimonial\TestimonialsController;
use App\Http\Controllers\Testimonial\HighlightedProfileController;
use App\Http\Controllers\Packages\PackageController;
use App\Http\Controllers\PrivacyDetails\TermsAndConditionController;
use App\Http\Controllers\PrivacyDetails\PrivacyPolicyController;
use App\Http\Controllers\PrivacyDetails\RefundPolicyController;
use App\Http\Controllers\PrivacyDetails\AboutUsController;
use App\Http\Controllers\PrivacyDetails\SeoController;
use App\Http\Controllers\PrivacyDetails\BlogController;
use App\Http\Controllers\Authentication\RegisterController;
use App\Http\Controllers\Web\InterestController;
use App\Http\Controllers\Web\SettingsController;
use App\Http\Controllers\Web\PlanController;
use App\Http\Controllers\Web\PaymentPlansController;
use App\Http\Controllers\Web\AllProfilesController;
use App\Http\Controllers\Web\ChatsController;
use App\Http\Controllers\Web\SetPreferenceController;
use App\Http\Controllers\Web\MyProfileController;
use App\Http\Controllers\Packages\ReceiptsController;
use App\Http\Controllers\Packages\PaymentController;
use App\Http\Controllers\Web\SearchController;
use App\Http\Controllers\Web\WishlistsController;
use App\Http\Controllers\Customize\AdvertisementController;
use App\Http\Controllers\Customize\MobileAdvertisementController;
use App\Http\Controllers\SecuredSettings\AdminInfoController;
use App\Http\Controllers\SecuredSettings\ContactInfoController;
use App\Http\Controllers\SecuredSettings\PrintInfoController;
use App\Http\Controllers\SecuredSettings\PaymentInfoController;
use App\Http\Controllers\TestingController;


// Professional User Dashboard
Route::get('professional/dashboard', function () {
    return view('professional.dashboard');
})->middleware(['auth', 'verified', 'professional'])->name('professional.dashboard');


// Website
Route::get('/', [WebController::class, 'index']);
Route::get('terms-and-condition', [WebController::class, 'termsAndCondition']);
Route::get('help', [WebController::class, 'Help']);
Route::get('privacy-policy', [WebController::class, 'privacyPolicy']);
Route::get('refund-policy', [WebController::class, 'refundPolicy']);
Route::get('about-us', [WebController::class, 'aboutUs']);
Route::get('contact-us', [WebController::class, 'contactUs']);
Route::post('store-contact-us', [WebController::class, 'storeContactUs'])->name('storeContactUs');
Route::resource('payment-plans', PaymentPlansController::class);
Route::get('home-search', [AllProfilesController::class, 'HomeSearch'])->name('homeSearch');
Route::get('blog-details/{id}', [BlogController::class, 'BlogDetails'])->name('blogDetails');
Route::middleware(['auth', 'verified', 'standard'])->group(function () {

    // Tour Process
    Route::get('partner-preference', [TourController::class, 'PartnerPreference'])->name('tour1');
    Route::get('privacy-setting', [TourController::class, 'PrivacySettings'])->name('tour2');
    Route::get('payment-service', [TourController::class, 'PaymentService'])->name('tour3');
    Route::post('tourComplete', [TourController::class, 'TourComplete'])->name('tourComplete');

    Route::get('personal-details', [WebController::class, 'registerStep1'])->name('registerStep1');
    Route::post('storePersonalDetails', [WebController::class, 'storeRegisterStep1'])->name('storeRegisterStep1');
    Route::get('physical-details', [WebController::class, 'registerStep2'])->name('registerStep2');
    Route::post('storePhysicalDetails', [WebController::class, 'storeRegisterStep2'])->name('storeRegisterStep2');
    Route::get('habitual-details', [WebController::class, 'registerStep3'])->name('registerStep3');
    Route::post('storeHabitualDetails', [WebController::class, 'storeRegisterStep3'])->name('storeRegisterStep3');
    Route::get('educationJob-details', [WebController::class, 'registerStep4'])->name('registerStep4');
    Route::post('storeEducationJobDetails', [WebController::class, 'storeRegisterStep4'])->name('storeRegisterStep4');
    Route::get('family-details', [WebController::class, 'registerStep5'])->name('registerStep5');
    Route::post('storeFamilyDetails', [WebController::class, 'storeRegisterStep5'])->name('storeRegisterStep5');
    Route::get('horoscope-details', [WebController::class, 'registerStep6'])->name('registerStep6');
    Route::post('storeHoroscopeDetails', [WebController::class, 'storeRegisterStep6'])->name('storeRegisterStep6');
    Route::get('address-details', [WebController::class, 'registerStep7'])->name('registerStep7');
    Route::post('storeAddressDetails', [WebController::class, 'storeRegisterStep7'])->name('storeRegisterStep7');
    Route::get('home', [WebController::class, 'dashboard'])->name('dashboard');
    Route::get('contacts', [WebController::class, 'contacts'])->name('contacts');
    Route::get('package-404', [WebController::class, 'package404'])->name('package-404');
    Route::resource('interests', InterestController::class);
    Route::post('/interests/accept/{id}', [InterestController::class, 'accept'])->name('interests.accept');
    Route::post('/interests/deny/{id}', [InterestController::class, 'deny'])->name('interests.deny');
    Route::resource('settings', SettingsController::class);
    Route::resource('plan', PlanController::class);
    Route::resource('all-profiles', AllProfilesController::class);
    Route::get('profileLists', [AllProfilesController::class, 'profileLists'])->name('profileLists');
    Route::post('viewed-profiles', [AllProfilesController::class, 'viewedProfiles'])->name('all-profiles.viewedProfiles');
    Route::get('all-profiles',[AllProfilesController::class,'index'])->name('all-profiles.index');
    Route::post('all-profiles', [AllProfilesController::class, 'contact'])->name('all-profiles.contact');
    Route::post('/send-interest', [InterestController::class, 'sendInterest'])->name('web.sendInterest');
    Route::resource('chats', ChatsController::class);
    Route::get('chat-details/{chatUserId}', [ChatsController::class, 'chatDetailsAjax'])->name('chat.details.ajax');
//    Route::get('chat-details/{chatUserId}', [ChatsController::class, 'chatDetails'])->name('chats.details');
    Route::resource('set-preference', SetPreferenceController::class);
    Route::resource('my-profile', MyProfileController::class);
    Route::get('downloadProfile', [MyProfileController::class, 'downloadProfile'])->name('downloadProfile');
    Route::resource('receipts', ReceiptsController::class);
    Route::match(['get', 'post'], 'payment', [PaymentController::class, 'razorPayment'])->name('payment.razorpay');
    Route::post('payment-success', [PaymentController::class, 'paymentSuccess'])->name('payment.success');
    Route::get('notifications', [AllProfilesController::class, 'showNotifications'])->name('notifications');
    Route::get('notifications/mark-as-read/{notifiableId}/{id}', [ChatsController::class, 'markChatNotificationsAsRead'])->name('notifications.markAsRead');
    Route::get('horoscope-search', [SearchController::class, 'horoscopeSearch'])->name('horoscopeSearch');
    Route::get('gallery', [MyProfileController::class, 'Gallery']);
    Route::post('gallery-store', [MyProfileController::class, 'GalleryStore'])->name('gallery.store');
    Route::delete('gallery-delete/{id}', [MyProfileController::class, 'GalleryDelete'])->name('gallery.delete');
    Route::get('wishlists', [WishlistsController::class, 'WishLists']);
    Route::post('addWishlist', [WishlistsController::class, 'AddWishlist'])->name('addWishlist');
    Route::post('wishlists', [WishlistsController::class, 'RemoveWishlist'])->name('wishlist.remove');
    Route::get('ai-search', [SearchController::class, 'aiSearch'])->name('aiSearch');

    Route::post('checkoutPhonePe', [PhonePeController::class, 'checkoutPhonePe'])->name('checkoutPhonePe');
    Route::post('initiatePhonePe', [PhonePeController::class, 'createOrder'])->name('createOrder');
    Route::get('callbackPhonePe', [PhonePeController::class, 'callback'])->name('callbackPhonePe');
    Route::get('phonePeHistory', [PhonePeController::class, 'phonePeHistory'])->name('phonePeHistory');
    Route::get('phonePePaymentHistory', [PhonePeController::class, 'phonePePaymentHistory'])->name('phonePePaymentHistory');
    Route::get('phonePeCheckStatus', [PhonePeController::class, 'phonePeCheckStatus'])->name('phonePeCheckStatus');
});

//Notifications
Route::get('fetchNotifications', [NotificationsController::class, 'FetchNotifications'])->name('FetchNotifications');
Route::post('readNotification', [NotificationsController::class, 'ReadNotification'])->name('ReadNotification');
Route::delete('deleteNotification', [NotificationsController::class, 'DeleteNotification'])->name('DeleteNotification');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Dynamically create Register fields
Route::prefix('admin')->middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('dashboard', [AdminController::class, 'Index'])->name('admin.dashboard');
    Route::get('profile', [AdminController::class, 'myProfile'])->name('admin.profile');
    Route::post('profile', [AdminController::class, 'updateMyProfile'])->name('admin.profile.update');
    Route::post('profile/password', [AdminController::class, 'updateMyPassword'])->name('admin.profile.password');
    Route::get('register', [AdminController::class, 'Register']);
    Route::post('register', [AdminController::class, 'Registration'])->name('admin.register');

    Route::resource('user-list', UserProfileController::class);
    Route::get('users', [UserProfileController::class , 'allUsers'])->name('allUsers');
    Route::get('profile-analysis', [UserProfileController::class, 'ProfileAnalysis'])->name('profileAnalysis');
    Route::resource('user-details', UserDetailsController::class);
    Route::post('image-delete', [UserDetailsController::class, 'deleteImage'])->name('deleteImage');

    Route::get('print-user/{id}', [UserDetailsController::class, 'printUser']);
    Route::get('add-preference/{id}', [UserDetailsController::class, 'addPreference']);
    Route::post('storePreference/{id}', [UserDetailsController::class, 'storePreference'])->name('storePreference');

    // Customization routes
    Route::resource('table-approval', TableApprovalController::class);
    Route::resource('languages', LanguageController::class);
    Route::resource('castes', CasteController::class);
    Route::resource('sub-castes', SubCastesController::class);
    Route::resource('kulam', KulamController::class);
    Route::resource('gothram', GothramController::class);
    Route::resource('stars', StarsController::class);
    Route::resource('rashi', RashiController::class);
    Route::resource('religion', ReligionController::class);
    Route::resource('family-god', FamilyGodController::class);
    Route::resource('education', EducationController::class);
    Route::resource('occupation', OccupationController::class);
    Route::resource('marital-status', MaritalStatusController::class);
    Route::resource('skin-tone', SkinToneController::class);
    Route::resource('height', HeightController::class);
    Route::resource('body-type', BodyTypeController::class);
    Route::resource('drinking-habit', DrinkingHabitController::class);
    Route::resource('smoking-habit', SmokingHabitController::class);
    Route::resource('eating-habit', EatingHabitController::class);
    Route::resource('employed-in', EmployedInController::class);
    Route::resource('salary', SalaryController::class);
    Route::resource('dosham', DoshamController::class);
    Route::resource('lagnam', LagnamController::class);
    Route::resource('padam', PadamController::class);
    Route::resource('advertisement', AdvertisementController::class);
    Route::resource('mobile_advertisement', MobileAdvertisementController::class);
    Route::get('app_slider', [MobileAdvertisementController::class, 'getAppSlider']);
    Route::post('appSliderStore', [MobileAdvertisementController::class, 'appSliderStore'])->name('appSliderStore');
    Route::put('appSliderUpdate/{id}', [MobileAdvertisementController::class, 'appSliderUpdate'])->name('appSliderUpdate');
    Route::delete('appSliderDelete/{id}', [MobileAdvertisementController::class, 'appSliderDelete'])->name('appSliderDelete');
    Route::resource('property-detail', PropertyDetailController::class);
    Route::resource('ethnicity', EthnicityController::class);
    Route::resource('nationality', NationalityController::class);
    Route::resource('visa-status', VisaStatusController::class);

    // Packages & Payments routes
    Route::resource('packages', PackageController::class);
    Route::get('package-reports', [PaymentListController::class, 'index'])->name('admin.package-reports');
    Route::get('payments', [PaymentListController::class, 'index'])->name('admin.payments.index');
    Route::get('payments/{id}', [PaymentListController::class, 'show'])->name('admin.payments.show');

    // Register routes
    Route::resource('register-details', RegisterController::class);

    // Testimonial routes
    Route::resource('happy-stories', HappyStoriesController::class);
    Route::resource('testimonials', TestimonialsController::class);
    Route::resource('highlighted-profiles', HighlightedProfileController::class);

    // PrivacyDetails routes
    Route::get('terms-and-conditions', [TermsAndConditionController::class, 'termsAndCondition']);
    Route::post('terms-conditions', [TermsAndConditionController::class, 'termsAndConditionStore'])->name('terms-and-conditions.store');
    Route::post('terms-conditions-image', [TermsAndConditionController::class, 'termsAndConditionImageStore'])->name('terms-and-conditions.image.store');

    Route::get('privacy-policy', [PrivacyPolicyController::class, 'privacyPolicy']);
    Route::post('privacy-policy', [PrivacyPolicyController::class, 'privacyPolicyStore'])->name('privacy-policy.store');
    Route::post('privacy-policy-image', [PrivacyPolicyController::class, 'privacyPolicyImageStore'])->name('privacy-policy-image');

    Route::get('refund-policy', [RefundPolicyController::class, 'refundPolicy']);
    Route::post('refund-policy', [RefundPolicyController::class, 'refundPolicyStore'])->name('refund-policy.store');

    Route::get('about-us', [AboutUsController::class, 'aboutUs']);
    Route::post('about-us', [AboutUsController::class, 'aboutUsStore'])->name('about-us.store');
    Route::post('about-us-image', [AboutUsController::class, 'aboutUsImageStore'])->name('about-us-image');

    Route::resource('seo', SeoController::class);

    // Blogs
    Route::get('blog', [BlogController::class, 'Blog']);
    Route::post('blog', [BlogController::class, 'BlogStore'])->name('blog.store');
    Route::post('blog_image', [BlogController::class, 'blogImageStore'])->name('blog_image.store');
    Route::get('blogs', [BlogController::class, 'Blogs']);
    Route::get('blog-details/{id}', [BlogController::class, 'AdminBlogDetails'])->name('adminBlogDetails');
    Route::get('edit-blog/{id}', [BlogController::class, 'AdminEditBlog'])->name('adminEditBlog');
    Route::post('update-blog/{id}', [BlogController::class, 'AdminUpdateBlog'])->name('adminUpdateBlog');
    Route::delete('delete-blog/{id}', [BlogController::class, 'deleteBlog'])->name('deleteBlog');
    //End: Blogs

    //Backup Database
    Route::get('/backup', [BackupController::class, 'createBackup'])->name('backup.create');
    Route::get('backup-images', [BackupController::class, 'backupImages'])->name('backup.images');
    Route::get('horoscope_images', [BackupController::class, 'horoscopeImages'])->name('horoscope.images');
    Route::get('export', [BackupController::class, 'Export'])->name('Export');

    // Secured Settings controller
    Route::resource('admin_info', AdminInfoController::class);
    Route::resource('contact_info', ContactInfoController::class);
    Route::resource('print_info', PrintInfoController::class);
    Route::resource('payment_info', PaymentInfoController::class);
    Route::resource('track_broker', TrackBrokerController::class);
    Route::get('seasonal_notification', [NotificationsController::class, 'seasonalNotification']);
    Route::post('seasonal_notification', [NotificationsController::class, 'seasonalNotificationStore'])->name('seasonalNotificationStore');
    Route::get('user-verification', [AdminController::class, 'userVerification']);
    Route::get('user-contacts', [AdminController::class, 'userContacts']);
    Route::get('contactViews/{id}', [AdminController::class, 'contactViewsData'])->name('contactViewsData');
    Route::get('complaint_portal', [AdminController::class, 'complaintPortal']);
    Route::put('updateEnquiry/{id}', [AdminController::class, 'updateEnquiry'])->name('updateEnquiry');
    Route::delete('deleteEnquiry/{id}', [AdminController::class, 'deleteEnquiry'])->name('deleteEnquiry');
    Route::get('profile-updation', [AdminController::class, 'profileUpdation'])->name('profileUpdation');
    Route::post('profileUpdateStore', [AdminController::class, 'profileUpdateStore'])->name('profileUpdateStore');
    Route::put('profileEditUpdate/{id}', [AdminController::class, 'profileEditUpdate'])->name('profileEditUpdate');
    Route::delete('profileUpdateDelete/{id}', [AdminController::class, 'profileUpdateDelete'])->name('profileUpdateDelete');
    Route::post('photoVerify/{id}', [AdminController::class, 'photoVerify'])->name('photoVerify');
    Route::get('new_data', [AdminController::class, 'newDataApproval']);
    Route::get('editedProfiles', [AdminController::class, 'EditedProfileLists']);
    Route::post('editedProfileStatusUpdate/{id}', [AdminController::class, 'EditedProfileStatusUpdate'])->name('editedProfileStatusUpdate');

    // Triumph Portal
    Route::get('triumph_portal', [TriumphPortalController::class, 'triumphPortal'])->name('triumph.portal');
    Route::get('triumph-portal/{user}', [TriumphPortalController::class, 'triumphPortalUser'])->name('triumph.portal.user');

    // Coupon code
    Route::get('coupons', [AdminController::class, 'Coupons']);
    Route::get('help', [AdminController::class, 'Help']);

    Route::resource('register-test', TestingController::class);
});


// Dependant Dropdown user
$dropdownRoutes = function () {
    Route::post('get-states', [DropdownController::class, 'getStates']);
    Route::post('get-cities', [DropdownController::class, 'getCities']);
    Route::post('get-occupations', [DropdownController::class, 'getOccupations']);
    Route::post('get-education', [DropdownController::class, 'getEducation']);
    Route::post('get-subCastes', [DropdownController::class, 'getSubCastes']);
};
Route::prefix('/')->group($dropdownRoutes);
Route::prefix('admin/{section}')
    ->where(['section' => 'user-details|register-details|add-preference'])
    ->group($dropdownRoutes);
Route::prefix('admin')->group($dropdownRoutes);


require __DIR__.'/auth.php';

Route::get('login-otp', [SmsController::class, 'loginOtpPage']);
Route::post('send-otp', [SmsController::class, 'sendOtp'])->name('send-otp');
Route::get('verify-otp/{mobile}', [SmsController::class, 'verifyOtpPage'])->name('verify-otp');
Route::post('verify-otp', [SmsController::class, 'verifyOtp'])->name('verify.otp');

//Route::get('verify-register-otp/{mobile}', [SmsController::class, 'verifyRegisterOtpPage'])->name('verify-register-otp');
Route::post('resendOtp', [SmsController::class, 'resendOtp'])->name('resendOtp');
//Route::post('verify-register-otp', [SmsController::class, 'verifyRegisterOtp'])->name('verify.register.otp');

// Mail OTP
Route::post('mailOTP', [SmsController::class, 'mailOTP'])->name('mailOTP');
Route::post('resendMailOtp', [SmsController::class, 'resendMailOtp'])->name('resendMailOtp');
Route::get('verify-otp', [SmsController::class, 'showOtpForm'])->name('showOtpForm');
Route::post('verifyEmailOTP', [SmsController::class, 'verifyEmailOtp'])->name('verifyEmailOTP');
Route::get('verifyRegisterOtp', [SmsController::class, 'verifyRegisterEmailPage'])->name('verifyRegisterEmailPage');
Route::post('verifyRegisterEmailOTP', [SmsController::class, 'verifyRegisterEmailOtp'])->name('verifyRegisterEmailOtp');
