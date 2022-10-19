<!--  BEGIN NAVBAR  -->
<div class="header-container fixed-top">
    <header class="header navbar navbar-expand-sm">
        <ul class="navbar-item flex-row">
            <li class="nav-item theme-logo">
        <a href="javascript:void(0);" class="sidebarCollapse" data-placement="bottom">
            <svg xmlns="http://www.w3.org/2000/svg" width="44" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-list"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3" y2="6"></line><line x1="3" y1="12" x2="3" y2="12"></line><line x1="3" y1="18" x2="3" y2="18"></line></svg></a>
<a/>
            </li>
        </ul>

        <ul class="navbar-item flex-row">
            <li class="nav-item theme-logo">
                <a href="{{url('home')}}">
                    <img src="{{ Auth::user()->lote()->first()->country()->first()->co_logo }}" class="navbar-logo" alt="logo">
                    <b style="font-size: 14px; color:#3B3F5C">{{ Auth::user()->lote()->first()->country()->first()->co_name }}</b>
                </a>
            </li>
        </ul>


        <ul class="navbar-item flex-row search-ul">
            <li class="nav-item align-self-center ">

                    <div class="">
                        <a href="{{ Auth::user()->lote()->first()->country()->first()->co_url_video}}" target="_blank" class="btn btn-success">
                            Videos
                        </a>
                    </div>

            </li>
        </ul>
        <ul class="navbar-item flex-row search-ul">
            <li class="nav-item align-self-center">
                    <div class="">
                        <a  href="{{ Auth::user()->lote()->first()->country()->first()->co_url_gps}}" target="_blank" class="btn btn-success">
                            GPS
                        </a>
                    </div>
            </li>
        </ul>

        <ul class="navbar-item flex-row navbar-dropdown">

            <li class="nav-item dropdown user-profile-dropdown  order-lg-0 order-1">
                <a href="javascript:void(0);" class="nav-link dropdown-toggle user" id="userProfileDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="far fa-user text-dar"></i>
                </a>
                <div class="dropdown-menu position-absolute animated fadeInUp" aria-labelledby="userProfileDropdown">
                    <div class="user-profile-section">
                        <div class="media mx-auto">
                            <img src="assets/img/avatar.png" class="img-fluid mr-2" alt="avatar">
                            <div class="media-body">
                                <h5>{{ Auth::user()->us_name }}</h5>
                                <p>{{ Auth::user()->us_apellido }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="dropdown-item">
                        <a href="user_profile.html">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg> <span>My Profile</span>
                        </a>
                    </div>

                    <div class="dropdown-item">
                        <a href="{{url('logout')}}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg> <span>Salir</span>
                        </a>
                    </div>
                </div>
            </li>
        </ul>
    </header>


</div>
<!--  END NAVBAR  -->
