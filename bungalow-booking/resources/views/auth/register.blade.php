<x-layouts.app title="Register">
    <div class="card" style="max-width:520px;margin:auto">
        <div class="card-body">
            <form class="stack" method="POST" action="{{ route('register.store') }}">
                @csrf
                <h1>Create Account</h1>
                <label>Name
                    <input name="name" value="{{ old('name') }}" required>
                </label>
                <label>Email
                    <input type="email" name="email" value="{{ old('email') }}" required>
                </label>
                <label>Password
                    <input type="password" name="password" required>
                </label>
                <label>Confirm Password
                    <input type="password" name="password_confirmation" required>
                </label>
                <button class="button" type="submit">Register</button>
            </form>
        </div>
    </div>
</x-layouts.app>
