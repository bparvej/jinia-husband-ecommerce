@extends('layouts.admin')

@section('content')
<div class="page-header">
    <div class="header-content">
        <h2>User Management</h2>
        <p class="text-muted">Manage administrators, staff, and customers.</p>
    </div>
    <div class="header-actions">
        <button class="btn btn-primary" onclick="openCreateModal()">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
            Add User
        </button>
    </div>
</div>

<div class="filter-card">
    <form hx-get="/admin/users" hx-target="#user-table-container" hx-push-url="true" hx-indicator=".loader" class="filter-form">
        <input type="hidden" name="_partial" value="true">
        <div class="search-input">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            <input type="text" name="search" placeholder="Search by name, email or phone..." value="{{ $filters['search'] ?? '' }}">
        </div>
        <select name="role_id">
            <option value="">All Roles</option>
            @foreach ($roles as $role)
                <option value="{{ $role->id }}" {{ ($filters['role_id'] ?? '') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-secondary">Filter</button>
    </form>
</div>

<div id="userModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">Add New User</h3>
            <span class="close" onclick="closeModal('userModal')">&times;</span>
        </div>
        <form id="userForm" hx-post="/api/v1/users" hx-target="#user-table-container" hx-indicator=".loader" onsubmit="handleUserSubmit(event)">
            @csrf
            <input type="hidden" name="id" id="userId">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" name="name" id="userName" required placeholder="John Doe">
            </div>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="userEmail" required placeholder="john@example.com">
            </div>
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="text" name="phone" id="userPhone" placeholder="+880 1XXX-XXXXXX">
            </div>
            <div class="form-group">
                <label for="role_id">Role</label>
                <select name="role_id" id="userRole" required>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="password" id="passwordLabel">Password</label>
                <input type="password" name="password" id="userPassword" placeholder="Enter password">
                <small class="text-muted" id="passwordHint">Leave blank to keep current password when editing.</small>
            </div>
            <div class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="is_active" id="userIsActive" checked>
                    Account Active
                </label>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('userModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Save User</button>
            </div>
        </form>
    </div>
</div>

<div id="user-table-container">
    @include('admin.users.partials.users-table')
</div>

<script>
    function openCreateModal() {
        document.getElementById('modalTitle').innerText = 'Add New User';
        document.getElementById('userForm').reset();
        document.getElementById('userId').value = '';
        document.getElementById('userForm').setAttribute('hx-post', '/api/v1/users');
        document.getElementById('userForm').removeAttribute('hx-put');
        document.getElementById('passwordLabel').innerText = 'Password';
        document.getElementById('userPassword').required = true;
        document.getElementById('passwordHint').style.display = 'none';
        htmx.process(document.getElementById('userForm'));
        openModal('userModal');
    }

    function editUser(user) {
        document.getElementById('modalTitle').innerText = 'Edit User';
        document.getElementById('userId').value = user.id;
        document.getElementById('userName').value = user.name;
        document.getElementById('userEmail').value = user.email;
        document.getElementById('userPhone').value = user.phone || '';
        document.getElementById('userRole').value = user.role_id;
        document.getElementById('userIsActive').checked = user.is_active;
        document.getElementById('userPassword').value = '';
        document.getElementById('userPassword').required = false;
        document.getElementById('passwordLabel').innerText = 'Change Password';
        document.getElementById('passwordHint').style.display = 'block';
        document.getElementById('userForm').setAttribute('hx-put', '/api/v1/users/' + user.id);
        document.getElementById('userForm').removeAttribute('hx-post');
        htmx.process(document.getElementById('userForm'));
        openModal('userModal');
    }

    function handleUserSubmit(event) {
        document.body.addEventListener('htmx:afterOnLoad', function(evt) {
            if (evt.detail.target.id === 'user-table-container' || evt.detail.xhr.status === 200) {
                closeModal('userModal');
                if (evt.detail.xhr.status === 200 && !evt.detail.xhr.responseText.includes('table')) {
                    htmx.trigger('#user-table-container', 'refresh');
                }
            }
        }, { once: true });
    }
</script>
@endsection
