<link rel="stylesheet" href="core/views/css/header.css">
<header>
    <div class="header_first_part">
        <a href="/main">Forum::404devs</a>
    </div>
    <div class="header_second_part">
        <a href = "/main">Main</a>
        <a href = "/about">About</a>
        <a href = "/profile">Profile <img style="height: 40px; width: 40px; border-radius: 100px;" id='header_avatar'></a>
    </div>
</header>
<script>
    let userAvatar = <?php echo $user ?  '"'.$user['avatar'].'"' : null ;?>;
    if(userAvatar) document.querySelector('#header_avatar').src = userAvatar
    else document.querySelector('#header_avatar').src = 'core/views/avatars/avatar1.jpg'
    document.querySelector('#header_avatar').addEventListener('click' , e => {
        window.location.href = '/profile'
    })
</script>