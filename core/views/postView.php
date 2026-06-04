<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404devs</title>
    <link rel="stylesheet" href="core/views/css/postView.css">
    <link href='https://fonts.googleapis.com/css?family=Inria Sans' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css">
    <style>


    </style>
</head>
<body>
    <main>
        <div class="search">
            <input type="search" id='search-bar' data-type = 'answer'>
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
        <div class="Post" id="Post"></div>
        <div class="create-answer">
            <form class="answer-form">
                <div class="text-section">
                    <textarea id="form-text" name="text" placeholder="text"></textarea>
                </div>
                <div class="form-footer">
                    <div class="picture">
                        
                    </div>
                    <div class="form-media">
                        <label for="media">
                            <img src="core\views\svgs\clip.svg" width="30px" height="30px" style="cursor:pointer;" >                        
                            <input type="file" id="media" name="media[]" accept="image/*" multiple style="display: none;">
                        </label>                   
                    </div>
                    <div class="form-button">
                        <button type="submit" id="create-answer" class="button_create_answer">Create Answer</button>
                    </div>
                </div>
            </form>
        </div>
        <div id="answers-container"></div>
        <div id='pages-container'> </div>
    </main>

    <template id="post-template">
        <div class="post-container">
            <div class="content-box">
                
                <h2 class="post_title"></h2>
                <div class="post_imgs">

                </div>
                <p class="post_text"></p>
            </div>

            <div class="sidebar_label">
                
                <div>
                    <button class="vote-btn">△</button>
                </div>
                <span class="votes"></span>
                <div>
                    <button class="vote-btn">▽</button>
                </div>
            </div>

            <div class="post-footer">
                <div class="views">
                    <p>Views: <span class="views_count"></span></p>
                </div>
                <div class="answer">
                    <p>Answers: <span class="answer_count"></span></p>
                </div>
                <div class="level">
                    <p>Level: <span class="lvl"></span></p>
                </div>
                <div class="category">
                    <p>Category: <span class="category_name"></span></p>
                </div>
                
            </div>
            
            <div class="author-tag">
                <div class="avatar">
                    <img class="ava">
                </div>
                <div class="author">
                    <p class="author-name"></p>
                </div>
            </div>
            <div class="answer-block"></div>
        </div>
    </template>
    <template id="answer-template">
    <div class="answer-card">
        <div class="content-box">
            <p class="answer-text"></p>
            <div class="answer-images">
            </div>
        </div>
        <div class="sidebar_label">
            <img src="core/views/svgs/options.svg" class="options">
            <div class="answer-menu">
                <button class="edit-answer">Edit</button>
                <button class="delete-answer">Delete</button>
            </div>
            <div>
                <button class="vote-btn">△</button>
            </div>
            <span class="answer-votes">0</span>
            <div>
                <button class="vote-btn">▽</button>
            </div>
        </div>
        <div class="answer-footer">
            <div class="answer-date-box">
                <p class="answer-date"></p>
            </div>
        </div>

        <div class="author-tag">
            <div class="avatar">
                <img class="ava">
            </div>
            <div class="author">
                <span class="answer-author"></span>
            </div>
        </div>
    </div>
</template>



