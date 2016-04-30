@extends('layout.master')

@section('page_title')
    数据录入
@stop

@section('content')
    <div id="animedata" class="container">
        <h2>主要信息</h2>

        <form id="maininfo" class="form form-horizontal" @submit="createData">
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
                <td>
                    <pre>@{{ basicData.oriGenreLv1.haschild | json }}</pre>
                </td>
            </tr>

            <tr>
                <td class="row">
                    <originalworks data="{{ $oriWorks }}"></originalworks>
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
                        <input type="text" v-model="basicData.eps.value">
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
        <button @click="createData" class="btn btn-success">创建数据</button>
        </form>

        <template id="original-works">
            <div style="width: 10%">
                <label>
                    原作类型
                </label>
            </div>

            <div class="ori-item" style="width: 15%">
                <select v-model="oriGenreLv1" class="form-control">
                    <option v-for="lv1 in data | orderBy 'ori_id' | filtByValue 0 'ori_pid'"
                            v-bind:value="{ id:lv1.ori_id, haschild:lv1.haschild }"
                    >
                        @{{ lv1.ori_catalog }}
                    </option>
                </select>
            </div>

            <div v-if="haschild" class="ori-item" style="width: 75%">
                <originalworkschild v-if="oriGenreLv1.id == 65"
                                    :haschild="false"
                                    :pid="65"
                                    :data="data"
                                    :selected.sync="oriGenreLv2"
                ></originalworkschild>
                <originalworkschild v-else
                                    :haschild="haschild"
                                    :pid="oriGenreLv1.id"
                                    :data="data"
                                    :selected.sync="oriGenreLv2"
                ></originalworkschild>
            </div>
        </template>

        <template id="original-works-child">
            <div v-if="haschild" class="ori-item" style="width: 60%">
                <div v-for="lv2 in data | filtByValue pid 'ori_pid'">
                    <label class="ori-item" style="width:30%">@{{ lv2.ori_catalog }}</label>

                    <div class="ori-item" style="width: 70%">
                        <select v-model="selected[$index]" class="form-control">
                            <option v-for="lv3 in data | filtByValue lv2.ori_id 'ori_pid'"
                                    v-bind:value="{ 'id':lv3.ori_id }"
                            >
                                @{{ lv3.ori_catalog }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>

            <div v-if="selected[0].id == 24 || selected[0].id == 29">
                <originalworkschild :haschild="false"
                                    :pid="selected[0].id"
                                    :data="data"
                                    :selected.sync="basicData.oriGenreLv3"
                ></originalworkschild>
            </div>

            <div v-if="!haschild"
                 class="ori-item"
                 style="margin-left:15px;width: 30%"
            >
                <select v-model="selected[$index]" class="form-control">
                    <option v-for="lv2 in data | filtByValue pid 'ori_pid'"
                            v-bind:value="{ 'id':lv2.ori_id }">@{{ lv2.ori_catalog }}</option>
                </select>
            </div>
        </template>

                                                                <!-- STAFF BIGIN -->

        <h2>Staff信息</h2>

        <textformat :text.sync="sourceBox"
                    :pos="'staff'"
        ></textformat>

        <br>
        <br>

        <div class="form-group row">
            <div>
                <textarea class="form-control" rows="10" v-model="staffSource" placeholder="请输入源数据"></textarea>
            </div>
        </div>
        <button class="btn btn-primary" @click="toArray(staffSource ,'staff')">获取Staff数据</button>
        <button class="btn btn-danger" @click="staffSource = ''">清除输入框</button>

        <br>
        <br>

        <form id="staff" class="form">
            <table class="sco">
                <tr style="display:none">
                    <td>
                        防止 Lastpass 注入输入框
                        <input type="text">
                        <input type="text">
                    </td>
                </tr>
                <tr v-for="staffMember in staffMembers"
                    track-by="$index"
                    v-bind:class="{ zebrarow:$index % 2 }"
                >
                    <td style="width:10%">
                        <input v-model="staffMember.id" type="text" disabled="disabled" placeholder="ID">
                    </td>
                    <td style="width:10%">
                        <input v-model="staffMember.staffNameID" type="text" disabled="disabled" placeholder="岗位ID">
                    </td>
                    <td style="width:20%">
                        <input v-model="staffMember.staffNameOri" type="text" placeholder="岗位名称（原）">
                        <input v-model="staffMember.staffNameZhCN" type="text" placeholder="岗位名称（中）">
                    </td>
                    <td style="width:20%">
                        <input style="width:50%" v-model="staffMember.staffMemberID" type="text" disabled="disabled" placeholder="人员ID">
                        <input v-model="staffMember.staffMemberName" type="text" placeholder="人员名称">
                    </td>
                    <td style="width:20%">
                        <input style="width:50%" v-model="staffMember.staffBelongsToID" type="text" disabled="disabled" placeholder="所属公司ID">
                        <input v-model="staffMember.staffBelongsToName" type="text" placeholder="所属公司名称">
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
            </table>
        </form>

                                                                <!-- STAFF END -->

        <br>

                                                                <!-- CAST BEGIN -->
        <h2>Cast信息</h2>

        <textformat :text.sync="castSource" :pos="'cast'"></textformat>

        <div class="form-group row">
            <div>
                <textarea class="form-control" rows="10" v-model="castSource" placeholder="请输入源数据"></textarea>
            </div>
        </div>

        <button class="btn btn-primary" @click="toArray(castSource ,'cast')">获取Cast数据</button>
        <button class="btn btn-danger" @click="castSource = ''">清除输入框</button>

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
                    <td style="width:10%">
                        <input v-model="castMember.charaID" type="text" disabled="disabled" placeholder="角色ID">
                    </td>
                    <td style="width:30%">
                        <input v-model="castMember.charaNameOri" type="text" placeholder="角色名称（原）">
                    </td>
                    <td style="width:10%">
                        <input v-model="castMember.cvID" type="text" disabled="disabled" placeholder="演员ID">
                    </td>
                    <td style="width:20%">
                        <input v-model="castMember.cvNameOri" type="text" placeholder="演员名称">
                    </td>
                    <td>
                        <togglebutton :toggle.sync="castMember.isImportant"
                                      :style="'glyphicon glyphicon-star'"
                                      :content=""
                        ></span>'"
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

                                                                <!-- CAST END -->

                                                                <!-- ONAIR BEGIN -->

        <h2>播放信息</h2>

        <textarea class="form-control" id="" cols="30" rows="10"
                  v-model="onairDataInput"
        ></textarea>

        <br>

        <button class="btn btn-primary"
        @click="onairDataFormat"
                                                                >
                                                                格式化日期
        </button>

        <button class="btn btn-primary" @click="onairDataInput = ''">清除数据</button>

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
                    <td style="width:8%">
                        <input v-model="datetime.tvID" type="text" placeholder="电视台ID"/>
                    </td>
                    <td style="width:19%">
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

                                                                <!-- ONAIR END -->

        <template id="text-format">
            <button class="btn btn-primary"
            @click="format(text, pos, 'separator')"
                  >
                  转换分隔符
            </button>
            <button class="btn btn-primary"
            @click="format(text, pos, 'cleanHTML')"
                  >
                  清除HTML标签
            </button>
            <button class="btn btn-primary"
            @click="format(text, pos, 'oddEven')"
                  >
                  奇偶行合并
            </button>
            <button class="btn btn-primary"
            @click="format(text, pos, 'wikiCV')"
                  v-if="pos == 'cast'"
                  >
                  维基百科声优
            </button>
        </template>

        <button class="btn btn-primary" @click="outputData">转换为 JSON</button>
        <br>
        <br>

        <div class="panel panel-default">
            <p class="panel-title panel-heading">JSON输出</p>

            <div class="panel-body">
                <pre>@{{ basicData | json }}</pre>
            </div>
        </div>

        <template id="toggle-button">
            <button @click="toggle = !toggle" type="button"
                          v-bind:class="toggle?'btn-primary':'btn-default'"
                          class="btn btn-xs"
                          >
            <span class="@{{ style }}">@{{ content }}</span>
            <input type="checkbox" v-model="toggle" class="hidden"/>
            </button>
        </template>

        <template id="row-control">
            <div class="@{{ style }}">
                <div class="col-xs-3">
                    <button @click="rowUp(arr,index)" type="button" class="btn btn-default btn-xs">
                    <span class="glyphicon glyphicon-arrow-up"></span>
                    </button>
                </div>
                <div class="col-xs-3">
                    <button @click="rowDown(arr,index)" type="button" class="btn btn-default btn-xs">
                    <span class="glyphicon glyphicon-arrow-down"></span>
                    </button>
                </div>
                <div class="col-xs-3">
                    <button @click="removeRow(arr,index)" type="button" class="btn btn-danger btn-xs">
                    <span class="glyphicon glyphicon-remove"></span>
                    </button>
                </div>
                <div class="col-xs-3">
                    <button @click="addRow(arr,index)" type="button" class="btn btn-success btn-xs">
                    <span class="glyphicon glyphicon-plus"></span>
                    </button>
                </div>
            </div>
        </template>
    </div>
@stop