<div class="navigation">

    <nav class="nav">
        <a href="{{ route('student.dashboard') }}">
            <img class="logo" src="{{ asset('assets/images/Logo.jpg') }}" alt="Logo">
        </a>
        <span class="profile" id="user-profile">
            <img class="icon" src="{{ !empty(Auth::user()->profile_image) ? asset('storage/profile_images/' . Auth::user()->profile_image) : asset('storage/profile_images/default_profile.png') }}" alt="Profile Image Preview" style="max-width: 48px; clip-path: circle();">
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
                        <a href="{{ route('student.profile') }}" class="link" title="Profile">
                            <i class="material-icons icon">account_circle</i>
                            <span class="link-name">My Profile</span>
                        </a>
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
                <a href="{{ route('student.dashboard') }}" class="link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}" title="Dashboard">
                    <i class="material-icons icon">dashboard</i>
                    <span class="link-name">Dahsboard</span>
                </a>
            </li>

            <li class="item-menu">
                <a href="{{ route('student.manageclass') }}" class="link {{ 
                    request()->routeIs('student.manageclass') ||
                    request()->routeIs('student.view.class')
                    ? 'active' : '' }}" title="My Classes">
                    <i class="material-icons icon">class</i>
                    <span class="link-name">My Classes</span>
                </a>
            </li>

            <li class="item-menu">
                <a href="{{ route('student.academic.records') }}" class="link {{ 
                    request()->routeIs('student.academic.records') ||
                    request()->routeIs('student.view.academic.records')
                    ? 'active' : '' }}" title="Academic Records">
                    <i class="material-icons icon">school</i>
                    <span class="link-name">My Academics</span>
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