</body>
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
<script type="module">
    import { Post } from './core/views/js/modules/Post.mjs'
    import { Answer } from './core/views/js/modules/Answer.mjs'
    import {SearchBar} from './core/views/js/widgets/SearchBar.mjs'
    import { Pages } from './core/views/js/widgets/Pages.mjs'
    import { Filter } from './core/views/js/widgets/Filter.mjs'
    SearchBar.init()
    Post.allInstances = []
    const postTemplate = document.querySelector('#post-template')
    const answerTemplate = document.querySelector('#answer-template')
    const pageInfo = <?php echo json_encode($data ?? null);?>;
    window.currentId = <?php echo $user ? $user['id']: 'null';?>;
    if(!currentId){
        answerTemplate.content.querySelector('.options').remove()
    }
    const postInfo = JSON.parse(pageInfo.parentPost)
    let post = new Post(postInfo , postTemplate).render()
    Pages.init(pageInfo)
    Pages.draw()
    document.querySelector('.Post').appendChild(post)

    pageInfo.data.forEach(answerData => {
        console.log(JSON.parse(answerData))
        document.querySelector('#answers-container').appendChild(new Answer(JSON.parse(answerData) , answerTemplate).render())
    })
    
    document.getElementById('form-text').addEventListener('input', e=>{
        e.target.style.height = 'auto'

        e.target.style.height = e.target.scrollHeight + 'px'
    })
    document.querySelector('.answer-form').addEventListener('submit' , e => {
        e.preventDefault()
        e.stopImmediatePropagation()
        if(document.getElementById('form-text').value.trim() == ''){
            alert('Text must be one letter')
            return
        }
        if (selectedFiles.length > 5) {
            alert('There should be no more than 5 photos')
            return
        }

        let formData = new FormData(document.querySelector('.answer-form'))
    
        selectedFiles.forEach(file => {
            formData.append('media[]', file)
        })
        

        formData.append('postId' , Post.allInstances[0].id )
        fetch('/add-answer' , {
            method: 'POST',
            body: formData
        }).then(
            resp => {
                document.querySelector('.answer-form').reset()
                document.querySelector('.picture').innerHTML = ''
                selectedFiles = []
                if(resp.status == 201) window.location.href = '/view-post?id='+Post.allInstances[0].id
            }
        )
    })
    let selectedFiles = []
    Fancybox.bind(`[data-fancybox=gallery-1]`)
    Fancybox.bind(`[data-fancybox=gallery-edit]`)
