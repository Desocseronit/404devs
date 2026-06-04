export class Pages{
    static containerNode;
    static currPage = 1;
    static endPage = 1;
    static nextPage = null;
    static prevPage = null;
    
    static init(pageInfo){
        this.containerNode = document.querySelector('#pages-container');
        this.currPage = pageInfo.current_page ? Number(pageInfo.current_page) : null;
        this.endPage = Number(pageInfo.total_pages);
        this.nextPage = pageInfo.has_next ? this.currPage + 1 : null;
        this.prevPage = pageInfo.has_prev ? this.currPage - 1 : null;
    }

    static draw(){
        let pages = [this.currPage != 0 && this.currPage != 1 ? 1 : null , this.prevPage != 1 ? this.prevPage : null , this.currPage  , this.nextPage != this.currPage ? this.nextPage : this.currPage, this.endPage && this.endPage != this.currPage && this.endPage != this.nextPage ? this.endPage : null];
        pages.forEach((page , index , arr) => {
            if(page != null){
                let url = new URL(window.location.href)
                url.searchParams.set('page', page);
                let a = document.createElement('a');
                a.href = url.pathname + url.search; 
                a.textContent = page
                this.containerNode.appendChild(a);
                if(index == 0 || index == 3){
                    if( arr[index+1] && page - arr[index+1] <= -2){
                        let span = document.createElement('span')
                        span.textContent = '...'
                        this.containerNode.appendChild(span)
                    }
                }
            }
        })
    }
}