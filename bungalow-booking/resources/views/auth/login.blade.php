<x-layouts.app title="Login">
    <div class="card" style="max-width:460px;margin:auto">
        <div class="card-body">
            <form class="stack" method="POST" action="{{ route('login.store') }}">
                @csrf
                <h1>Login</h1>
                <label>Email
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus>
                </label>
                <label>Password
                    <input type="password" name="password" required>
                </label>
                <label style="display:flex;align-items:center;gap:8px;font-weight:500">
                    <input style="width:auto" type="checkbox" name="remember" value="1"> Remember me
                </label>
                <button class="button" type="submit">Login</button>
            </form>
        </div>
    </div>
</x-layouts.app>
