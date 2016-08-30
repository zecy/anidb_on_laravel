<style>
    .process-bar__box {
        width: 100%;
        padding: 2px;
        border: 1px solid #ccc;
        background-color: #fff;
    }

    .process-bar__bar {
        height: 1em;
    }

    .process-bar__bar--unready {
        background-color: indianred;
    }

    .process-bar__bar--forpublish {
        background-color: darkorange;
    }

    .process-bar__bar--forcount {
        background-color: yellowgreen;
    }

    .process-bar__bar--complete {
        background-color: mediumseagreen;
    }

    .process-bar__content {
        display: inline-block;
        width: 100%;
        text-align: center;
    }
</style>
<template id="process-bar">
    <div class="process-bar__box">
        <div class="process-bar__bar"
             v-bind:class="'process-bar__bar--' + states"
             v-bind:style="'width: ' + ((value === 0 ? 0.2 : value) / max) * 100 + '%'"
        >
            @{{ show_content ? percentage : '' }}
        </div>
    </div>
</template>
<script>
    Vue.component('processbar', {
        template: '#process-bar',
        props:['value', 'max', 'show_content'],
        data: function(){
            return {
                percentage: '',
                states: ''
            }
        },
        computed: {
            percentage: function() {
                return((this.value/this.max)*100).toString().substr(0,2) + '%';
            },
            states: function() {
                const per = ((this.value/this.max)*100).toString().substr(0,2);

                switch (per) {
                    case '0':
                        return 'unready';
                    case '33':
                        return 'forpublish';
                    case '66':
                        return 'forcount';
                    case '10':
                        return 'complete';
                }

            }
        }
    })
</script>