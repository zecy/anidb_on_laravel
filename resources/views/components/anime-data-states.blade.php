<style>
    .anime-data-states {
        width: 100%;
        height: 310px;
        overflow: hidden;
        display: flex;
        outline: 1px solid #ccc;
        padding: 5px;
        margin-bottom: 20px;
    }

    .ads__thumb {
        width: 200px;
        height: 300px;
        flex: 0 0 200px;
        border: 1px solid #ccc;
        padding: 3px;
        overflow: hidden;
    }

    .ads__thumb img {
        width: 100%;
    }

    .ads__info {
        flex: 1 1 auto;
        height: 100%;
        padding: 5px;
    }

    .ads__info > ul {
        height: 100%;
        display: flex;
        flex-direction: column;
        flex-wrap: wrap;
    }

    .ads__info li {
        font-size: 1.2em;
        flex:0 0 10%;
        display: flex;
        flex-direction: row;
        align-items: center;
        padding: 5px 0;
    }

    .ads__info li > label {
        flex:0 0 50%;
        text-align: right;
        padding-right: 1em;
    }

    .ads__processbar {
        height: 100%;
        flex: 0 0 50%;
    }
</style>
<template id="anime-data-states">
    <div class="anime-data-states"
    >
        <div class="ads__thumb">
            <img v-bind:src="'{{ asset('anime-image') }}/' + ( hasThumb ? (abbr + '/') : '') + 'thumb.png'">
        </div>

        <div class="ads__info">
            <ul>
                <li>
                    <label>系&emsp;&emsp;列</label>
                    <div class="ads__processbar">
                        <processsetter
                                :value.sync="data_states.s_series"
                                :max="3"
                        ></processsetter>
                    </div>
                </li>
                <li>
                    <label>标&emsp;&emsp;题</label>
                    <div class="ads__processbar">
                        <processsetter
                                :value.sync="data_states.s_title"
                                :max="3"
                        ></processsetter>
                    </div>
                </li>
                <li>
                    <label>原作类型</label>
                    <div class="ads__processbar">
                        <processsetter
                                :value.sync="data_states.s_ori_works"
                                :max="3"
                        ></processsetter>
                    </div>
                </li>
                <li>
                    <label>相关网页</label>
                    <div class="ads__processbar">
                        <processsetter
                                :value.sync="data_states.s_url"
                                :max="3"
                        ></processsetter>
                    </div>
                </li>
                <li>
                    <label>系列长度</label>
                    <div class="ads__processbar">
                        <processsetter
                                :value.sync="data_states.s_duration"
                                :max="3"
                        ></processsetter>
                    </div>
                </li>
                <li>
                    <label>集&emsp;&emsp;数</label>
                    <div class="ads__processbar">
                        <processsetter
                                :value.sync="data_states.s_eps"
                                :max="3"
                        ></processsetter>
                    </div>
                </li>
                <li>
                    <label>时间规格</label>
                    <div class="ads__processbar">
                        <processsetter
                                :value.sync="data_states.s_time_format"
                                :max="3"
                        ></processsetter>
                    </div>
                </li>
                <li>
                    <label>首播媒体</label>
                    <div class="ads__processbar">
                        <processsetter
                                :value.sync="data_states.s_media"
                                :max="3"
                        ></processsetter>
                    </div>
                </li>
                <li>
                    <label>播出日期</label>
                    <div class="ads__processbar">
                        <processsetter
                                :value.sync="data_states.s_date"
                                :max="3"
                        ></processsetter>
                    </div>
                </li>
                <li>
                    <label>播出时间</label>
                    <div class="ads__processbar">
                        <processsetter
                                :value.sync="data_states.s_time"
                                :max="3"
                        ></processsetter>
                    </div>
                </li>
                <li>
                    <label>作品简介</label>
                    <div class="ads__processbar">
                        <processsetter
                                :value.sync="data_states.has_description"
                                :bool="true"
                                :max="3"
                        ></processsetter>
                    </div>
                </li>
                <li>
                    <label>STAFF</label>
                    <div class="ads__processbar">
                        <processsetter
                                :value.sync="data_states.s_staff"
                                :max="3"
                        ></processsetter>
                    </div>
                </li>
                <li>
                    <label>缩略图</label>
                    <div class="ads__processbar">
                        <processsetter
                                :value.sync="data_states.has_thumb"
                                :bool="true"
                                :max="3"
                        ></processsetter>
                    </div>
                </li>
                <li>
                    <label>宣传画</label>
                    <div class="ads__processbar">
                        <processsetter
                                :value.sync="data_states.has_poster"
                                :bool="true"
                                :max="3"
                        ></processsetter>
                    </div>
                </li>
                <li>
                    <label>片头曲</label>
                    <div class="ads__processbar">
                        <processsetter
                                :value.sync="data_states.s_op_themes"
                                :max="3"
                        ></processsetter>
                    </div>
                </li>
                <li>
                    <label>片尾曲</label>
                    <div class="ads__processbar">
                        <processsetter
                                :value.sync="data_states.s_ed_themes"
                                :max="3"
                        ></processsetter>
                    </div>
                </li>
                <li>
                    <label>插曲</label>
                    <div class="ads__processbar">
                        <processsetter
                                :value.sync="data_states.s_insert_songs"
                                :max="3"
                        ></processsetter>
                    </div>
                </li>
                <li>
                    <label>CAST</label>
                    <div class="ads__processbar">
                        <processsetter
                                :value.sync="data_states.s_cv"
                                :max="3"
                        ></processsetter>
                    </div>
                </li>
            </ul>
        </div>
    </div>

    <createeditbutton
            :create_condition="data_states.anime_id === 0"
            :edit_condition="data_states.anime_id != 0"
            pos="states"
            :anime_id="data_states.anime_id"
            :is_complete.sync="data_states"
    ></createeditbutton>

</template>
<script>
    Vue.component('animedatastates', {
        template: '#anime-data-states',
        props:['data_states','abbr'],
        data: function() {
            return {
                hasThumb: false
            }
        },
        computed: {
            hasThumb: function(){
                return this.data_states.has_thumb
            }
        }
    })
</script>