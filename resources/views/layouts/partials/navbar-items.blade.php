<!-- resources/views/layouts/partials/navbar-items.blade.php -->
<ul class="navbar-nav">
    <!-- Language Dropdown -->
    <li class="nav-item d-flex align-items-center">
        <img src="{{ asset('files/images/flags/ug.png') }}" class="wd-20 me-1" title="Uganda" alt="Uganda">
        <span class="ms-1 me-1 d-none d-md-inline-block">Uganda</span>
    </li>

    <!-- Profile Logout -->
    <li class="nav-item">
        <form id="logoutForm" method="POST" action="{{ route('logout') }}" class="d-inline">
            @csrf
            @php
                $currentUser = Auth::user();
                $photoUrl = $currentUser && $currentUser->photo 
                    ? route('images.show', ['folder' => 'profile-photos', 'filename' => $currentUser->photo])
                    : route('images.show', ['folder' => 'default-avatar', 'filename' => 'default-avatar.png']);
            @endphp
            <img class="wd-30 ht-30 rounded-circle" src="{{ $photoUrl }}" alt="profile" style="cursor:pointer;" onclick="if(confirm('Are you sure you want to logout?')) document.getElementById('logoutForm').submit();" title="{{ $currentUser ? $currentUser->first_name . ' ' . $currentUser->last_name : 'User' }}">
        </form>
    </li>
</ul>