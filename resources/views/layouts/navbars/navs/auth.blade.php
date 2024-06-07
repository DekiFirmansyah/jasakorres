<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-transparent  bg-primary  navbar-absolute">
    <div class="container-fluid">
        <div class="navbar-wrapper">
            <div class="navbar-toggle">
                <button type="button" class="navbar-toggler">
                    <span class="navbar-toggler-bar bar1"></span>
                    <span class="navbar-toggler-bar bar2"></span>
                    <span class="navbar-toggler-bar bar3"></span>
                </button>
            </div>
            <a class="navbar-brand" href="#pablo">{{ $namePage }}</a>
        </div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation"
            aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navigation">
            <!-- <form>
                <div class="input-group no-border">
                    <input type="text" value="" class="form-control" placeholder="Search...">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <i class="now-ui-icons ui-1_zoom-bold"></i>
                        </div>
                    </div>
                </div>
            </form> -->
            <ul class="navbar-nav">
                <!-- <li class="nav-item">
                    <a class="nav-link" href="#pablo">
                        <i class="now-ui-icons media-2_sound-wave"></i>
                        <p>
                            <span class="d-lg-none d-md-block">{{ __("Stats") }}</span>
                        </p>
                    </a>
                </li> -->
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        <i class="now-ui-icons ui-1_bell-53"></i>
                        @if (Auth::user()->unreadNotifications->count())
                        <span class="badge badge-danger">{{ Auth::user()->unreadNotifications->count() }}</span>
                        @endif
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        @if (Auth::user()->unreadNotifications->count())
                        <h6 class="dropdown-header">Notifikasi Baru</h6>
                        <div id="notificationList">
                            @foreach (Auth::user()->unreadNotifications as $notification)
                            <a class="dropdown-item d-flex align-items-center" href="{{ $notification->data['url'] }}">
                                <div class="mr-3">
                                    <div class="icon-circle bg-primary">
                                        <i class="fas fa-file-alt text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <span class="font-weight-bold">{{ $notification->data['title'] }}</span><br>
                                    <strong class="text-danger">{{ $notification->data['message'] }}</strong><br>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                            @endforeach
                        </div>
                        <a class="dropdown-item text-center small text-gray-500"
                            href="{{ $notification->data['url'] }}">Lihat
                            Semua Surat</a>
                        <button id="markAllAsRead" class="dropdown-item text-center small text-gray-500"><i
                                class="fas fa-check"></i> Tandai Semua Telah Dibaca</button>
                        @else
                        <a class="dropdown-item text-center" href="#">Tidak ada notifikasi baru</a>
                        @endif
                    </div>

                    <!-- <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <i class="now-ui-icons location_world"></i>
                        <p>
                            <span class="d-lg-none d-md-block">{{ __("Some Actions") }}</span>
                        </p>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="#">{{ __("Action") }}</a>
                        <a class="dropdown-item" href="#">{{ __("Another action") }}</a>
                        <a class="dropdown-item" href="#">{{ __("Something else here") }}</a>
                    </div> -->
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="true">
                        <i class="now-ui-icons users_single-02"></i>
                        <p>
                            <span class="d-lg-none d-md-block">{{ __("Account") }}</span>
                        </p>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="{{ route('profile.edit') }}">{{ __("My profile") }}</a>
                        <a class="dropdown-item" href="{{ route('profile.edit') }}">{{ __("Edit profile") }}</a>
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                          document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- End Navbar -->