<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/register.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel = "stylesheet" href="css/footer.css">
    <link href='https://fonts.googleapis.com/css?family=Inria Sans' rel='stylesheet'>
    <style>


    </style>
</head>

<body>
    <main>
        <form class="register-container">
            <div class="register-form">
                <h1>REGISTRATION</h1>
                <div class="email">
                    <input type="email" name="email" placeholder="Email">
                </div>
                <div class="login">
                    <input type="text" name="username" placeholder="Username">
                </div>
                <div class="password">
                    <input type="password" name="password" placeholder="Password">
                </div>
                <div class="password_again">
                    <input type="password"  placeholder="Password-verify">
                </div>        
            </div>
            <div class="register">
                <button id="register" type="submit">Registration</button>
            </div>
        </form>
        <div class="form-footer">
            <p>Have account?</p>
            <a href>Login</a>
        </div>      
    </main>
</body>
<script>
 document.querySelector('form').addEventListener('submit',e=>{
        e.preventDefault()
        let formData = new FormData(e.target)
         fetch('http://404devs/registrate',{
            method:'POST',
            body: formData
        })
        .then(resp=>{
            if(resp.status == 200){
               return 
            }
        })
        .then(( )=>{
            window.location.href = 'http://404devs/main';
        })})
</script>
</html>
