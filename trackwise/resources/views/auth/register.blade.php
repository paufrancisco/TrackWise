<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrackWise &mdash; Create Account</title>
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
            padding: 24px;
        }

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
            max-width: 440px;
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

        .auth-logo span em { font-style: normal; color: var(--accent); }

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

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
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
        <h2>Create your account</h2>
        <p class="subtitle">Start tracking your income and expenses today</p>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="field">
                <label class="field-label" for="name">Full Name</label>
                <input id="name" name="name" type="text"
                       value="{{ old('name') }}"
                       placeholder="Juan dela Cruz"
                       autocomplete="name"
                       class="form-input {{ $errors->has('name') ? 'has-error' : '' }}" />
                @error('name')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

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

            <div class="grid-2">
                <div class="field">
                    <label class="field-label" for="password">Password</label>
                    <input id="password" name="password" type="password"
                           placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;"
                           autocomplete="new-password"
                           class="form-input {{ $errors->has('password') ? 'has-error' : '' }}" />
                    @error('password')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label class="field-label" for="password_confirmation">Confirm</label>
                    <input id="password_confirmation" name="password_confirmation" type="password"
                           placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;"
                           autocomplete="new-password"
                           class="form-input {{ $errors->has('password_confirmation') ? 'has-error' : '' }}" />
                    @error('password_confirmation')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <button type="submit" class="btn-submit">Create Account</button>
        </form>
    </div>

    <div class="auth-footer">
        Already have an account?
        <a href="{{ route('login') }}">&larr; Sign in</a>
    </div>
</div>

</body>
</html>