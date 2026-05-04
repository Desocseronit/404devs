<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="core/views/css/profile.css">
    <link href='https://fonts.googleapis.com/css?family=Inria Sans' rel='stylesheet'>
    <style>
        

    </style>
</head>
<body>
    <main>
        <div class="info-conatainer">
           <img class="ava" src="https://yt3.googleusercontent.com/eyrzi8qLE-Iv7c_MFQQdIK33CNOUeN2lOasBUeRhItCQyXo79TDE-40ng6fmQCjwgDShjCGNZw=s900-c-k-c0x00ffffff-no-rj" alt="" width="256" height="256">
            <div class="info">
                <p class="showName"></p>
                <p class="Name"></p>
                <button type="button">Изменить</button>
                <p class="email"></p>
            </div>
        </div>

    </main>
    <script>
    let a = JSON.parse(<?php echo json_encode($userData ?? null); ?>)
    console.log(a);
    if(a){
        document.querySelector('.showName').innerText = a.showname;
        document.querySelector('.Name').innerText = a.name;
        document.querySelector('.email').innerText = a.email;
    }
    </script>
</body>