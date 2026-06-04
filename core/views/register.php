<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="core/views/css/register.css">
    <link href='https://fonts.googleapis.com/css?family=Inria Sans' rel='stylesheet'>
    <style>


    </style>
</head>

<body>
    <main>
        <form class="register-container">
            <p class="message"></p>
            <div class="register-form">
                <h1>REGISTRY</h1>
                <div class="email">
                    <input type="email" id="email" name="email" placeholder="Email">
                </div>
                <div class="login">
                    <input type="text" id="login" name="username" placeholder="Username">
                </div>
                <div class="password">
                    <input type="password" id="password" name="password" placeholder="Password">
                </div>
                <div class="password_again">
                    <input type="password"  id="password_verify" placeholder="Password-verify">
                </div>        
            </div>
            <div class="register">
                <button id="register" type="submit">Registration</button>
            </div>
        </form>
        <div class="form-footer">
            <p>Have account?</p>
            <a href="/login">Login</a>
        </div>      
    </main>
</body>
<script>
 document.querySelector('form').addEventListener('submit',e=>{
        e.preventDefault()
        if(document.getElementById('password').value !== document.getElementById('password_verify').value){
            document.querySelector('.message').textContent = 'The passwords don\'t match' 
            return
        }
        if(document.getElementById('email').value == '' || document.getElementById('login').value == '' || document.getElementById('password').value == '' || document.getElementById('password_verify').value == ''){
            document.querySelector('.message').textContent = 'Some fields are not filled in' 
            return
        }
        
        let formData = new FormData(e.target)
         fetch('/sign-up',{
            method:'POST',
            body: formData
        })
        .then(resp=>{
            
            if(resp.status == 201){ 
                document.querySelector('.message').textContent = 'Sign up successful'
                window.location.href = '/main'
            }
            else if(resp.status == 403){
                document.querySelector('.message').textContent = 'Sign up failed'
                return 
            }
        })})

        



</script>
</html>
