<h2>播放信息</h2>

<textarea class="form-control" id="" cols="30" rows="10"
          v-model="onairSource"
></textarea>

<br>

<button class="btn btn-primary" v-on:click="toArray(onairSource, 'onair')">格式化日期</button>

<button class="btn btn-primary" v-on:click="onairSource = ''">清除数据</button>

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
                        <option value="7">工作日</option>
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
                            :pos="'onair'"
                ></rowcontrol>
            </td>
        </tr>
        </tbody>
    </table>
</form>

<createeditbutton
        :create_condition="onair[0].id == 0"
        :edit_condition="onair[0].id != 0"
        :pos="'onair'"
        :anime_id="basicData.id.value"
        :is_complete.sync="onair"
></createeditbutton>

