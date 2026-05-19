/* ============================================
   Admin Users Page — Interactive Logic
   ============================================ */

function openCreateModal() {
    const modalTitle = document.getElementById('modalTitle');
    const userForm = document.getElementById('userForm');
    const userId = document.getElementById('userId');
    const passwordLabel = document.getElementById('passwordLabel');
    const userPassword = document.getElementById('userPassword');
    const passwordHint = document.getElementById('passwordHint');

    if (modalTitle) modalTitle.innerText = 'Add New User';
    if (userForm) {
        userForm.reset();
        userForm.setAttribute('hx-post', '/api/v1/users');
        userForm.removeAttribute('hx-put');
        // Re-process HTMX for the form
        if (window.htmx) htmx.process(userForm);
    }
    if (userId) userId.value = '';
    if (passwordLabel) passwordLabel.innerText = 'Password';
    if (userPassword) userPassword.required = true;
    if (passwordHint) passwordHint.style.display = 'none';
    
    openModal('userModal');
}

function editUser(user) {
    const modalTitle = document.getElementById('modalTitle');
    const userId = document.getElementById('userId');
    const userName = document.getElementById('userName');
    const userEmail = document.getElementById('userEmail');
    const userPhone = document.getElementById('userPhone');
    const userRole = document.getElementById('userRole');
    const userIsActive = document.getElementById('userIsActive');
    const userPassword = document.getElementById('userPassword');
    const passwordLabel = document.getElementById('passwordLabel');
    const passwordHint = document.getElementById('passwordHint');
    const userForm = document.getElementById('userForm');

    if (modalTitle) modalTitle.innerText = 'Edit User';
    if (userId) userId.value = user.id;
    if (userName) userName.value = user.name;
    if (userEmail) userEmail.value = user.email;
    if (userPhone) userPhone.value = user.phone || '';
    if (userRole) userRole.value = user.role_id;
    if (userIsActive) userIsActive.checked = user.is_active;
    
    if (userPassword) {
        userPassword.value = '';
        userPassword.required = false;
    }
    if (passwordLabel) passwordLabel.innerText = 'Change Password';
    if (passwordHint) passwordHint.style.display = 'block';
    
    if (userForm) {
        userForm.setAttribute('hx-put', `/api/v1/users/${user.id}`);
        userForm.removeAttribute('hx-post');
        // Re-process HTMX for the form
        if (window.htmx) htmx.process(userForm);
    }
    
    openModal('userModal');
}

function handleUserSubmit(event) {
    // HTMX handles the submission, we just need to close the modal on success
    document.body.addEventListener('htmx:afterOnLoad', function(evt) {
        if (evt.detail.target.id === 'user-table-container' || evt.detail.xhr.status === 200) {
            closeModal('userModal');
            // Optionally refresh the table if it wasn't the target
            if (evt.detail.xhr.status === 200 && !evt.detail.xhr.responseText.includes('table')) {
                if (window.htmx) htmx.trigger('#user-table-container', 'refresh');
            }
        }
    }, { once: true });
}

// Ensure handleUserSubmit is called if the form is submitted via HTMX
document.addEventListener('DOMContentLoaded', () => {
    const userForm = document.getElementById('userForm');
    if (userForm) {
        userForm.addEventListener('htmx:afterOnLoad', (evt) => {
             if (evt.detail.xhr.status === 200) {
                 closeModal('userModal');
             }
        });
    }
});
