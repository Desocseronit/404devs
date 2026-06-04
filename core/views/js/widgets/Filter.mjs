export class Filter {
    static filterContainer;
    static side = 0;
    static category = '';
    static level = '';
    static filterby = 'votes';

    static init() {
        this.filterContainer = document.querySelector('#filterContainer');
        if (!this.filterContainer) return;

        this.type = this.filterContainer.dataset.type || 'post';

        this.filterContainer.addEventListener('click', (e) => {
            const button = e.target.closest('button');
            if (!button || button.id === 'filterButton') return;

            const section = button.closest('#filters, #sides, #categories, #levels');
            if (!section) return;


            if (button.classList.contains('selected')) {
                button.classList.remove('selected');
                this.resetValue(button.dataset);
                return;
            }

            const oldSelect = section.querySelector('.selected');
            if (oldSelect) oldSelect.classList.remove('selected');
            button.classList.add('selected');

            this.setValue(button.dataset);
        });

        const filterBtn = this.filterContainer.querySelector('#filterButton');
        if (filterBtn) {
            filterBtn.addEventListener('click', this.filter.bind(this));
        }
    }

    static setValue(dataset) {
        if (dataset.filter) this.filterby = dataset.filter;
        if (dataset.side) this.side = dataset.side;
        if (dataset.category) this.category = dataset.category;
        if (dataset.level) this.level = dataset.level;
    }

    static resetValue(dataset) {
        if (dataset.filter) this.filterby = 'votes'; 
        if (dataset.side) this.side = 0;            
        if (dataset.category) this.category = '';
        if (dataset.level) this.level = '';
    }

    static filter() {
        let url = new URL(window.location.origin + window.location.pathname);

        url.searchParams.set('side', this.side);
        url.searchParams.set('filterby', this.filterby);
        url.searchParams.set('page', '1');

        if (this.category) url.searchParams.set('category', this.category);
        if (this.level) url.searchParams.set('level', this.level);
        
        window.location.href = url.toString();
    }
}
