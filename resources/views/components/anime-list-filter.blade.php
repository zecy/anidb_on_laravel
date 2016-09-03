<style>
    .anime-list-filter {
        width: 100%;
        margin: 0 auto;
        display: flex;
        padding: 5px;
        border: 1px solid #aaa;
        border-radius: 3px;
        background-color: #fefefe;
    }
    
    .anime-list-filter button {
        float: none;
    }
    
    .alf__item {
        display: flex;
        align-items: center;
        flex-shrink: 0;
        margin-right: 10px;
    }

    .alf__item > label {
        font-size: 1.2em;
        padding: 0 5px;
        margin-right:5px;
        flex-shrink: 0;
    }

    .alf__item > span {
        margin: 0 5px;
    }

    .alf__datetime {
        width: 350px;
        flex-grow: 0;
        flex-shrink: 0;
    }

        .alf__year {
            flex-basis: 60px;
            margin-right: 10px;
        }

        .alf__year > input {
            text-align: right;
            padding-right: 1em;
        }

        .alf__season {
            flex-basis: 60px;
        }

    .alf__timeslot {
        flex-shrink: 0;
        width: 80px;
    }

    .alf__lifecycle {
        flex-shrink: 0;
        width: 100px;
    }
</style>

<template id="anime-list-filter">
    <div class="anime-list-filter">
        <div class="alf__item alf__datetime">
            <label>首播时间</label>
            <div class="alf__year">
                <input type="text" v-model="filters.startYear">
            </div>
            <div class="alf__season">
                <vselect
                        :vs_value.sync="filters.startSeason"
                        :vs_options="seasons"
                        :multipe="false"
                ></vselect>
            </div>
            <span>〜</span>
            <div class="alf__year">
                <input type="text" v-model="filters.endYear">
            </div>
            <div class="alf__season">
                <vselect
                        :vs_value.sync="filters.endSeason"
                        :vs_options="seasons"
                        :multipe="false"
                ></vselect>
            </div>
        </div>
        <div class="alf__item">
            <label>播出时段</label>
            <div class="alf__timeslot">
                <vselect
                        :vs_value.sync="filters.timeslot"
                        :vs_options="timeslots"
                        :multipe="false"
                ></vselect>
            </div>
        </div>
        <div class="alf__item">
            <label>出品周期</label>
            <div class="alf__lifecycle">
                <vselect
                        :vs_value.sync="filters.lifecycle"
                        :vs_options="lifecycle"
                        :multipe="false"
                ></vselect>
            </div>
        </div>
        <div class="alf__item">
            <button class="btn btn-sm btn-success"
                    type="button"
                    v-bind:disabled="loading"
                    v-on:click="filt"
            >
                过滤
            </button>
        </div>
    </div>
</template>

<script>
    Vue.component('animelistfilter', {
        template: '#anime-list-filter',
        props: ['anime_list', 'loading', 'filters', 'use_filter'],
        data: function(){
            return {
                'seasons': [
                    {'label': '冬', 'value': 1},
                    {'label': '春', 'value': 4},
                    {'label': '夏', 'value': 7},
                    {'label': '秋', 'value': 10}
                ],
                'timeslots': [
                    {'label':'晨间档', 'value':'morning'},
                    {'label':'日间档', 'value':'daytime'},
                    {'label':'黄金档', 'value':'prime'},
                    {'label':'深夜档', 'value':'midnight'}
                ],
                'lifecycle': [
                    {'label': '策划中', 'value': 'planning'},
                    {'label': '动画化决定', 'value': 'decided'},
                    {'label': '即将播出', 'value': 'comming' },
                    {'label': '播出中', 'value': 'airing'},
                    {'label': '完结', 'value': 'ended'}
                ]
            }
        },
        methods: {
            filt: function() {
                this.use_filter = true;
            }
        }
    })
</script>