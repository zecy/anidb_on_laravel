/**
 * Created by zecy on 16/02/29.
 * Use For Vue.JS
 */


/**
 * 格式化内容
 *
 */

/**
 * 格式化 Staff & Cast 内容
 *
 * 目标文本形式:
 * 1、维基百科: 原作 - 矢立肇、富野由悠季
 * 2、动画官网: 原作 [\t :：・] サンリオ・セガトイズ
 * 全部统一为「 - 」分隔, 以使用转换函数
 *
 * 3、动画官网: プロデューサー
 *             植月 幹夫(ABC)
 *             遠藤 里紗(ADK)
 *             内藤 圭祐
 *
 * 奇偶行处理, 格式化为名字行缩进, 缩进最后再转换为「 - 」分隔
 *
 * 4、源代码:
 *  <ul>
 *      <li>
 *          <dl>
 *              <dt>
 *                  原作
 *              </dt>
 *              <dd>
 *                  バンダイナムコエンターテインメント
 *              </dd>
 *          </dl>
 *      </li>
 *      <li>
 *          <dl>
 *              <dt>
 *                  監督・音響監督
 *              </dt>
 *              <dd>
 *                  平尾隆之
 *              </dd>
 *          </dl>
 *      </li>
 * </ul>
 * 输入岗位/角色, 人员的相应标签, 去除其他标签, 转换为标准形式
 *
 * 5、维基百科声优:
 * 乙坂 有宇
 * 声 - 内山昂輝
 * balabalabalbalabalabalbnala
 * 友利 奈緒
 * 声 - 佐倉綾音
 * balabalabalbalabalabalbnala
 *
 * 已「声 - 」为标记, 保留角色名称和声优, 最后转换为标准形式
 *
 * 处理完成格式:
 *
 * 原作 - 矢立肇、富野由悠季
 *
 * 友利奈緒 - 佐倉綾音
 *
 * */
var staffAndCastFormat = function (str) {

    var otherSeparator, oddEven, fromSource, wikiCV;

    otherSeparator = function (str) {
        str = str.replace(/[\t :：・]+/g, ' - ');
        return str;
    };

    oddEven = function (str) {
        var res   = [];
        var lines = str.split('\n');

        for (var i = 0; i < lines.length; i += 2) {
            res.push([lines[i], lines[i + 1]].join(' - '))
        }

        return res.join('\n')
    };

    //TODO: 两列合并

    fromSource = function (str) {
        str = str.replace(/ +/g, '');
        str = str.replace(/<.*?>\n*/g, '');
        str = str.replace(/\n$/, '');

        return oddEven(str);
    };

    wikiCV = function (str) {
        var lines = [], res = [];
        str       = str.replace(/ +/g, '');
        lines     = str.split('\n');
        for (var i = 0; i < lines.length; i++) {
            if (lines[i].indexOf('声-') != -1) {
                var charaName  = lines[i - 1].replace(/（.*）/g, '');
                var charaVoice = lines[i].substr(2);
                res.push(
                    [charaName, charaVoice].join(' - ')
                )
            }
        }
        return res.join('\n')
    };

    return {
        'otherSeparator': otherSeparator(str),
        'oddEven':        oddEven(str),
        'fromSource':     fromSource(str),
        'wikiCV':         wikiCV(str)
    }
};

/**
 * Convert a formated document to JSON
 *
 * Formated Doc :
 *
 *   原作 - 矢立肇、富野由悠季
 *   企划、出品 - 创通、SUNRISE
 *   监督 - 长井龙雪
 *   编剧 - 冈田磨里
 *   角色设定、总作画指导 - 千叶道德
 *   动画制作 - SUNRISE
 *
 * Array :
 *
 *   [
 *      ['原作','矢立肇',false],
 *      ['原作','富野由悠季',false],
 *      ['企划','SUNRISE',false],
 *      ['企划','创通',false],
 *      ['出品','创通',false],
 *      ['出品','SUNRISE',false],
 *      ['监督','长井龙雪',false],
 *      ['编剧','冈田磨里',false],
 *      ['角色设定','千叶道德',false],
 *      ['总作画指导','千叶道德',false],
 *      ['动画制作','SUNRISE',false]
 *   ]
 *
 **/
