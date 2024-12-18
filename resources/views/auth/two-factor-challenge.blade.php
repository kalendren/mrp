<!-- resources/views/auth/two-factor-challenge.blade.php -->

<form method="POST" action="{{ route('two-factor.login') }}">
    @csrf
    <div>
        <label for="code">Authentication Code</label>
        <input id="code" type="text" name="code" required autofocus>
    </div>

    <div>
        <button type="submit">
            Authenticate
        </button>
    </div>
</form>
