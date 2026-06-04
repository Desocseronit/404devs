class Answer {
    static allInstances = []
    constructor(data, template) {
        this.id = data.id
        this.text = data.text
        this.votes = data.votes
        this.created_at = data.created_at
        this.images = data.images
        this.userData = JSON.parse(data.user)
        this.template = template
        Answer.allInstances.push(this)
    }
    render() {
        let answer = this.template.content.cloneNode(true)
        let card = answer.querySelector('.answer-card')
        if(answer.querySelector('.options') && window.currentId != this.userData.id){
          answer.querySelector('.options').remove() 
          answer.querySelector('.answer-menu').remove() 
        }
        card.dataset.id = this.id
        card.answerInstance = this
        answer.querySelector('.answer-author').textContent = this.userData.show_name
        answer.querySelector('.answer-date').textContent = this.created_at
        answer.querySelector('.answer-text').textContent = this.text
        answer.querySelector('.answer-votes').textContent = this.votes
        // console.log(this.userData)
        if(this.userData.avatar) answer.querySelector('.ava').src = this.userData.avatar.path
        else answer.querySelector('.ava').src = "core/views/avatars/avatar1.jpg"
        
        if(this.images && this.images.length){
            const galleryId = `gallery-${this.id}`
            let image = answer.querySelector('.answer-images')
            image.innerText = ''
            this.images.forEach(imgPath => {
                let link = document.createElement('a')
                link.href = imgPath
                link.setAttribute('data-fancybox', galleryId)

                let img = document.createElement('img')
                img.classList.add('answer_img')
                img.src = imgPath
                
                link.appendChild(img)
                answer.querySelector('.answer-images').appendChild(link)
                Fancybox.bind(`[data-fancybox="${galleryId}"]`)
            })
        }
        else {
            answer.querySelector('.answer-images').style.display = 'none'
        }
        answer.querySelector('.author-tag').addEventListener('click', e => {
            window.location.href = `profile?id=${this.userData.id}`
        })
        return answer
    }
}

export {Answer}