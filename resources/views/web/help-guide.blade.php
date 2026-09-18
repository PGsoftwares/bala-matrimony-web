@extends('web.layouts.layout')
@section('title', 'Bala Matrimony Bureau | User Help Guide')

@section('content')
    @include('web.includes.header')
    <div class="container my-5">

        <!-- Accordion for Help Sections -->
        <div class="accordion" id="helpAccordion">
            <!-- 1. Login -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingLogin">
                    <button class="accordion-button shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapseLogin" aria-expanded="true" aria-controls="collapseLogin">
                        1. 🔐 Login
                    </button>
                </h2>
                <div id="collapseLogin" class="accordion-collapse collapse show" aria-labelledby="headingLogin" data-bs-parent="#helpAccordion">
                    <div class="accordion-body">
                        <ol class="mb-3">
                            <li>Visit the <strong>Login</strong> page.</li>
                            <li>Enter your <strong>registered email / mobile number</strong> and <strong>password</strong>.</li>
                            <li>Click <span class="text-primary fw-semibold">Login</span>.</li>
                            <li>If you forgot your password, use the <em>Forgot Password</em> option to reset it.</li>
                        </ol>
                        <div class="alert alert-success" role="alert">
                            <i class="bi bi-info-circle-fill me-2"></i> You must be registered and OTP‑verified to log in.
                        </div>
                    </div>
                </div>
            </div>
            <!-- 2. Registration with OTP Verification -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOtp">
                    <button class="accordion-button collapsed shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOtp" aria-expanded="false" aria-controls="collapseOtp">
                        2. 📝 Registration with OTP Verification
                    </button>
                </h2>
                <div id="collapseOtp" class="accordion-collapse collapse" aria-labelledby="headingOtp" data-bs-parent="#helpAccordion">
                    <div class="accordion-body">
                        <ol class="mb-3">
                            <li>Go to the <strong>Registration</strong> page.</li>
                            <li>Fill in your basic details:
                                <ul>
                                    <li>Name</li>
                                    <li>Email</li>
                                    <li>Mobile Number</li>
                                    <li>Password</li>
                                </ul>
                            </li>
                            <li>Click <span class="text-primary fw-semibold">Register</span>.</li>
                            <li>An <abbr title="One‑Time Password">OTP</abbr> is sent to your email or mobile.</li>
                            <li>Enter the OTP in the verification box.</li>
                            <li>After successful verification, you’ll be redirected to complete your profile.</li>
                        </ol>
                        <div class="alert alert-warning" role="alert">
                            <i class="bi bi-shield-lock-fill me-2"></i> OTP verification is <strong>mandatory</strong> to proceed.
                        </div>
                    </div>
                </div>
            </div>
            <!-- 3. Personal Details -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingPersonal">
                    <button class="accordion-button collapsed shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePersonal" aria-expanded="false" aria-controls="collapsePersonal">
                        3. 👤 Register Personal Details
                    </button>
                </h2>
                <div id="collapsePersonal" class="accordion-collapse collapse" aria-labelledby="headingPersonal" data-bs-parent="#helpAccordion">
                    <div class="accordion-body">
                        <p>Provide the following personal information:</p>
                        <ul class="list-group list-group-flush mb-3">
                            <li class="list-group-item">Profile For (Self, Brother, Sister, etc.)</li>
                            <li class="list-group-item">Gender</li>
                            <li class="list-group-item">Date of Birth</li>
                            <li class="list-group-item">Marital Status</li>
                            <li class="list-group-item">Height, Skin Tone, Body Type</li>
                            <li class="list-group-item">Lifestyle habits (Eating, Drinking, Smoking)</li>
                            <li class="list-group-item">Religion, Caste, Sub‑caste</li>
                        </ul>
                        <div class="alert alert-info" role="alert">
                            All required fields must be filled to continue.
                        </div>
                    </div>
                </div>
            </div>
            <!-- 4. Education & Job Details -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingEduJob">
                    <button class="accordion-button collapsed shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEduJob" aria-expanded="false" aria-controls="collapseEduJob">
                        4. 🎓 Register Education & Job Details
                    </button>
                </h2>
                <div id="collapseEduJob" class="accordion-collapse collapse" aria-labelledby="headingEduJob" data-bs-parent="#helpAccordion">
                    <div class="accordion-body">
                        <p>Enter your educational and professional background:</p>
                        <ul class="list-group list-group-flush mb-3">
                            <li class="list-group-item">Highest Qualification</li>
                            <li class="list-group-item">Employed In (Government, Private, Business, etc.)</li>
                            <li class="list-group-item">Occupation</li>
                            <li class="list-group-item">Monthly Income</li>
                        </ul>
                        <div class="alert alert-success" role="alert">
                            Accurate job & education info improves your profile visibility.
                        </div>
                    </div>
                </div>
            </div>
            <!-- 5. Family Details -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingFamily">
                    <button class="accordion-button collapsed shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFamily" aria-expanded="false" aria-controls="collapseFamily">
                        5. 👪 Register Family Details
                    </button>
                </h2>
                <div id="collapseFamily" class="accordion-collapse collapse" aria-labelledby="headingFamily" data-bs-parent="#helpAccordion">
                    <div class="accordion-body">
                        <p>Provide details about your family background:</p>
                        <ul class="list-group list-group-flush mb-3">
                            <li class="list-group-item">Father’s & Mother’s Name and Profession</li>
                            <li class="list-group-item">Family Type (Joint/Nuclear)</li>
                            <li class="list-group-item">Family Status & Values</li>
                            <li class="list-group-item">Siblings and their marital status</li>
                            <li class="list-group-item">Property/Assets (optional)</li>
                        </ul>
                        <div class="alert alert-secondary" role="alert">
                            These details help others understand your family environment.
                        </div>
                    </div>
                </div>
            </div>
            <!-- 6. Horoscope Details -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingHoroscope">
                    <button class="accordion-button collapsed shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapseHoroscope" aria-expanded="false" aria-controls="collapseHoroscope">
                        6. 🔯 Register Horoscope Details
                    </button>
                </h2>
                <div id="collapseHoroscope" class="accordion-collapse collapse" aria-labelledby="headingHoroscope" data-bs-parent="#helpAccordion">
                    <div class="accordion-body">
                        <p>Fill in your horoscope information (if applicable):</p>
                        <ul class="list-group list-group-flush mb-3">
                            <li class="list-group-item">Rasi, Nakshatra, Laknam, Padam</li>
                            <li class="list-group-item">Kulam, Gothram</li>
                            <li class="list-group-item">Dosham</li>
                            <li class="list-group-item">Upload Horoscope Image</li>
                        </ul>
                        <div class="alert alert-warning" role="alert">
                            Horoscope info may be required for community‑specific matchmaking.
                        </div>
                    </div>
                </div>
            </div>
            <!-- 7. Address Details -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingAddress">
                    <button class="accordion-button collapsed shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAddress" aria-expanded="false" aria-controls="collapseAddress">
                        7. 📍 Register Address Details
                    </button>
                </h2>
                <div id="collapseAddress" class="accordion-collapse collapse" aria-labelledby="headingAddress" data-bs-parent="#helpAccordion">
                    <div class="accordion-body">
                        <p>Complete your current address:</p>
                        <ul class="list-group list-group-flush mb-3">
                            <li class="list-group-item">Full Address</li>
                            <li class="list-group-item">Country, State, City</li>
                            <li class="list-group-item">Pin Code</li>
                        </ul>
                        <div class="alert alert-info" role="alert">
                            Ensure accuracy for regional match suggestions and communication.
                        </div>
                    </div>
                </div>
            </div>

            <!-- 8. Guided Tour After Registration -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTour">
                    <button class="accordion-button collapsed shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTour" aria-expanded="false" aria-controls="collapseTour">
                        8. 🧭 Guided Tour After Registration
                    </button>
                </h2>
                <div id="collapseTour" class="accordion-collapse collapse" aria-labelledby="headingTour" data-bs-parent="#helpAccordion">
                    <div class="accordion-body">
                        <p>Once your profile is fully completed, you will be guided through a short, optional tour to help personalize your experience. This includes:</p>
                        <ol class="mb-3">
                            <li><strong>Set Preferences:</strong> Choose your desired partner preferences such as age, location, caste, and more.</li>
                            <li><strong>Privacy Settings:</strong> Decide who can view your profile picture, contact details, and other sensitive info.</li>
                            <li><strong>Payment Settings:</strong> Explore available membership plans and activate premium features if needed.</li>
                        </ol>
                        <div class="alert alert-info mb-3">
                            <i class="bi bi-skip-forward-fill me-1"></i> You can choose to <strong>skip</strong> this tour at any time and complete the settings later.
                        </div>
                        <p class="mb-0">After completing or skipping the tour, you will be redirected to your <strong>Dashboard Home</strong> where you can explore your matches, profile insights, and more.</p>
                    </div>
                </div>
            </div>

            <!-- 9. Dashboard Home Overview -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingDashboard">
                    <button class="accordion-button collapsed shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDashboard" aria-expanded="false" aria-controls="collapseDashboard">
                        9. 🏠 Dashboard Home Overview
                    </button>
                </h2>
                <div id="collapseDashboard" class="accordion-collapse collapse" aria-labelledby="headingDashboard" data-bs-parent="#helpAccordion">
                    <div class="accordion-body">
                        <p>Once you've completed your registration and guided tour (or skipped it), you’ll land on the <strong>Dashboard Home Page</strong>. This is your personalized hub for all activity and features on the platform.</p>

                        <h6 class="fw-semibold">Key Features of the Dashboard:</h6>
                        <ul class="list-group list-group-flush mb-3">
                            <li class="list-group-item">
                                ✅ <strong>Recently Registered Profiles:</strong> View the latest profiles that have joined the platform.
                            </li>
                            <li class="list-group-item">
                                👀 <strong>Viewed My Profile:</strong> See who has visited your profile.
                            </li>
                            <li class="list-group-item">
                                👁️ <strong>Viewed By Me:</strong> Track the profiles you have viewed.
                            </li>
                            <li class="list-group-item">
                                🎯 <strong>Matched Profiles (Based on Preferences):</strong> Automatically displayed matches tailored to your preferences.
                            </li>
                            <li class="list-group-item">
                                📢 <strong>Right-Side Advertisement Area:</strong> Displays promotional banners, offers, or platform updates.
                            </li>
                        </ul>

                        <div class="alert alert-info" role="alert">
                            <i class="bi bi-lightbulb-fill me-1"></i>
                            Tip: Keep your profile updated and preferences accurate for best results.
                        </div>
                    </div>
                </div>
            </div>

            <!-- 10. Search Matches -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingSearch">
                    <button class="accordion-button collapsed shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSearch" aria-expanded="false" aria-controls="collapseSearch">
                        10. 🔍 Search Matches Screen
                    </button>
                </h2>
                <div id="collapseSearch" class="accordion-collapse collapse" aria-labelledby="headingSearch" data-bs-parent="#helpAccordion">
                    <div class="accordion-body">
                        <p>The <strong>Search Matches</strong> screen helps you explore all registered user profiles with powerful filtering options to narrow down your ideal matches.</p>

                        <h6 class="fw-semibold">Features:</h6>
                        <ul class="list-group list-group-flush mb-3">
                            <li class="list-group-item">🔎 <strong>All Profiles:</strong> By default, all active and public user profiles are displayed in a grid or list format.</li>
                            <li class="list-group-item">📁 <strong>Search Filters:</strong> Use filters like Age, Religion, Caste, Location, Qualification, Marital Status, etc., to refine your search.</li>
                            <li class="list-group-item">🖼️ <strong>Profile Cards:</strong> Each profile displays basic info like photo, name, age, height, religion, caste, location.</li>
                            <li class="list-group-item">⚙️ <strong>Interactive Actions:</strong> View Profile, Add to Wishlist.</li>
                        </ul>

                        <div class="alert alert-secondary" role="alert">
                            <i class="bi bi-info-circle me-2"></i>
                            Tip: Combine filters wisely for better match results. Too many filters may narrow your results too much.
                        </div>
                    </div>
                </div>
            </div>

            <!-- 11. About Us -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingAbout">
                    <button class="accordion-button collapsed shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAbout" aria-expanded="false" aria-controls="collapseAbout">
                        11. 🏢 About Us
                    </button>
                </h2>
                <div id="collapseAbout" class="accordion-collapse collapse" aria-labelledby="headingAbout" data-bs-parent="#helpAccordion">
                    <div class="accordion-body">
                        <p>We are committed to helping individuals find their ideal life partner through a secure, user-friendly platform. Our mission is to connect like-minded people based on trust, compatibility, and shared values.</p>
                    </div>
                </div>
            </div>

            <!-- 12. Privacy Policy -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingPrivacy">
                    <button class="accordion-button collapsed shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePrivacy" aria-expanded="false" aria-controls="collapsePrivacy">
                        12. 🔐 Privacy Policy
                    </button>
                </h2>
                <div id="collapsePrivacy" class="accordion-collapse collapse" aria-labelledby="headingPrivacy" data-bs-parent="#helpAccordion">
                    <div class="accordion-body">
                        <p>Your personal data is handled with the utmost care. We do not share your information with third parties without consent. Users can manage profile visibility and contact preferences in privacy settings.</p>
                    </div>
                </div>
            </div>

            <!-- 13. Refund Policy -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingRefund">
                    <button class="accordion-button collapsed shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapseRefund" aria-expanded="false" aria-controls="collapseRefund">
                        13. 💸 Refund Policy
                    </button>
                </h2>
                <div id="collapseRefund" class="accordion-collapse collapse" aria-labelledby="headingRefund" data-bs-parent="#helpAccordion">
                    <div class="accordion-body">
                        <p>All payments made are final. Refunds are only considered under special circumstances at the discretion of the admin. Please contact support with proof of transaction and reason for refund.</p>
                    </div>
                </div>
            </div>

            <!-- 14. Account Deletion -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingDeletion">
                    <button class="accordion-button collapsed shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDeletion" aria-expanded="false" aria-controls="collapseDeletion">
                        14. 🗑️ Account Deletion
                    </button>
                </h2>
                <div id="collapseDeletion" class="accordion-collapse collapse" aria-labelledby="headingDeletion" data-bs-parent="#helpAccordion">
                    <div class="accordion-body">
                        <p>You can delete your account permanently from your <strong>My Profile</strong> page. Once deleted, your data will be removed and you will not be able to log in again.</p>
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i>
                            To recover a deleted account, you must contact the administrator.
                        </div>
                    </div>
                </div>
            </div>

            <!-- 15. Logout -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingLogout">
                    <button class="accordion-button collapsed shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapseLogout" aria-expanded="false" aria-controls="collapseLogout">
                        15. 🚪 Logout
                    </button>
                </h2>
                <div id="collapseLogout" class="accordion-collapse collapse" aria-labelledby="headingLogout" data-bs-parent="#helpAccordion">
                    <div class="accordion-body">
                        <p>You can securely log out of your account by clicking on the <strong>Logout</strong> option in the header menu. This will end your session and return you to the login screen.</p>
                    </div>
                </div>
            </div>


            <!-- 16. Support -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingSupport">
                    <button class="accordion-button collapsed shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSupport" aria-expanded="false" aria-controls="collapseSupport">
                        16. 🆘 Support
                    </button>
                </h2>
                <div id="collapseSupport" class="accordion-collapse collapse" aria-labelledby="headingSupport" data-bs-parent="#helpAccordion">
                    <div class="accordion-body">
                        <p>Our support team is available to assist you with technical issues, account queries, or any feature-related questions. You can raise a support ticket or contact us via the help section.</p>
                        <div class="alert alert-success">
                            <i class="bi bi-life-preserver me-1"></i>
                            Help is just a message away. Don’t hesitate to reach out!
                        </div>
                    </div>
                </div>
            </div>

            <!-- 17. Payment Plans & Package Update -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingPaymentPlans">
                    <button class="accordion-button collapsed shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePaymentPlans" aria-expanded="false" aria-controls="collapsePaymentPlans">
                        17. Payment Plans & Package Update
                    </button>
                </h2>
                <div id="collapsePaymentPlans" class="accordion-collapse collapse" aria-labelledby="headingPaymentPlans" data-bs-parent="#helpAccordion">
                    <div class="accordion-body">
                        <h6 class="fw-semibold">Upgrade to a Premium Package in Simple Steps:</h6>
                        <ol class="mb-3">
                            <li>Navigate to the <strong>Payment Plans</strong> page to view available packages.</li>
                            <li>Select your preferred package (Silver, Gold, etc.).</li>
                            <li>Click on <strong>Pay Now</strong> to proceed with online payment via the secure gateway.</li>
                            <li>Upon successful payment, your package will be automatically updated.</li>
                            <li>You can verify this on the <strong>My Plan Details</strong> screen.</li>
                        </ol>

                        <h6 class="fw-semibold">Manual Payment via Scanner or Bank Transfer:</h6>
                        <ul class="list-group list-group-flush mb-3">
                            <li class="list-group-item">If you paid using a QR scanner or transferred to the admin account manually, your package will <strong>not be updated automatically</strong>.</li>
                            <li class="list-group-item">Please contact the admin with your <strong>payment screenshot or transaction ID</strong>.</li>
                            <li class="list-group-item">After verification, the admin will manually activate your package.</li>
                        </ul>

                        <div class="alert alert-info">
                            <i class="bi bi-clock-history me-1"></i>
                            Manual payments may take a few hours to reflect depending on admin verification.
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>

    @include('web.includes.footer')
@endsection
