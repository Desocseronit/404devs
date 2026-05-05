<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="/css/footer.css">
    <link rel="stylesheet" href="/css/header.css">
    <link href='https://fonts.googleapis.com/css?family=Inria Sans' rel='stylesheet'>
    <style>
        

    </style>
</head>
<body>
    <?php require 'header.php'?>
    <main>

        <form class="login-container" action="http://404devs/login" method="POST">
            <p class="message"></p>
            <div class="auth-form">
                <h1>LOGIN</h1>
                <div class="login">
                    <input type="text" name="username" placeholder="Username">
                </div>
                <div class="password">
                    <input type="password" name="password" placeholder="Password">
                </div>
            </div>
            <div class="Enter">
                <button id="Enter" type="submit">Login</button>
            </div>
        </form>
        <div class="form-footer">
            <p>Have no account?</p>
            <a href="http://404devs/Sign-up">Sign up</a>
        </div>
    </main>
    <?php require "footer.php"?>
</body>
<script>
        document.querySelector('form').addEventListener('submit',e=>{
        e.preventDefault()
        let formData = new FormData(e.target)
         fetch('http://404devs/login',{
            method:'POST',
            body: formData
        })
        .then(resp=>{
            if(resp.status == 200){
                document.querySelector('.message').textContent = 'Вход успешен'
                window.location.href = 'http://404devs/main';
            }
        })
        })
</script>
</html>