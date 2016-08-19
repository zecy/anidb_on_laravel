<style>
    .batch-import {
        width: 100%;
        padding: 10px;
        font-size: 1.2rem;
    }

    .batch-import .import-box textarea {
        padding: 10px;
        width: 100%;
        font-size: 1.5rem;
    }

    .quick-import-dialog {
        border: 1px solid #ccc;
        margin: 10px auto;
        border-radius: 2px;
    }

    .quick-import-dialog input {
        padding: 5px 10px;
        border: 0;
        background: transparent;
        width: 100%;
    }

    .dialog-header {
        width: 100%;
        background: #efefef;
        box-shadow: inset 1px 1px 2px 0 #fff;
    }

    .title-ori, .title-zh-cn {
        flex: 0 0 50%
    }

    .quick-import-dialog .dialog-body {
        padding: 5px 0;
    }

    .dialog-body .homepage {
        width: 100%;
        border-bottom: 1px solid #ccc;
    }

    .quick-import-dialog .toggle-button .btn{
        font-size: 1rem;
        padding: 2px 5px;
        margin: 0 2px;
    }

</style>

<template id="batch-import">
    <div class="batch-import">
        <h3>批量录入</h3>

        <div class="import-box">
            <textarea v-model="batch_import_source"
                      rows="25"
                      placeholder="请粘贴动画数据"
            >
            </textarea>
        </div>

        <div v-for="a in [1,2,3]"
             class="quick-import-dialog">
            <div class="dialog-header flex-grid">
                <div class="title-ori">
                    <input
                            type="text"
                            placeholder="原标题"
                    >
                </div>
                <div class="title-zh-cn">
                    <input
                            type="text"
                            placeholder="常用译名"
                    >
                </div>
            </div>

            <div class="dialog-body flex-grid">

                <div class="homepage">
                    <input type="text" placeholder="官方网站">
                </div>

                <div class="lifecycle toggle-button">
                    <label>出品周期</label>
                    <button class="btn btn-default"
                            type="button"
                            {{--v-on:click="basicData.oa_time.value = time.value"--}}
                            {{--v-bind:class="basicData.oa_time.value === time.value ? 'btn-primary' : 'btn-default'"--}}
                            v-for="link in [{ label: '企划中', value : 'planning'},{ label: '动画化决定', value : 'comming'},{ label: '播出中', value : 'airing'},{ label: '完结', value : 'end'}]"
                    >
                        <span>@{{ link.label }}</span>
                        <input type="radio" value="@{{ link.value }}" class="hidden">
                    </button>
                </div>
                <div class="airing-time toggle-button">
                    <label>播出时段</label>
                    <button class="btn btn-default"
                            v-for="time in [{'label':'晨间档', 'value':'morning'},{'label':'日间档', 'value':'daytime'},{'label':'黄金档', 'value':'prime'},{'label':'深夜档', 'value':'midnight'}]"
                            type="button"
                            {{--v-on:click="basicData.oa_time.value = time.value"--}}
                            {{--v-bind:class="basicData.oa_time.value === time.value ? 'btn-primary' : 'btn-default'"--}}
                    >
                        <span>@{{ time.label }}</span>
                        <input type="radio" value="@{{ time.value }}" class="hidden">
                    </button>
                </div>
            </div>
        </div>
</template>

<script>
    var batch_import = Vue.extend({
        template: '#batch-import',
        data: function() {
            return {
                'batch_import_source' : ''
            }
        }
    })
</script>
