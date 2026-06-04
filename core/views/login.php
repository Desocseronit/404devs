<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="core/views/css/login.css">
    <link href='https://fonts.googleapis.com/css?family=Inria Sans' rel='stylesheet'>
    <style>
        

    </style>
</head>
<body>
    <main>

        <form class="login-container" action="/login" method="POST">
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
            <a href="/sign-up">Sign up</a>
        </div>
    </main>
    </main>
</body>
<script>
        document.querySelector('form').addEventListener('submit',e=>{
        e.preventDefault()
        let formData = new FormData(e.target)
         fetch('/login',{
            method:'POST',
            body: formData
        })
        .then(resp=>{
            if(resp.status == 200){
                document.querySelector('.message').textContent = 'Login successful'
                window.location.href = '/main'
            }
            else if(resp.status == 401){
                document.querySelector('.password').querySelector('input').value = ''
                document.querySelector('.message').textContent = 'Incorrect login or password'
            }
        })
        })
</script>
</html>