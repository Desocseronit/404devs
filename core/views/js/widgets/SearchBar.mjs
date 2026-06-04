import {Post} from '../modules/Post.mjs'
export class SearchBar {
    static type; //post / answer / user
    static searchBar;

    static init() {
        this.searchBar = document.querySelector('#search-bar')
        this.searchBar.placeholder = 'Search something...'
        this.type = this.searchBar.dataset.type

        document.addEventListener('keydown', e => {
            if (e.key == 'Enter' && document.querySelector('input:focus') == this.searchBar) {
                this.search()
            }
        })
    }

    static search() {
        const params = this.getParams();
        let basePath = '/all-posts';

        if (this.type === 'answer') {
            basePath = '/view-post';
        } else if (this.type === 'user') {
            basePath = '/profile';
        }

        const url = new URL(basePath, window.location.origin);

        if (this.type === 'answer' && Post.allInstances[0]) {
            url.searchParams.set('id', Post.allInstances[0].id);
        }

        Object.entries(params).forEach(([key, val]) => {
            if (val !== undefined && val !== null && val !== '') {
                url.searchParams.set(key, val);
            }
        });

        window.location.href = url.pathname + url.search;
    }

    static getParams() {
        let rawParams = this.searchBar.value
        let result = {title: ''};

        let parts = rawParams.split(' ');
        parts.forEach(part => {
            if (part.includes('::')) {
                let [key, val] = part.split('::');
                if (key && val) {
                    result[key] = val;
                } else {
                    result.title += val;
                }
            }
            else {
                result.title += (result.title ? ' ' : '') + part;
            }
        });
        return result
    }
}