var formatedTextToArray = function (str) {

    var arr          = [];
    var splitByLines = str.split("\n");
    var separator    = "、";

    for (var i = 0; i < splitByLines.length; i++) {

        var jobName = [];

        if (splitByLines[i].indexOf(" - ") != -1) {

            jobName = splitByLines[i].split(" - ");

        } else {
            alert("** 格式有误，请进行检查 **" + "\
                \n" + (i - 1) + " | " + splitByLines[i - 1] + "\
                \n" + i + " | " + splitByLines[i] + "\
                \n" + (i + 1) + " | " + splitByLines[i + 1]);
            return
        }
        var isJobs  = "";
        var isNames = "";

        isJobs  = (jobName[0].indexOf(separator) != -1);
        isNames = (jobName[1].indexOf(separator) != -1);

        var jobs, names, jobNameArr = [];
        var j, n                    = 0;

        /* 企划、出品 - 创通、SUNRISE */
        if (isJobs && isNames) {
            jobs  = jobName[0].split(separator);
            names = jobName[1].split(separator);
            for (j = 0; j < jobs.length; j++) {
                for (n = 0; n < names.length; n++) {
                    jobNameArr = [jobs[j], names[n]];
                    arr.push(jobNameArr);
                }
            }
        }
        /* splitByLines[i] == "角色设定、总作画指导 - 千叶道德" */
        else if (isJobs && !isNames) {
            jobs = jobName[0].split(separator); // jobName[0] == ['角色设定、总作画指导']
                                                // jobs       == ['角色设定','总作画指导']
            for (j = 0; j < jobs.length; j++) {
                jobNameArr = [jobs[j], jobName[1]];
                /**
                 * jobNameArr[0] = jobs[0]    == '角色设定'
                 * jobNameArr[1] = jobName[1] == '千叶道德'
                 * jobNameArr    == ['角色设定','千叶道德']
                 **/
                arr.push(jobNameArr); // arr = [...,['角色设定','千叶道德'],...]
            }
        }
        /* 原作 - 矢立肇、富野由悠季 */
        else if (!isJobs && isNames) {
            names = jobName[1].split(separator);
            for (n = 0; n < names.length; n++) {
                jobNameArr = [jobName[0], names[n]];
                arr.push(jobNameArr);
            }
        }
        /* 监督 - 长井龙雪 */
        else {
            jobNameArr = [jobName[0], jobName[1]];
            arr.push(jobNameArr)
        }
    }
    return arr
};

