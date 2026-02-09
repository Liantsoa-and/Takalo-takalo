<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
        <form method="post" action="login" role="form">
        <div class="form-group">
            <label for="user">User name: </label>
            <input type="email" required id="user" name="user" class="form-control" value="{{ isset($user) ? $user : '' }}"><br>
        </div>
        <div class="form-group">
            <label for="password">Password: </label>
            <input type="password" required id="password" name="password" class="form-control"><br>
        </div>
        <button type="submit" class="btn btn-default">Login</button>
    </form>
</body>
</html>