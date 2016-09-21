{{-- 搜索动画 --}}
<template id="search-anime">
    <div id="search" class="form-group" style="width: 75%;margin:50px auto">
        <h2>查找</h2>

        <div class="row">
            <div class="col-xs-10">
                <input class="form-control" type="text"
                       v-model="title"
                       v-on:keyup.enter="searchAnime"
                >
            </div>
            <div class="col-xs-2">
                <button class="btn btn-primary"
                        v-on:click="searchAnime"
                        v-if="!searchProcessing"
                >
                    <span class="glyphicon glyphicon-search"></span>
                </button>
                <button class="btn btn-primary btn-processing"
                        v-else
                        disabled
                >
                    @{{ searching_msg }}<span>...</span>
                </button>
            </div>
        </div>
        <div v-if="res === true">
            <a class="search-result btn btn-default btn-block"
               role="button"
               v-for="animeName in animeNameList"
               :href="'/input/' + animeName.abbr"
            >
                <span>@{{ animeName.ori }}</span>
                <span>@{{ animeName.zh_cn }}</span>
            </a>
        </div>
        <div v-if="res === false" class="nores btn-processing">
            @{{ errmsg }}<span v-if="anime_id === -1">...</span>
        </div>
    </div>
</template>

{{-- 原作类型 --}}
<template id="ori-work">
        {{-- Argument:
                pid      : 父项目 ID
                data     : basicData.oriWorks
                orilist  : 来自数据库的全部原作类型列表
                multiple_children : 子项目是否多选
                haschild : 是否有子项目, haschild
                lv       : 当前层数, ori_level
                index    : 用于结合 v-for 插入数组, $index
        --}}

        {{-- 第一级父项目 --}}
        <div v-if="pid===0" class="ori-lv0">
            <vselect
                    :vs_value.sync="data[0][0]"
                    vs_placeholder="原作类型"
                    :vs_options=" orilist | filtByValue 0 'ori_pid'"
                    :multiple="false"
                    vs_label="ori_catalog"
                    vs_value_label="ori_id"
                    :opt_is_value="true"
            ></vselect>

            <div v-if="data[0][0].haschild">
                <originalwork
                        :pid="data[0][0].ori_id"
                        :data.sync="data"
                        :orilist="orilist"
                        :multiple_children="data[0][0].multiple_children"
                        :multiple_selected="data[0][0].multiple_selected"
                        :haschild="data[0][0].haschild"
                        :parent_name="data[0][0].ori_catalog"
                        :lv="1"
                        :index="0"
                >
                </originalwork>
            </div>
        </div>
        {{-- 子项目 --}}
        <div v-if="pid>0" :class="'ori-lv' + lv ">
            {{-- 子项目单选 --}}
            <div v-if="!multiple_children">
                {{-- 单选 --}}
                <vselect :vs_value.sync="data[lv][index]"
                         :multiple="multiple_selected"
                         :class="{'ori-multiple' : multiple_selected}"
                         :vs_options="orilist | filtByValue pid 'ori_pid'"
                         :opt_is_value="true"
                         vs_label="ori_catalog"
                         vs_value_label="ori_id"
                         :vs_placeholder="'请选择' + parent_name"
               ></vselect>

                {{-- 模板递归 --}}
                {{-- 生成单选第三项及第四项等的下拉选择框 --}}
                <div v-if="data[lv][index] ? data[lv][index].haschild : false">
                    <originalwork
                        :pid="data[lv][index].ori_id"
                        :data.sync="data"
                        :orilist="orilist"
                        :multiple_children="data[lv][index].multiple_children"
                        :multiple_selected="data[lv][index].multiple_selected"
                        :haschild="data[lv][index].haschild"
                        :parent_name="data[lv][index].ori_catalog"
                        :lv="lv+1"
                        :index="index"
                    ></originalwork>
                </div>
            </div>

            {{-- 子项目多选 --}}
            <div class="clearfix"
                 v-if="multiple_children"
                 v-for="item in orilist | filtByValue pid 'ori_pid'"
            >
                {{-- 多选第二项, 作为第三项下拉框的标签 --}}
                <label>@{{ item.ori_catalog }}</label>
                <select v-model="data[lv][$index]" class="hidden">
                    <option :value="item" selected></option>
                </select>

                {{-- 第三项 --}}
                {{-- 模板递归 --}}
                <originalwork
                        :pid="item.ori_id"
                        :data.sync="data"
                        :orilist="orilist"
                        :multiple_children="item.multiple_children"
                        :multiple_selected="item.multiple_selected"
                        :haschild="item.haschild"
                        :parent_name="item.ori_catalog"
                        :lv="lv+1"
                        :index="$index"
                ></originalwork>
            </div>
        </div>
</template>

{{-- 介绍框 --}}
<template id="descri-box">
    <div id="describox">
        <label class="control-label"
               v-if="!processing"
        >
            @{{ descri_label }}
        </label>
        <label class="control-label btn-processing"
               v-else
        >
            介绍更新中<span>...</span>
        </label>
                    <textarea cols="30" rows="10"
                              class="form-control"
                              v-model="descri_value"
                              v-on:keyup="shortCut(anime_id, $event)"
                              disabled="@{{ processing }}"
                    ></textarea>
    </div>
</template>

