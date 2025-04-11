<div class="navigation">

    <nav class="nav">
        <a href="{{ route('teacher.dashboard') }}">
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
                                echo Auth::user()->teacher->designation;
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
                <a href="{{ route('teacher.dashboard') }}" class="link {{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}" title="Dashboard">
                    <i class="material-icons icon">dashboard</i>
                    <span class="link-name">Dashboard</span>
                </a>
            </li>

            <li class="item-menu">
                <a href="{{ route('teacher.students') }}" class="link {{ 
                    request()->routeIs('teacher.students') ||
                    request()->routeIs('teacher.view.student') ? 'active' : '' }}"title="Student">
                    <i class="material-icons icon">people</i>
                    <span class="link-name">Students</span>
                </a>
            </li>

            <li class="item-menu">
                <a href="{{ route('teacher.manageclass') }}" class="link {{ 
                    request()->routeIs('teacher.manageclass') ||
                    request()->routeIs('teacher.create.class') ||
                    request()->routeIs('teacher.edit.class') ||
                    request()->routeIs('teacher.view.class')
                    ? 'active' : '' }}" title="Classes">
                    <i class="material-icons icon">class</i>
                    <span class="link-name">Classes</span>
                </a>
            </li>

            <li class="item-menu">
                <a href="{{ route('teacher.academic.records') }}" class="link {{ 
                    request()->routeIs('teacher.academic.records') ||
                    request()->routeIs('teacher.create.academic.record') ||
                    request()->routeIs('teacher.edit.academic.record') ||
                    request()->routeIs('teacher.view.academic.records')
                    ? 'active' : '' }}" title="Academic Records">
                    <i class="material-icons icon">school</i>
                    <span class="link-name">Academic</span>
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