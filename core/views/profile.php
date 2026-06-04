<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404devs</title>
    <link rel="stylesheet" href="core/views/css/profile.css">
    <link href='https://fonts.googleapis.com/css?family=Inria Sans' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css">
    <style>


    </style>
</head>

<body>
    <main>
        <div class="info-container">
            <img class="ava"
                alt="" width="256" height="256">
                <input type="file" id="avatar-input" accept="image/*" style="display: none;">
            <div class="info">
                <table>
                    <tbody>
                        <tr>
                            <td>Showname: </td>
                            <td class="showName"></td>
                        </tr>
                        <tr>
                            <td>Name:</td> 
                            <td class="Name"></td>
                        </tr>
                        <tr>
                            <td>Bio:</td>
                            <td><p class="bio-text" ></p></td>
                        </tr>
                            <tr id='email_info'>
                            <td>Email:</td>
                            <td class="email"></td>
                        </tr>
                        
                    </tbody>
                </table>
                <button id="edit" type="button">Edit</button>
                <div id="user-actions">
                    <button id="logOut">Log out</button>
                    <button id="deleteUser">Delete Me💀</button>
                </div>
                <script>
                    document.querySelector('#logOut').addEventListener('click' , e => {
                        window.location.href = '/logout'
                    })

                    document.querySelector('#deleteUser').addEventListener('click' , e => {
                        if(confirm('ARE YOU SURE???!!!')){
                            fetch('/delete-user' , {method: 'POST'}).then(resp => {
                                if(resp.ok){
                                    window.location.href = '/logout'
                                }
                            })
                        }
                    })
                </script>
            </div>
        </div>
        <div class="Posts">
            <div class="search">
            <input type="search" id='search-bar' data-type = 'user'>
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
            <div class="create-post">
                <form class="post-form">
                    <div class="text-title">
                        <input id='form-title' name='title' placeholder='title'></input>
                    </div>
                    <div class="text-section">
                        <textarea id="form-text" name="text" placeholder="text"></textarea>
                    </div>
                    <div class="form-footer">
                        <div class="picture">

                        </div>
                    <div class="form-option">
                        <div class="select-box">
                            <input type="hidden" name="category_id" id="category">
                            <button type="button" class="select-button" id="category_button">select category</button>
                            <div class="dropdown" id="category_dropdown"></div>
                        </div>
                        <script type="module">
                            import { getCategories } from './core/views/js/categories.mjs'

                            let categoryButton = document.getElementById('category_button')
                            let categoryDrop = document.getElementById('category_dropdown')
                            let levelDrop = document.getElementById('level_dropdown')

                            categoryButton.addEventListener('click', e => {
                                e.stopPropagation()
                                if(levelDrop) levelDrop.classList.remove('active')
                                categoryDrop.classList.toggle('active')
                            })

                            document.addEventListener('click', e => {
                                if (categoryDrop.classList.contains('active') && !categoryDrop.contains(e.target) && e.target !== categoryButton) {
                                    categoryDrop.classList.remove('active')
                                }
                            })

                            getCategories().then(categories => {
                                Object.keys(categories).forEach(key => {
                                    let div = document.createElement('div')
                                    let label = document.createElement('label')
                                    div.appendChild(label)
                                    label.textContent = key

                                    let grid = document.createElement('div')
                                    grid.className = 'category-grid'

                                    categories[key].forEach(cat => {
                                        let button = document.createElement('button')
                                        button.textContent = cat.name
                                        button.type = 'button'

                                        button.addEventListener('click', e => {
                                            document.getElementById('category').value = cat.id
                                            categoryButton.textContent = cat.name
                                            categoryDrop.classList.remove('active')
                                        })
                                        grid.appendChild(button)
                                    })
                                    div.appendChild(grid)
                                    categoryDrop.appendChild(div)
                                })
                            })
                        </script>

                        <div class="select-box">
                            <input type="hidden" name="level_id" id="level" required>
                            <button type="button" class="select-button" id="level_button">select level</button>
                            <div class="dropdown" id="level_dropdown">
                                <div class="custom-options-grid" id="levels_grid"></div>
                            </div>
                        </div>
                        <script type="module">
                            import { levels } from './core/views/js/levels.mjs'

                            let levelButtonn = document.getElementById('level_button')
                            let levelDrop = document.getElementById('level_dropdown')
                            let categoryDrop = document.getElementById('category_dropdown')
                            let levelsGrid = document.getElementById('levels_grid')
                            levelsGrid.className = 'levels_grid'

                            levelButtonn.addEventListener('click', e => {
                                e.stopPropagation()
                                if(categoryDrop) categoryDrop.classList.remove('active')
                                levelDrop.classList.toggle('active')
                            })

                            document.addEventListener('click', e => {
                                if (levelDrop.classList.contains('active') && !levelDrop.contains(e.target) && e.target !== levelButtonn) {
                                    levelDrop.classList.remove('active')
                                }
                            })

                            levels.forEach(lvl => {
                                let button = document.createElement('button')
                                button.textContent = lvl.name
                                button.type = 'button'

                                button.addEventListener('click', e => {
                                    document.getElementById('level').value = lvl.id
                                    levelButtonn.textContent = lvl.name
                                    levelDrop.classList.remove('active')
                                })
                                levelsGrid.appendChild(button)
                            })
                        </script>
                    </div>
                        <div class="form-media">
                            <label for="media">
                                <img src="core\views\svgs\clip.svg" width="30px" height="30px" style="cursor:pointer;">
                                <input type="file" id="media" name="media[]" accept="image/*" multiple
                                    style="display: none;">
                            </label>
                        </div>
                        <div class="form-button">
                            <button type="sumbit" id="create-post" class="button_create_answer">Create Post</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="allPosts">

            </div>
            <div id='pages-container'></div>
        </div>
        <template id="post-template">
            <div class="post-container">
                <div class="content-box">
                    <h2 class="post_title"></h2>
                    <p class="post_text"></p>
                </div>

                <div class="sidebar_label">
                
                    <img src="core/views/svgs/options.svg" class="options">
                    <div class="post-menu">
                        <button class="edit-post">Edit</button>
                        <button class="delete-post">Delete</button>
                    </div>
                    <p>Votes:<span class="votes"></span></p>
                </div>

                <div class="post-footer">
                    <div class="views">
                        <span>Views: <span class="views_count"></span></span>
                    </div>
                    <div class="answer">
                        <span>Answers: <span class="answer_count"></span></span>
                    </div>
                    <div class="level">
                        <span>Level: <span class="lvl"></span></span>
                    </div>
                    <div class="category">
                        <span>Category: <span class="category_name"></span></span>
                    </div>
                    
                </div>

                <div class="author-tag">
                    <div class="avatar">
                        <img class="ava">
                    </div>
                    <span class="author-name"></span>
                </div>
            </div>
        </template>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
    <script type="module">
        import { Post } from './core/views/js/modules/Post.mjs'
        import { Pages } from './core/views/js/widgets/Pages.mjs'
        import {SearchBar} from './core/views/js/widgets/SearchBar.mjs'
        import { Filter } from './core/views/js/widgets/Filter.mjs'
        SearchBar.init()
        Post.allInstances = []
        const postTemplate = document.querySelector('#post-template')
        const postInfo = <?php echo json_encode($data['posts'] ?? null);?>;
        Pages.init(postInfo)
        Pages.draw()
        let userData = JSON.parse(<?php echo json_encode($data['userData'] ?? null); ?>)
        if (userData) {
            let isOwner = Boolean(userData.email)
            document.querySelector('.showName').innerText = userData.show_name
            document.querySelector('.Name').innerText = userData.name
            document.querySelector('.bio-text').innerText = userData.bio ? userData.bio : 'Nothing'
            document.querySelector('.ava').src =  userData.avatar ? userData.avatar.path : 'core/views/avatars/avatar1.jpg'
            
            if(isOwner){
                document.querySelector('.email').innerText = userData.email
            }
            else{
                document.querySelector('.email').remove()
                document.querySelector('#edit').remove()
                document.querySelector('#user-actions').remove()
                document.querySelector('.post-form').remove()
                postTemplate.content.querySelector('.post-menu').remove();
                postTemplate.content.querySelector('.options').remove();
                document.querySelector('#email_info').remove()
            }
        }
        postInfo.data.forEach(postData => {
            document.querySelector('.allPosts').appendChild(new Post(JSON.parse(postData), postTemplate).render())
        })

        if(document.querySelector('.post-form'))document.querySelector('.post-form').addEventListener('submit', e => {

            e.preventDefault()
            if (document.getElementById('form-title').value == '' || document.getElementById('form-text').value == '') {
                alert('Title or text can not be empty')
                return
            }
            let formData = new FormData(document.querySelector('.post-form'))
            formData.delete('media[]')
            selectedFiles.forEach(file => {
                formData.append('media[]', file)
            })
            fetch('/create-post', {
                method: 'POST',
                body: formData
            }).then(() => {
                document.querySelector('.post-form').reset()
                document.querySelector('.picture').innerHTML = ''
                selectedFiles = []
                window.location.reload()
            })
        })
        let selectedFiles = []
        Fancybox.bind(`[data-fancybox=gallery-1]`)
        Fancybox.bind(`[data-fancybox=gallery-edit]`)
        function preview() {

            document.querySelector('.picture').innerText = ''
            selectedFiles.forEach((file, index) => {
                let reader = new FileReader()

                reader.onload = () => {
                    let a = document.createElement('a')
                    a.title = 'To delete an image, press the mouse wheel'
                    a.setAttribute('data-fancybox', 'gallery-1')
                    let img = document.createElement('img')
                    img.className = 'preview-pic'
                    img.style.width = '60px'
                    img.style.height = '60px'
                    img.style.objectFit = 'cover'
                    img.style.marginRight = '5px'
                    img.style.borderRadius = '4px'
                    img.style.cursor = 'pointer'
                    document.querySelector('.picture').appendChild(a)
                    a.appendChild(img)
                    
                    img.src = reader.result
                    a.href = reader.result

                }
                reader.readAsDataURL(file)
            })
        }
        if(document.getElementById('media'))document.getElementById('media').addEventListener('change', e => {
            let files = e.target.files
            if (!files) return
            if (selectedFiles.length + files.length > 5) {
                alert('Max count 5 photo')
                e.target.value = ''
                return
            }
            selectedFiles = selectedFiles.concat(Array.from(files))
            preview()
            e.target.value = ''
        })
        if(document.querySelector('.picture'))
        document.querySelector('.picture').addEventListener('mousedown', e => {
            if (e.button == 1) {
                e.preventDefault()
                if (e.target.closest('a')) {
                    selectedFiles.splice(Array.from(document.querySelectorAll('.picture a')).indexOf(e.target.closest('a')), 1)
                    preview()
                }
            }
        })
        let showName = document.querySelector('.showName')
        let bio = document.querySelector('.bio-text')
        let avaImg = document.querySelector('.ava')
        let avatarInput = document.getElementById('avatar-input')
        let EditShowNameInput
        let EditBio
        let isEditing = false
        avaImg.addEventListener('click',e => {
            if (isEditing == true) {
                avatarInput.click()
            }
        })
        avatarInput.addEventListener('change', e => {
            let file = e.target.files[0]
            if (file) {
               let reader = new FileReader()
                reader.onload = () => {
                    avaImg.src = reader.result
                }
                reader.readAsDataURL(file)
            }
        })
        if(document.getElementById('edit'))
            document.getElementById('edit').addEventListener('click', e => {
            if (!isEditing) {
                isEditing = true
                document.getElementById('edit').innerText = 'Save'
                avaImg.style.cursor = 'pointer'
                EditShowNameInput = document.createElement('input')
                EditShowNameInput.type = 'text'
                EditShowNameInput.value = showName.innerText
                EditShowNameInput.maxLength = 40
                

                EditBio = document.createElement('textarea')
                EditBio.maxLength = 100
                EditBio.className = 'edit-bio'
                EditBio.value = bio.innerText == 'Nothing' ? ' ' : bio.innerText


                showName.replaceWith(EditShowNameInput)
                bio.replaceWith(EditBio)

            }
            else {
                let NewShowName = EditShowNameInput.value.trim()
                if (NewShowName.length > 40) {
                    alert('Maxlength name 40 characters')
                    return
                }
                let NewBio = EditBio.value.trim()
               if (NewBio.length > 100) {
                    alert('Maxlength Bio 100 characters')
                    return
                }
                if (NewShowName == '') {
                    alert('YOU ARE STUPID SHOW NAME CAN NOT BE EMPTY')
                    return
                }
                let updateData = new FormData()
                if (avatarInput.files[0]) {
                    updateData.append('avatar', avatarInput.files[0])
                }
                updateData.append('show_name', NewShowName)
                updateData.append('bio', NewBio)

                fetch('/edit-user', {
                    method: 'POST',
                    body: updateData
                })
                    .then(response => {
                        if (response.ok) {
                            window.location.reload()
                        }
                    })
            }
        })
        let editTitlePost
        let editTextAreaPost
        let editingOldImages = []
        let editingNewImages = []
        document.addEventListener('click', e=> {
            
            if(e.target.classList.contains('options')) {
                let post = e.target.closest('.post-container')
                let menu = post.querySelector('.post-menu')
                menu.classList.toggle('active')
            }
            if(e.target.classList.contains('delete-post')){

                e.preventDefault()
                e.stopPropagation()
                if(confirm('ARE YOU SURE???')){
                    let deleteData = new FormData()
                    deleteData.append('postId',e.target.closest('.post-container').dataset.id)

                    fetch('/delete-post',{
                        method:'POST',
                        body:deleteData
                    })
                    .then(resp=>{
                        window.location.reload()
                    })
                }
            }
            
            if(e.target.classList.contains('edit-post')){
                e.preventDefault()
                let buttonPost = e.target
                let PostContainer = e.target.closest('.post-container')
                let titlePost = PostContainer.querySelector('.post_title')
                let textPost = PostContainer.querySelector('.post_text')
                let allPostsElements = document.querySelectorAll('.allPosts .post-container')
                let postIndex = Array.from(allPostsElements).indexOf(PostContainer)
                
                let postInstance = Post.allInstances[postIndex]
                console.table(postInstance.images)
                if(!postInstance.isEdit){
                    postInstance.isEdit = true
                    buttonPost.textContent = 'Save'


                    editTitlePost = document.createElement('input')
                    editTitlePost.type = 'text'
                    editTitlePost.className = 'edit_title_post'
                    editTitlePost.value = postInstance.title

                    editTextAreaPost = document.createElement('textarea')
                    editTextAreaPost.className = 'edit_text_post'
                    editTextAreaPost.value = postInstance.text

                    titlePost.replaceWith(editTitlePost)
                    textPost.replaceWith(editTextAreaPost)

                    editingOldImages = []
                    editingNewImages = []
                        if (postInstance.images) {
                            postInstance.images.forEach((url, index) => {
                                let correctUrl = url.startsWith('/') ? url : '/' + url;
                                editingOldImages.push({ id: index, path: correctUrl });
                            });
                        }
                    let editImage = document.createElement('div')
                    editImage.className = 'edit_image'
                    document.querySelector('.content-box').appendChild(editImage)
                    let renderImage = () =>{
                        editImage.innerHTML = ''
                        editingOldImages.forEach((imgObj,index)=>{
                            let a = document.createElement('a')
                            a.title = 'To delete an image, press the mouse wheel'
                            a.setAttribute('data-fancybox', 'gallery-1')
                            a.href = imgObj.path
                            let img = document.createElement('img')
                            img.className = 'preview-pic'
                            img.style.width = '60px'
                            img.style.height = '60px'
                            img.style.objectFit = 'cover'
                            img.style.marginRight = '5px'
                            img.style.borderRadius = '4px'
                            img.style.cursor = 'pointer'
                            img.src = imgObj.path
                            a.appendChild(img)
                            editImage.appendChild(a)
                            a.addEventListener('mousedown', e => {
                                if (e.button == 1) {
                                    e.preventDefault()
                                    editingOldImages.splice(index, 1) 
                                    renderImage()
                                }
                            })
                        })
                        editingNewImages.forEach((file,index) => {
                            let reader = new FileReader()
                            reader.onload = () => {
                                let a = document.createElement('a')
                                a.title = 'To delete an image, press the mouse wheel'
                                a.setAttribute('data-fancybox', 'gallery-edit')
                                a.href = reader.result
                                let img = document.createElement('img')
                                img.className = 'new-pic'
                                img.style.width = '60px'
                                img.style.height = '60px'
                                img.style.objectFit = 'cover'
                                img.style.marginRight = '5px'
                                img.style.borderRadius = '4px'
                                img.style.cursor = 'pointer'
                                img.src = reader.result
                                a.appendChild(img)
                                
                                document.querySelector('.edit-label').before(a)
                                a.href = reader.result
                                a.addEventListener('mousedown', e => {
                                    if (e.button == 1) {
                                        e.preventDefault()
                                        editingNewImages.splice(index, 1) 
                                        renderImage()
                                    }
                                })
                        
                            }
                            reader.readAsDataURL(file)
                        })
                        let label = document.createElement('label')
                        label.className = 'edit-label'
                        label.setAttribute('for',`edit-media-${PostContainer.dataset.id}`)
                        let img = document.createElement('img')
                        img.style.width = "30px"
                        img.style.height = "30px"
                        img.style.cursor = "pointer"
                        img.src = "core/views/svgs/clip.svg"
                        label.appendChild(img)
                        editImage.appendChild(label)
                        let input = document.createElement('input')
                        input.type = "file"
                        input.id = `edit-media-${PostContainer.dataset.id}`
                        input.name = "media[]"
                        input.accept = "image/*"
                        input.multiple = true
                        input.style.display = "none"

                        input.addEventListener('change',e=>{
                            let files = e.target.files
                            if(!files) return

                            if(editingOldImages.length + editingNewImages.length + files.length > 5){
                                alert('Max count 5 photo')
                                return
                            }
                            editingNewImages = editingNewImages.concat(Array.from(files))
                            renderImage()
                        })
                        editImage.appendChild(input)
                    }
                    renderImage()
                }
                else{
                    let newTitle = editTitlePost.value
                    let newText = editTextAreaPost.value

                    if (newTitle == '' || newText == '') {
                        alert('ARE YOU STUPID')
                        return
                    }
                    let currentImageId = editingOldImages.map(img => img.id)                  
                    let newImagesString = editingOldImages.map(img => {
                        return img.path.split('/')[img.path.split('/').length - 1]
                    }).join(',')
                    let updateDataPost = new FormData()
                    updateDataPost.append('newImages', newImagesString)
                    updateDataPost.append('postId', PostContainer.dataset.id)
                    updateDataPost.append('title', newTitle)
                    updateDataPost.append('text', newText)
                    editingNewImages.forEach(file => {
                        updateDataPost.append('media[]', file)
                    })
                    fetch('/change-post', {
                        method: 'POST',
                        body: updateDataPost
                    })
                    .then(resp=>{
                        if(resp.ok){
                            window.location.reload()
                        }
                    })
                }
                
            }
        })
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
</body>