/**
 * Convert the formated onair data to array
 *
 * Formated text :
 *
 * 毎日放送          , 2010年10月8日 - 12月24日       , 金曜 1:25 - 1:55（木曜深夜）
 * TBSテレビ         , 2010年10月9日 - 12月25日       , 土曜 1:55 - 2:25（金曜深夜）
 * 中部日本放送       , 2010年10月14日 - 2011年1月6日  , 木曜 2:00 - 2:30（水曜深夜）
 * ニコニコチャンネル                ,                   木曜 3:15 更新（水曜深夜）   <== 补齐分隔符
 * バンダイチャンネル  , 2010年10月 -                  , 金曜 15:00 更新
 * ShowTime                                                                      <== 继承上条内容
 * AT-X              , 2010年12月10日 - 2011年2月25日 , 金曜 9:30 - 10:00
 *
 * 2011年7月5日 - 9月20日   , 火曜 2:00 - 2:30（月曜深夜） , テレビ東京              <== 自适应两种不同结构
 * 2011年7月6日 - 9月21日   , 水曜 2:03 - 2:33（火曜深夜） ,  テレビせとうち
 *                           水曜 2:30 - 3:00（火曜深夜） , テレビ北海道       <== 补齐分隔符
 * 2011年7月8日 - 9月23日   , 金曜 3:00 - 3:30（木曜深夜） , テレビ愛知
 * 2011年7月10日 - 9月25日  , 日曜 2:55 - 3:25（土曜深夜） , テレビ大阪
 * 2011年7月11日 - 9月26日  , 月曜 11:00 - 11:30          , AT-X
 *
 * Array :
 *
 *   [
 *     {
 *       animeID:       '',
 *       oaID:          '',
 *       tvID:          '',
 *       tvName:        '毎日放送',
 *       startDate:     '2010/10/8',
 *       endDate:       '2010/12/24',
 *       startTime:     '1:25',
 *       endTime:       '1:55',
 *       weekday:       '5',
 *       tvColumn:      '',
 *       isProduction:  false,
 *       description:   ''
 *     },
 *
 *     {
 *       animeID:       '',
 *       oaID:          '',
 *       tvID:          '',
 *       tvName:        'ニコニコチャンネル',
 *       startDate:     '2010/10/14',
 *       endDate:       '2011/1/6',
 *       startTime:     '3:15',
 *       endTime:       '1:55',
 *       weekday:       '4',
 *       tvColumn:      '',
 *       isProduction:  false,
 *       description:   ''
 *     },
 *   ]
 *
 **/
var onairFormatedTextToArray = function (str, animeID) {

    var arr = [], rows = [];
    var tvName,
        oaDate, startDate, startYear, startMonth, startDay, endDate, endYear, endMonth, endDay,
        weekday,
        oaTime, startTime, endTime;

    rows = str.split('\n');

    for (var i = 0; i < rows.length; i++) {
        var cols = [];
        cols     = rows[i].split(',');

        for (var j = 0; j < cols.length; j++) {
            var col = "";
            col     = cols[j];
            if (col.indexOf('年') != -1) {
                oaDate = col ? col : oaDate;  //取得日期      2010年10月8日 - 12月24日
                                              //如果当前记录日期为空, 保留上一条记录的日期
            } else if (col.indexOf(':') != -1) {
                oaTime = col ? col : oaTime;  //取得时间      金曜 1:25 - 1:55（木曜深夜）
                //如果当前记录时间为空, 保留上一条记录的时间
            } else {
                tvName = col ? col : "";      //取得电视台名字 毎日放送
            }
        }

        // 拆分年月日
        try {
            startYear  = oaDate.match(/\d{4}(?=年)/g)[0];
            startMonth = oaDate.match(/\d{1,2}(?=月)/g)[0];
            startDay   = oaDate.match(/\d{1,2}(?=日)/g)[0];
            endYear    = oaDate.match(/\d{4}(?=年)/g)[1] ? oaDate.match(/\d{4}(?=年)/g)[1] : startYear;
            endMonth   = oaDate.match(/\d{1,2}(?=月)/g)[1] ? oaDate.match(/\d{1,2}(?=月)/g)[1] : "";
            endDay     = oaDate.match(/\d{1,2}(?=日)/g)[1] ? oaDate.match(/\d{1,2}(?=日)/g)[1] : "";

            startDate = startYear + '/' + startMonth + '/' + startDay;
            if (endMonth == "" || endDay == "") {
                endDate = "";
            } else {
                endDate = endYear + '/' + endMonth + '/' + endDay;
            }
        }
        catch (err) {
            alert('第 ' + i + ' 条资料日期格式存在错误，请进行检查。');
            break;
        }

        // 拆分播放时间
        try {
            startTime = oaTime.match(/\d{1,2}:\d{1,2}/g)[0];
            endTime   = oaTime.match(/\d{1,2}:\d{1,2}/g)[1] ? oaTime.match(/\d{1,2}:\d{1,2}/g)[1] : "";
        }
        catch (err) {
            alert('第 ' + i + ' 条资料时间格式存在错误，请进行检查。');
            break;
        }

        weekday = new Date(startDate).getDay();

        arr.push({
            'animeID':      animeID,
            'id':         0,
            'tvID':         '',
            'tvName':       tvName,
            'startDate':    startDate,
            'endDate':      endDate,
            'startTime':    startTime,
            'endTime':      endTime,
            'weekday':      weekday,
            'tvColumn':     '',
            'isProduction': false,
            'orderIndex':   0,
            'description':  ''
        });
    }

    return arr;
};

