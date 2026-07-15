@php $user = auth()->user(); @endphp
<aside class="app-sidebar text-white" style="width:250px; min-height:100vh; background:#1e2a3a;">
    <div class="p-3 border-bottom border-secondary">
        <a href="{{ url('/') }}" class="text-white text-decoration-none fs-5 fw-bold">
            <i class="bi bi-calendar2-check"></i> AttendSys
        </a>
    </div>
    <ul class="nav flex-column p-2">
        @if ($user->isAdmin())
            <li class="nav-item"><a class="nav-link text-white" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="{{ route('admin.students.index') }}"><i class="bi bi-mortarboard me-2"></i>Students</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="{{ route('admin.teachers.index') }}"><i class="bi bi-person-badge me-2"></i>Teachers</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="{{ route('admin.departments.index') }}"><i class="bi bi-building me-2"></i>Departments</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="{{ route('admin.classes.index') }}"><i class="bi bi-easel me-2"></i>Classes</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="{{ route('admin.subjects.index') }}"><i class="bi bi-journal-bookmark me-2"></i>Subjects</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="{{ route('attendance.index') }}"><i class="bi bi-clipboard-check me-2"></i>Attendance</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="{{ route('admin.reports') }}"><i class="bi bi-bar-chart me-2"></i>Reports</a></li>
        @elseif ($user->isTeacher())
            <li class="nav-item"><a class="nav-link text-white" href="{{ route('teacher.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="{{ route('attendance.create') }}"><i class="bi bi-clipboard-check me-2"></i>Take Attendance</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="{{ route('attendance.index') }}"><i class="bi bi-list-check me-2"></i>Attendance History</a></li>
        @elseif ($user->isStudent())
            <li class="nav-item"><a class="nav-link text-white" href="{{ route('student.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
        @endif
        <li class="nav-item"><a class="nav-link text-white" href="{{ route('profile.edit') }}"><i class="bi bi-person-circle me-2"></i>Profile</a></li>
    </ul>
</aside>

<style>
    .app-sidebar .nav-link:hover { background: rgba(255,255,255,.1); border-radius: .375rem; }
</style>
