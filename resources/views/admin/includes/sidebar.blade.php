@php
    use Illuminate\Support\Facades\Auth;$user = Auth::user();
@endphp

<div class="vertical-menu">
    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" key="t-menu">Menu</li>

                <li>
                    <a href="{{ url('admin/dashboard') }}" class="waves-effect">
                        <i class="bx bx-home-circle"></i>
                        <span key="t-dashboards">Dashboard</span>
                    </a>
                </li>

                {{-- Staff Admin only --}}
                @if($user->role === 'staff_admin')
                    <li>
                        <a href="{{ url('admin/register') }}" class="waves-effect">
                            <i class="bx bx-user-plus"></i>
                            <span>Registration</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('admin/user-list') }}" class="waves-effect">
                            <i class="bx bxs-user-detail"></i>
                            <span>All Members</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('admin/payments') }}" class="waves-effect">
                            <i class="bx bx-credit-card"></i>
                            <span>Payments</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('admin/triumph_portal') }}" class="waves-effect">
                            <i class="bx bx-file"></i>
                            <span>Users Logs</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('admin/complaint_portal') }}" class="waves-effect">
                            <i class="bx bx-message-dots"></i>
                            <span>Enquiry & Complaints</span>
                        </a>
                    </li>
                @endif


                @if($user->role === 'admin')
{{--                    <li>--}}
{{--                        <a href="javascript: void(0);" class="has-arrow waves-effect">--}}
{{--                            <i class="bx bx-user-plus"></i>--}}
{{--                            <span key="t-dashboards">Registration</span>--}}
{{--                        </a>--}}
{{--                        <ul class="sub-menu" aria-expanded="false">--}}
{{--                            <li><a href="{{ url('admin/register') }}" key="t-tui-calendar">Register</a></li>--}}
                            {{--                        <li><a href="{{ url('admin/register-details/create') }}" key="t-full-calendar">Register details</a></li>--}}
                            {{--<li><a href="{{ url('admin/table-approval') }}" key="t-full-calendar">Table Approval</a></li>--}}
{{--                        </ul>--}}
{{--                    </li>--}}

                    <li>
                        <a href="{{ url('admin/register') }}" class="waves-effect">
                            <i class="bx bx-user-plus"></i>
                            <span>Register</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('admin/user-list') }}" class="waves-effect">
                            <i class="bx bx-group"></i>
                            <span key="t-contacts">All Members</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('admin/profile-analysis') }}" class="waves-effect">
                            <i class="bx bx-analyse"></i>
                            <span key="t-contacts">Profile Analysis</span>
                        </a>
                    </li>

                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="bx bx-store"></i>
                            <span key="t-ecommerce">Masters</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="{{ url('admin/ethnicity') }}" key="t-products">Ethnicity</a></li>
                            <li><a href="{{ url('admin/nationality') }}" key="t-products">Nationality</a></li>
                            <li><a href="{{ url('admin/visa-status') }}" key="t-products">Visa Status</a></li>
                            <li><a href="{{ url('admin/languages') }}" key="t-products">Mother Tongue</a></li>
                            <li><a href="{{ url('admin/marital-status') }}" key="t-product-detail">Marital Status</a>
                            </li>
                            <li><a href="{{ url('admin/religion') }}" key="t-add-product">Religion</a></li>
                            <li><a href="{{ url('admin/castes') }}" key="t-orders">Caste</a></li>
                            <li><a href="{{ url('admin/sub-castes') }}" key="t-customers">Sub Caste</a></li>
{{--                            <li><a href="{{ url('admin/kulam') }}" key="t-cart">Kulam</a></li>--}}
                            <li><a href="{{ url('admin/gothram') }}" key="t-checkout">Gothram</a></li>
                            <li><a href="{{ url('admin/stars') }}" key="t-shops">Nakshatra</a></li>
                            <li><a href="{{ url('admin/rashi') }}" key="t-add-product">Rashi</a></li>
                            <li><a href="{{ url('admin/drinking-habit') }}" key="t-add-product">Drinking Habit</a></li>
                            <li><a href="{{ url('admin/smoking-habit') }}" key="t-add-product">Smoking Habit</a></li>
                            <li><a href="{{ url('admin/eating-habit') }}" key="t-add-product">Eating Habit</a></li>
                            {{--                        <li><a href="{{ url('admin/family-god') }}" key="t-add-product">Family God</a></li>--}}
                            <li><a href="{{ url('admin/education') }}" key="t-add-product">Education</a></li>
                            <li><a href="{{ url('admin/occupation') }}" key="t-add-product">Occupation</a></li>
                            <li><a href="{{ url('admin/skin-tone') }}" key="t-add-product">Skin Tone</a></li>
                            <li><a href="{{ url('admin/height') }}" key="t-add-product">Height</a></li>
                            <li><a href="{{ url('admin/body-type') }}" key="t-add-product">Body Type</a></li>
                            <li><a href="{{ url('admin/employed-in') }}" key="t-add-product">Employed In</a></li>
                            <li><a href="{{ url('admin/salary') }}" key="t-add-product">Monthly Income</a></li>
                            <li><a href="{{ url('admin/dosham') }}" key="t-add-product">Dosha</a></li>
{{--                            <li><a href="{{ url('admin/lagnam') }}" key="t-add-product">Lagnam</a></li>--}}
{{--                            <li><a href="{{ url('admin/padam') }}" key="t-add-product">Padam</a></li>--}}
                            <li><a href="{{ url('admin/property-detail') }}" key="t-add-product">Property Details</a></li>
{{--                            <li><a href="{{ url('admin/new_data') }}" key="t-add-product">User Requirements</a></li>--}}
                        </ul>
                    </li>


                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="bx bx-receipt"></i>
                            <span key="t-invoices">Pages</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="{{ url('admin/terms-and-conditions') }}" key="t-invoice-list">Terms &
                                    Conditions</a></li>
                            <li><a href="{{ url('admin/privacy-policy') }}" key="t-invoice-detail">Privacy Policy</a>
                            </li>
                            <li><a href="{{ url('admin/refund-policy') }}" key="t-invoice-detail">Refund Policy</a></li>
                            <li><a href="{{ url('admin/about-us') }}" key="t-invoice-detail">About Us</a></li>
                            <li><a href="{{ url('admin/seo') }}" key="t-invoice-detail">SEO</a></li>
                        </ul>
                    </li>