/** VUE.jS **/

// 打开 debug 模式
Vue.config.debug = true;

/** 组件 **/

Vue.component('basicinput', {
    template: '#basic-input',
    props:    ['item']
});

Vue.component('originalwork', {
    template: '#ori-work',
    props:    ['pid', 'data', 'orilist', 'multiple', 'haschild', 'lv', 'index']
    //created:  function () {
    //    this.orilist = JSON.parse(this.orilist);
    //}
});

Vue.component('rowcontrol', {
    template: '#row-control',
    props:    ['style', 'arr', 'index', 'pos'],
    methods:  {
        /* Row Up */
        rowUp: function (arr, index) {
            var i = Number(index);
            if (i == 0) {
                alert('这已经是首行，添加行请用「 + 」按钮')
            } else {
                var tmp = arr[i - 1];
                arr.splice(i - 1, 1, arr[i]);
                arr.splice(i, 1, tmp);
            }
        },
        /* Row Down */
        rowDown:   function (arr, index) {
            var i = Number(index);
            if (index == (arr.length - 1)) {
                alert('这已经是尾行，添加行请用「 + 」按钮')
            } else {
                var tmp = arr[i];
                arr.splice(i, 1, arr[i + 1]);
                arr.splice(i + 1, 1, tmp);
            }
        },
        /* Remove A Row */
        removeRow: function (pos, arr, index) {
            const i  = Number(index);
            const id = arr[i].id;
            const p  = pos;

            if (id == 0) {
                arr.splice(i, 1);
            } else {
                const r = confirm("该记录存在于数据库中\n本操作将删除从数据库删除该记录！\n是否确认删除？");
                if(r) {
                    vue.removeData(p, id, arr, i);
                }
            }
        },
        /* Add A Row */
        addRow:    function (arr, index) {
            var obj = JSON.parse(JSON.stringify(arr[index]));
            obj.id = 0;
            arr.splice(Number(index), 0, obj);
        }
    }
});

Vue.component('togglebutton', {
    template: '#toggle-button',
    props:    ['toggle', 'style', 'content']
});

Vue.component('textformat', {
    template: '#text-format',
    props:    ['text', 'pos'],
    methods:  {
        format: function (text, pos, method) {
            var res, str;
            str = staffAndCastFormat(text);

            switch (method) {
                case 'separator':
                    res = str.otherSeparator;
                    break;
                case 'oddEven':
                    res = str.oddEven;
                    break;
                case 'cleanHTML':
                    res = str.fromSource;
                    break;
                case 'wikiCV':
                    res = str.wikiCV;
                    break;
            }

            switch (pos) {
                case 'staff':
                    vue.staffSource = res;
                    break;
                case 'cast':
                    vue.castSource = res;
                    break;
            }
        }
    }
});

/** 过滤器 **/

Vue.filter('filtByValue', function (arr, search, key) {
    var res = [];
    for (var i = 0, l = arr.length; i < l; i++) {
        if (arr[i][key] == search) res.push(arr[i]);
    }
    return res;
});

/** VUE JS 实例 **/

Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#token').getAttribute('value');

/** 空数据模板 **/

