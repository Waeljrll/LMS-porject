<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            @php
                $dashboardRoute = match (auth()->user()->role) {
                    'admin' => route('admin.dashboard'),
                    'instructor' => route('instructor.dashboard'),
                    'student' => route('student.dashboard'),
                    default => route('dashboard'),
                };
                $isDashboard =
                    request()->routeIs('admin.dashboard') ||
                    request()->routeIs('instructor.dashboard') ||
                    request()->routeIs('student.dashboard');
            @endphp
            <a class="nav-link {{ $isDashboard ? '' : 'collapsed' }}" href="{{ $dashboardRoute }}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>

        @if (auth()->user()->role === 'admin')
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.users.*') ? '' : 'collapsed' }}"
                    href="{{ route('admin.users.index') }}">
                    <i class="bi bi-people"></i>
                    <span>Users</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.categories.*') ? '' : 'collapsed' }}"
                    href="{{ route('admin.categories.index') }}">
                    <i class="bi bi-journal-bookmark"></i>
                    <span>Categories</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.courses.index') ? '' : 'collapsed' }}"
                    href="{{ route('admin.courses.index') }}">
                    <i class="bi bi-journal-bookmark"></i>
                    <span>All Courses</span>
                </a>
            </li>
        @endif

        @if (auth()->user()->role === 'instructor')
            <li class="nav-heading">My Courses</li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('instructor.courses.my') ? '' : 'collapsed' }}"
                    href="{{ route('instructor.courses.my') }}">
                    <i class="bi bi-journal-text"></i>
                    <span>My Courses</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('instructor.courses.create') ? '' : 'collapsed' }}"
                    href="{{ route('instructor.courses.create') }}">
                    <i class="bi bi-plus-circle"></i>
                    <span>Create Course</span>
                </a>
            </li>

            <li class="nav-heading">Browse</li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('instructor.courses.index') ? '' : 'collapsed' }}"
                    href="{{ route('instructor.courses.index') }}">
                    <i class="bi bi-globe"></i>
                    <span>All Courses</span>
                </a>
            </li>
        @endif

        {{-- القائمة الخاصة بالطالب --}}
        @if (auth()->user()->role === 'student')
            <li class="nav-heading">Learning</li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('student.courses.index') ? '' : 'collapsed' }}"
                    href="{{ route('student.courses.index') }}">
                    <i class="bi bi-globe"></i>
                    <span>Browse Courses</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('student.courses.my') ? '' : 'collapsed' }}"
                    href="{{ route('student.courses.my') }}">
                    <i class="bi bi-book"></i>
                    <span>My Courses</span>
                </a>
            </li>
        @endif

        <li class="nav-heading">Account</li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('profile.view') ? '' : 'collapsed' }}"
                href="{{ route('profile.view') }}">
                <i class="bi bi-person"></i>
                <span>Profile</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('profile.edit') ? '' : 'collapsed' }}"
                href="{{ route('profile.edit') }}">
                <i class="bi bi-pencil-square"></i>
                <span>Edit Profile</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="#"
                onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>
            </a>
            <form id="logout-form-sidebar" method="POST" action="{{ route('logout') }}" style="display: none;">
                @csrf
            </form>
        </li>

    </ul>

</aside>
