class Post {
    static allInstances = []
    constructor(data, template) {
        this.id = data.id
        this.title = data.title
        this.text = data.text
        this.votes = data.votes
        this.views = data.views
        this.answer = data.answersCount
        this.category = data.category

        this.level = data.level
        this.userData = JSON.parse(data.user)
        this.template = template

        this.isEdit = false;

        this.isChanged = data.is_changed == 't' 

        if(data.images) this.images = data.images

        Post.allInstances.push(this)
    }

    render() {
        let post = this.template.content.cloneNode(true)
        post.querySelector('.post-container').dataset.id = this.id;
        let isChanged = post.querySelector('.changed-flag')
        
        post.querySelector('.post_title').textContent = this.title
        if(post.querySelector('.post_imgs')){
            post.querySelector('.post_text').textContent = this.text
        }
        else{
            post.querySelector('.post_text').textContent = this.text.length < 50 ? this.text : this.text.substring(0, 50-1) + '...'
        }
        if(this.isChanged){
            let p = document.createElement('p')
            p.className = 'is_changed'
            p.innerText = 'Changed'
            let postFooter = post.querySelector('.post-footer')
            postFooter.appendChild(p)
        }
        post.querySelector('.votes').textContent = this.votes
        post.querySelector('.views_count').textContent = this.views
        post.querySelector('.answer_count').textContent = this.answer
        post.querySelector('.category_name').textContent = this.category
        post.querySelector('.lvl').textContent = this.level
        post.querySelector('.ava').src = this.userData.avatar ? this.userData.avatar.path : 'core/views/avatars/avatar1.jpg' 
        post.querySelector('.author-name').textContent = this.userData.show_name

        if (post.querySelector('.post_imgs') && this.images.length) {
            const galleryId = `gallery-${this.id}`
            this.images.forEach(imgPath => {
                let link = document.createElement('a')
                link.href = imgPath
                link.setAttribute('data-fancybox',galleryId)


                let imgNode = document.createElement('img');
                imgNode.classList.add('post_img')
                imgNode.src = imgPath;
                link.appendChild(imgNode)
                post.querySelector('.post_imgs').appendChild(link);

                Fancybox.bind(`[data-fancybox="${galleryId}"]`);
            });
        }
        else if (!post.querySelector('.post_imgs')){
            post.querySelector('.post-container').addEventListener('click', e => {
                if (this.isEdit) return
                if(
                    e.target.closest('.author-tag') ||
                    e.target.classList.contains('options')||
                    e.target.closest('.post-menu')||
                    e.target.classList.contains('edit_title_post')||
                    e.target.closest('.edit_text_post')
                ){
                    return
                }
                window.location.href = `view-post?id=${this.id}`
            })
        }

        post.querySelector('.author-tag').addEventListener('click', e => {
            window.location.href = `profile?id=${this.userData.id}`
        })
        return post
        
        
    
    }

}
export { Post }