{{--                    <li>--}}
{{--                        <a href="javascript: void(0);" class="has-arrow waves-effect">--}}
{{--                            <i class='bx bxl-blogger'></i>--}}
{{--                            <span key="t-blog">Blog</span>--}}
{{--                        </a>--}}
{{--                        <ul class="sub-menu" aria-expanded="false">--}}
{{--                            <li><a href="{{ url('admin/blog') }}" key="t-blog-list">Add Blog</a></li>--}}
{{--                            <li><a href="{{ url('admin/blogs') }}" key="t-blog-grid">Blogs</a></li>--}}
{{--                        </ul>--}}
{{--                    </li>--}}


                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="bx bx-receipt"></i>
                            <span key="t-invoices">Home Page</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="{{ url('admin/testimonials') }}" key="t-invoice-list">Testimonial</a></li>
                            <li><a href="{{ url('admin/happy-stories') }}" key="t-invoice-detail">Happy stories</a></li>
                            <li><a href="{{ url('admin/highlighted-profiles') }}" key="t-invoice-detail">Highlighted
                                    Profiles</a></li>
                        </ul>
                    </li>


                    <li>
                        <a href="{{ url('admin/payments') }}" class="waves-effect">
                            <i class="bx bx-credit-card"></i>
                            <span>Payments</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('admin/packages') }}" class="waves-effect">
                            <i class="bx bx-package"></i>
                            <span>Packages</span>
                        </a>
                    </li>

                    <li>
                        <a href="javascript: void(0);" class="waves-effect has-arrow">
                            <i class="bx bx-download"></i>
                            <span key="t-contacts">Backup</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li>
                                <form action="{{ route('backup.create') }}" method="GET" class=""
                                      style="display: flex;justify-content: space-around;margin-left: -21px;">
                                    @csrf
                                    <button type="submit" class="btn text-start"
                                            style="border: none; background: none;">
                                        Backup Database
                                    </button>
                                </form>
                            </li>
                            <li>
                                <a href="{{ route('backup.images') }}" key="t-invoice-list">Profile Images</a>
                            </li>
                            <li>
                                <a href="{{ route('horoscope.images') }}" key="t-invoice-list">Horoscope Images</a>
                            </li>
                            <li><a href="{{ url('admin/export') }}" key="t-invoice-list">Profile Export</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="bx bx-receipt"></i>
                            <span key="t-invoices">Advertisement</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="{{ url('admin/advertisement') }}" key="t-invoice-list">Website
                                    Advertisement</a></li>
                            <li><a href="{{ url('admin/mobile_advertisement') }}" key="t-invoice-detail">Mobile
                                    Advertisement</a></li>
                            <li>
                                <a href="{{ url('admin/app_slider') }}" key="t-invoice-detail">
                                    Mobile Slider
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="fa fa-cogs"></i>
                            <span key="t-invoices">secured settings</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="{{ url('admin/admin_info') }}" key="t-invoice-list">Admin Users</a></li>
                            <li><a href="{{ url('admin/contact_info') }}" key="t-invoice-detail">Contact Settings</a></li>
                            <li><a href="{{ url('admin/print_info') }}" key="t-invoice-detail">Print Settings</a></li>
                            <li><a href="{{ url('admin/payment_info') }}" key="t-invoice-detail">Payment Settings</a></li>
                            <li><a href="{{ url('admin/seasonal_notification') }}" key="t-invoice-detail">Seasonal Notification</a></li>
                            <li><a href="{{ url('admin/track_broker') }}" key="t-invoice-detail">Track broker</a></li>
                            <li><a href="{{ url('admin/user-verification') }}" key="t-invoice-detail">User Verification</a></li>
                            <li><a href="{{ url('admin/user-contacts') }}" key="t-invoice-detail">User Contacts</a></li>
{{--                            <li><a href="{{ url('admin/profile-updation') }}" key="t-invoice-detail">Profile Updation</a></li>--}}
{{--                            <li><a href="{{ url('admin/editedProfiles') }}" key="t-invoice-detail">Profile Edited Users</a></li>--}}
                        </ul>
                    </li>

                    <li>
                        <a href="{{ url('admin/triumph_portal') }}" class="waves-effect">
                            <i class="bx bx-file"></i>
                            <span key="t-dashboards">Users Logs</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('admin/complaint_portal') }}" class="waves-effect">
                            <i class="bx bx-message-dots"></i>
                            <span key="t-dashboards">Enquiry & Complaints</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('admin/help') }}" class="waves-effect">
                            <i class="bx bx-list-check"></i>
                            <span key="t-dashboards">Help</span>
                        </a>
                    </li>
                @endif

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
