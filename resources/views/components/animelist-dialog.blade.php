<style>
    .anime-list__list {
        display: flex;
        flex-wrap: wrap;
        width: 100%;
    }

    .anime-card {
        flex: 0 0 370px;
        display: flex;
        border: 1px solid #ccc;
        margin: 2.5px;
        word-break: break-all;
    }

    /*<editor-fold desc="anime-card__thumb">*/
    .anime-card__thumb {
        flex: 0 1 20%;
    }

        .anime-card__thumb > a {
            display: block;
            width: 150px;
            height: 200px;
            overflow: hidden;
        }
        .anime-card__thumb > a > img {
            display: block;
            width: 100%;
        }

        /*</editor-fold>*/

    /*<editor-fold desc="anime-card__info">*/
    .anime-card__info {
        flex: 0 1 80%;
        height: 100%;
        padding: 5px 10px;
    }

        .anime-card__info > li {
            display: flex;
            margin-bottom:5px;
        }

        .anime-card__info > li >label {
            line-height: 1.5em;
            flex:0 0 30%;
        }

        .anime-card__info > li > p {
            line-height: 1.5em;
            margin: 0;
            flex: 0 0 70%;
        }

        .anime-card__info > li > hr {
            width: 100%;
            margin: 0;
            border-color: #e1e1e1;
        }

    /*</editor-fold>*/

    .anime-info-status {
        display: flex;
        flex-wrap: wrap;
        width: 100%;
    }

    .anime-info-status > li {
        flex:0 0 50%;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
    }

    .anime-info-status > li > label {
        flex: 0 0 50%;
    }

    .anime-info-status__process-bar {
        flex: 0 0 50%;
        padding: 2px 3px;
    }

</style>
<template id="animelist-dialog">
    <ul class="anime-list__list">
        <li class="anime-card"
            v-for="anime in anime_list"
            track-by="$index"
        >
            <div class="anime-card__thumb">
                <a :href="'/input/' + anime.abbr" target="_blank">
                    <img v-bind:src="'{{ asset('anime-image') }}/' + (anime.has_thumb ? (anime.abbr + '/') : '') + 'thumb.png'">
                </a>
            </div>

            <ul class="anime-card__info">
                <li>
                    <label>原&nbsp;&nbsp;标&nbsp;&nbsp;题:</label>
                    <p>@{{ anime.title_ori }}</p>
                </li>
                <li>
                    <label>通用译名:</label>
                    <p>@{{ anime.title_zh_cn }}</p>
                </li>
                <li>
                    <label>播出时间:</label>
                    <p>
                        @{{{ showOATime(anime) }}}
                    </p>
                </li>
                <li>
                    <label>官方网站:</label>
                    <p><a v-bind:href="anime.hp" target="_blank">@{{ anime.hp }}</a></p>
                </li>
                <li><hr></li>
                <li>
                    <label>出品周期:</label>
                    <p>@{{ showLifecycle(anime.lifecycle) }}</p>
                </li>
                <li>
                    <ul class="anime-info-status">
                        <li>
                            <label>原作类型</label>
                            <div class="anime-info-status__process-bar">
                                <processbar
                                        :value="anime.s_ori_works"
                                        :max="3"
                                ></processbar>
                            </div>
                        </li>
                        <li>
                            <label>时间规格</label>
                            <div class="anime-info-status__process-bar">
                                <processbar
                                        :value="anime.s_time_format"
                                        :max="3"
                                ></processbar>
                            </div>
                        </li>
                        <li>
                            <label>集&#x3000;&#x3000;数</label>
                            <div class="anime-info-status__process-bar">
                                <processbar
                                        :value="anime.s_eps"
                                        :max="3"
                                ></processbar>
                            </div>
                        </li>
                        <li>
                            <label>简&#x3000;&#x3000;介</label>
                            <div class="anime-info-status__process-bar">
                                <processbar
                                        :value="anime.has_description ? 3 : 0"
                                        :max="3"
                                ></processbar>
                            </div>
                        </li>
                        <li>
                            <label>S&#x2006;T&#x2006;A&#x2006;F&#x2006;F</label>
                            <div class="anime-info-status__process-bar">
                                <processbar
                                        :value="anime.s_staff"
                                        :max="3"
                                ></processbar>
                            </div>
                        </li>
                        <li>
                            <label>&ensp;C&ensp;A&ensp;S&ensp;T</label>
                            <div class="anime-info-status__process-bar">
                                <processbar
                                        :value="anime.s_cv"
                                        :max="3"
                                ></processbar>
                            </div>
                        </li>
                        <li>
                            <label>POSTER</label>
                            <div class="anime-info-status__process-bar">
                                <processbar
                                        :value="anime.has_poster ? 3 : 0"
                                        :max="3"
                                ></processbar>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </li>
    </ul>
</template>
<script>
    Vue.component('animelistdialog',{
        template: '#animelist-dialog',
        props: ['anime_list'],
        methods: {
            showOATime: function(item) {

                const date   = item.oa_date === null ? false : item.oa_date;
                const year   = item.oa_year === '' ? false : item.oa_year;
                const season = item.oa_season === '' ? false : item.oa_season;

                const time     = item.oa_time === null ? false : item.oa_time;
                const timeslot = item.oa_timeslot === null ? false : item.oa_timeslot;

                let res     = '';
                let resDate = '';
                let resTime = '';

                if (!date && !year) {
                    resDate = '日期未定';
                    return resDate;
                } else if (date) {
                    // 2016-01-01
                    const d = date.split('-');
                    resDate = d[0] + '年' + (d[1][0] === '0' ? d[1][1] : d[1]) + '月' + (d[2][0] === '0' ? d[2][1] : d[2]) + '日';
                } else if (!date && year) {
                    // 2016年
                    resDate = year + '年';
                    if (season) {
                        // 2016年秋
                        resDate += this.showSeason(season);
                    }
                }

                if (!time && !timeslot) {
                    resTime = '时间未定'
                } else if (time) {
                    // 12:30
                    resTime =  time.substr(0,5);
                } else if (!time && timeslot) {
                    // 深夜档
                    resTime = this.showTimeslot(timeslot)
                }

                res = resDate + '&emsp;' + resTime;
                return res;
            },
            showSeason: function(season) {
                return {
                    1: '冬',
                    4: '春',
                    7: '夏',
                    10: '秋'
                }[season]
            },
            showTimeslot: function(timeslot) {
                return {
                    'morning': '晨间档',
                    'daytime': '日间档',
                    'prime': '黄金档',
                    'midnight': '深夜档'
                }[timeslot]
            },
            showLifecycle: function (lifecycle) {
                return {
                    'planning': '动画化策划中',
                    'decided':  '动画化决定',
                    'comming':  '即将播出',
                    'airing':   '播放中',
                    'ended':    '已完结'
                }[lifecycle]
            }
        }
    })
</script>