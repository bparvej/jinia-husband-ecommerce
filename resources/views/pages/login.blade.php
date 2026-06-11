<div class="login-page">
    <div class="login-container">
        <div class="login-brand">
            <a href="/" class="logo">
                <span class="logo-text">HOMEI</span>
                <span class="logo-tagline">Admin Panel</span>
            </a>
        </div>

        @if ($error)
        <div class="login-error">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
            {{ $error }}
        </div>
        @endif

        <form action="/login" method="POST" class="login-form">
            @csrf

            <div class="login-field">
                <label for="email">Email Address</label>
                <div class="login-input-wrap">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    <input type="email" id="email" name="email" placeholder="admin@homei.com" required autofocus>
                </div>
            </div>

            <div class="login-field">
                <label for="password">Password</label>
                <div class="login-input-wrap">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    <input type="password" id="password" name="password" placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" class="login-btn">
                Sign In
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </button>
        </form>

        <div class="login-footer">
            <p>Default: <strong>admin@homei.com</strong> / <strong>Admin@123</strong></p>
        </div>
    </div>
</div>

<style>
.login-page {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #2C1810 0%, #5C3D2E 50%, #3E2518 100%);
    padding: 2rem;
}

.login-container {
    width: 100%;
    max-width: 420px;
    background: #fff;
    border-radius: 24px;
    padding: 3rem 2.5rem;
    box-shadow: 0 25px 80px rgba(0,0,0,0.3);
}

.login-brand {
    text-align: center;
    margin-bottom: 2rem;
}

.login-brand .logo-text {
    font-size: 2rem;
    font-weight: 800;
    letter-spacing: 4px;
    color: #3E2518;
}

.login-brand .logo-tagline {
    display: block;
    font-size: 0.75rem;
    color: #9B8C80;
    letter-spacing: 2px;
    text-transform: uppercase;
}

.login-error {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 16px;
    background: #FEF2F2;
    border: 1px solid #FECACA;
    border-radius: 12px;
    color: #C44D4D;
    font-size: 0.88rem;
    margin-bottom: 1.5rem;
}

.login-field {
    margin-bottom: 1.25rem;
}

.login-field label {
    display: block;
    font-size: 0.85rem;
    font-weight: 600;
    color: #3E2518;
    margin-bottom: 6px;
}

.login-input-wrap {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 16px;
    background: #FAF6F1;
    border: 1.5px solid #EDE6DB;
    border-radius: 12px;
    transition: all 0.2s;
}

.login-input-wrap:focus-within {
    border-color: #C4956A;
    box-shadow: 0 0 0 4px rgba(196,149,106,0.1);
}

.login-input-wrap svg {
    color: #9B8C80;
    flex-shrink: 0;
}

.login-input-wrap input {
    flex: 1;
    border: none;
    background: transparent;
    font-size: 0.95rem;
    color: #2C1810;
    outline: none;
}

.login-input-wrap input::placeholder {
    color: #BEB3A6;
}

.login-btn {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 14px;
    background: #5C3D2E;
    color: #fff;
    border: none;
    border-radius: 12px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    margin-top: 0.5rem;
}

.login-btn:hover {
    background: #7A5640;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(92,61,46,0.3);
}

.login-footer {
    text-align: center;
    margin-top: 1.5rem;
    font-size: 0.78rem;
    color: #9B8C80;
}

.login-footer strong {
    color: #5C3D2E;
}
</style>
