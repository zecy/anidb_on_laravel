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
        component: anime_list
    },
    '/manager/batch-import' : {
        component: batch_import
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
router.start(App, '#anime-manager')