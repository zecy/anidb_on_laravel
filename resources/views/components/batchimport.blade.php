<style>
    .batch-import {
        width: 100%;
        font-size: 1.2rem;
    }

    /*<editor-fold desc="INPUT">*/
    .batch-import input {
        height: 30px;
        line-height: 30px;
        background: transparent;
        border: 1px solid #ccc;
        padding: 5px 10px;
        outline: 0;
        width: 100%;
    }

    .batch-import input:hover,
    .batch-import input:focus {
        border-color: deepskyblue;
    }

    /*</editor-fold>*/

    .batch-import .import-box,
    #unify-setting {
        flex: 1 0 100%;
    }

    .batch-import .import-box textarea {
        padding: 10px;
        width: 100%;
        font-size: 1.5rem;
    }

    #unify-setting {
        font-size: 1.4rem;
        color: #666;
        padding: 10px;
        background: rgba(0,0,0,.05);
        border: 1px solid #aaa;
        border-radius: 3px;
    }

        #unify-setting  label {
            margin:0 1em;
        }

        #unify-setting .toggle-button {
            margin: 0 5px;
        }

        #unify-setting-year {
            width: 60px;
            margin-right: 0.5em;
        }

            #unify-setting-year input {
                height: 24px;
                line-height: 24px;
                text-align: center;
            }

    .quick-import-dialog {
        border: 1px solid #aaa;
        border-radius: 3px;
        margin: 10px;
        width: calc(50% - 20px);
    }

    .quick-import-dialog label {
        height: 30px;
        line-height: 30px;
        margin: 0 10px;
    }


    /* <editor-fold desc="Dialog Header"> */
    .dialog-header {
        width: 100%;
        background: #efefef;
        border-top-left-radius: 3px;
        border-top-right-radius: 3px;
        padding: 5px;
    }

    .title-ori {
        flex-grow: 1;
    }

    .title-ori input {
        border: none;
    }

    /* </editor-fold> */

    .quick-import-dialog .dialog-row {
        border-top: 1px solid #ccc;
        padding: 5px;
    }

    .quick-import-dialog .dialog-row > div:not(:last-child) {
        margin-right: 6px;
    }

    /*<editor-fold desc="  OA SEASON  ">*/
    #oa-year {
        width: 66px;
        margin-right: 5px;
        position: relative;
    }

    #oa-year > span {
        position: absolute;
        line-height: 30px;
        right: 0.5em;
        top: 0;
        user-select: none;
    }

    #oa-year > input {
        text-align: right;
        padding-right: 2em;
    }

    #oa-month {
        width: 60px;
    }

    /* </editor-fold> */

</style>

