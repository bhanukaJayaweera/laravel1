<style>
    body {
        background-color: #f8f9fa;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        padding: 20px;
        background-image: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    }
    
    .login-container {
        background-color: white;
        padding: 2.5rem;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 450px;
        transition: all 0.3s ease;
    }
    
    .login-container:hover {
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
    }
    
    .logo {
        text-align: center;
        margin-bottom: 2rem;
    }
    
    .logo svg {
        width: 50px;
        height: 50px;
        fill: #4f46e5;
    }
    
    .logo-text {
        font-size: 1.8rem;
        font-weight: 700;
        color: #4f46e5;
        margin-top: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }
    
    .input-group {
        margin-bottom: 1.5rem;
    }
    
    .input-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: #374151;
    }
    
    .input-field {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s;
    }
    
    .input-field:focus {
        outline: none;
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }
    
    .remember-me {
        display: flex;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    
    .remember-me input {
        margin-right: 10px;
        accent-color: #4f46e5;
    }
    
    .login-button {
        width: 100%;
        padding: 12px;
        background-color: #4f46e5;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .login-button:hover {
        background-color: #4338ca;
        transform: translateY(-2px);
    }
    
    .login-button:active {
        transform: translateY(0);
    }
    
    .footer-links {
        display: flex;
        justify-content: space-between;
        margin-top: 1.5rem;
        font-size: 0.9rem;
    }
    
    .footer-link {
        color: #4f46e5;
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .footer-link:hover {
        text-decoration: underline;
        color: #4338ca;
    }
    
    .divider {
        display: flex;
        align-items: center;
        margin: 1.5rem 0;
        color: #9ca3af;
    }
    
    .divider::before, .divider::after {
        content: "";
        flex: 1;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .divider::before {
        margin-right: 1rem;
    }
    
    .divider::after {
        margin-left: 1rem;
    }
    
    @media (max-width: 480px) {
        .login-container {
            padding: 1.5rem;
        }
        
        .footer-links {
            flex-direction: column;
            gap: 10px;
        }
    }
</style>
<body>
   <div class="login-container">
        <div class="logo">
           <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
            </svg>
            <div class="logo-text">Login Order Management</div>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div class="input-group">
                <label for="email" class="input-label">Email Address</label>
                <input id="email" class="input-field" type="email" name="email" :value="old('email')" required autofocus placeholder="Enter your email" />
            </div>

            <!-- Password -->
            <div class="input-group">
                <label for="password" class="input-label">Password</label>
                <input id="password" class="input-field"
                    type="password"
                    name="password"
                    required autocomplete="current-password" 
                    placeholder="Enter your password" />
            </div>

            <!-- Remember Me -->
            <div class="remember-me">
                <input id="remember_me" type="checkbox" name="remember">
                <label for="remember_me">Remember me</label>
            </div>

            <button type="submit" class="login-button">Sign In</button>

            <div class="footer-links">
                @if (Route::has('password.request'))
                    <a class="footer-link" href="{{ route('password.request') }}">
                        Forgot password?
                    </a>
                @endif
                
                @if (Route::has('register'))
                    <a class="footer-link" href="{{ route('register') }}">
                        Create an account
                    </a>
                @endif
            </div>
        </form>
    </div>
</body>
