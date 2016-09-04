/**
 * Created by zecy on 16/8/10.
 */

Vue.config.debug = true;

/**
 * COMPONENTS
 */

Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#token').getAttribute('value');

var App = Vue.extend({});

var router = new VueRouter({
    history: true
});

router.map({
    '/manager': {
        component: anime_list.extend({
            ready: function () {
                this.getAll();
            }
        })
    },
    '/manager/batch-import' : {
        component: batch_import
    },
    '/manager/:date': {
        component: anime_list.extend({
            ready: function() {
                const date = this.$route.params.date;
                let year   = 0;
                let season = 0;
                if(date.length === 7) {
                    year = Number(date.substr(0,4));
                    const strSeason = date.substr(4,7);
                    switch (strSeason) {
                        case 'jan':
                            season = 1;
                            break;
                        case 'apr':
                            season = 4;
                            break;
                        case 'jul':
                            season = 7;
                            break;
                        case 'oct':
                            season = 10;
                            break;
                    }
                } else if (date.length = 4) {
                    year   = Number(date);
                    season = 0;
                } else {
                    year   = 0;
                    season = 0
                }
                this.filters.startYear   = year;
                this.filters.endYear     = year;
                this.filters.startSeason = season;
                this.filters.endSeason   = season;
                this.someAnime();
            }
        })
    },
    '/manager/spring': {
        component: anime_list.extend({
            computed: {
                'after_date': function () {
                    return '2016-04-01'
                }
            }
        })
    },
    '/manager/summer': {
        component: anime_list.extend({
            computed: {
                'after_date': function () {
                    return '2016-07-01'
                }
            }
        })
    }
})

// 现在我们可以启动应用了！
// 路由器会创建一个 App 实例，并且挂载到选择符 #app 匹配的元素上。
router.start(App, '#anime-manager');