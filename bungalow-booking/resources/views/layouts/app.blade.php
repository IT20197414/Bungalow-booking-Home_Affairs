<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Bungalow Booking' }}</title>
    <style>
        :root { color-scheme: light; --ink:#172026; --muted:#667085; --line:#d8dee4; --bg:#f6f8fa; --panel:#ffffff; --brand:#1b6f5c; --accent:#c74634; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; color: var(--ink); background: var(--bg); }
        a { color: inherit; text-decoration: none; }
        .shell { width: min(1120px, calc(100% - 32px)); margin: 0 auto; }
        .nav { background: var(--panel); border-bottom: 1px solid var(--line); position: sticky; top: 0; z-index: 10; }
        .nav-inner { min-height: 68px; display: flex; align-items: center; justify-content: space-between; gap: 20px; }
        .brand { font-weight: 800; letter-spacing: .02em; color: var(--brand); }
        .nav-links { display: flex; align-items: center; gap: 14px; flex-wrap: wrap; }
        .nav-links a, .link-button { font-size: 14px; color: #334155; border: 0; background: transparent; cursor: pointer; padding: 0; }
        .button { display: inline-flex; align-items: center; justify-content: center; min-height: 40px; padding: 0 16px; border-radius: 6px; border: 1px solid var(--brand); background: var(--brand); color: white; font-weight: 700; cursor: pointer; }
        .button.secondary { background: white; color: var(--brand); }
        .button.danger { background: var(--accent); border-color: var(--accent); }
        .hero { padding: 56px 0 32px; background: linear-gradient(135deg, #eef7f0 0%, #f9f4eb 54%, #edf5f8 100%); border-bottom: 1px solid var(--line); }
        .hero h1 { max-width: 780px; font-size: clamp(34px, 6vw, 64px); line-height: 1; margin: 0 0 14px; letter-spacing: 0; }
        .hero p { max-width: 700px; color: var(--muted); font-size: 18px; line-height: 1.6; margin: 0; }
        main { padding: 32px 0 56px; }
        .grid { display: grid; gap: 18px; }
        .grid.cards { grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); }
        .card { background: var(--panel); border: 1px solid var(--line); border-radius: 8px; overflow: hidden; }
        .card-body { padding: 18px; }
        .media { min-height: 170px; background: linear-gradient(135deg, #1b6f5c, #e2b842); display: grid; place-items: center; color: white; font-weight: 800; }
        .media.image { background: #d8dee4; overflow: hidden; }
        .media.image img { width: 100%; height: 100%; min-height: inherit; object-fit: cover; display: block; }
        .listing-card { display: flex; flex-direction: column; height: 100%; }
        .listing-card .media { min-height: 178px; height: 178px; flex: 0 0 178px; }
        .listing-card .card-body { display: flex; flex: 1; flex-direction: column; }
        .listing-card .button { margin-top: auto; width: 100%; }
        .muted { color: var(--muted); }
        .section-head { display: flex; align-items: end; justify-content: space-between; gap: 18px; margin-bottom: 18px; }
        .section-head h2, h1 { margin: 0; }
        .table-wrap { overflow-x: auto; background: var(--panel); border: 1px solid var(--line); border-radius: 8px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 13px 14px; border-bottom: 1px solid var(--line); text-align: left; vertical-align: top; }
        th { background: #f0f4f2; font-size: 12px; text-transform: uppercase; color: #475569; }
        tr:last-child td { border-bottom: 0; }
        form.stack, .stack { display: grid; gap: 14px; }
        label { display: grid; gap: 6px; font-weight: 700; font-size: 14px; }
        input, textarea, select { width: 100%; border: 1px solid var(--line); border-radius: 6px; padding: 11px 12px; font: inherit; background: white; }
        textarea { min-height: 120px; resize: vertical; }
        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 14px; }
        .notice { padding: 12px 14px; border-radius: 6px; background: #eaf7ee; border: 1px solid #b8dfc1; color: #195c35; margin-bottom: 18px; }
        .errors { padding: 12px 14px; border-radius: 6px; background: #fff1f0; border: 1px solid #ffc9c3; color: #9f2f22; margin-bottom: 18px; }
        .badge { display: inline-flex; align-items: center; padding: 4px 8px; border-radius: 999px; background: #eef2f7; color: #344054; font-size: 12px; font-weight: 700; }
        .actions { display: flex; gap: 8px; flex-wrap: wrap; align-items: center; }
        .pagination { margin-top: 18px; }
        @media (max-width: 720px) { .nav-inner, .section-head { align-items: flex-start; flex-direction: column; } .hero { padding-top: 36px; } }
    </style>
</head>
<body>
    <nav class="nav">
        <div class="shell nav-inner">
            <a class="brand" href="{{ route('home') }}">Bungalow Booking</a>
            <div class="nav-links">
                <a href="{{ route('bungalows.index') }}">Bungalows</a>
                @auth
                    <a href="{{ route('customer.bookings.index') }}">My Bookings</a>
                    <a href="{{ route('customer.profile.show') }}">Profile</a>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}">Admin</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="link-button" type="submit">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}">Login</a>
                    <a class="button secondary" href="{{ route('register') }}">Register</a>
                @endauth
            </div>
        </div>
    </nav>

    @isset($hero)
        <header class="hero">
            <div class="shell">{{ $hero }}</div>
        </header>
    @endisset

    <main class="shell">
        @if(session('status'))
            <div class="notice">{{ session('status') }}</div>
        @endif

        @if($errors->any())
            <div class="errors">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        {{ $slot }}
    </main>
</body>
</html>
