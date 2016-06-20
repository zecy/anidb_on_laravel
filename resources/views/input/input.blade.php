@extends('layout.master')

@section('page_title')
    数据录入
@stop

@section('content')

    <div id="animedata" class="container">

        <div class="form-group" style="width: 75%;margin:50px auto">
            <h2>查找</h2>
            <div class="row">
                <div class="col-xs-10">
                    <input class="form-control" type="text"
                           v-model="animeNameSearchInput"
                    >
                </div>
                <button class="btn btn-primary col-xs-2"
                        v-on:click="searchAnime"
                >
                    <span class="glyphicon glyphicon-search"></span>
                </button>
            </div>
            <div>
                <button class="btn btn-block"
                        v-for="animeName in animeNameList"
                        v-on:click="showAnime(animeName.id)"
                >
                    @{{ animeName.ori + ' | ' + animeName.zh_CN }}
                </button>
            </div>
        </div>

        <h2>主要信息</h2>

        <form id="maininfo" class="form form-horizontal">
        <table class="table">
            <tr style="display: none">
                <td>
                    禁止 Lastpass 注入
                    <input type="text">
                    <input type="text">
                </td>
            </tr>
            <tr>
                <td>
                    <label style="width: 7%">系列ID</label>

                    <div style="width: 10%">
                        <input type="text" v-model="basicData.seriesID.value">
                    </div>

                    <label style="width: 9%">系列标题</label>

                    <div style="width: 20%">
                        <input type="text" v-model="basicData.seriesTitle.value">
                    </div>

                    <label style="width: 7%">动画ID</label>

                    <div style="width: 10%">
                        <input type="text" v-model="basicData.id.value">
                    </div>

                    <label style="width: 5%">简称</label>

                    <div style="width: 15%">
                        <input type="text" v-model="basicData.abbr.value">
                    </div>

                    <label style="width: 13%">是否纳入统计</label>

                    <div id="is-counted" style="width: 4%">
                        <togglebutton :toggle.sync="basicData.isCounted"
                                      :style="'glyphicon glyphicon-ok'"
                                      :content=""
                        ></togglebutton>
                    </div>
                </td>
            </tr>
            <tr v-for="title in basicData.title" track-by="$index">
                <td>
                    <div style="width: 35%">
                        <input type="text"
                               v-model="title.value"
                               placeholder="@{{ title.label }}"
                        >
                    </div>
                    <div style="width: 17%">
                        <select v-model="title.lang">
                            @foreach( $transLangs as $lang )
                                <option value="{{ $lang->content }}">{{ $lang->comment }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="is-official" style="width: 4%">
                        <togglebutton :toggle.sync="title.isOfficial"
                                      :style="'glyphicon glyphicon-ok'"
                                      :content=""
                        ></togglebutton>
                    </div>
                    <div style="width: 26%">
                        <input v-model="title.comment" type="text" placeholder="备注">
                    </div>
                    <div style="width: 18%">
                        <rowcontrol :arr.sync="basicData.title"
                                    :index.sync="$index"
                        ></rowcontrol>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="row">
                    <originalwork :orilist="{{ $oriWorks }}"
                                  :data.sync="basicData.oriWorks"
                                  pid="0"
                                  multiple="false"
                                  haschild="true"
                                  :lv=0
                                  :index="0"
                    ></originalwork>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="width:10%">
                        <label>首播媒体</label>
                    </div>
                    <div style="width: 20%">
                        <select v-model="basicData.premiereMedia.value">
                            @foreach($premiereMedia as $pm)
                                <option value="{{ $pm->content }}">{{ $pm->comment }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div style="width:10%">
                        <label>时间规格</label>
                    </div>
                    <div style="width: 20%">
                        <select v-model="basicData.duration.value">
                            @foreach($animeDurationFormat as $adf)
                                <option value="{{ $adf->content }}">{{ $adf->comment }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div style="width: 10%">
                        <label>系列长度</label>
                    </div>
                    <div style="width: 12%">
                        <select v-model="basicData.kur.value">
                            <option value="0">特别篇</option>
                            <option value="1">一季度</option>
                            <option value="2">两季度</option>
                            <option value="3">三季度</option>
                            <option value="4">年番</option>
                            <option value="5">长篇</option>
                            <option value="6">大长篇</option>
                        </select>
                    </div>

                    <div style="width: 6%">
                        <label>集数</label>
                    </div>
                    <div style="width: 8%">
                        {{--<input type="text" v-model="basicData.eps.value">--}}
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="width:10%">
                        <label>
                            @{{ basicData.isSequel.label }}
                        </label>
                    </div>

                    <div style="width: 5%" id="is-sequel">
                    <togglebutton :toggle.sync="basicData.isSequel.value"
                                  :style="'glyphicon glyphicon-ok'"
                                  :content=""
                        ></togglebutton>
                    </div>

                    <div style="width: 6%">
                        <label>
                            @{{ basicData.sequelComment.label }}
                        </label>
                    </div>

                    <div style="width: 40%">
                        <input type="text" v-model="basicData.sequelComment.value">
                    </div>

                    <div style="width:10%">
                        <label>
                            @{{ basicData.isEnd.label }}
                        </label>
                    </div>

                    <div style="width: 5%" id="is-end">
                        <togglebutton :toggle.sync="basicData.isEnd.value"
                                      :style="'glyphicon glyphicon-ok'"
                                      :content=""
                        ></togglebutton>
                    </div>
                </td>
            </tr>
            <tr v-for="link in basicData.links" track-by="$index">
                <td>
                    <div style="width: 18%">
                        <select class="col-xs-1 form-control" v-model="link.class">
                            @foreach( $links as $link )
                                <option value="{{ $link->content }}">{{ $link->comment }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div style="width: 40%">
                        <input type="text"
                               v-model="link.value"
                               placeholder="网站地址"
                        >
                    </div>

                    <div class="is-official" style="width: 4%">
                        <togglebutton :toggle.sync="link.isOfficial"
                                      :style="'glyphicon glyphicon-ok'"
                                      :content=""
                        ></togglebutton>
                    </div>

                    <div style="width: 21%">
                        <input type="text" v-model="link.comment" placeholder="备注">
                    </div>

                    <div style="width: 17%">
                        <rowcontrol :arr.sync="basicData.links"
                                    :index.sync="$index"
                        ></rowcontrol>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <label class="control-label">@{{ basicData.description.label }}</label>
                    <textarea v-model="basicData.description.value" cols="30" rows="10" class="form-control"></textarea>
                </td>
            </tr>
        </table>
        </form>
        <div v-if="basicData.id.value == 0">
            <button class="btn btn-success"
                    v-on:click="createData('basicData')"
            >
                创建数据
            </button>
        </div>
        <div v-if="basicData.id.value != 0">
            <button class="btn btn-success"
                    v-on:click="editData('basicData', basicData.id.value)"
            >
                更新数据（动画ID：@{{ basicData.id.value }}）
            </button>
        </div>

        <div v-if="basicData.id.value != ''">
                                                                {{-- STAFF BIGIN --}}

        <h2>Staff信息</h2>

        <textformat :text.sync="staffSource"
                    :pos="'staff'"
        ></textformat>

        <br>
        <br>

        <div class="form-group row">
            <div>
                <textarea class="form-control" rows="10" v-model="staffSource" placeholder="请输入源数据"></textarea>
            </div>
        </div>
        <button class="btn btn-primary" v-on:click="toArray(staffSource ,'staff')">获取Staff数据</button>
        <button class="btn btn-danger" v-on:click="staffMembers = []">Staff列表</button>
        <button class="btn btn-danger" v-on:click="staffSource = ''">清除输入框</button>

        <br>
        <br>

        <form id="staff" class="form">
            <table class="sco">
                <thead style="text-align: center;background-color: #EEEEEE;border-bottom:1px solid #ccc">
                <tr>
                    <td style="width:10%">ID</td>
                    {{--<td style="width:10%">
                        <input v-model="staffMember.staffPostID" type="text" disabled="disabled" placeholder="岗位ID">
                    </td>--}}
                    <td style="width:30%">岗位名称</td>
                    <td style="width:20%">人员名称</td>
                    <td style="width:20%">所属公司名称</td>
                    <td>
                        <span class="glyphicon glyphicon-star"></span>
                    </td>
                    <td>条目操作</td>
                </tr>
                </thead>
                <tbody>
                <tr style="display:none">
                    <td>
                        防止 Lastpass 注入输入框
                        <input type="text">
                        <input type="text">
                    </td>
                </tr>
                <tr v-for="staffMember in staffMembers | orderBy 'orderIndex'"
                    track-by="$index"
                    v-bind:class="{ zebrarow:$index % 2 }"
                >
                    <td style="width:10%">
                        <input v-model="staffMember.id" type="text" disabled="disabled" placeholder="ID">
                    </td>
                    {{--<td style="width:10%">
                        <input v-model="staffMember.staffPostID" type="text" disabled="disabled" placeholder="岗位ID">
                    </td>--}}
                    <td style="width:30%">
                        <input type="text"
                               id="staffPostOri-@{{ $index }}"
                               v-model="staffMember.staffPostOri"
                               v-on:keyup="focusMove('staffPostOri-', $index, $event)"
                               placeholder="岗位名称（原）"
                        >
                        <input type="text"
                               id="staffPostZhCN-@{{ $index }}"
                               v-model="staffMember.staffPostZhCN"
                               v-on:keyup="focusMove('staffPostZhCN-', $index, $event)"
                               placeholder="岗位名称（中）"
                        >
                    </td>
                    <td style="width:20%">
                        {{--<input style="width:50%" v-model="staffMember.staffMemberID" type="text" disabled="disabled" placeholder="人员ID">--}}
                        <input v-model="staffMember.staffMemberName"
                               id="staffMemberName-@{{ $index }}"
                               type="text"
                               v-on:keyup="focusMove('staffMemberName-', $index, $event)"
                               placeholder="人员名称"
                        >
                    </td>
                    <td style="width:20%">
                        {{--<input style="width:50%" v-model="staffMember.staffBelongsToID" type="text" disabled="disabled" placeholder="所属公司ID">--}}
                        <input type="text"
                               id="staffBelongsToName-@{{ $index }}"
                               v-model="staffMember.staffBelongsToName"
                               v-on:keyup="focusMove('staffBelongsToName-', $index, $event)"
                               placeholder="所属公司名称"
                        >
                    </td>
                    <td>
                        <togglebutton :toggle.sync="staffMember.isImportant"
                                      :style="'glyphicon glyphicon-star'"
                                      :content=""
                        ></togglebutton>
                    </td>
                    <td>
                        <rowcontrol :style="'snc-row-control'"
                                    :arr.sync="staffMembers"
                                    :index.sync="$index"
                        ></rowcontrol>
                    </td>
                </tr>
                </tbody>
            </table>
        </form>

            <div v-if="staffMembers[0].id == 0 && staffMembers.length > 0">
                <button class="btn btn-success"
                        v-on:click="createData('staff')"
                >
                    创建STAFF@{{ "（动画ID：" + basicData.id.value + "）" }}
                </button>
            </div>
            <div v-if="staffMembers[0].id != 0 || staffMembers.length > 0">
                <button class="btn btn-success"
                        v-on:click="editData('staff', basicData.id.value)"
                >
                    更新STAFF@{{ "（动画ID：" + basicData.id.value + "）" }}
                </button>
            </div>

                                                                {{-- STAFF END --}}

        <br>

                                                                {{-- CAST BEGIN --}}
        <h2>Cast信息</h2>

        <textformat :text.sync="castSource" :pos="'cast'"></textformat>

        <br>
        <br>

        <div class="form-group row">
            <div>
                <textarea class="form-control" rows="10" v-model="castSource" placeholder="请输入源数据"></textarea>
            </div>
        </div>

        <button class="btn btn-primary" v-on:click="toArray(castSource ,'cast')">获取Cast数据</button>
        <button class="btn btn-danger" v-on:click="castMembers = []">清除Cast列表</button>
        <button class="btn btn-danger" v-on:click="castSource = ''">清除输入框</button>

        <br>
        <br>

        <form id="cast" class="form">
            <table class="sco">
                <tr style="display:none">
                    <td>
                        防止 Lastpass 注入输入框
                        <input type="text">
                        <input type="text">
                    </td>
                </tr>
                <tr v-for="castMember in castMembers"
                    track-by="$index"
                    v-bind:class="{ zebrarow:$index % 2 }"
                >
                    <td style="width:10%">
                        <input v-model="castMember.id" type="text" disabled="disabled" placeholder="ID">
                    </td>
                    {{--
                    <td style="width:10%">
                        <input v-model="castMember.charaID" type="text" disabled="disabled" placeholder="角色ID">
                    </td>
                    --}}
                    <td style="width:40%">
                        <input v-model="castMember.charaNameOri" type="text" placeholder="角色名称（原）">
                    </td>
                    {{--
                    <td style="width:10%">
                        <input v-model="castMember.cvID" type="text" disabled="disabled" placeholder="演员ID">
                    </td>
                    --}}
                    <td style="width:30%">
                        <input v-model="castMember.cvNameOri" type="text" placeholder="演员名称">
                    </td>
                    <td>
                        <togglebutton :toggle.sync="castMember.isImportant"
                                      :style="'glyphicon glyphicon-star'"
                                      :content=""
                        >
                        </togglebutton>
                    </td>
                    <td>
                        <rowcontrol :style="'snc-row-control'"
                                    :arr.sync="castMembers"
                                    :index.sync="$index"
                        ></rowcontrol>
                    </td>
                </tr>
            </table>
        </form>

        <br>

        <button v-on:click="createData('cast')" class="btn btn-success">创建CAST@{{ "（动画ID：" + basicData.id.value + "）" }}</button>

                                                                {{-- CAST END --}}

                                                                {{-- ONAIR BEGIN --}}

        <h2>播放信息</h2>

        <textarea class="form-control" id="" cols="30" rows="10"
                  v-model="onairDataInput"
        ></textarea>

        <br>

        <button class="btn btn-primary" v-on:click="toArray(onairDataInput, 'onair')">格式化日期</button>

        <button class="btn btn-primary" v-on:click="onairDataInput = ''">清除数据</button>

        <br>
        <br>

        <form id="onair" class="sco form">
            <table>
                <tbody>
                <tr style="display:none">
                    <td>
                        防止 Lastpass 注入输入框
                        <input type="text">
                        <input type="text">
                    </td>
                </tr>
                <tr v-for="datetime in onair"
                    track-by="$index"
                    v-bind:class="{ zebrarow:$index % 2 }"
                    class="row"
                >
                    <td style="width:8%">
                        <input v-model="datetime.id" type="text" placeholder="ID"/>
                    </td>
                    {{--
                    <td style="width:8%">
                        <input v-model="datetime.tvID" type="text" placeholder="电视台ID"/>
                    </td>
                    --}}
                    <td style="width:24%">
                        <input v-model="datetime.tvName" type="text" placeholder="电视台"/>
                    </td>
                    <td style="width:15%">
                        <input v-model="datetime.startDate" type="text" placeholder="开始日期"/>
                        <input v-model="datetime.endDate" type="text" placeholder="结束日期"/>
                    </td>
                    <td style="width:8%">
                        <div style="border-bottom:1px solid #333;padding-top:9px">
                            <select v-model="datetime.weekday">
                                <option value="1">一</option>
                                <option value="2">二</option>
                                <option value="3">三</option>
                                <option value="4">四</option>
                                <option value="5">五</option>
                                <option value="6">六</option>
                                <option value="0">日</option>
                            </select>
                        </div>
                    </td>
                    <td style="width:10%">
                        <input v-model="datetime.startTime" type="text" placeholder="开始时间"/>
                        <input v-model="datetime.endTime" type="text" placeholder="结束时间"/>
                    </td>
                    <td style="width:18%">
                        <input v-model="datetime.tvColumn" type="text" placeholder="播放栏目"/>
                        <input v-model="datetime.description" type="text" placeholder="备注"/>
                    </td>
                    <td style="width:3%">
                        <togglebutton :toggle.sync="datetime.isProduction"
                                      :style="'glyphicon glyphicon-usd'"
                                      :content=""
                        >
                        </togglebutton>
                    </td>
                    <td style="width:14%">
                        <rowcontrol :arr.sync="onair"
                                    :index.sync="$index"
                                    :style="row"
                        ></rowcontrol>
                    </td>
                </tr>
                </tbody>
            </table>
        </form>

        <button v-on:click="createData('onair')" class="btn btn-success">创建播放信息@{{ "（动画ID：" + basicData.id.value + "）" }}</button>

                                                                {{-- ONAIR END --}}
</div>

        {{-- TEMPLATE --}}

        {{-- 原作类型 --}}
        <template id="ori-work">
            {{-- Argument:
                    pid      : 父项目 ID
                    data     : basicData.oriWorks
                    orilist  : 来自数据库的全部原作类型列表
                    multiple : 子项目是否多选
                    haschild : 是否有子项目, haschild
                    lv       : 当前层数, ori_level
                    index    : 用于结合 v-for 插入数组, $index
            --}}

            {{-- 第一级父项目 --}}
            <div v-if="pid==0" class="">
                <select v-model="data[0]">
                    <option v-for="item in orilist | filtByValue 0 'ori_pid'"
                            :value="[{ 'id': item.ori_id, 'haschild': item.haschild, 'multiple': item.multiple, 'pid': 0}]">
                        @{{ item.ori_catalog }}
                    </option>
                </select>
                <div v-if="data[0][0].haschild">
                    <originalwork
                            :pid="data[0][0].id"
                            :data.sync="data"
                            :orilist="orilist"
                            :multiple="data[0][0].multiple"
                            :haschild="data[0][0].haschild"
                            :lv="1"
                            :index="0"
                    >
                    </originalwork>
                </div>
            </div>

            {{-- 子项目 --}}
            <div v-if="pid>0">
                {{-- 子项目单选 --}}
                <div v-if="!multiple">
                    <select v-model="data[lv][index]">
                        <option v-for="item in orilist | filtByValue pid 'ori_pid'"
                                :value="{ 'id': item.ori_id, 'haschild': item.haschild, 'multiple': item.multiple, 'pid': pid}"
                        >
                            @{{ item.ori_catalog }}
                        </option>
                    </select>

                    {{-- 模板递归 --}}
                    {{-- 生成单选第二项及第四项等 --}}
                    <div v-if="data[lv][index] ? data[lv][index].haschild : false">
                        <originalwork
                                :pid="data[lv][index].id"
                                :data.sync="data"
                                :orilist="orilist"
                                :multiple="data[lv][index].multiple"
                                :haschild="data[lv][index].haschild"
                                :lv="lv+1"
                                :index="0"
                        ></originalwork>
                    </div>
                </div>

                {{-- 子项目多选 --}}
                <div v-if="multiple"
                     v-for="item in orilist | filtByValue pid 'ori_pid'"
                >
                    {{-- 多选第二项 --}}
                    <label>@{{ item.ori_catalog }}</label>
                    <select class="hidden" type="text" v-model="data[lv][$index]">
                        <option selected
                                :value="{ 'id': item.ori_id, 'haschild': item.haschild, 'multiple': item.multiple, 'pid': pid}">
                        </option>
                    </select>

                    {{-- 第三项 --}}
                    {{-- 模板递归 --}}
                    <originalwork
                              :pid="item.ori_id"
                              :data.sync="data"
                              :orilist="orilist"
                              :multiple="item.multiple"
                              :haschild="item.haschild"
                              :lv="lv+1"
                              :index="$index"
                    ></originalwork>
                </div>
            </div>
        </template>

        {{-- 信息栏内容格式化按钮 --}}
        <template id="text-format">
            <button class="btn btn-primary"
                    v-on:click="format(text, pos, 'separator')"
            >
                  转换分隔符
            </button>
            <button class="btn btn-primary"
                    v-on:click="format(text, pos, 'cleanHTML')"
            >
                  清除HTML标签
            </button>
            <button class="btn btn-primary"
                    v-on:click="format(text, pos, 'oddEven')"
            >
                  奇偶行合并
            </button>
            <button class="btn btn-primary"
                    v-on:click="format(text, pos, 'wikiCV')"
                    v-if="pos == 'cast'"
            >
                  维基百科声优
            </button>
        </template>

        {{-- 重要按钮 --}}
        <template id="toggle-button">
            <button v-on:click="toggle = !toggle" type="button"
                          v-bind:class="toggle?'btn-primary':'btn-default'"
                          class="btn btn-xs"
                          >
            <span class="@{{ style }}">@{{ content }}</span>
            <input type="checkbox" v-model="toggle" class="hidden"/>
            </button>
        </template>

        {{-- 行上下增删操作 --}}
        <template id="row-control">
            <div class="@{{ style }}">
                <div class="col-xs-3">
                    <button v-on:click="rowUp(arr,index)" type="button" class="btn btn-default btn-xs" tabindex="-1">
                    <span class="glyphicon glyphicon-arrow-up"></span>
                    </button>
                </div>
                <div class="col-xs-3">
                    <button v-on:click="rowDown(arr,index)" type="button" class="btn btn-default btn-xs" tabindex="-1">
                    <span class="glyphicon glyphicon-arrow-down"></span>
                    </button>
                </div>
                <div class="col-xs-3">
                    <button v-on:click="removeRow(arr,index)" type="button" class="btn btn-danger btn-xs" tabindex="-1">
                    <span class="glyphicon glyphicon-remove"></span>
                    </button>
                </div>
                <div class="col-xs-3">
                    <button v-on:click="addRow(arr,index)" type="button" class="btn btn-success btn-xs" tabindex="-1">
                    <span class="glyphicon glyphicon-plus"></span>
                    </button>
                </div>
            </div>
        </template>
    </div>
@stop