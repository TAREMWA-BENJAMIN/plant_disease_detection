<nav class="navbar" style="border-bottom: 2px solid blue;">
    <a href="#" class="sidebar-toggler">
        <i data-feather="menu"></i>
    </a>
    <div class="navbar-content">
        <form class="search-form" autocomplete="off">
            <div class="input-group position-relative">
                <div class="input-group-text">
                    <i data-feather="search"></i>
                </div>
                <input type="text" class="form-control" id="navbarForm" placeholder="Search here...">
                <div id="navbar-search-results" class="dropdown-menu w-100" style="max-height:300px; overflow-y:auto; display:none; position:absolute; top:100%; left:0; z-index:1000;"></div>
            </div>
        </form>
        <ul class="navbar-nav">
            @include('layouts.partials.navbar-items')
        </ul>
    </div>
</nav>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('navbarForm');
    const resultsDropdown = document.getElementById('navbar-search-results');
    let timeout = null;

    searchInput.addEventListener('input', function() {
        clearTimeout(timeout);
        const query = this.value.trim();
        if (query.length < 2) {
            resultsDropdown.style.display = 'none';
            resultsDropdown.innerHTML = '';
            return;
        }
        timeout = setTimeout(function() {
                                    fetch(`{{ url('/search/users') }}?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(users => {
                    if (users.length === 0) {
                        resultsDropdown.innerHTML = '<div class="dropdown-item text-muted">No users found</div>';
                    } else {
                        resultsDropdown.innerHTML = users.map(user =>
                            `<a href="{{ url('/users') }}/${user.id}/edit" class="dropdown-item">${user.name} 
                                <small class='text-muted'>${user.email}</small></a>`
                        ).join('');
                    }
                    resultsDropdown.style.display = 'block';
                });
        }, 250);
    });

    // Hide dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !resultsDropdown.contains(e.target)) {
            resultsDropdown.style.display = 'none';
        }
    });
    // Hide dropdown on escape
    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            resultsDropdown.style.display = 'none';
        }
    });
});
</script>
@endpush