<header id="page-topbar">
        <div class="navbar-header">
            <div class="d-flex">

                <div class="navbar-brand-box p-0">
                    <a href="" class="logo logo-dark">
                       <span class="logo-sm">
                           <img src="{{asset('asset/img/logo/fav-icon-pg.png')}}" alt="" style="height: 65px">
                       </span>
                        <span class="logo-lg">
                            <img src="{{ asset('asset/img/logo/pg.webp' ) }}" alt="" style="width: initial;height: 60px">
                        </span>
                    </a>
                </div>

                <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect" id="vertical-menu-btn">
                    <i class="fa fa-fw fa-bars"></i>
                </button>

            </div>

            <div class="d-flex">
                <div class="dropdown d-inline-block">
                    @php
                        $adminAvatar = !empty(Auth::user()->photo) && file_exists(public_path('Profile Image/' . Auth::user()->photo))
                            ? asset('Profile Image/' . Auth::user()->photo)
                            : asset('asset/img/logo/fav-icon-pg.png');
                    @endphp
                    <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img class="rounded-circle header-profile-user" src="{{ $adminAvatar }}"
                             alt="Header Avatar" style="width: 36px; height: 36px; object-fit: cover;">
                        <span class="d-none d-xl-inline-block ms-1" key="t-henry">{{ Auth::user()->name }}</span>
                        <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                    </button>

                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="{{ route('admin.profile') }}"><i class="bx bx-user font-size-16 align-middle me-1"></i> <span key="t-profile">My Profile</span></a>
                        <div class="dropdown-divider"></div>
                        <form id="logout-form" method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bx bx-power-off font-size-16 align-middle me-1 text-danger"></i>
                                <span key="t-logout">Logout</span>
                            </button>
                        </form>
                    </div>

                </div>

            </div>
        </div>
</header>
