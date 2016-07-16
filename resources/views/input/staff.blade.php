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
        <thead>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
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
        </tbody>
        {{-- 一个完整的 STAFF 条目 --}}
        <tbody v-for="staffMember in staffMembers"
               track-by="$index"
               v-bind:class="{ zebrarow:$index % 2 }"
        >
        {{-- STAFF 父项目 --}}
        <tr>
            <td colspan="4"
                style="width: 80%">
                <staffrow
                        :controlledarr.sync="staffMembers"
                        :staffitem.sync="staffMember"
                        :lv="staffMember.lv"
                        :index.sync="$index"
                ></staffrow>
            </td>
            <td style="width:3%">
                <togglebutton :toggle.sync="staffMember.isImportant"
                              :style="'glyphicon glyphicon-star'"
                              :content=""
                ></togglebutton>
            </td>
            <td style="width:3%">
                <button type="button"
                        v-if="staffMember.lv == 0"
                        class="btn btn-xs btn-default"
                        v-on:click="addChild(staffMembers, $index)"
                >
                    <span class="glyphicon glyphicon-th-list"></span>
                </button>
            </td>
            <td style="width:14%">
                <rowcontrol :style="'snc-row-control'"
                            :arr.sync="staffMembers"
                            :index.sync="$index"
                            :pos="'staff'"
                ></rowcontrol>
            </td>
        </tr>
        {{-- STAFF 子项目 --}}
        <tr v-if="staffMember.haschild">
            <td colspan="7">子项目</td>
        </tr>
        <tr v-if="staffMember.haschild"
            v-for="staffChild in staffMember.child"
            track-by="$index"
            class="staff-child"
        >
            <td style="width:3%"></td>
            <td colspan="4"
                style="width:80%">
                <staffrow
                        :controlledarr.sync="staffMember.child"
                        :staffitem.sync="staffChild"
                        :lv="staffChild.lv"
                        :index.sync="$index"
                ></staffrow>
            </td>
            <td style="width:3%">
                <togglebutton :toggle.sync="staffChild.isImportant"
                              :style="'glyphicon glyphicon-star'"
                              :content=""
                ></togglebutton>
            </td>
            <td style="width:12%">
                <rowcontrol :style="'snc-row-control'"
                            :arr.sync="staffMember.child"
                            :index.sync="$index"
                            :pos="'staff'"
                ></rowcontrol>
            </td>
        </tr>
        <tr v-if="staffMember.haschild">
            <td colspan="7">子项目</td>
        </tr>
        </tbody>
    </table>
</form>

<createeditbutton
        :create_condition="staffMembers[0].id == 0"
        :edit_condition="staffMembers[0].id != 0"
        :pos="'staff'"
        :anime_id="basicData.id.value"
        :is_complete.sync="staffMembers"
></createeditbutton>
