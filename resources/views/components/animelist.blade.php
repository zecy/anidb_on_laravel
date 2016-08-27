<style>
    .anime-list {
        width: 750px;
        margin: 0 auto;
    }

    .anime-list .flex-cell {
        flex: 0 0 20%
    }

    .anime-info {
        height: 300px;
        padding: 5px;
    }

    .anime-img {
        display: block;
        width: 100%;
        height: 200px;
        background-color: #ccc;
    }

    .anime-img img {
        width: 100%;
    }

    .paginavitor {
        width: auto;
        margin: auto;
        text-align: center;
    }

    .paginavitor__page {
        text-align: center;
        padding:0 5px;
    }

    .paginavitor__page__btn {
        font-size: 1.4rem;
        padding: 0 0.5em;
    }

    .paginavitor__page.flex-cell {
        flex-shrink: 1;
        flex-basis: auto;
    }
</style>
<template id="anime-list">
    <div class="anime-list flex-grid">

        <div v-if="loading">
            <spinkit></spinkit>
        </div>

        <div v-if="!loading"
             class="anime-info flex-cell"
             v-for="anime in animeList"
             track-by="$index"
        >
            <a class="anime-img" :href="'/input/' + anime.abbr" about="_blank">
                <img :src="'{{ asset('anime-image') }}/' + anime.abbr + '/thumb.png'">
            </a>

            <p class="title">@{{ anime.title_ori }}</p>

            <p class="title">@{{ anime.title_zh_cn }}</p>
        </div>

        <ul class="paginavitor flex-grid">
            <li class="paginavitor__page flex-cell"
            >
                <button class="paginavitor__page__btn btn btn-xs btn-default"
                        v-on:click="currentPage = 1"
                        v-bind:disabled="currentPage < 4"
                >
                    <span class="glyphicon glyphicon-step-backward"></span>
                    1
                    </button>
            </li>
            <li class="paginavitor__page flex-cell">
                <button class="paginavitor__page__btn btn btn-xs btn-default"
                        v-on:click="currentPage -= 1"
                        v-bind:disabled="currentPage === 1"
                >
                    <span class="glyphicon glyphicon-triangle-left"></span>
                </button>
            </li>
            <li class="paginavitor__page flex-cell"
                v-for="page in pages"
                v-on:click="currentPage = page"
            >
                <button class="btn btn-xs paginavitor__page__btn"
                        v-bind:class="page === currentPage ? 'btn-primary' : 'btn-default'"
                >
                    @{{ page }}
                </button>
            </li>
            <li class="paginavitor__page flex-cell">
                <button class="paginavitor__page__btn btn btn-xs btn-default"
                        v-on:click="currentPage += 1"
                        v-bind:disabled="currentPage === lastPage"
                >
                    <span class="glyphicon glyphicon-triangle-right"></span>
                </button>
            </li>
            <li class="paginavitor__page flex-cell"
            >
                <button class="paginavitor__page__btn btn btn-xs btn-default"
                        v-on:click="currentPage = lastPage"
                        v-bind:disabled="currentPage > 8"
                >
                    @{{ lastPage }}
                    <span class="glyphicon glyphicon-step-forward"></span>
                </button>
            </li>
        </ul>
    </div>
</template>
<script>
    var anime_list = Vue.extend({
        template: '#anime-list',
        props:    {
            'after_date':  {
                // 统计晚于这个日期播出的作品(含)
                // 格式: yyyy-mm-dd
                type:    String,
                default: ''
            },  // 统计晚于这个日期播出的作品(含)
            'before_date': {
                // 统计早这个日期播出的作品(含)
                // 格式: yyyy-mm-dd
                type:    String,
                default: ''
            },  // 统计早这个日期播出的作品(含)
            'time':        {
                // 作品播出的时段
                // 分 all, daily, morning, prime, midnight
                type:    String,
                default: 'all'
            }, // 作品播出的时段
            'is_end':      {
                // 统计的作品是否已经结束
                // true: 1, false: -1, all: 0
                type:    Number,
                default: 0
            }, // 统计的作品是否已经结束
            'is_complete': {
                // 作品信息是否已经录入完全
                // true: 1, false: -1, all: 0
                type:    Number,
                default: 0
            } // 作品信息是否已经录入完全
        },
        data:     function () {
            return {
                'animeList':   [],
                'useFilter':   false,
                'loading':     true,
                'pages':       [1],
                'currentPage': 0,
                'lastPage':  1
            }
        },
        computed: {
            useFilter: function () {
                if (this.after_date === '' &&
                        this.before_date === '' &&
                        this.time === 'all' &&
                        this.is_end === 0 &&
                        this.is_complete === 0
                ) {
                    return false
                } else {
                    return true
                }
            }
        },
        watch: {
            currentPage: function(val) {
                const curPage = val;
                const lastPage = this.lastPage;
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
                this.pages = pages;
            }
        },
        ready:    function () {
            if (!this.useFilter) {
                this.all();
            } else {
                const afterDate   = this.after_date;
                const beforeDate  = this.before_date;
                const time        = this.time;
                const isEnd       = this.is_end;
                const isCompelete = this.is_complete;
                this.animeList    = this.someAnime(afterDate, beforeDate, time, isEnd, isCompelete);
            }
        },
        methods:  {
            all:       function () {
                this.$http.get('/manager/resource').then(function (res) {
                    if (res.status === 200) {
                        this.animeList   = res.data.animes;
                        this.currentPage = res.data.current_page;
                        this.lastPage  = res.data.last_page;
                        this.loading     = false;
                    }
                })
            },
            someAnime: function (ad, bd, ti, ie, ic) {
                const conditions = {
                    'after_date':  ad,
                    'before_date': bd,
                    'time':        ti,
                    'is_end':      ie,
                    'is_complete': ic
                };
                console.log(conditions);
                /*                this.$http.post('/manager/resource/filt?=' + conditions).then(function (res) {
                 if (res.status === 200) {
                 return res.data;
                 }
                 });*/
            }
        }
    });
</script>
