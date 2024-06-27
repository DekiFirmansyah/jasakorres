<div class="sidebar" data-color="yellow">
    <!--
    Tip 1: You can change the color of the sidebar using: data-color="blue | green | orange | red | yellow"
-->
    <div class="logo">
        <a href="" class="simple-text logo-mini">
            <img src="{{ asset('assets') }}/img/logo-jpt.png" alt="CT" />
        </a>
        <a href="" class="simple-text logo-normal">
            {{ __('JASAKORRES') }}
        </a>
    </div>
    <div class="sidebar-wrapper" id="sidebar-wrapper">
        <ul class="nav">
            <li class="@if ($activePage == 'home') active @endif">
                <a href="{{ route('home') }}">
                    <i class="now-ui-icons design_app"></i>
                    <p>{{ __('Dashboard') }}</p>
                </a>
            </li>
            <!-- <li>
                <a data-toggle="collapse" href="#laravelExamples">
                    <i class="fab fa-laravel"></i>
                    <p>
                        {{ __("Laravel Examples") }}
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse show" id="laravelExamples">
                    <ul class="nav">
                        <li class="@if ($activePage == 'profile') active @endif">
                            <a href="{{ route('profile.edit') }}">
                                <i class="now-ui-icons users_single-02"></i>
                                <p> {{ __("User Profile") }} </p>
                            </a>
                        </li>
                        <li class="@if ($activePage == 'users') active @endif">
                            <a href="{{ route('user.index') }}">
                                <i class="now-ui-icons design_bullet-list-67"></i>
                                <p> {{ __("User Management") }} </p>
                            </a>
                        </li>
                    </ul>
                </div>
            </li> -->
            <li class="@if ($activePage == 'profile') active @endif">
                <a href="{{ route('profile.edit') }}">
                    <i class="now-ui-icons users_single-02"></i>
                    <p> {{ __("Profil User") }} </p>
                </a>
            </li>
            @hasanyrole('admin')
            <li class="@if ($activePage == 'users') active @endif">
                <a href="{{ route('user.index') }}">
                    <i class="now-ui-icons design_bullet-list-67"></i>
                    <p> {{ __("Manajemen User") }} </p>
                </a>
            </li>
            @endhasanyrole
            @hasanyrole('officer|secretary|manager')
            <li class="@if ($activePage == 'letters') active @endif">
                <a href="{{ route('letters.index') }}">
                    <i class="now-ui-icons education_paper"></i>
                    <p>{{ __('Surat') }}</p>
                </a>
            </li>
            @endhasanyrole
            @hasanyrole('secretary|manager|general-manager|general-director|executive-director')
            <li class="@if ($activePage == 'validations') active @endif">
                <a href="{{ route('validations.index') }}">
                    <i class="now-ui-icons ui-1_bell-53"></i>
                    <p>{{ __('Validasi Surat') }}</p>
                </a>
            </li>
            @endhasanyrole
            @hasanyrole('manager|general-manager|general-director|executive-director')
            <li class="@if ($activePage == 'letterValids') active @endif">
                <a href="{{ route('validations.valid') }}">
                    <i class="now-ui-icons education_paper"></i>
                    <p>{{ __('Surat Valid') }}</p>
                </a>
            </li>
            @endhasanyrole
            @hasanyrole('secretary')
            <li class="@if ($activePage == 'notebooks') active @endif">
                <a href="{{ route('notebooks.index') }}">
                    <i class="now-ui-icons location_map-big"></i>
                    <p>{{ __('Agenda Surat') }}</p>
                </a>
            </li>
            <li class="@if ($activePage == 'archives') active @endif">
                <a href="{{ route('archives.index') }}">
                    <i class="now-ui-icons design_bullet-list-67"></i>
                    <p>{{ __('Arsip Surat') }}</p>
                </a>
            </li>
            @endhasanyrole
            <!-- <li class=" @if ($activePage == 'notifications') active @endif">
                <a href="{{ route('page.index','notifications') }}">
                    <i class="now-ui-icons ui-1_bell-53"></i>
                    <p>{{ __('Notifications') }}</p>
                </a>
            </li>
            <li class=" @if ($activePage == 'table') active @endif">
                <a href="{{ route('page.index','table') }}">
                    <i class="now-ui-icons design_bullet-list-67"></i>
                    <p>{{ __('Table List') }}</p>
                </a>
            </li>
            <li class="@if ($activePage == 'typography') active @endif">
                <a href="{{ route('page.index','typography') }}">
                    <i class="now-ui-icons text_caps-small"></i>
                    <p>{{ __('Typography') }}</p>
                </a>
            </li> -->
        </ul>
    </div>
</div>