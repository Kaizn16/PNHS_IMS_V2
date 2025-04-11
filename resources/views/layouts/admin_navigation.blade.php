<div class="navigation">

    <nav class="nav">
        <a href="{{ route('admin.dashboard') }}">
            <img class="logo" src="{{ asset('assets/images/Logo.jpg') }}" alt="Logo">
        </a>
        <span class="profile" id="user-profile">
            <i class="material-icons icon">account_circle</i>
            <div class="dropdown-container">
                <div class="items">
                    <div class="item">
                        <strong>
                            Name:
                            @php
                                echo Auth::user()->name;
                            @endphp
                        </strong>
                    </div>
                    <div class="item">
                        <strong>
                            Role:
                            @php
                                echo Auth::user()->role->role_type;
                            @endphp
                        </strong>
                    </div>
                    <div class="item">
                        <a class="link" title="Logout" id="Signout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="material-icons icon">logout</i>
                            <span class="link-name">Logout</span>
                            <form id="logout-form" action="{{ route('auth.logout') }}" method="POST" style="display: none;">
                                @csrf
                                @method('POST')
                            </form>
                        </a>
                    </div>
                </div>
            </div>
        </span>
    </nav>

    <sidebar class="sidebar">
        <header class="header">
            <h1>PNHS</h1>
        </header>
        <uL class="sidebar-menu">
            <li class="item-menu">
                <a href="{{ route('admin.dashboard') }}" class="link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" title="Dashboard">
                    <i class="material-icons icon">dashboard</i>
                    <span class="link-name">Dashboard</span>
                </a>
            </li>

            <li class="item-menu">
                <a href="{{ route('admin.teachers') }}" class="link {{ 
                    request()->routeIs('admin.teachers') || 
                    request()->routeIs('admin.create.teacher') || 
                    request()->routeIs('admin.edit.teacher') ? 'active' : '' }}" title="Teachers">
                    <i class="material-icons icon">supervised_user_circle</i>
                    <span class="link-name">Teachers</span>
                </a>
            </li>

            <li class="item-menu">
                <a href="{{ route('admin.students') }}" class="link {{ 
                    request()->routeIs('admin.students') ||
                    request()->routeIs('admin.view.student') ||
                    request()->routeIs('admin.create.student') || 
                    request()->routeIs('admin.edit.student') ? 'active' : '' }}"title="Student">
                    <i class="material-icons icon">people</i>
                    <span class="link-name">Students</span>
                </a>
            </li>

            <li class="item-menu">
                <a href="{{ route('admin.manageclass') }}" class="link {{ 
                    request()->routeIs('admin.manageclass') ||
                    request()->routeIs('admin.create.class') ||
                    request()->routeIs('admin.edit.class') ||
                    request()->routeIs('admin.view.class')
                    ? 'active' : '' }}" title="Classes">
                    <i class="material-icons icon">class</i>
                    <span class="link-name">Classes</span>
                </a>
            </li>

            <li class="item-menu">
                <a href="{{ route('admin.rooms') }}" class="link {{ 
                    request()->routeIs('admin.rooms') ||
                    request()->routeIs('admin.create.room') ||
                    request()->routeIs('admin.edit.room')
                    ? 'active' : '' }}" title="Rooms">
                    <i class="material-icons icon">meeting_room</i>
                    <span class="link-name">Rooms</span>
                </a>
            </li>

            <li class="item-menu">
                <a href="{{ route('admin.subjects') }}" class="link {{ 
                    request()->routeIs('admin.subjects') ||
                    request()->routeIs('admin.create.subject') ||
                    request()->routeIs('admin.edit.subject')
                    ? 'active' : '' }}" title="Subjects">
                    <i class="material-icons icon">local_library</i>
                    <span class="link-name">Subjects</span>
                </a>
            </li>

            <li class="item-menu">
                <a href="{{ route('admin.users') }}" class="link {{ 
                    request()->routeIs('admin.users') ||
                    request()->routeIs('admin.create.user') || 
                    request()->routeIs('admin.edit.user') 
                    ? 'active' : '' }}" title="Users">
                    <i class="material-icons icon">manage_accounts</i>
                    <span class="link-name">Users</span>
                </a>
            </li>
        </uL>
    </sidebar>
</div>
<script>
    const userProfile = document.getElementById('user-profile');
  
    userProfile.addEventListener('click', function (event) {
      this.classList.toggle('toggle-dropdown');
      event.stopPropagation();
    });
  
    document.addEventListener('click', function () {
      userProfile.classList.remove('toggle-dropdown');
    });
</script>