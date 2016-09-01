<style>
    .process-setter {
        width: 100%;
        height: 100%;
        border:1px solid #ccc;
        padding: 2px;
    }

    .ps__box {
        width: 100%;
        height: 100%;
        position: relative;
    }

    .ps__bar-0, .ps__bar-1, .ps__bar-2, .ps__bar-3 {
        position: absolute;
        cursor: pointer;
        top: 0;
        height: 100%;
        left: 0;
    }

    .ps__bar-0 {
        width: 25%;
        z-index: 4;
    }

    .ps__bar--unready {
        background-color: indianred;
    }

    .ps__bar-1 {
        width: 50%;
        z-index: 3;
    }

    .ps__bar--forpublished{
        background-color: darkorange;
    }

    .ps__bar-2 {
        width: 75%;
        z-index: 2;
    }

    .ps__bar--forcounted{
        background-color: yellowgreen;
    }

    .ps__bar-3 {
        width: 100%;
        z-index: 1;
   }

    .ps__bar-0:hover, .ps__bar-1:hover, .ps__bar-2:hover, .ps__bar-3:hover {
        background-color: deepskyblue;
    }

    .ps__bar--complete {
        background-color: mediumseagreen;
    }

</style>
<template id="process-setter">
    <div class="process-setter">
        <div class="ps__box">
            <div class="ps__bar-0"
                 v-bind:class="state === 0 ? 'ps__bar--unready' : ''"
                 v-on:click="setValue(0)"
            ></div>
            <div class="ps__bar-1"
                 v-bind:class="state === 1 ? 'ps__bar--forpublished' : ''"
                 v-bind:style="bool ? 'display:none' : ''"
                 v-on:click="setValue(1)"
            ></div>
            <div class="ps__bar-2"
                 v-bind:class="state === 2 ? 'ps__bar--forcounted' : ''"
                 v-bind:style="bool ? 'display:none' : ''"
                 v-on:click="setValue(2)"
            ></div>
            <div class="ps__bar-3"
                 v-bind:class="state === 3 ? 'ps__bar--complete' : ''"
                 v-on:click="setValue(3)"
            ></div>
        </div>
    </div>
</template>
<script>
    Vue.component('processsetter', {
        template: '#process-setter',
        props: {
            'value': {
                twoWay:  true
            },
            'max':   {
                type:    Number,
                default: 3
            },
            'bool': {
                type: Boolean,
                default: false
            }
        },
        data: function(){
            return {
                state: 0
            }
        },
        computed: {
            state: function(){
                const val = this.value;
                if (this.bool) {
                    return val ? 3 : 0;
                } else {
                   return val
                }
            }
        },
        methods: {
            setValue: function(newVal) {
                if(!this.bool) {
                    this.value = newVal;
                } else {
                    if( newVal === 3 ) {
                        this.value = true
                    } else {
                        this.value = false
                    }
                }
            }
        }
    });
</script>