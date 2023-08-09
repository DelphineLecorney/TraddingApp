<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
</head>
<body>
    <h1>Welcome to the Trading App</h1>

    <p>
        This is the landing page of the Trading App. You can access the following features:
    </p>

    <ul>
        <li><a href="{{ route('login') }}">Login</a></li>
        <li><a href="{{ route('signup') }}">Sign Up</a></li>
        <li><a href="{{ route('logout') }}">Logout</a></li>
    </ul>

</body>
</html>
