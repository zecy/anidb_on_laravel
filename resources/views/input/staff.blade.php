<h2>Staff信息</h2>

<div id="staff">
    <fieldset>
        <div class="textformatbox">

            <textformat :text.sync="staffSource"
                        :pos="'staff'"
            ></textformat>
            <textarea class="form-control" rows="10" v-model="staffSource" placeholder="请输入源数据"></textarea>

            <button class="btn btn-primary" v-on:click="toArray(staffSource ,'staff')">获取Staff数据</button>
            <button class="btn btn-danger" v-on:click="staffSource = ''">清空输入框</button>
            <button class="btn btn-danger" v-on:click="resetData('staff')">清空当前Staff列表</button>
        </div>

        <form id="staff-form" class="form">
            <table class="sco">
                <thead>
                    <tr>
                        <th style="width: 50px;">ID</th>
                        <th style="width: 250px;">岗位名称</th>
                        <th style="width: 100px;">人员名称</th>
                        <th style="width: 100px;">所属公司</th>
                        <th style="width: 30px;">★</th>
                        <th style="width: 50px;">子项目</th>
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
                </tbody>
                {{-- 一个完整的 STAFF 条目 --}}
                <tbody v-for="staffMember in staffMembers"
                       track-by="$index"
                       v-bind:class="{ zebrarow:$index % 2 }"
                >
                {{-- STAFF 父项目 --}}
                <tr is="staffrow"
                    :controlledarr.sync="staffMembers"
                    :staffitem.sync="staffMember"
                    :lv="staffMember.lv"
                    :index.sync="$index"
                >
                </tr>
                {{-- STAFF 子项目 --}}
                <tr v-if="staffMember.haschild"
                    class="staff-child"
                >
                    <th>子<br>项<br>目</th>
                    <td colspan="6">
                        <table>
                            <thead>
                            <tr>
                                <th style="width: 50px">ID</th>
                                <th style="width: 250px">岗位名称</th>
                                <th style="width: 100px">人员名称</th>
                                <th style="width: 100px">所属公司</th>
                                <th style="width: 30px">★</th>
                                <th style="width: 100px">行操作</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr is="staffrow"
                                    v-bind:class="{ zebrarow:$index % 2 }"
                                    v-for="staffChild in staffMember.child"
                                    :controlledarr.sync="staffMember.child"
                                    :staffitem.sync="staffChild"
                                    :lv="staffChild.lv"
                                    :index.sync="$index"
                                    track-by="$index"
                                >
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tbody>
            </table>
            <formtotop form_id="staff-form"
                       :view_top.sync="scrolled"
            ></formtotop>
        </form>
    </fieldset>
</div>

<createeditbutton
        :create_condition="staffMembers[0].id == 0"
        :edit_condition="staffMembers[0].id != 0"
        :pos="'staff'"
        :anime_id="basicData.id.value"
        :is_complete.sync="staffMembers"
></createeditbutton>
