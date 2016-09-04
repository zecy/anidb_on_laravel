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

    .anime-list__filter-bar {
        display: flex;
        width: 100%;
        margin-top: 20px;
        align-items: center;
    }

    .anime-list__filter {
        flex: 0 0 92%;
    }

    .anime-list__filter-btn {
        text-align: center;
        flex: 0 0 8%;
    }

    .anime-list__page {
        width: 100%;
        margin: 20px;
    }

    .anime-list__states {
        width: 100%;
        text-align: center;
        padding: 10px 0;
        font-size: 1.5em;
    }

</style>
<template id="anime-list">
    <div class="anime-list flex-grid">

        <div class="anime-list__filter-bar">
            <fieldset class="anime-list__filter"
                      v-bind:disabled="loading"
            >
                <animelistfilter
                        :anime_list.sync="animeList"
                        :filters.sync="filters"
                ></animelistfilter>
            </fieldset>

            <div class="anime-list__filter-btn">
                <button type="button"
                        class="btn btn-sm btn-success"
                        v-bind:disabled="loading"
                        v-on:click="someAnime()"
                >
                    过滤
                </button>
            </div>
        </div>


        <div class="anime-list__states">
            共 @{{ allAnimesCount }} 部动画，显示 @{{ animesCount }} 部
        </div>

        <div class="anime-list__page">
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

        <div class="anime-list__page">
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
        data:     function () {
            return {
                'animeList':      [],
                'allAnimesCount': 0,
                'animesCount':    0,
                'listEmpty':      true,
                'filters':        {
                    'startYear':   2016,
                    'startSeason': 1,
                    'endYear':     2016,
                    'endSeason':   10,
                    'lifecycle':   '',
                    'timeslot':    ''
                },
                'useFilter':      false,
                'loading':        true,
                'currentPage':    1,
                'lastPage':       1
            }
        },
        watch: {
            currentPage: function(val) {
                if(!this.useFilter) {
                    this.getAll(val);
                } else {
                    this.someAnime(val);
                }
            }
        },
        methods:  {
            getAll:       function (p) {
                this.loading = true;
                this.useFilter = false;
                const page = p === undefined ? '' : ('?page=' + p);
                this.$http.get('/manager/resource' + page).then(function (res) {
                    if (res.status === 200) {
                        this.animeList      = res.data.data;
                        this.allAnimesCount = res.data.total;
                        this.animesCount    = res.data.total;
                        this.currentPage    = res.data.current_page;
                        this.lastPage       = res.data.last_page;
                        this.listEmpty      = false;
                        this.loading        = false;
                    }
                })
            },
            someAnime: function (p) {
                this.loading = true;
                this.useFilter = true;
                const page = p === undefined ? '' : ('?page=' + p );
                this.$http.post('/manager/resource/filt' + page, {data: this.filters}).then(function (r) {
                    if (r.status == 200) {
                        if(r.data.data.length != 0) {
                            this.animeList      = r.data.data;
                            this.currentPage    = r.data.current_page;
                            this.lastPage       = r.data.last_page;
                            this.allAnimesCount = r.data.total_all;
                            this.animesCount    = r.data.total;
                            this.listEmpty      = false;
                            this.loading        = false;
                        } else {
                            this.listEmpty   = true;
                            this.loading     = false;
                        }
                    }
                });
            }
        }
    });
</script>