{{-- STAFF INFO --}}
<template id="staff-row">
    <tr class="staff-info">
        <td>
            <input v-model="staffitem.id" type="text" placeholder="ID">
        </td>
        <td>
            <input type="text"
                   :id="'staffPostOri-' + lv + '-' + index"
                   v-model="staffitem.staffPostOri"
                   v-on:keyup="focusMove('staffPostOri-' + lv + '-', index, $event)"
                   placeholder="岗位名称（原）"
            >
            <input type="text"
                   :id="'staffPostZhCN-' + lv + '-' + index"
                   v-model="staffitem.staffPostZhCN"
                   v-on:keyup="focusMove('staffPostZhCN-' + lv + '-', index, $event)"
                   placeholder="岗位名称（中）"
            >
        </td>
        <td>
            <input v-model="staffitem.staffMemberName"
                   :id="'staffMemberName-' + lv + '-' + index"
                   type="text"
                   v-on:keyup="focusMove('staffMemberName-' + lv + '-', index, $event)"
                   placeholder="人员名称"
            >
        </td>
        <td>
            <input type="text"
                   :id="'staffBelongsToName-' + lv + '-' + index"
                   v-model="staffitem.staffBelongsToName"
                   v-on:keyup="focusMove('staffBelongsToName-' + lv + '-', index, $event)"
                   placeholder="所属公司名称"
            >
        </td>
        <td>
            <togglebutton :toggle.sync="staffitem.isImportant"
                          :style="'glyphicon glyphicon-star'"
                          :content=""
            ></togglebutton>
        </td>
        <td v-if="lv === 0">
            <button type="button"
                    class="btn btn-xs btn-default"
                    v-on:click="addChild(controlledarr, index)"
            >
                <span class="glyphicon glyphicon-th-list"></span>
            </button>
        </td>
        <td>
            <rowcontrol :arr.sync="controlledarr"
                        :index.sync="index"
                        pos="staff"
            ></rowcontrol>
        </td>
    </tr>
</template>

{{-- 创建 / 更新按钮 --}}
<template id="create-edit-btn">
    <div :id="pos + '-cae-btn'" class="cae-btn">
        <div v-if="!btnProcessing">
            <div v-if="create_condition">
                <button class="btn btn-success"
                        v-on:click="createData(pos)"
                >
                    创建@{{ msg }}信息（动画ID：@{{ anime_id }}）
                </button>
            </div>
            <div v-if="edit_condition">
                <button class="btn btn-success"
                        v-on:click="editData(pos, anime_id)"
                >
                    更新@{{ msg }}信息（动画ID：@{{ anime_id }}）
                </button>
            </div>
        </div>
        <div v-else>
            <button class="btn btn-success btn-processing"
                    disabled
            >
                @{{ processing_msg }}<span>...</span>
            </button>
        </div>
    </div>
</template>

{{-- 信息栏内容格式化按钮 --}}
<template id="text-format">
    <div class="textformat-btn clearfix">
        <div>
            <button class="btn"
                    v-on:click="format(text, pos, 'separator')"
            >
                转换分隔符
            </button>
        </div>
        <div>
            <button class="btn"
                    v-on:click="format(text, pos, 'cleanHTML')"
            >
                清除HTML标签
            </button>
        </div>
        <div>
            <button class="btn"
                    v-on:click="format(text, pos, 'oddEven')"
            >
                奇偶行合并
            </button>
        </div>
        <div>
            <button class="btn"
                    v-on:click="format(text, pos, 'colCombine')"
            >
                两列合并
            </button>
        </div>
        <div>
            <button class="btn"
                    v-on:click="format(text, pos, 'wikiCV')"
                    v-if="pos == 'cast'"
            >
                维基百科声优
            </button>
        </div>
    </div>
</template>

{{-- 行上下增删操作 --}}
<template id="row-control">
    <div class="row-control">
        <div>
            <button v-on:click="rowUp(arr,index)" type="button" class="btn btn-default btn-xs" tabindex="-1">
                <span class="glyphicon glyphicon-arrow-up"></span>
            </button>
        </div><!--
        --><div>
            <button v-on:click="rowDown(arr,index)" type="button" class="btn btn-default btn-xs" tabindex="-1">
                <span class="glyphicon glyphicon-arrow-down"></span>
            </button>
        </div><!--
        --><div>
            <button v-on:click="removeRow(pos, arr, index)" type="button" class="btn btn-danger btn-xs" tabindex="-1">
                <span class="glyphicon glyphicon-remove"></span>
            </button>
        </div><!--
        --><div>
            <button v-on:click="addRow(arr,index, pos)" type="button" class="btn btn-success btn-xs" tabindex="-1">
                <span class="glyphicon glyphicon-plus"></span>
            </button>
        </div>
    </div>
</template>

{{-- 滚动到表格顶端 --}}
<template id="form-to-top">
    <div class="goto"
         v-bind:class="{'fixed' : arrivedTop, 'top': !arrivedBottom, 'bottom' : arrivedBottom}"
    >
        <a :href="'#' + pos + '-form'">
            <button type="button" class="btn btn-primary">
                <span style="text-align: center" class="glyphicon glyphicon-chevron-up"></span>
            </button>
        </a>
        <a :href="'#' + pos + '-cae-btn'" class="bottom">
            <button type="button" class="btn btn-primary">
                <span style="text-align: center" class="glyphicon glyphicon-chevron-down"></span>
            </button>
        </a>
    </div>
</template>

{{-- 导航按钮 --}}
<template id="nav-btn">
   <div id="navbtn">
       <a href="#animedata">
           <button type="button" class="btn btn-primary">
               <span class="glyphicon glyphicon-chevron-up"></span>
           </button>
       </a>
       <a href="#basic-data">
           <button type="button" class="btn btn-primary">
               B
           </button>
       </a>
       <a href="#staff">
           <button type="button" class="btn btn-primary">
               S
           </button>
       </a>
       <a href="#cast">
           <button type="button" class="btn btn-primary">
               C
           </button>
       </a>
       <a href="#onair">
           <button type="button" class="btn btn-primary">
               O
           </button>
       </a>
   </div>
</template>
