<!-- Responsive Navbar -->
<nav class="navbar fixed-top bg-white shadow-sm">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <!-- Logo -->
        <div class="d-flex align-items-center">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('asset/img/logo/pg.webp') }}" alt="Bala Matrimony Bureau" style="height: 85px;">
            </a>
        </div>

        <!-- Right Section: Toggler + Notifications + Avatar/Login -->
        <div class="d-flex align-items-center gap-3">
            <!-- Mobile Toggler -->
            <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-controls="mobileMenu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Desktop Menu -->
            <div class="d-none d-lg-flex justify-content-center ">
                @auth
                    @if(auth()->user()->register_step >= 7)
                        <ul class="navbar-nav flex-row gap-4" style="padding-right: 100px">
                            <li class="nav-item"><a class="nav-link text-dark fw-medium" href="{{ url('home') }}">Home</a></li>
                            <li class="nav-item"><a class="nav-link text-dark fw-medium" href="{{ url('profileLists') }}">All Profiles</a></li>
                            <li class="nav-item"><a class="nav-link text-dark fw-medium" href="{{ url('all-profiles') }}">Search Matches</a></li>
                            <li class="nav-item"><a class="nav-link text-dark fw-medium" href="{{ url('horoscope-search') }}">Horoscope Search</a></li>
                            <li class="nav-item"><a class="nav-link text-dark fw-medium" href="{{ url('payment-plans') }}">Upgrade Now</a></li>
                        </ul>
                    @endif
                @endauth
            </div>

            @auth
                @if(auth()->user()->register_step >= 7)
                    <!-- Notification Dropdown -->
                    <div class="dropdown position-relative">
                        <a class="text-dark fs-5" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
                            <i class="bi bi-bell d-flex align-items-center justify-content-center rounded-circle bg-light-primary text-dark" style="width: 45px; height: 45px; font-size: 20px;"></i>
                            <span id="notification-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-circle text-danger">
                                0
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm p-2" id="notification-dropdown">
                            <li class="dropdown-header fw-bold">Loading...</li>
                        </ul>
                    </div>

                    <!-- User Dropdown -->
                    <div class="dropdown rounded-pill px-1 py-1 bg-light-primary">
                        <a href="#" class="d-flex align-items-center dropdown-toggle text-dark text-decoration-none" data-bs-toggle="dropdown" aria-expanded="false">
                            @php
                                $img = !empty($userAndUserDetails->profile_image)
                                    ? 'public/Profile Image/' . $userAndUserDetails->profile_image
                                    : 'public/web/assets/default/' . ($userAndUserDetails->gender === 'Male' ? 'default-male.jpg' : 'default-female.jpg');
                            @endphp
                            <img src="{{ asset($img) }}" class="avatar rounded-circle me-2 border-1" style="width: 40px; height: 40px; object-fit: cover;" alt="">
                            <span class="d-none d-md-block">{{ $userAndUserDetails->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li><a class="dropdown-item" href="{{ url('my-profile') }}">My Profile</a></li>
                            <li><a class="dropdown-item" href="{{ url('settings') }}">Edit Privacy</a></li>
                            <li><a class="dropdown-item" href="{{ url('plan') }}">My Plan Details</a></li>
                            <li><a class="dropdown-item" href="{{ url('set-preference') }}">Edit Partner Preference</a></li>
                            <li><a class="dropdown-item" href="{{ url('wishlists') }}">Wishlists</a></li>
                            <li><a class="dropdown-item" href="{{ url('interests') }}">Interests</a></li>
                            <li><a class="dropdown-item" href="{{ url('gallery') }}">My Gallery</a></li>
                            <li><a class="dropdown-item" href="{{ url('chats') }}">Chat</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form1').submit();">
                                    Logout
                                </a>
                                <form id="logout-form1" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn button1 rounded-pill px-4 py-2">Login</a>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn button1 rounded-pill px-4 py-2">Login</a>
            @endauth
        </div>
    </div>



    <!-- Mobile Offcanvas Menu -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="mobileMenu" aria-labelledby="mobileMenuLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="mobileMenuLabel">Menu</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            @auth
                @if(optional(auth()->user())->register_step >= 7)
                    <ul class="navbar-nav">
                        <li class="nav-item"><a class="nav-link text-dark fw-medium" href="{{ url('home') }}">Home</a></li>
                        <li class="nav-item"><a class="nav-link text-dark fw-medium" href="{{ url('profileLists') }}">All Profiles</a></li>
                        <li class="nav-item"><a class="nav-link text-dark fw-medium" href="{{ url('all-profiles') }}">Search Matches</a></li>
                        <li class="nav-item"><a class="nav-link text-dark fw-medium" href="{{ url('horoscope-search') }}">Horoscope Search</a></li>
                        <li class="nav-item"><a class="nav-link text-dark fw-medium" href="{{ url('payment-plans') }}">Upgrade Now</a></li>
                        <li class="nav-item"><a class="nav-link text-dark fw-medium" href="{{ url('my-profile') }}">My Profile</a></li>
                        <li class="nav-item"><a class="nav-link text-dark fw-medium" href="{{ url('settings') }}">Edit Privacy</a></li>
                        <li class="nav-item"><a class="nav-link text-dark fw-medium" href="{{ url('plan') }}">My Plan Details</a></li>
                        <li class="nav-item"><a class="nav-link text-dark fw-medium" href="{{ url('set-preference') }}">Edit Partner Preference</a></li>
                        <li class="nav-item"><a class="nav-link text-dark fw-medium" href="{{ url('wishlists') }}">Wishlists</a></li>
                        <li class="nav-item"><a class="nav-link text-dark fw-medium" href="{{ url('interests') }}">Interests</a></li>
                        <li class="nav-item"><a class="nav-link text-dark fw-medium" href="{{ url('gallery') }}">My Gallery</a></li>
                        <li class="nav-item"><a class="nav-link text-dark fw-medium" href="{{ url('chats') }}">Chat</a></li>
                        <li class="nav-item"><hr class="dropdown-divider"></li>
                        <li>
                            <a class="btn button1 w-100 mb-2" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                @else
                    <div class="mt-4">
                        <a href="{{ route('login') }}" class="btn button1 w-100 mb-2">Login</a>
                        <a href="{{ route('register') }}" class="btn button1 w-100">Register</a>
                    </div>
                @endif
            @else
                <div class="mt-4">
                    <a href="{{ route('login') }}" class="btn button1 w-100 mb-2">Login</a>
                    <a href="{{ route('register') }}" class="btn button1 w-100">Register</a>
                </div>
            @endauth
        </div>
    </div>
</nav>

<!-- Top Padding -->
<div style="padding-top: 88px;"></div>

<script>
    window.csrfToken = "{{ csrf_token() }}";
    window.routes = {
        fetchNotifications: "{{ route('FetchNotifications') }}",
        readNotification: "{{ route('ReadNotification') }}",
        deleteNotification: "{{ route('DeleteNotification') }}"
    };
</script>
<script src="{{ asset('asset/js/seasonal-notification.js') }}"></script>
