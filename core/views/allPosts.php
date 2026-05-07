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
    <main>
        <div class="search">
            <input type="search">
        </div>
        <div class="allPosts" id="allPosts">

        </div>
    </main>
    
    <template id="card-template">
    <div class="card-container">
        <div class="content-box">
            <h2 class="card_title"></h2>
            <p class="card_text"></p>
        </div>
        
        <div class="sidebar_label">
            <p>Views:<span class="votes"></span></p>
        </div>

        <div class="cards-footer">
            <div class="views">
                <span class="views_count"></span>
            </div>
            <div class="answer">
                <span class="answer_count"></span>
            </div>
            <div class="level">
                <span class="lvl"></span>
            </div>
            <div class="category">
                <span class="category_name"></span>
            </div>
        </div>

        <div class="author-tag">
            <div class="avatar-circle"></div>
            <span class="author-name"></span>
        </div>
    </div>
</template>

</body>
<script>
    const PostsData= JSON.parse(<?php echo json_encode($data ?? null); ?>)
    
    function renderCards() {
        PostsData.forEach(post => {
            let card = document.getElementById('card-template').content.cloneNode(true)

            card.querySelector('.card_title').textContent = post.title
            card.querySelector('.card_text').textContent = post.text
            card.querySelector('.votes').textContent = post.votes
            card.querySelector('.views_counnt').textContent = post.views
            card.querySelector('.category_name').textContent = post.category_id
            card.querySelector('.lvl').textContent = post.level_id
            card.querySelector('.answer_count').textContent = post.user_id

            document.getElementById('allPosts').appendChild(card)
        })
    }
    document.addEventListener('DOMContentLoaded', renderCards)
</script>
</html>