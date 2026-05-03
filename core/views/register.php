<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/register.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel = "stylesheet" href="css/footer.css">
    <style>


    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <main>
        <form method="POST">
            <div class="register-form">
                <div class="email">
                    <input type="email" name="email" placeholder="Почта">
                </div>
                <div class="login">
                    <input type="text" name="username" placeholder="Логин">
                </div>
                <div class="password">
                    <input type="password" name="password" placeholder="Пароль">
                </div>
                <div class="password_again">
                    <input type="password"  placeholder="Пароль">
                </div>        
            </div>
            <div class="register">
                <button id="register" type="submit">Регистрация</button>
            </div>
        </form>      
    </main>
    <?php include 'footer.php'; ?>
</body>
<script>
</script>
</html>
