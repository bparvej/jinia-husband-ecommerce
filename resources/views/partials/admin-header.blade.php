<header class="admin-topbar">
    <div class="topbar-left">
        <button class="topbar-toggle" @click="sidebarOpen = !sidebarOpen" aria-label="Toggle sidebar">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </button>
        <h1 class="topbar-title">{{ str_replace(' — HomeI Admin', '', $title ?? 'Dashboard') }}</h1>
    </div>

    <div class="topbar-right">
        <div class="topbar-search">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"></circle>
                <path d="M21 21l-4.35-4.35"></path>
            </svg>
            <input type="text" placeholder="Search..." aria-label="Search">
        </div>

        <div class="topbar-actions" x-data="{ userMenu: false }">
            <button class="topbar-user-btn" @click="userMenu = !userMenu" @click.outside="userMenu = false">
                <div class="topbar-avatar">
                    {{ auth()->check() ? strtoupper(auth()->user()->name[0]) : 'A' }}
                </div>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
            </button>

            <div class="topbar-dropdown" x-show="userMenu" x-transition>
                <div class="dropdown-header">
                    <strong>{{ auth()->check() ? auth()->user()->name : 'Admin' }}</strong>
                    <span>{{ auth()->check() ? auth()->user()->email : '' }}</span>
                </div>
                <div class="dropdown-divider"></div>
                <a href="/" class="dropdown-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                    View Store
                </a>
                <form method="POST" action="/logout" style="display:contents">
                    @csrf
                    <button type="submit" class="dropdown-item text-danger" style="background:none;border:none;width:100%;cursor:pointer;display:flex;align-items:center;gap:0.5rem;padding:0.5rem 1rem;font-size:0.875rem;color:#dc2626;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
