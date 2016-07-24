
<searchanime :is_complete="basicData.id.value"
></searchanime>

<h2>基本信息</h2>
<form id="maininfo" class="form form-horizontal">
    <fieldset disabled="@{{ processing }}">
        <table>
            <tr style="display: none">
                <td>
                    禁止 Lastpass 注入
                    <input type="text">
                    <input type="text">
                </td>
            </tr>
            {{-- 系列ID, 系列标题, 系列属性 --}}
            <tr>
                <td>
                    <label>系列&ensp;ID</label>

                    <div class="input-id">
                        <input type="text" v-model="basicData.seriesID.value" disabled>
                    </div>

                    <label>系列标题</label>

                    <div class="input-text">
                        <input type="text" v-model="basicData.seriesTitle.value">
                    </div>

                    <label>系列属性</label>

                    <div class="input-text">
                        <input type="text" disabled>
                    </div>

                </td>
            </tr>

            {{-- 动画ID, 简称, 动画情况 --}}
            <tr>
                <td>
                    <label>动画&ensp;ID</label>

                    <div class="input-id">
                        <input type="text" v-model="basicData.id.value" disabled>
                    </div>

                    <label>简&#x3000;&#x3000;称</label>

                    <div class="input-text">
                        <input type="text" v-model="basicData.abbr.value">
                    </div>

                    <label>动画情况</label>

                    <div class="toggle-button">
                        <togglebutton :toggle.sync="basicData.isSequel.value"
                                      content="续作"
                        ></togglebutton>
                    </div>

                    <div class="toggle-button">
                        <togglebutton :toggle.sync="basicData.isEnd.value"
                                      content="完结"
                        ></togglebutton>
                    </div>

                    <div class="toggle-button">
                        <togglebutton :toggle.sync="basicData.isCounted.value"
                                      content="纳入统计"
                        ></togglebutton>
                    </div>
                </td>
            </tr>

            <tr class="hr">
                <td></td>
            </tr>

            {{-- 动画标题 --}}
            <tr class="anime-title"
                v-for="title in basicData.title" track-by="$index">
                <td>
                    <div style="width: 300px">
                        <input type="text"
                               v-model="title.value"
                               placeholder="@{{ title.label }}"
                        >
                    </div>
                    <div style="width: 100px">
                        <select v-model="title.lang">
                            @foreach( $transLangs as $lang )
                                <option value="{{ $lang->content }}">{{ $lang->comment }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="is-official" style="width: 24px">
                        <togglebutton :toggle.sync="title.isOfficial"
                                      :style="'glyphicon glyphicon-ok'"
                                      :content=""
                        ></togglebutton>
                    </div>
                    <div style="width: 170px">
                        <input v-model="title.comment" type="text" placeholder="备注">
                    </div>
                    <div style="width: 124px;margin:0">
                        <rowcontrol :arr.sync="basicData.title"
                                    :index.sync="$index"
                                    :pos="'title'"
                                    class="title-rowcontrol"
                        ></rowcontrol>
                    </div>
                </td>
            </tr>

            <tr class="hr">
                <td></td>
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

            <tr class="hr">
                <td></td>
            </tr>

            {{-- 首播季度, 首播媒体, 系列长度, 集数 --}}
            <tr>
                <td>
                    {{-- 播放季度 --}}
                    <div id="oa-season">
                        <label>
                            首播季度
                        </label>

                        <div class="input" >
                            <input type="text" v-model="basicData.oa_year.value">
                            <span>年</span>
                        </div>

                        <div>
                            <select v-model="basicData.oa_season.value">
                                <option value="1">1月</option>
                                <option value="4">4月</option>
                                <option value="7">7月</option>
                                <option value="10">10月</option>
                            </select>
                        </div>
                    </div>

                    {{-- 首播媒体 --}}
                    <label>首播媒体</label>
                    <div class="input-id">
                        <select v-model="basicData.premiereMedia.value">
                            @foreach($premiereMedia as $pm)
                                <option value="{{ $pm->content }}">{{ $pm->comment }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 系列长度 --}}
                    <label>系列长度</label>

                    <div class="input-id">
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

                    {{-- 播出集数 --}}
                    <label>集&#x3000;&#x3000;数</label>
                    
                    <div style="width:88px">
                        <input type="text">
                    </div>
                </td>
            </tr>
            {{-- 时间规格, 首播时段, 总集数 --}}
            <tr>
                <td>

                    <label>时间规格</label>
                    <div style="width: 170px">
                        <select v-model="basicData.duration.value">
                            @foreach($animeDurationFormat as $adf)
                                <option value="{{ $adf->content }}">{{ $adf->comment }}</option>
                            @endforeach
                        </select>
                    </div>

                    <label>首播时段</label>

                    <div style="width: 270px">
                        <div class="toggle-button"
                             v-for="time in [{'label':'晨间档', 'value':'morning'},{'label':'日间档', 'value':'daytime'},{'label':'黄金档', 'value':'prime'},{'label':'深夜档', 'value':'midnight'}]">
                            <button class="btn btn-xs"
                                    type="button"
                                    v-on:click="basicData.oa_time.value = time.value"
                                    v-bind:class="basicData.oa_time.value === time.value ? 'btn-primary' : 'btn-default'"
                            >
                                <span>@{{ time.label }}</span>
                                <input type="radio" value="@{{ time.value }}" v-model="basicData.oa_time.value" class="hidden">
                            </button>
                        </div>
                    </div>

                    <label>总&nbsp;&nbsp;集&nbsp;&nbsp;数</label>
                    <div style="width: 88px;">
                        <input type="text" v-model="basicData.eps_soft.value" placeholder="圆盘">
                    </div>
                </td>
            </tr>

            <tr class="hr">
                <td></td>
            </tr>

            {{-- LINK --}}
            <tr class="anime-link" v-for="link in basicData.links" track-by="$index">
                <td>
                    <div style="width: 100px">
                        <select v-model="link.class">
                            @foreach( $links as $link )
                                <option value="{{ $link->content }}">{{ $link->comment }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div style="width: 300px">
                        <input type="text"
                               v-model="link.value"
                               placeholder="网站地址"
                        >
                    </div>

                    <div class="is-official" style="width: 24px">
                        <togglebutton :toggle.sync="link.isOfficial"
                                      :style="'glyphicon glyphicon-ok'"
                                      :content=""
                        ></togglebutton>
                    </div>

                    <div style="width: 170px">
                        <input type="text" v-model="link.comment" placeholder="备注">
                    </div>

                    <div style="width: 124px;margin: 0;">
                        <rowcontrol :arr.sync="basicData.links"
                                    :index.sync="$index"
                                    :pos="'link'"
                        ></rowcontrol>
                    </div>
                </td>
            </tr>

            <tr class="hr">
                <td></td>
            </tr>

            {{-- 介绍 --}}
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
