<!DOCTYPE html>
<html>
<head>
    <title>Liste des utilisateurs</title>
</head>
<body>
    <h1>Liste des utilisateurs</h1>

    <ul>
        @foreach($users as $user)
            <li>
                Nom: {{ $user->name }}<br>
                Email: {{ $user->email }}<br>
            </li>
            <br>
        @endforeach
    </ul>
</body>
</html>
