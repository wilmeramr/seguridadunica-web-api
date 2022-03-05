

@if(\Illuminate\Support\Facades\Auth::user()->roles[0]->name == "Seguridad"
 || \Illuminate\Support\Facades\Auth::user()->roles[0]->name == "Administracion"
 || \Illuminate\Support\Facades\Auth::user()->roles[0]->name == "Administrador"
 )
 <li class="side-menus menu-header ">
    <a class="nav-link" href="/home">
        <i class=" fas fa-building"></i><span>Dashboard</span>
    </a>
 
    </li>
    @if(\Illuminate\Support\Facades\Auth::user()->roles[0]->name == "Administracion"
            || \Illuminate\Support\Facades\Auth::user()->roles[0]->name == "Administrador")
    <li class="menu-header side-menus">
    <a class="nav-link" href="/usuarios">
        <i class=" fas fa-users"></i><span>Usuarios</span>
    </a>
</li>
<li class="menu-header side-menus">
    <a class="nav-link" href="/roles">
        <i class=" fas fa-user-lock"></i><span>Roles</span>
    </a>
</li>
@endif
    <li class="menu-header side-menus">
    <a class="nav-link" href="/blogs">
        <i class=" fas fa-blog"></i><span>Blogs</span>
    </a>
    </li>
    @if(\Illuminate\Support\Facades\Auth::user()->roles[0]->name == "Administrador")
    <li class="menu-header side-menus">
    <a class="nav-link" href="/countrys">
       <i class=" fas fa-blog"></i><span>Countrys</span></a>
       </li>
    @endif

    @endif

        