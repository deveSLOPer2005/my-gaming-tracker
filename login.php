<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>authorization</title>
    <link rel="stylesheet" href="water.css">
</head>
<body>

    <h1>my game tracker</h1>

    <h2>sign in</h2>
    <form action="login.php" method="post">
        <input type="hidden" name="action" value="login">
        
        <label for="login_user">login:</label>
        <input type="text" id="login_user" name="username" required>
        
        <label for="login_pass">password:</label>
        <input type="password" id="login_pass" name="password" required>
        
        <button type="submit">submit</button>
    </form>

    <h1>or</h1>

    <h2>sign up</h2>
    <form action="login.php" method="post">
        <input type="hidden" name="action" value="register">
        
        <label for="reg_user">login:</label>
        <input type="text" id="reg_user" name="username" required>
        
        <label for="reg_pass">password:</label>
        <input type="password" id="reg_pass" name="password" required>
        
        <button type="submit">submit</button>
    </form>

</body>
</html>