var basicDataTmp = {
    'id':            {'label': '动画ID', 'value': 0},
    'seriesID':      {'label': '系列ID', 'value': 0},
    'seriesTitle':   {'label': '系列ID', 'value': ''},
    'title':         [
        {'id': 0, 'label': '官方标题', 'lang': 'jp', 'isOfficial': true, 'value': '', 'comment': '', 'orderIndex': 0},
        {'id': 0, 'label': '译名', 'lang': 'zh-cn', 'isOfficial': false, 'value': '', 'comment': '', 'orderIndex': 0}
    ],
    'abbr':          {'label': '简称', 'value': ''},
    'kur':           {'label': '长度', 'value': 1},
    'eps':           {'label': '集数', 'value': ''},
    'duration':      {'label': '时间规格', 'value': 'general'},
    'oriWorks':      [
        [{'id': 0, 'haschild': false, 'multiple': false}],
        [{'id': 0, 'haschild': false, 'multiple': false}],
        [{'id': 0, 'haschild': false, 'multiple': false}],
        [{'id': 0, 'haschild': false, 'multiple': false}]
    ],
    'premiereMedia': {'label': '首播媒体', 'value': 'tv'},
    'links':         [
        {'id': 0, 'class': 'hp', 'isOfficial': true, 'value': '', 'comment': '', 'orderIndex': 0}
    ],
    'isSequel':      {'label': '是否续作', 'value': false},
    'sequelComment': {'label': '备注', 'value': ''},
    'isEnd':         {'label': '是否完结', 'value': true},
    'isCounted':     {'label': '是否纳入统计', 'value': true},
    'story':         {'label': '故事', 'value': ''},
    'description':   {'label': '介绍', 'value': ''}
};

var staffMembersTmp = [{
    'id':                 0,
    'animeID':            0,
    'staffPostOri':       '',
    'staffPostZhCN':      '',
    'staffMemberName':    '',
    'staffBelongsToName': '',
    'isImportant':        false,
    'orderIndex':         0
}];

var castMembersTmp = [{
    'id':           0,
    'animeID':      0,
    'charaID':      '',
    'cvID':         '',
    'charaNameOri': '',
    'cvNameOri':    '',
    'isImportant':  false
}];

var onairTmp = [{
    'id':           0,
    'animeID':      0,
    'tvID':         '',
    'tvName':       '',
    'startDate':    '',
    'endDate':      '',
    'startTime':    '',
    'endTime':      '',
    'weekday':      1,
    'tvColumn':     '',
    'description':  '',
    'orderIndex':   0,
    'isProduction': false
}];

