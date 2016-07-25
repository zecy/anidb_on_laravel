<h2>Cast信息</h2>

<div id="cast">
    <fieldset>
        <div class="textformatbox">
            <textformat :text.sync="castSource" :pos="'cast'"></textformat>

            <textarea class="form-control" rows="10" v-model="castSource" placeholder="请输入源数据"></textarea>

            <button class="btn btn-primary" v-on:click="toArray(castSource ,'cast')">获取Cast数据</button>
            <button class="btn btn-danger" v-on:click="castSource = ''">清空输入框</button>
            <button class="btn btn-danger" v-on:click="resetData('cast')">清空当前Cast列表</button>
        </div>

        <form id="cast-form" class="form">
            <table class="sco">
                <thead>
                <tr>
                    <th style="width: 50px;">ID</th>
                    <th style="width: 300px;">角色名称</th>
                    <th style="width: 200px;">演员名称</th>
                    <th style="width: 30px;">★</th>
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
                <tr v-for="castMember in castMembers"
                    track-by="$index"
                    v-bind:class="{ zebrarow:$index % 2 }"
                >
                    <td>
                        <input v-model="castMember.id" type="text" disabled="disabled" placeholder="ID">
                    </td>
                    {{--
                    <td style="width:10%">
                        <input v-model="castMember.charaID" type="text" disabled="disabled" placeholder="角色ID">
                    </td>
                    --}}
                    <td>
                        <input v-model="castMember.charaNameOri" type="text" placeholder="角色名称（原）">
                    </td>
                    {{--
                    <td style="width:10%">
                        <input v-model="castMember.cvID" type="text" disabled="disabled" placeholder="演员ID">
                    </td>
                    --}}
                    <td>
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
                        <rowcontrol :arr.sync="castMembers"
                                    :index.sync="$index"
                                    pos="cast"
                        ></rowcontrol>
                    </td>
                </tr>
                </tbody>
            </table>
            <formtotop form_id="cast-form"
                       :view_top.sync="scrolled"
            ></formtotop>
        </form>
    </fieldset>
</div>

<createeditbutton
        :create_condition="castMembers[0].id == 0"
        :edit_condition="castMembers[0].id != 0"
        :pos="'cast'"
        :anime_id="basicData.id.value"
        :is_complete.sync="castMembers"
></createeditbutton>