<template id="batch-import">
    <div class="batch-import flex-grid">
        <h3>批量录入</h3>

        <div class="import-box flex-cell">
            <textarea v-model="batch_import_source"
                      rows="25"
                      placeholder="请粘贴动画数据" >
            </textarea>
        </div>

        <button v-on:click="sourceToList">导入</button>

        <div id="unify-setting" class="flex-cell">
            <div class="flex-grid">

                {{-- 出品周期 --}}
                <div class="flex-cell">
                    <div class="flex-grid">
                        <label>出品周期</label>

                        <div class="toggle-button flex-cell"
                             v-for="link in [{ label: '企划中', value : 'planning'},{ label: '动画化决定', value : 'comming'},{ label: '播出中', value : 'airing'},{ label: '完结', value : 'end'}]"
                        >
                            <button class="btn btn-xs"
                                    type="button"
                                    v-on:click="unifySetting.lifecycle = link.value"
                                    v-bind:class="unifySetting.lifecycle === link.value ? 'btn-primary' : 'btn-default'"
                            >
                                <span>@{{ link.label }}</span>
                                <input type="radio" value="@{{ link.value }}" v-model="unifySetting.lifecycle"
                                       class="hidden">
                            </button>
                        </div>
                    </div>
                </div>

                {{-- 首播年月 --}}
                <div id="unify-setting-season" class="flex-cell">
                    <div class="flex-grid">
                        <label>首播季度</label>

                        <div id="unify-setting-year" class="flex-cell">
                            <input type="text"
                                   v-model="unifySetting.oa_year"
                            >
                        </div>

                        <div class="flex-cell">年</div>

                        <div class="toggle-button flex-cell"
                             v-for="season in [{'label':'冬', 'value':1},{'label':'春', 'value':4},{'label':'夏', 'value':7},{'label':'秋', 'value':10}]">
                            <button class="btn btn-xs"
                                    type="button"
                                    v-on:click="unifySetting.oa_season = season.value"
                                    v-bind:class="unifySetting.oa_season === season.value ? 'btn-primary' : 'btn-default'"
                            >
                                <span>@{{ season.label }}</span>
                                <input type="radio" value="@{{ season.value }}" v-model="unifySetting.season"
                                       class="hidden">
                            </button>
                        </div>
                    </div>
                </div>

                {{--  --}}
                <div style="flex-grow: 1; text-align: right" class="flex-cell">
                    <button type="button" class="btn btn-sm btn-primary"
                            v-on:click="unifySet"
                    >
                        统一设置
                    </button>
                </div>
            </div>
        </div>

        <div class="quick-import-dialog flex-cell"
             v-for="animeData in animeList"
        >
            {{-- 第一行 --}}
            <div class="dialog-header flex-grid">
                <div class="title-ori flex-cell">
                    <input type="text"
                           placeholder="原标题"
                           v-model="animeData.title_ori"
                    >
                </div>
            </div>

            <div class="dialog-body">

                <div class="dialog-row">
                    <div class="title-zh-cn">
                        <input type="text"
                               placeholder="常用译名"
                               v-model="animeData.title_zhcn"
                        >
                    </div>
                </div>

                {{-- 第二行 --}}
                <div class="dialog-row">
                        <input type="text"
                               placeholder="官方网站"
                               v-model="animeData.hp"
                        >
                </div>

                <div class="dialog-row flex-grid">

                    {{-- 简称 --}}
                    <div style="width: 100px" class="flex-cell">
                        <input type="text"
                               placeholder="作品简称"
                               v-model="animeData.abbr"
                        >
                    </div>

                    {{-- 生命周期 --}}
                    <div style="width: 100px;" class="flex-cell">
                        <vselect
                                :vs_value.sync="animeData.lifecycle"
                                :vs_options="[{ label: '企划中', value : 'planning'},{ label: '动画化决定', value : 'comming'},{ label: '播出中', value : 'airing'},{ label: '完结', value : 'end'}]"
                                :multiple="false"
                                vs_placeholder="出品周期"
                        ></vselect>
                    </div>

                    {{-- 首播季度 --}}
                    <div id="oa-season" class="flex-cell">
                        <div class="flex-grid">
                            <div id="oa-year" class="flex-cell">
                                <input v-model="animeData.oa_year" type="text">
                                <span>年</span>
                            </div>

                            <div id="oa-month" class="flex-cell">
                                <vselect
                                        :vs_value.sync="animeData.oa_season"
                                        :vs_options="[{'label':'冬', 'value':1},
                                                  {'label':'春', 'value':4},
                                                  {'label':'夏', 'value':7},
                                                  {'label':'秋', 'value':10}]"
                                        :multipe="false"
                                ></vselect>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <button type="button"
                class="btn btn-success"
                v-on:click="create"
        >导入</button>
    </div>
</template>

<script>
    var batch_import = Vue.extend({
        template: '#batch-import',
        data:     function () {
            return {
                'batch_import_source': '',
                'unifySetting': {
                    'lifecycle': 'comming',
                    'oa_year': 2016,
                    'oa_season': 1
                },
                'animeList': [{
                    'title_ori': '',
                    'title_zhcn': '',
                    'hp': '',
                    'abbr': '',
                    'oa_year': 2016,
                    'oa_season': 1,
                    'lifecycle':''
                }]
            }
        },
        methods: {
            'sourceToList': function() {

                // 把源数据按换行切成数组
                const arr = this.batch_import_source.split('\n');

                // 用于存放结果的数组, 最后会赋值给 this.animeList
                let res = [];

                // ep. http://relife-anime.co.jp
                const getAbbrSLD = /http[s]?:\/\/(?:www.?\.)?(.*?)\..*/;

                // ep. http://www.tbs.co.jp/anime/urara
                const getAbbrTLD = /http[s]?:.*\/(.*?)$/;

                // 找出属于使用三级域名的网址
                const SLD = /tbs|dreamcreation|tv-tokyo|mbs/;
                getAbbrSLD.compile(getAbbrSLD);
                getAbbrTLD.compile(getAbbrTLD);
                SLD.compile(SLD);

                for(let i = 0; i < arr.length; i++) {
                    const item = arr[i].split(',');
                    let abbr = arr[i] !== '' ? arr[i] : '';
                    if(abbr.search(SLD) !== -1) {
                        abbr = abbr.match(getAbbrTLD)[1];
                    } else {
                        abbr = abbr.match(getAbbrSLD)[1];
                    }
                    res.push({
                        'title_ori': item[0],
                        'title_zhcn': '',
                        'hp': item[1],
                        'abbr': abbr,
                        'oa_year': 2016,
                        'oa_season': 1,
                        'lifecycle':'comming'
                    });
                }

                this.animeList = res;
            },
            'unifySet': function() {
                let targetArr = this.animeList;
                const sourceObj = this.unifySetting;

                for(let i = 0; i < targetArr.length; i++) {
                    targetArr[i].oa_year   = sourceObj.oa_year;
                    targetArr[i].oa_season = sourceObj.oa_season;
                    targetArr[i].lifecycle = sourceObj.lifecycle;
                }

                this.animeList = targetArr;
            },
            'create': function(){
                this.$http.post('/manager/resource', {data: this.animeList}).then(function (r) {
                    if (r.status == 200) {
                        console.log('year');
                    }
                });
            }
        }
    })
</script>