var vue = new Vue({
    el:      '#animedata',
    data: {
        'basicData':            JSON.parse(JSON.stringify(basicDataTmp)),
        'staffMembers':         JSON.parse(JSON.stringify(staffMembersTmp)),
        'castMembers':          JSON.parse(JSON.stringify(castMembersTmp)),
        'onair':                JSON.parse(JSON.stringify(onairTmp)),
        'staffSource':          '',
        'castSource':           '',
        'onairDataInput':       '',
        'animeNameSearchInput': '',
        'animeNameList':        []
    },
    watch:   {
        'basicData.oriWorks[0][0]': function (newVal, oldVal) {
            if (newVal.id != oldVal.id && oldVal.id != 0) {
                let item = this.basicData.oriWorks[1];
                // 重置空数据, 部分类型第二项无内容, 不这样重置会造成第二项始终为空
                let items = [{'id': 0, 'haschild': false, 'multiple': false, 'pid': 0}];
                if (newVal.haschild) {
                    items = [];
                    for (let i = 0; i < item.length; i++) {
                        if (item[i].pid == newVal.id) {
                            items.push(item[i])
                        }
                    }
                }
                this.basicData.oriWorks = [
                    [newVal],
                    items,
                    [{'id': '', 'haschild': false, 'multiple': false}],
                    [{'id': '', 'haschild': false, 'multiple': false}]
                ]
            }
        }
    },
    methods: {
        /*
         * Display the Anime Basic Data
         * */
        createData: function (pos) {

            //e.preventDefault();

            switch(pos) {
                case 'basicData':
                    this.$http.post('anime', {data: this.basicData}).then(function (r) {
                        if (r.status == 200) alert('录入成功!!');
                        this.showAnime(r.data.id);
                    });
                    break;
                case 'staff':
                    this.$http.post('anime/staff', {data: this.staffMembers}).then(function (r) {
                        if (r.status == 200) alert('录入成功!!');
                        this.showAnime(this.basicData.id.value);
                    });
                    break;
                case 'cast':
                    this.$http.post('anime/cast', {data: this.castMembers}).then(function (r) {
                        if (r.status == 200) alert('录入成功!!');
                        this.showAnime(this.basicData.id.value);
                    });
                    break;
                case 'onair':
                    this.$http.post('anime/onair', {data: this.onair}).then(function (r) {
                        if (r.status == 200) alert('录入成功!!');
                        this.showAnime(this.basicData.id.value);
                    });
                    break;
            }
        },

        editData: function (pos, id) {

            let animeID = id;

            switch (pos) {
                case 'basicData':
                    for(let i = 0; i < this.basicData.title.length; i++) {
                        let title = this.basicData.title[i];
                        title.orderIndex = i;
                    }

                    for(let j = 0; j < this.basicData.links.length; j++) {
                        let link = this.basicData.links[j];
                        link.orderIndex = j;
                    }

                    this.$http.put('anime/' + animeID, {data: this.basicData}).then(function (r) {
                        if (r.status == 200) {
                            alert('更新成功!!');
                            this.showAnime(animeID);
                        }
                    });
                    break;
                case 'staff':
                    for ( let i = 0; i < this.staffMembers.length; i++) {
                        let staff = this.staffMembers[i];
                        staff.orderIndex = i;
                    }
                    this.$http.put('anime/staff/' + animeID, {data: this.staffMembers}).then(function (r) {
                        if (r.status == 200) {
                            alert('更新成功!!');
                            this.showAnime(r.data.animeID);
                        }
                    });
                    break;
                case 'cast':
                    for ( let i = 0; i < this.castMembers.length; i++) {
                        let cast = this.castMembers[i];
                        cast.orderIndex = i;
                    }
                    this.$http.put('anime/cast/' + animeID, {data: this.castMembers}).then(function (r) {
                        if (r.status == 200) {
                            alert('更新成功!!');
                            this.showAnime(r.data.animeID);
                        }
                    });
                    break;
                case 'onair':
                    for (let i = 0; i < this.onair.length; i++) {
                        let oa = this.onair[i];
                        oa.orderIndex = i;
                    }
                    this.$http.put('anime/onair/' + animeID, {data: this.onair}).then(function (r) {
                        if (r.status == 200) {
                            alert('更新成功!!');
                            this.showAnime(r.data.animeID);
                        }
                    });
                    break;
            }
        },

        removeData: function (pos, id, arr, index) {
            let res = 0;
            this.$http.delete('anime/' + pos + '/' + id)
                .then(function (r) {
                    if (r.status == 200) {
                        alert('删除成功！！');
                        arr.splice(index, 1);
                    } else {
                        alert('删除失败！！');
                        console.log('删除失败:\n' + r);
                    }
                });
        },
        searchAnime: function() {
            this.$http.get('anime/search/' + vue.animeNameSearchInput).then(function (r) {

                if( r.data.multiple == 0) {

                    /*
                    // 初始化
                    const bD = JSON.parse(JSON.stringify(basicDataTmp));
                    const sM = JSON.parse(JSON.stringify(staffMembersTmp));
                    const cM = JSON.parse(JSON.stringify(castMembersTmp));

                    this.basicData    = r.data.basicData;
                    this.staffMembers = r.data.staffMembers;
                    this.castMembers  = r.data.castMembers;
                    this.onair        = r.data.onairs;
                    */
                    const id = r.data.basicData.id.value;
                    this.showAnime(id);
                } else {
                    var animeNames;

                    animeNames = r.data.animes;

                    for(var i = 0; i < animeNames.length; i++) {

                        var anime = {};

                        var animeName = animeNames[i];

                        anime.id = animeName[0].trans_name_id;

                        anime.ori = animeName[0].trans_name;

                        anime.zh_CN = (animeName[1].trans_language == 'zh-cn') ? animeName[1].trans_name : '';

                        this.animeNameList.push(anime);
                    }
                }

            });
        },

        showAnime: function(id) {
            this.$http.get('anime/' + id).then(function(r){

                const bD = r.data.basicData;
                const sM = r.data.staffMembers;
                const cM = r.data.castMembers;
                const oa = r.data.onairs;

                let basicData    = bD.id.value != 0 ? bD : JSON.parse(JSON.stringify(basicDataTmp));
                let staffMembers = sM.length   != 0 ? sM : JSON.parse(JSON.stringify(staffMembersTmp));
                let castMembers  = cM.length   != 0 ? cM : JSON.parse(JSON.stringify(castMembersTmp));
                let onairs       = oa.length   != 0 ? oa : JSON.parse(JSON.stringify(onairTmp));

                //当 oriWorks 未有数据时, 重置 oriWorks.
                if (basicData.oriWorks.length == 0) {
                    basicData.oriWorks = JSON.parse(JSON.stringify(basicDataTmp.oriWorks));
                }

                this.$set('basicData', basicData);
                this.$set('staffMembers', staffMembers);
                this.$set('castMembers', castMembers);
                this.$set('onair', onairs)
            });
        },
        /**
         * Get the Formated Text from sourceBox
         */
        toArray:         function (data, pos) {

            let item, items;
            let res = [];

            //TODO: 已有内容的, 在后面添加

            switch (pos) {

                case 'staff':

                    items = formatedTextToArray(data);

                    for (let i = 0; i < items.length; i++) {
                        item = {
                            'id':                   0,
                            'animeID':              vue.basicData.id.value,
                            'staffPostOri':         items[i][0],
                            'staffPostZhCN':        '',
                            'staffMemberName':      items[i][1],
                            'staffBelongsToName':   '',
                            'isImportant':          false,
                            'orderIndex':           i
                        };

                        res.push(item);
                    }

                    if(vue.staffMembers[0].id == 0) {
                        vue.staffMembers = res;
                    } else {
                        vue.staffMembers.concat(res);
                    }

                    break;
                case 'cast':

                    items = formatedTextToArray(data);

                    for (let j = 0; j < items.length; j++) {
                        item = {
                            'id'            : 0,
                            'animeID'       : vue.basicData.id.value,
                            'charaNameOri'  : items[j][0],
                            'cvNameOri'     : items[j][1],
                            'isImportant'   : false,
                            'orderIndex'    : j
                        };

                        res.push(item);
                    }

                    if(vue.castMembers[0].id == 0) {
                        vue.castMembers = res;
                    } else {
                        vue.castMembers.concat(res);
                    }
                    break;
                case 'onair':
                    items = data.replace(/\t/g, '\,');
                    vue.onair = onairFormatedTextToArray(items, vue.basicData.id.value);
                    break;
            }
        },

        /**
         * Input Focus Move
         */
        focusMove: function (id,index,e) {

            var key = e.keyCode;

            var preIndex = Number(index) - 1;

            var nextIndex = Number(index) + 1;

            switch(key){
                // Down
                case 40:
                    var item = document.getElementById(id + nextIndex.toString());
                    if ( item ) item.focus();
                    break;
                // Up
                case 38:
                    var item = document.getElementById(id + preIndex.toString());
                    if ( item ) item.focus();
                    break;
            }
        },

        /**
         * Clean the sourceBox
         */
        cleanSource:     function () {
            vue.sourceBox     = "";
            vue.formatedReady = false
        },
        outputData:      function () {
            vue.member = JSON.stringify(vue.staffMembers)
        }
    }
});