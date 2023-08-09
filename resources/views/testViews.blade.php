<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Users</title>
</head>
<body>
    <h1>Welcome to the Trading App</h1>

    <p>
        This is the landing page of the Trading App. You can access the following features:
    </p>

    <ul>
        <p>ID: {{ $user->id }}</p>
        <p>Email: {{ $user->email }}</p>

    </ul>

</body>
</html>