function preview(){        
    
    document.querySelector('.picture').innerText = ''
    selectedFiles.forEach((file, index) => {
        let reader = new FileReader()

        reader.onload = () =>{
            let a = document.createElement('a')
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
document.getElementById('media').addEventListener('change', e => {
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
document.querySelector('.picture').addEventListener('mousedown', e => {
    if (e.button == 1) {  
        e.preventDefault()
        if (e.target.closest('a')) {
            selectedFiles.splice(Array.from(document.querySelectorAll('.picture a')).indexOf(e.target.closest('a')), 1)
            preview()
        }
    }
})

document.querySelector('.Post').addEventListener('click', e=> {
    let button = e.target.closest('button.vote-btn')
    if(!button) return
    let postId = Post.allInstances[0].id
    let votesElement = document.querySelector('.votes')
    
    let vote
    
    if( button.textContent === '△'){
        vote = 1
    }
    else{
        vote = -1
    }

    let formData = new FormData()
    formData.append('id', postId)
    formData.append('vote', vote)

    fetch('/vote-post', {
        method: 'POST',
        body: formData
    })
    .then(resp =>{
        if(resp.ok){
            let votes = parseInt(votesElement.textContent)
            votesElement.textContent = votes + vote
        }
    })
})
document.getElementById('answers-container').addEventListener('click', e=> {
    let button = e.target.closest('button.vote-btn')
    if(!button) return
    let card = e.target.closest('.answer-card')
    if(!card){
        
    }
    let votesElement = card.querySelector('.answer-votes')
    
    let vote
    
    if(button.textContent === '△'){
        vote = 1
    }
    else{
        vote = -1
    }

    let formData = new FormData()
    formData.append('id', card.dataset.id)
    formData.append('vote', vote)

    fetch('/vote-answer', {
        method: 'POST',
        body: formData
    })
    .then(resp =>{
        if(resp.ok){
            let votes = parseInt(votesElement.textContent) || 0
            votesElement.textContent = votes + vote
        }
    })
})
    Filter.init()

    document.querySelector('.filter-icon').addEventListener('click', e=>{
        document.getElementById('filterContainer').classList.toggle('active')
    })
    document.addEventListener('click', e=> {
    if (document.getElementById('filterContainer').classList.contains('active') && 
        !document.getElementById('filterContainer').contains(e.target) && 
        e.target !== document.querySelector('.filter-icon')) {
        document.getElementById('filterContainer').classList.remove('active')
    }
})

        let editTitleAnswer
        let editTextAreaAnswer
        let editingOldImages = []
        let editingNewImages = []
        document.addEventListener('click', e=> {
            if (document.querySelector('.answer-menu.active')) {
                if (!document.querySelector('.answer-menu.active').contains(e.target) && !e.target.classList.contains('options')) {
                    document.querySelector('.answer-menu.active').classList.remove('active')
                }
            }
            if(e.target.classList.contains('options')) {
                let answerCard = e.target.closest('.answer-card')
                if (answerCard) {
                    let menu = answerCard.querySelector('.answer-menu')
                    if (menu) {
                        menu.classList.toggle('active')
                    }
                }
            }
            if(e.target.classList.contains('delete-answer')){

                e.preventDefault()
                e.stopPropagation()
                let answerCard = e.target.closest('.answer-card')
                let answerInstance = answerCard.answerInstance
                if(confirm('ARE YOU SURE???')){
                    let deleteData = new FormData()
                    deleteData.append('answerId',answerInstance.id)

                    fetch('/delete-answer',{
                        method:'POST',
                        body:deleteData
                    })
                    .then(resp=>{
                        window.location.reload()
                    })
                }
            }
            
            if(e.target.classList.contains('edit-answer')){
                e.preventDefault()
                let buttonAnswer = e.target
                let answerCard = e.target.closest('.answer-card')
                let textAnswer = answerCard.querySelector('.answer-text')
                let contentBox = answerCard.querySelector('.content-box')
                let answerInstance = answerCard.answerInstance
                if(!answerInstance.isEdit){
                    answerInstance.isEdit = true
                    buttonAnswer.textContent = 'Save'
                    let currentImages = answerCard.querySelector('.answer-images')
                        if (currentImages) {
                            currentImages.style.display = 'none'
                        }
                    editTextAreaAnswer = document.createElement('textarea')
                    editTextAreaAnswer.className = 'edit_text_answer'
                    editTextAreaAnswer.value = textAnswer.textContent
                    textAnswer.replaceWith(editTextAreaAnswer)

                    editingOldImages = []
                    editingNewImages = []
                    if(answerInstance.images){
                        answerInstance.images.forEach((url, index) => {
                            let correctUrl = url.startsWith('/') ? url : '/' + url
                            editingOldImages.push({ id: index, path: correctUrl })
                        })
                    }
                    let editImage = document.createElement('div')
                    editImage.className = 'edit_image'
                    contentBox.appendChild(editImage)
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
                        label.setAttribute('for',`edit-media-${answerInstance.id}`)
                        let img = document.createElement('img')
                        img.style.width = "30px"
                        img.style.height = "30px"
                        img.style.cursor = "pointer"
                        img.src = "core/views/svgs/clip.svg"
                        label.appendChild(img)
                        editImage.appendChild(label)
                        let input = document.createElement('input')
                        input.type = "file"
                        input.id = `edit-media-${answerInstance.id}`
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
                    let newText = editTextAreaAnswer.value

                    if (newText == '') {
                        alert('ARE YOU STUPID')
                        return
                    }
                    let currentImageId = editingOldImages.map(img => img.id)                  
                    let newImagesString = editingOldImages.map(img => {
                        return img.path.split('/')[img.path.split('/').length - 1]
                    }).join(',')
                    let updateDataPost = new FormData()
                    updateDataPost.append('newImages', newImagesString)
                    updateDataPost.append('answerId', answerInstance.id)
                    updateDataPost.append('text', newText)
                    editingNewImages.forEach(file => {
                        updateDataPost.append('media[]', file)
                    })
                    fetch('/change-answer', {
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
</script>
