/**
 * Created by zecy on 16/8/10.
 */

/**
 * COMPONENTS
 */




Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#token').getAttribute('value');

var vue = new Vue ({
   el: '#anime-manager',
   data: {
      'animeList': []
   },
   ready() {
       this.getAll();
   },
    methods: {
        getAll() {
        }
    }
});
