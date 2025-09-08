<nav class="sidebar" style="background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%);">
    <div class="sidebar-header">
        <a href="#" class="sidebar-brand" style="color: white;">
            <span style="color: #2196F3; font-weight: bold;">Plant</span><span style="color: #4CAF50; font-weight: bold;">D</span>
        </a>
        <div class="sidebar-toggler not-active">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <div class="sidebar-body">
        <ul class="nav" style="color: white;">
            <li class="nav-item nav-category" style="color: #2563eb; font-weight: bold;">Main</li>
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link">
                    <i class="link-icon" data-feather="home"></i>
                    <span class="link-title">Dashboard</span>
                </a>
            </li>

            <li class="nav-item nav-category" style="color: #2563eb; font-weight: bold;">Community</li>
            <li class="nav-item">
                <a href="{{ route('experts.index') }}" class="nav-link">
                    <i class="link-icon" data-feather="users"></i>
                    <span class="link-title">Agricultural Experts</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('community.index') }}" class="nav-link">
                    <i class="link-icon" data-feather="message-square"></i>
                    <span class="link-title">Farmer Forum</span>
                </a>
            </li>

           

            <li class="nav-item nav-category" style="color: #2563eb; font-weight: bold;">Scan GPT DETECTION HISTORY</li>
            <li class="nav-item">
                <a href="{{ route('reports.generate') }}" class="nav-link">
                    <i class="link-icon" data-feather="pie-chart"></i>
                    <span class="link-title">Generated Report</span>
                </a>
            </li>
            
            <li class="nav-item nav-category" style="color: #2563eb; font-weight: bold;">Users</li>
            <li class="nav-item">
                <a href="{{ route('users.index') }}" class="nav-link">
                    <i class="link-icon" data-feather="users"></i>
                    <span class="link-title">User List</span>
                </a>
            </li>

            <li class="nav-item nav-category" style="color: #2563eb; font-weight: bold;">Admin</li>
            <li class="nav-item">
                <a href="{{ route('audit_trail.index') }}" class="nav-link">
                    <i class="link-icon" data-feather="activity"></i>
                    <span class="link-title">Audit Trail</span>
                </a>
            </li>
            <li class="nav-item nav-category" style="color: #2563eb; font-weight: bold;">Market</li>
            <li class="nav-item">
                <a href="{{ route('market-prices.index') }}" class="nav-link">
                    <i class="link-icon" data-feather="trending-up"></i>
                    <span class="link-title">Market Prices</span>
                </a>
            </li>
            <li class="nav-item nav-category" style="color: #2563eb; font-weight: bold;">News</li>
            <li class="nav-item">
                <a href="{{ route('news.index') }}" class="nav-link">
                    <i class="link-icon" data-feather="file-text"></i>
                    <span class="link-title">Agriculture News</span>
                </a>
            </li>
            <!-- TRAINING section placed right after News -->
            <li class="nav-item nav-category" style="color: #2563eb; font-weight: bold;">Training</li>
            <li class="nav-item">
                <a href="{{ url('/farming-resources') }}" class="nav-link">
                    <i class="link-icon" data-feather="play-circle"></i>
                    <span class="link-title">Farming Resources</span>
                </a>
            </li>
        </ul>
    </div>
</nav>