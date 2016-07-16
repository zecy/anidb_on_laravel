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
                            :pos = "'cast'"
                ></rowcontrol>
            </td>
        </tr>
    </table>
</form>

<br>

<createeditbutton
        :create_condition="castMembers[0].id == 0"
        :edit_condition="castMembers[0].id != 0"
        :pos="'cast'"
        :anime_id="basicData.id.value"
        :is_complete.sync="castMembers"
></createeditbutton>

