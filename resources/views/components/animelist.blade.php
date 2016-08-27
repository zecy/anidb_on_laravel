<style>
    .anime-list {
        width: 750px;
        margin: 0 auto;
    }

    .anime-list .flex-cell {
        flex: 0 0 20%
    }

    .anime-list__spinkit {
        width: 100%;
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

</style>
<template id="anime-list">
    <div class="anime-list flex-grid">

        <div style="width: 100%;">
            <paginavigator
                    :last_page="lastPage"
                    :current_page.sync="currentPage"
            ></paginavigator>
        </div>

        <div class="anime-list__spinkit"
             v-if="loading"
        >
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

        <div style="width: 100%;">
            <paginavigator
                    :last_page="lastPage"
                    :current_page.sync="currentPage"
            ></paginavigator>
        </div>
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
                'currentPage': 1,
                'lastPage':    1
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
                this.getAll(val);
            }
        },
        ready:    function () {
            if (!this.useFilter) {
                this.getAll();
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
            getAll:       function (p) {
                this.loading = true;
                const page = p === undefined ? '' : ('?page=' + p);
                this.$http.get('/manager/resource' + page).then(function (res) {
                    if (res.status === 200) {
                        this.animeList   = res.data.animes;
                        this.currentPage = res.data.current_page;
                        this.lastPage    = res.data.last_page;
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
