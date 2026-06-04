<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404devs</title>
    <link rel="stylesheet" href="core/views/css/main.css">
    <link href='https://fonts.googleapis.com/css?family=Inria Sans' rel='stylesheet'>
    <style>


    </style>
</head>

<body>
    <main>
        <div class="search">
            <input type="search" id='search-bar' data-type = 'post'>
            <img src="core/views/svgs/filter.svg" class="filter-icon">
        </div>
        <div id="filterContainer">
            <div id="filters">
                <p>Filter by:</p>
                <div>
                    <button data-filter = 'votes'>votes</button>
                    <button data-filter = 'views'>views</button>
                    <button data-filter = 'created_at'>date</button>
                </div>
            </div>
            <div id="sides">
                <p>Side:</p>
                <div>
                    <button data-side = '0'>descending</button>
                    <button data-side = '1'>ascending</button>
                </div>
            </div>
            <div id="categories">
                <p>Categories:</p>
            </div>
            <script type="module">
                import {getCategories} from '/core/views/js/categories.mjs'
                let catDiv = document.querySelector('#filterContainer').querySelector('#categories')
                getCategories().then(categories => {
                                    Object.keys(categories).forEach(key => {
                                        let div = document.createElement('div')
                                        let label = document.createElement('label')
                                        div.appendChild(label)
                                        label.textContent = key
                                        categories[key].forEach(category => {
                                            let button = document.createElement('button')
                                            button.dataset.category = category.name
                                            button.textContent = category.name
                                            div.appendChild(button)
                                        })
                                        catDiv.appendChild(div)
                                    })
                                })
            </script>
            <div id="levels">
                <p>Level:</p>
                <div></div>
            </div>
            <script type="module">
                import {levels} from '/core/views/js/levels.mjs'
                let lvlDiv = document.querySelector('#filterContainer').querySelector('#levels').querySelector('div')
                levels.forEach(level => {
                    let button = document.createElement('button')
                    button.textContent = level.name
                    button.dataset.level = level.name
                    lvlDiv.appendChild(button)
                })
            </script>
            <button id="filterButton">Filter</button>
        </div>
        <div class="allPosts" id="allPosts">

        </div>
        <div id='pages-container'> </div>
    </main>

    <template id="post-template">
        <div class="post-container">
            <div class="content-box">
                <h2 class="post_title"></h2>
                <p class="post_text"></p>
            </div>

            <div class="sidebar_label">
                <p>Votes: <b><span class="votes"></span></b></p>
            </div>

            <div class="post-footer">
                <div class="views">
                    <span>Views: <b><span class="views_count"></span></b></span>
                </div>
                <div class="answer">
                    <span>Answers: <b><span class="answer_count"></span></b></span>
                </div>
                <div class="level">
                    <span>Level: <b><span class="lvl"></span></b></span>
                </div>
                <div class="category">
                    <span>Category: <b><span class="category_name"></span></b></span>
                </div>
            </div>

            <div class="author-tag">
                <div class="avatar">
                    <img class="ava">
                </div>
                <div class="author">
                    <span class="author-name"></span>
                </div>
            </div>
        </div>
    </template>

</body>
<script type="module">
    import { Post } from './core/views/js/modules/Post.mjs'
    import {SearchBar} from './core/views/js/widgets/SearchBar.mjs'
    import { Pages } from './core/views/js/widgets/Pages.mjs'
    import { Filter } from './core/views/js/widgets/Filter.mjs'
    Post.allInstances = []
    const postTemplate = document.querySelector('#post-template')
    const pageInfo = <?php echo json_encode($data ?? null); ?>;
    Pages.init(pageInfo)
    Pages.draw()
    pageInfo.data.forEach(postData => {
        document.querySelector('.allPosts').appendChild(new Post(JSON.parse(postData) , postTemplate).render())
    })

    
    SearchBar.init()

    Filter.init()

    document.querySelector('.filter-icon').addEventListener('click', e=>{
        document.getElementById('filterContainer').classList.toggle('active')
    })
    document.addEventListener('click', e=> {
    if (document.getElementById('filterContainer').classList.contains('active') && 
        !document.getElementById('filterContainer').contains(e.target) && 
        e.target !== document.querySelector('.filter-icon')) {
        document.getElementById('filterContainer').classList.remove('active');
    }
});
</script>

</html>