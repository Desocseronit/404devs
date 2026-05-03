<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">
    <style>


    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <main>
        <form  method="POST">
            <div class="auth-form">
                <div class="login">
                    <input type="text" name="username" placeholder="Логин">
                </div>
                <div class="password">
                    <input type="password" name="password" placeholder="Пароль">
                </div>     
            </div>
            <div class="Enter">
                <button id="Enter" type="submit">Вход</button>
            </div>
        </form>      
    </main>
    <?php include 'footer.php';?>
</body>
<script>
</script>
</html>