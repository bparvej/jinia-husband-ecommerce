<table class="data-table">
    <thead>
        <tr>
            <th>User</th>
            <th>Role</th>
            <th>Status</th>
            <th>Last Login</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @if (isset($users) && count($users) > 0)
            @foreach ($users as $user)
                <tr id="user-{{ $user->id }}">
                    <td>
                        <div class="user-info">
                            <div class="user-avatar-small">
                                {{ strtoupper($user->name[0]) }}
                            </div>
                            <div class="user-details">
                                <span class="user-name">{{ $user->name }}</span>
                                <span class="user-email">{{ $user->email }}</span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="role-badge {{ $user->role->name ?? '' }}">
                            {{ $user->role->name ?? 'N/A' }}
                        </span>
                    </td>
                    <td>
                        <span class="status-badge {{ $user->is_active ? 'active' : 'inactive' }}">
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="text-muted">
                        {{ $user->last_login ? \Carbon\Carbon::parse($user->last_login)->format('d M Y') : 'Never' }}
                    </td>
                    <td>
                        <div class="actions">
                            <button class="btn-icon" onclick="editUser({{ json_encode($user) }})">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            </button>
                            @if ($user->id !== auth()->id() && ($user->role->name ?? '') !== 'super_admin')
                                <button class="btn-icon text-danger"
                                        hx-delete="/api/v1/users/{{ $user->id }}"
                                        hx-confirm="Are you sure you want to delete this user?"
                                        hx-target="#user-{{ $user->id }}"
                                        hx-swap="outerHTML">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        @else
            <tr><td colspan="5" class="text-center">No users found</td></tr>
        @endif
    </tbody>
</table>

@if (method_exists($users, 'hasPages') && $users->hasPages())
<div class="pagination">
    @if ($users->onFirstPage())
        <span class="page-btn disabled">← Prev</span>
    @else
        <a hx-get="/admin/users?page={{ $users->currentPage() - 1 }}&_partial=true" hx-target="#user-table-container" class="page-btn">← Prev</a>
    @endif
    @for ($i = 1; $i <= $users->lastPage(); $i++)
        <a hx-get="/admin/users?page={{ $i }}&_partial=true" hx-target="#user-table-container" class="page-btn {{ $i === $users->currentPage() ? 'active' : '' }}">{{ $i }}</a>
    @endfor
    @if ($users->hasMorePages())
        <a hx-get="/admin/users?page={{ $users->currentPage() + 1 }}&_partial=true" hx-target="#user-table-container" class="page-btn">Next →</a>
    @else
        <span class="page-btn disabled">Next →</span>
    @endif
</div>
@endif
