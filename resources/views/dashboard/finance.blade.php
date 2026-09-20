<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>BiteSync - Finance Dashboard</title>
</head>

<body>

    <h1>BiteSync Finance Dashboard</h1>

    <p>
        Welcome, {{ $user->name }}.
    </p>

    <p>
        Role: {{ $user->role }}
    </p>

    <form method="POST" action="{{ route('logout') }}">
        @csrf

        <button type="submit">
            Logout
        </button>
    </form>

</body>
</html>s