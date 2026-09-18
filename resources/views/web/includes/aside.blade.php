<div class="sidebar sticky-sidebar sidebar-modern d-none d-lg-block col-lg-3">

    <div class="row d-flex justify-content-center">
        <div class="col-md-11">

            <div class="card-body text-center bg-white shadow" style="border-radius: 7px;padding: 10px">
                @php
                    $defaultImage = 'public/web/assets/default/default.png';
                    if (!empty($userAndUserDetails->gender)) {
                        if ($userAndUserDetails->gender === 'Male') {
                            $defaultImage = 'public/web/assets/default/default-male.jpg';
                        } elseif ($userAndUserDetails->gender === 'Female') {
                            $defaultImage = 'public/web/assets/default/default-female.jpg';
                        }
                    }
                @endphp

                <img class="rounded-circle img-fluid" style="width: 150px;"
                     src="{{ empty($userAndUserDetails->profile_image) ? asset($defaultImage) : asset('Profile Image/' . $userAndUserDetails->profile_image) }}"
                     alt=""
                >

                <h5 class="m-0">{{ $userAndUserDetails->name }}</h5>
                <span>ID : BMB{{$userAndUserDetails->user_id}}</span>
                <p class="text-muted mb-1">{{ $userAndUserDetails->occupation_type }}</p>
                <p class="text-muted mb-4">{{ $userAndUserDetails->city }}</p>
                <div class="d-flex justify-content-center mb-2">
                    <a href="{{ url('my-profile') }}" type="button" data-mdb-button-init data-mdb-ripple-init class="btn primary_button">View</a>
                    <a href="{{ url('wishlists') }}" type="button" data-mdb-button-init data-mdb-ripple-init class="btn secondary_button ms-1">Wishlists</a>
                </div>

            </div>

            <div class="card shadow mt-5" style=" border-radius: 1em; ">
                <div class="primary_bg text-center text-white">
                    <h4> Menu</h4>
                </div>

                <div class="list-group list-group-flush mx-3 mt-4 ">
                    <a href="{{ url('home') }}" class="list-group-item list-group-item-action py-2 ripple {{ Request::is('home') ? 'active' : '' }}" aria-current="true">
                        <span>Home</span>
                    </a>
                    <a href="{{ url('my-profile') }}" class="list-group-item list-group-item-action py-2 ripple {{ Request::is('my-profile') ? 'active' : '' }}">
                        <span>My Profile</span>
                    </a>
                    <a href="{{ url('interests') }}" class="list-group-item list-group-item-action py-2 ripple {{ Request::is('interests') ? 'active' : '' }}">
                        <span>Mailbox</span>
                    </a>
                    <a href="{{ url('chats') }}" class="list-group-item list-group-item-action py-2 ripple {{ Request::is('chats') ? 'active' : '' }}">
                        <span>Chat</span>
                    </a>
                    <a href="{{ url('plan') }}" class="list-group-item list-group-item-action py-2 ripple {{ Request::is('plan') ? 'active' : '' }}">
                        <span>My Plan Details</span>
                    </a>
                    <a href="{{ url('set-preference') }}" class="list-group-item list-group-item-action py-2 ripple {{ Request::is('set-preference') ? 'active' : '' }}">
                        <span>Edit Partner Preference</span>
                    </a>
                    <a href="{{ url('settings') }}" class="list-group-item list-group-item-action py-2 ripple {{ Request::is('settings') ? 'active' : '' }}">
                        <span>Edit Privacy</span>
                    </a>
                </div>

            </div>

        </div>
    </div>
</div>


