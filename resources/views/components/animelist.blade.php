<style>
    .anime-list {
        width: 750px;
        margin: 0 auto;
    }

    .anime-list__spinkit {
        width: 100%;
    }

    .anime-img img {
        width: 100%;
    }
    
    .no-anime-waring {
        width: 100%;
        background: rgba(205, 92, 92, .05);
        margin: 30px auto;
        text-align: center;
        border-radius: 6px;
        padding: 20px;
        border: 3px solid indianred;
        color: indianred;
        font-size: 24px;
    }

</style>
<template id="anime-list">
    <div class="anime-list flex-grid">

        <animelistfilter
                :anime_list.sync="animeList"
                :filters.sync="filters"
                :loading="loading"
                :use_filter.sync="useFilter"
        ></animelistfilter>

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

        <div v-if="!loading" style="width: 100%">
            <animelistdialog
                    v-if="!listEmpty"
                    :anime_list="animeList"
            ></animelistdialog>

            <div v-if="listEmpty"
                 class="no-anime-waring"
            >
                没有相关动画
            </div>
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
                'listEmpty':   true,
                'filters':     {
                    'startYear':   2016,
                    'startSeason': 1,
                    'endYear':     2016,
                    'endSeason':   10,
                    'lifecycle':   'ended',
                    'timeslot':    'midnight'
                },
                'useFilter':   false,
                'loading':     true,
                'currentPage': 1,
                'lastPage':    1
            }
        },
        watch: {
            currentPage: function(val) {
                if(!this.useFilter) {
                    this.getAll(val);
                } else {
                    this.someAnime(val);
                }
            },
            useFilter: function(bool) {
                if(bool) {
                    this.someAnime();
                }
            }
        },
        ready:    function () {
            this.getAll();
        },
        methods:  {
            getAll:       function (p) {
                this.loading = true;
                const page = p === undefined ? '' : ('?page=' + p);
                this.$http.get('/manager/resource' + page).then(function (res) {
                    if (res.status === 200) {
                        this.animeList   = res.data.data;
                        this.currentPage = res.data.current_page;
                        this.lastPage    = res.data.last_page;
                        this.listEmpty   = false;
                        this.loading     = false;
                    }
                })
            },
            someAnime: function (p) {
                this.loading = true;
                const page = p === undefined ? '' : ('?page=' + p );
                this.$http.post('manager/resource/filt' + page, {data: this.filters}).then(function (r) {
                    if (r.status == 200) {
                        if(r.data.data.length != 0) {
                            this.animeList   = r.data.data;
                            this.currentPage = r.data.current_page;
                            this.lastPage    = r.data.last_page;
                            this.listEmpty   = false;
                            this.loading     = false;
                        } else {
                            this.listEmpty   = true;
                            this.loading     = false;
                            this.useFilter   = false;
                        }
                    }
                });
            }
        }
    });
</script>
