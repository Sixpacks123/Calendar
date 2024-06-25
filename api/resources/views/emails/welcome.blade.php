<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Calendar</title>
</head>
<body>
    <h1>Welcome, {{ $user->name }}</h1>
    <p>Your account has been created successfully.</p>
    <p>Your login details are as follows:</p>
    <p>Email: {{ $user->email }}</p>
    <p>Password: {{ $password }}</p>
    <p>Please log in and change your password immediately.</p>
    <p>Thank you!</p>
</body>
</html>
