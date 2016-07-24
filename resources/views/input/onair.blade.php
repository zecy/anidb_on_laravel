<h2>播放信息</h2>

<div id="onair">
    <fieldset>
        <div class="textformatbox">
            <textarea class="form-control" cols="30" rows="10" v-model="onairSource" placeholder="请输入源数据"></textarea>

            <button class="btn btn-primary" v-on:click="toArray(onairSource, 'onair')">格式化日期</button>

            <button class="btn btn-danger" v-on:click="onairSource = ''">清空输入框</button>

            <button class="btn btn-danger" v-on:click="resetData('onair')">清空当前播放信息列表</button>
        </div>

        <form class="form">
            <table class="sco">
                <thead>
                <tr>
                    <th style="width: 50px">ID</th>
                    <th style="width: 170px">电视台名称</th>
                    <th style="width: 90px;">播出日期</th>
                    <th style="width: 80px;">星期</th>
                    <th style="width: 70px;">时间</th>
                    <th style="width: 150px">节目信息</th>
                    <th style="width: 30px">$</th>
                    <th style="width: 100px">行操作</th>
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
                <tr v-for="datetime in onair"
                    track-by="$index"
                    v-bind:class="{ zebrarow:$index % 2 }"
                >
                    <td>
                        <input v-model="datetime.id" type="text" placeholder="ID"/>
                    </td>
                    {{--
                    <td style="width:8%">
                        <input v-model="datetime.tvID" type="text" placeholder="电视台ID"/>
                    </td>
                    --}}
                    <td>
                        <input v-model="datetime.tvName" type="text" placeholder="电视台"/>
                    </td>
                    <td>
                        <input v-model="datetime.startDate" type="text" placeholder="开始"/>
                        <span>〜</span>
                        <input v-model="datetime.endDate" type="text" placeholder="结束"/>
                    </td>
                    <td>
                        <div>
                            <select v-model="datetime.weekday">
                                <option value="1">一</option>
                                <option value="2">二</option>
                                <option value="3">三</option>
                                <option value="4">四</option>
                                <option value="5">五</option>
                                <option value="6">六</option>
                                <option value="0">日</option>
                                <option value="7">工作日</option>
                            </select>
                        </div>
                    </td>
                    <td>
                        <input v-model="datetime.startTime" type="text" placeholder="开始"/>
                        <span>〜</span>
                        <input v-model="datetime.endTime" type="text" placeholder="结束"/>
                    </td>
                    <td>
                        <input v-model="datetime.tvColumn" type="text" placeholder="播放栏目"/>
                        <input v-model="datetime.description" type="text" placeholder="备注"/>
                    </td>
                    <td>
                        <togglebutton :toggle.sync="datetime.isProduction"
                                      :style="'glyphicon glyphicon-usd'"
                                      :content=""
                        >
                        </togglebutton>
                    </td>
                    <td>
                        <rowcontrol :arr.sync="onair"
                                    :index.sync="$index"
                                    :style="row"
                                    :pos="'onair'"
                        ></rowcontrol>
                    </td>
                </tr>
                </tbody>
            </table>
        </form>
    </fieldset>
</div>

<createeditbutton
        :create_condition="onair[0].id == 0"
        :edit_condition="onair[0].id != 0"
        :pos="'onair'"
        :anime_id="basicData.id.value"
        :is_complete.sync="onair"
></createeditbutton>

