<aside id="sidebar-wrapper">
    <div class="sidebar-brand">
        <img class="navbar-brand-full app-header-logo" style="margin-bottom: 150px;border-radius: 5%;" src="{{ asset('img/'.Auth::user()->country->logo) }}" width="250"
             alt="Infyom Logo">
        <a href="{{ url('/') }}"></a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
        <a href="{{ url('/') }}" class="small-sidebar-text">
            <img class="navbar-brand-full" src="{{ asset('img/'.Auth::user()->country->logo) }}" width="45px" alt=""/>
        </a>
    </div>

    
    <ul class=" sidebar-menu ">
        @include('layouts.menu')
    </ul>
    

</aside>

