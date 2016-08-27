<style>
    .paginavigator {
        width: auto;
        margin: auto;
        text-align: center;
    }

    .paginavigator.flex-grid {
        align-items: center;
        justify-content: center;
    }

    .paginavigator__page {
        text-align: center;
        padding:0 5px;
    }

    .paginavigator__page__btn {
        font-size: 1.4rem;
        padding: 0 0.5em;
    }

    .paginavigator__page.flex-cell {
        flex-shrink: 1;
        flex-basis: auto;
    }
</style>

<template id="paginavigator">
    <ul class="paginavigator flex-grid">
        <li class="paginavigator__page flex-cell">
            <button class="paginavigator__page__btn btn btn-xs btn-default"
                    v-on:click="current_page = 1"
                    v-bind:disabled="current_page < 4"
            >
                <span class="glyphicon glyphicon-step-backward"></span>
                1
            </button>
        </li>
        <li class="paginavigator__page flex-cell">
            <button class="paginavigator__page__btn btn btn-xs btn-default"
                    v-on:click="current_page -= 1"
                    v-bind:disabled="current_page === 1"
            >
                <span class="glyphicon glyphicon-triangle-left"></span>
            </button>
        </li>
        <li class="paginavigator__page flex-cell"
            v-for="page in pages"
            v-on:click="current_page = page"
        >
            <button class="btn btn-xs paginavigator__page__btn"
                    v-bind:class="page === current_page ? 'btn-primary' : 'btn-default'"
                    v-on:click="current_page = page"
            >
                @{{ page }}
            </button>
        </li>
        <li class="paginavigator__page flex-cell">
            <button class="paginavigator__page__btn btn btn-xs btn-default"
                    v-on:click="current_page += 1"
                    v-bind:disabled="current_page === last_page"
            >
                <span class="glyphicon glyphicon-triangle-right"></span>
            </button>
        </li>
        <li class="paginavigator__page flex-cell"
        >
            <button class="paginavigator__page__btn btn btn-xs btn-default"
                    v-on:click="current_page = last_page"
                    v-bind:disabled="current_page > 8"
            >
                @{{ last_page }}
                <span class="glyphicon glyphicon-step-forward"></span>
            </button>
        </li>
    </ul>
</template>

<script>
    Vue.component('paginavigator', {
        template: '#paginavigator',
        props:['current_page', 'last_page'],
        data: function() {
          return {
              pages : [1]
          }
        },
        watch: {
            current_page: function(val) {
                this.pages = this.setPage(val, this.last_page);
            },
            last_page: function(val) {
                this.pages = this.setPage(1, val);
            }
        },
        methods: {
            setPage: function(curP, lastP) {
                const curPage = curP;
                const lastPage = lastP;
                let pages = [];

                if(lastPage > 5 && curPage <= 2 ) {
                    for(let i = 1; i <= 5; i++ ) {
                        pages.push(i);
                    }
                } else if ( lastPage > 5 && 2 < curPage && curPage < ( lastPage -2  ) ) {
                    pages[0] = curPage - 2;
                    pages[1] = curPage - 1;
                    pages[2] = curPage;
                    pages[3] = curPage + 1;
                    pages[4] = curPage + 2
                } else if ( lastPage > 5 && curPage > ( lastPage - 3 ) ) {
                    pages[0] = lastPage - 4;
                    pages[1] = lastPage - 3;
                    pages[2] = lastPage - 2;
                    pages[3] = lastPage - 1;
                    pages[4] = lastPage
                } else {
                    for(let i = 1; i <= lastPage; i++ ) {
                        pages.push(i);
                    }
                }
                return pages
            }
        }
    })
</script>