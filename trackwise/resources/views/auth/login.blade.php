<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrackWise &mdash; Sign In</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'DM Sans', sans-serif; box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg: #0f1117;
            --surface: #16181f;
            --surface2: #1e2029;
            --border: #2a2d3a;
            --accent: #6ee7b7;
            --accent2: #f87171;
            --text: #e8eaf0;
            --muted: #6b7280;
        }

        body {
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Subtle grid background */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(var(--border) 1px, transparent 1px),
                linear-gradient(90deg, var(--border) 1px, transparent 1px);
            background-size: 48px 48px;
            opacity: 0.3;
            pointer-events: none;
        }

        .auth-wrap {
            width: 100%;
            max-width: 420px;
            padding: 24px;
            position: relative;
            z-index: 1;
        }

        .auth-logo {
            text-align: center;
            margin-bottom: 32px;
        }

        .auth-logo span {
            font-size: 26px;
            font-weight: 600;
            letter-spacing: -0.5px;
            color: var(--text);
        }

        .auth-logo span em {
            font-style: normal;
            color: var(--accent);
        }

        .auth-logo p {
            font-size: 13px;
            color: var(--muted);
            margin-top: 6px;
        }

        .auth-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 32px;
        }

        .auth-card h2 {
            font-size: 18px;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 6px;
        }

        .auth-card p.subtitle {
            font-size: 13px;
            color: var(--muted);
            margin-bottom: 28px;
        }

        .field { margin-bottom: 18px; }

        .field-label {
            display: block;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 6px;
        }

        .form-input {
            width: 100%;
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 11px 14px;
            color: var(--text);
            font-size: 14px;
            font-family: 'DM Sans', sans-serif;
            outline: none;
            transition: border-color 0.15s;
        }

        .form-input:focus { border-color: var(--accent); }
        .form-input::placeholder { color: var(--muted); }
        .form-input.has-error { border-color: var(--accent2); }

        .field-error {
            color: var(--accent2);
            font-size: 12px;
            margin-top: 5px;
        }

        .btn-submit {
            width: 100%;
            padding: 12px;
            background: var(--accent);
            color: #0f1117;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            transition: background 0.15s;
            margin-top: 8px;
        }

        .btn-submit:hover { background: #34d399; }

        .auth-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: var(--muted);
        }

        .auth-footer a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 500;
        }

        .auth-footer a:hover { text-decoration: underline; }

        .remember-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .remember-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: var(--muted);
            cursor: pointer;
        }

        .remember-label input[type="checkbox"] {
            width: 15px; height: 15px;
            accent-color: var(--accent);
            cursor: pointer;
        }

        .forgot-link {
            font-size: 13px;
            color: var(--muted);
            text-decoration: none;
            transition: color 0.15s;
        }

        .forgot-link:hover { color: var(--accent); }

        /* Session status */
        .status-msg {
            background: rgba(110,231,183,0.08);
            border: 1px solid rgba(110,231,183,0.2);
            color: var(--accent);
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="auth-wrap">
    <div class="auth-logo">
        <span>Track<em>Wise</em></span>
        <p>Personal finance, simplified</p>
    </div>

    <div class="auth-card">
        <h2>Welcome back</h2>
        <p class="subtitle">Sign in to your account to continue</p>

        @if (session('status'))
            <div class="status-msg">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="field">
                <label class="field-label" for="email">Email Address</label>
                <input id="email" name="email" type="email"
                       value="{{ old('email') }}"
                       placeholder="you@example.com"
                       autocomplete="username"
                       class="form-input {{ $errors->has('email') ? 'has-error' : '' }}" />
                @error('email')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="field">
                <label class="field-label" for="password">Password</label>
                <input id="password" name="password" type="password"
                       placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;"
                       autocomplete="current-password"
                       class="form-input {{ $errors->has('password') ? 'has-error' : '' }}" />
                @error('password')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="remember-row">
                <label class="remember-label">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    Remember me
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="forgot-link">
                        Forgot password?
                    </a>
                @endif
            </div>

            <button type="submit" class="btn-submit">Sign In</button>
        </form>
    </div>

    @if (Route::has('register'))
        <div class="auth-footer">
            Don&rsquo;t have an account?
            <a href="{{ route('register') }}">Create one &rarr;</a>
        </div>
    @endif
</div>

</body>
</html>