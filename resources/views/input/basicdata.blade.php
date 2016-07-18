<h2>主要信息</h2>

<searchanime :is_complete="basicData.id.value"
></searchanime>

<form id="maininfo" class="form form-horizontal">
    <fieldset disabled="@{{ processing }}">
        <table class="table">
            <tr style="display: none">
                <td>
                    禁止 Lastpass 注入
                    <input type="text">
                    <input type="text">
                </td>
            </tr>
            {{-- 动画ID --}}
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
            {{-- 标题 --}}
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
                                    :pos="'title'"
                                    class="title-rowcontrol"
                        ></rowcontrol>
                    </div>
                </td>
            </tr>
            {{-- 原作类型 --}}
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
            {{-- 首播媒体 --}}
            <tr>
                <td>
                    <div style="width:10%">
                        <label>首播媒体</label>
                    </div>
                    <div style="width: 15%">
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
                        <input type="text" v-model="basicData.eps_oa.value" placeholder="电视">
                    </div>
                    <div style="width: 8%">
                        <input type="text" v-model="basicData.eps_soft.value" placeholder="圆盘">
                    </div>
                </td>
            </tr>
            {{-- 续作 --}}
            <tr>
                <td>
                    <div style="width:10%">
                        <label>
                            播放季度
                        </label>
                    </div>

                    <div style="width: 11%">
                        <div class="input-group">
                            <input type="text"
                                   style="border-top-right-radius: 0;
                                                  border-bottom-right-radius:0;
                                                  border-right: 0;
                                                  text-align: right;
                                                  padding-right: 0;
                                                  "
                                   v-model="basicData.oa_year.value"
                            >
                            <div class="input-group-addon"
                                 style="background-color: transparent;
                                                border-left: 0 solid transparent;
                                                padding-left: 0.25em
                                               "
                                 disabled
                            >
                                年
                            </div>
                        </div>
                    </div>

                    <div style="width: 10%">
                        <select v-model="basicData.oa_season.value">
                            <option value="1">1月</option>
                            <option value="4">4月</option>
                            <option value="7">7月</option>
                            <option value="10">10月</option>
                        </select>
                    </div>

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

                    <div style="width: 20%">
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
            {{-- LINK --}}
            <tr v-for="link in basicData.links" track-by="$index">
                <td>
                    <div style="width: 18%">
                        <select v-model="link.class">
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
                                    :pos="'link'"
                        ></rowcontrol>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <describox
                            :descri_label="basicData.description.label"
                            :descri_value.sync="basicData.description.value"
                            :anime_id="basicData.id.value"
                            :processing="processing"
                    ></describox>
                </td>
            </tr>
        </table>
    </fieldset>
</form>

<createeditbutton
        :create_condition="basicData.id.value == 0"
        :edit_condition="basicData.id.value != 0"
        :pos="'basicData'"
        :anime_id="basicData.id.value"
        :is_complete.sync="basicData"
></createeditbutton>

