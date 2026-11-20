<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Gadget Store') }}</title>
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/gadget-dark.css') }}" rel="stylesheet">
    <style>
        body { min-height: 100vh; }
        .auth-shell {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 28px 16px;
            background:
                radial-gradient(circle at 15% 20%, rgba(118,103,247,.28), transparent 28%),
                radial-gradient(circle at 85% 80%, rgba(34,211,238,.16), transparent 30%),
                linear-gradient(135deg,#090d1a,#10182b);
        }
        .auth-card {
            width: 100%;
            max-width: 460px;
            background: rgba(17,24,42,.96);
            border: 1px solid rgba(148,163,184,.16);
            border-radius: 22px;
            padding: 36px;
            box-shadow: 0 24px 70px rgba(0,0,0,.32);
        }
        .auth-card .form-control {
            height: 46px;
        }
        .auth-card .btn-block {
            height: 46px;
        }
        .auth-brand {
            width: 58px;
            height: 58px;
            border-radius: 16px;
            display: inline-flex;
            align-items:center;
            justify-content:center;
            background: linear-gradient(135deg,#7667f7,#22d3ee);
            color:#fff;
            margin-bottom:16px;
        }
    </style>
</head>
<body>
    <main class="auth-shell">
        <div class="auth-card">
            {{ $slot }}
        </div>
    </main>
</body>
</html>
