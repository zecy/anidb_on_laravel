/**
 * Created by zecy on 16/02/29.
 * Use For Vue.JS
 */

/**
 * 格式化内容
 *
 */

/**
* 字符串删除空格
* */
var strTrim = function (s) {
    return s.replace(/[ 　]+/g, '');
};

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
var staffAndCastFormat = function (s) {

    var otherSeparator, oddEven, fromSource, wikiCV;

    const str = strTrim(s);

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

    colCombine = function (str) {
        const res    = [];
        const lines  = str.split('\n');
        const keylen = (lines.length) / 2;

        for (let i = 0; i < keylen; i++) {
            const key   = lines[i];
            const value = lines[i + keylen];
            res.push(key + ' - ' + value);
        }

        return res.join('\n')
    };

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
        'colCombine':     colCombine(str),
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
var formatedTextToArray = function (str, pos) {

    let arr             = [];
    const splitByLines  = str.split("\n");
    const jobSeparator  = "・";
    const nameSeparator = "、";

    for (let i = 0; i < splitByLines.length; i++) {

        let keyValue = [];

        if (splitByLines[i].indexOf(" - ") != -1) {
            keyValue = splitByLines[i].split(" - ");
        } else {
            alert("** 格式有误，请进行检查 **" + "\
                \n" + (i - 1) + " | " + splitByLines[i - 1] + "\
                \n" + i + " | " + splitByLines[i] + "\
                \n" + (i + 1) + " | " + splitByLines[i + 1]);
            return
        }

        if (pos === 'staff') {

            let jobName = keyValue;

            let isJobs  = "";
            let isNames = "";

            isJobs  = (jobName[0].indexOf(jobSeparator) != -1);
            isNames = (jobName[1].indexOf(nameSeparator) != -1);

            let jobs, names, jobNameArr = [];
            let j, n                    = 0;

            /* 企划、出品 - 创通、SUNRISE */
            if (isJobs && isNames) {
                jobs  = jobName[0].split(jobSeparator);
                names = jobName[1].split(nameSeparator);
                for (j = 0; j < jobs.length; j++) {
                    for (n = 0; n < names.length; n++) {
                        jobNameArr = [jobs[j], names[n]];
                        arr.push(jobNameArr);
                    }
                }
            }
            /* splitByLines[i] == "角色设定、总作画指导 - 千叶道德" */
            else if (isJobs && !isNames) {
                jobs = jobName[0].split(jobSeparator); // jobName[0] == ['角色设定、总作画指导']
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
                names = jobName[1].split(nameSeparator);
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
        } else {
            let charaCV = keyValue;
            const charaCVArr = [charaCV[0], charaCV[1]];
            arr.push(charaCVArr);
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
            'id':           0,
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

/**
 * JQuery UI 效果
 */
$(document).ready(function () {
    // 平滑滚动
    $('a').smoothScroll({
        offset: -10,
        speed:  400
    });

    $('.goto .bottom').smoothScroll({
        offset: -($(window).height() - 50)
    });
});

window.addEventListener('scroll', function (e) {
    vue.scrolled = document.body.scrollTop;
});

/** VUE.JS **/

// 打开 debug 模式
Vue.config.debug = true;

/**
 *  组件 Components
 */

Vue.component('basicinput', {
    template: '#basic-input',
    props:    ['item']
});

Vue.component('originalwork', {
    template: '#ori-work',
    props:    ['pid', 'data', 'orilist', 'multiple_children', 'multiple_selected', 'haschild', 'lv', 'index', 'parent_name'],
    data() {
        return {
            optionslist() {
                return JSON.parse(this.orilist)
            }
        }
    },
    methods:  {
        oriChange: function (val) {
            let newOri             = JSON.parse(JSON.stringify(basicDataTmp)).oriWorks;
            newOri[0]              = val;
            vue.basicData.oriWorks = newOri;
        }
    }
});

Vue.component('staffrow', {
    template: '#staff-row',
    props:    ['staffitem', 'controlledarr', 'lv', 'index'], // props 必须全部字母使用小写
    methods:  {
        addChild:  function (arr, index) {
            const oldItem = arr[index];

            const animeID      = oldItem.animeID;
            const lv           = Number(oldItem.lv);
            const pid          = oldItem.id;
            const hasChild     = oldItem.haschild;
            const staffPostOri = oldItem.staffMemberName;
            const isImportant  = oldItem.isImportant;

            const child = {
                'id':                 0,
                'animeID':            animeID,
                'staffPostOri':       staffPostOri,
                'staffPostZhCN':      '',
                'staffMemberName':    '',
                'staffBelongsToName': '',
                'isImportant':        isImportant,
                'orderIndex':         0,
                'lv':                 lv + 1,
                'haschild':           false,
                'pid':                pid
            };

            if (!hasChild) {
                oldItem.haschild = true;
            }
            oldItem.child.push(child);
        },
        focusMove: function (id, index, e) {
            vue.focusMove(id, index, e)
        }
    }
});

Vue.component('describox', {
    template: '#descri-box',
    props:    ['processing', 'descri_label', 'descri_value', 'anime_id'],
    methods:  {
        shortCut: function (anime_id, e) {
            const key  = e.keyCode;
            const ctrl = e.ctrlKey;

            if (key === 83 && ctrl) { // ctrl + s
                if (anime_id === 0) {
                    vue.createData('basicData');
                } else if (anime_id != 0) {
                    vue.editData('basicData', anime_id);
                }
            }
        }
    }
});

Vue.component('createeditbutton', {
    template: '#create-edit-btn',
    props:    ['create_condition', 'edit_condition', 'pos', 'anime_id', 'is_complete'], // props 不能有连接线 -
    data:     function () {
        return {
            btnProcessing:  false,
            processing_msg: '准备写入数据库',
            msg:            ''
        }
    },
    computed: {
        msg: function () {
            const pos = this.pos;
            switch (pos) {
                case 'states':
                    return '统计';
                case 'basicData':
                    return '动画';
                case 'staff':
                    return ' STAFF ';
                case 'cast':
                    return ' CAST ';
                case 'onair':
                    return '播放'
            }
        }
    },
    watch:    {
        'is_complete': function (newVal) {
            const len = newVal.length;
            const pos = this.pos;

            if( pos === 'basicData') {
                if (newVal.id.value != 0 ) {
                    this.processing_msg = '录入成功！正在返回';
                    setTimeout(function () {
                        this.btnProcessing = false
                    }.bind(this), 1000);// 不使用 bind 的话 this 会被识别为 window
                }
            } else if ( pos === 'states') {
                if (newVal.anime_id != 0 ) {
                    this.processing_msg = '录入成功！正在返回';
                    setTimeout(function () {
                        this.btnProcessing = false
                    }.bind(this), 1000);// 不使用 bind 的话 this 会被识别为 window
                }
            } else  {
                if (newVal[len - 1].id != 0) {
                    this.processing_msg = '成功录入 ' + len + ' 条数据！正在返回';
                    setTimeout(function () {
                        this.btnProcessing = false;
                        switch (pos) {
                            case 'staff':
                                vue.staffSource = "";
                                break;
                            case 'cast':
                                vue.castSource = "";
                                break;
                            case 'onair':
                                vue.onairSource = "";
                        }
                    }.bind(this), 2000);// 不使用 bind 的话 this 会被识别为 window
                }
            }
        }
    },
    methods:  {
        createData: function (pos) {
            this.btnProcessing = true;
            this.$nextTick(function () {
                const r = confirm('是否确定？');
                if (r) {
                    this.processing_msg = '正在写入数据库';
                    vue.createData(pos);
                    if (vue.processing) this.processing_msg = '已写入数据库，正在返回数据'
                } else {
                    this.btnProcessing = false;
                }
            });
        },
        editData:   function (pos, anime_id) {
            this.btnProcessing = true;
            this.$nextTick(function () {
                this.processing_msg = '正在写入数据库';
                vue.editData(pos, anime_id);
                if (vue.processing) this.processing_msg = '已写入数据库，正在返回数据'
            });
        }
    }
});

Vue.component('rowcontrol', {
    template: '#row-control',
    props:    ['arr', 'index', 'pos'],
    methods:  {
        /* Row Up */
        rowUp:     function (arr, index) {
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
                if (r) {
                    vue.removeData(p, id, arr, i);
                }
            }
        },
        /* Add A Row */
        addRow:    function (arr, index, pos) {
            var obj = JSON.parse(JSON.stringify(arr[index]));
            obj.id  = 0;
            if (pos == 'staff') {
                obj.haschild = false;
                obj.child    = [];
            }
            arr.splice(Number(index) + 1, 0, obj);
        }
    }
});

Vue.component('searchanime', {
    template: '#search-anime',
    props:    ['is_complete'],
    data:     function () {
        return {
            'title':            '',
            'searchProcessing': false,
            'searching_msg':    '正在搜索',
            'res':              '',
            'animeNameList':    [],
            'errmsg': '没有找到了相关作品，请检查或换用关键词',
            'anime_id' : anime_id
        }
    },
    ready() {
        const animeID = this.anime_id;
        if ( animeID === 0) {
            return
        } else if (animeID === -1) {
            this.res = false;
            this.errmsg = '没有相关作品，即将返回';
            setTimeout("javascript:location.href='/input'", 2000);
        }
    },
    watch:    {
        'is_complete': function (newVal, oldVal) {
            if (oldVal != newVal) {
                this.searchProcessing = false;
                this.title            = "";
            }
        }
    },
    methods:  {
        searchAnime: function () {
            this.searchProcessing = true;
            this.$http.get('/input/search/' + this.title).then(function (r) {
                if (r.data.multiple === 0) {
                    const id = r.data.basicData.id.value;
                    vue.showAnime(id, r);
                } else if (r.data.multiple === 1) {

                    let animeNames;

                    this.animeNameList = [];

                    animeNames = r.data.animes;

                    for (var i = 0; i < animeNames.length; i++) {

                        let animeName = animeNames[i];

                        const anime = {
                            'id':    animeName.anime_id,
                            'ori':   animeName.ori,
                            'zh_cn': animeName.zh_cn,
                            'abbr' : animeName.anime_abbr
                        };
                        this.animeNameList.push(anime);
                    }
                    this.searchProcessing = false;
                    this.res              = true
                } else if (r.data.multiple === -1 && r.data.animes === '') {
                    this.res = false
                }
            });
        },
        showAnime:   function (id) {
            vue.showAnime(id);
            this.animeNameList = [];
        }
    }
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
                case 'colCombine':
                    res = str.colCombine;
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

Vue.component('formtotop', {
    template: '#form-to-top',
    props:    ['pos', 'view_top'],
    data:     function () {
        return {
            'form_id':       this.pos + '-form',
            'formTop':       0,
            'viewHeight':    0,
            'arrivedTop':    false,
            'arrivedBottom': false
        }
    },
    computed: {
        'formTop':    function () {
            return document.getElementById(this.form_id).offsetTop;
        },
        'viewHeight': function () {
            return window.innerHeight;
        }
    },
    watch:    {
        'view_top': function (newVal) {
            const viewTop = newVal;                                                // 滚动条滚过的距离
            const viewHeight = this.viewHeight;                                       // 窗口的高度
            const formHeight = document.getElementById(this.form_id).offsetHeight;    // 表格的高度
            const formTop = this.formTop;                                          // 表格上边到顶的距离
            const formBottom = formHeight + formTop;                                  // 表格下边到顶的距离
            const toTop = (viewTop - formTop) >= 0;                              // 表格上边到顶
            const toBottom = (viewTop - formBottom) >= -viewHeight;                 // 表格下边到窗口底边

            if (formHeight > viewHeight) {              // 表格高度大于窗口高度才运作
                if (toTop && !toBottom) {               // 表格顶到顶, 未看到表格底
                    this.arrivedTop    = true;
                    this.arrivedBottom = false
                } else if (toTop && toBottom) {       // 表格顶过顶, 表格底到底
                    this.arrivedTop    = false;
                    this.arrivedBottom = true
                } else {                                // 表格顶未到顶
                    this.arrivedTop    = false;
                    this.arrivedBottom = false
                }
            }
        }
    },
    methods:  {
        'goto': function (id, pos) {
            const formTop    = this.formTop;
            const formHeight = document.getElementById(id).offsetHeight;
            const formBottom = formHeight + formTop;
            const viewHeight = this.viewHeight;

            switch (pos) {
                case 'top':
                    let i = 0;
                    while (i < formTop) {
                        document.body.scrollTop = i - 50;
                        i += 10
                    }
                    break;
                case 'bottom':
                    document.body.scrollTop = formBottom - viewHeight + 50;
                    break;
            }
        }
    }
});

Vue.component('navbtn', {
    template: "#nav-btn"
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
        {'id': 0, 'label': '官方标题', 'lang': 'jp', 'isOfficial': true, 'isOriginal': false,'value': '', 'comment': '', 'orderIndex': 0},
        {'id': 0, 'label': '原作标题', 'lang': 'jp', 'isOfficial': true, 'isOriginal': true,'value': '', 'comment': '', 'orderIndex': 1},
        {'id': 0, 'label': '译名', 'lang': 'zh-cn', 'isOfficial': false, 'isOriginal': false,'value': '', 'comment': '', 'orderIndex': 2}
    ],
    'abbr':          {'label': '简称', 'value': ''},
    'kur':           {'label': '长度', 'value': 1},
    'eps_oa':        {'label': '集数', 'value': 0},
    'eps_soft':      {'label': '集数', 'value': 0},
    'duration':      {'label': '时间规格', 'value': 'general'},
    'oriWorks':      [
        [{'ori_id': 0, 'haschild': false, 'multiple_children': false, 'multiple_selected': false}],
        [{'ori_id': 0, 'haschild': false, 'multiple_children': false, 'multiple_selected': false}],
        [{'ori_id': 0, 'haschild': false, 'multiple_children': false, 'multiple_selected': false}],
        [
            [{'ori_id': 0, 'haschild': false, 'multiple_children': false, 'multiple_selected': false}],
            [{'ori_id': 0, 'haschild': false, 'multiple_children': false, 'multiple_selected': false}],
            [{'ori_id': 0, 'haschild': false, 'multiple_children': false, 'multiple_selected': false}],
            [{'ori_id': 0, 'haschild': false, 'multiple_children': false, 'multiple_selected': false}]
        ]
    ],
    'premiereMedia': {'label': '首播媒体', 'value': 'tv'},
    'links':         [
        {'id': 0, 'class': 'hp', 'isOfficial': true, 'value': '', 'comment': '', 'orderIndex': 0}
    ],
    'isSequel':      {'label': '是否续作', 'value': false},
    'sequelComment': {'label': '备注', 'value': ''},
    'lifecycle':     {'label': '出品周期', 'value': 'ended'},
    'isCounted':     {'label': '是否纳入统计', 'value': true},
    'story':         {'label': '故事', 'value': ''},
    'description':   {'label': '介绍', 'value': ''},
    'oa_year':       {'value': 2016},
    'oa_season':     {'value': 7},
    'oa_timeslot':   {'label': '播放时段', 'value': 'midnight'}
};

var dataStatesTmp = {
    'id':              0,
    'anime_id':        0,
    's_series':        0,
    's_title':         0,
    's_ori_works':     0,
    's_url':           0,
    's_eps':           0,
    's_duration':      0,
    's_time_format':   0,
    's_media':         0,
    's_date':          0,
    's_time':          0,
    'has_story':       false,
    'has_description': false,
    's_staff':         0,
    'has_thumb':       false,
    'has_poster':      false,
    's_op_themes':     0,
    's_ed_themes':     0,
    's_insert_songs':  0,
    's_cv':            0
};

var staffMembersTmp = [{
    'id':                 0,
    'pid':                0,
    'haschild':           false,
    'lv':                 0,
    'animeID':            0,
    'staffPostOri':       '',
    'staffPostZhCN':      '',
    'staffMemberName':    '',
    'staffBelongsToName': '',
    'isImportant':        false,
    'orderIndex':         0,
    'child':              []
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
    data:    {
        'processing':   false,
        'scrolled':     0,
        'basicData':    JSON.parse(JSON.stringify(basicDataTmp)),
        'dataStates':   JSON.parse(JSON.stringify(dataStatesTmp)),
        'staffMembers': JSON.parse(JSON.stringify(staffMembersTmp)),
        'castMembers':  JSON.parse(JSON.stringify(castMembersTmp)),
        'onair':        JSON.parse(JSON.stringify(onairTmp)),
        'staffSource':  '',
        'castSource':   '',
        'onairSource':  ''
    },
    ready() {
        const animeID = anime_id;
        if(animeID > 0) {
            this.showAnime(animeID);
        }
    },
    methods: {
        /*
         * Display the Anime Basic Data
         * */
        createData: function (pos) {

            this.processing = true;

            switch (pos) {
                case 'basicData':

                    this.basicData.oriWorks = this.oriWorksInsertPrepare(this.basicData.oriWorks);

                    this.$http.post('/input', {data: this.basicData}).then(function (r) {
                        if (r.status == 200) {
                            this.showAnime(r.data.id);
                            this.processing = false;
                        }
                    });
                    break;
                case 'states':
                    this.$http.post('/input/states', {data: this.dataStates}).then(function (r) {
                        if (r.status == 200) {
                            this.showAnime(this.basicData.id.value);
                            this.processing = false;
                        }
                    });
                    break;
                case 'staff':
                    this.$http.post('/input/staff', {data: this.staffMembers}).then(function (r) {
                        if (r.status == 200) {
                            this.showAnime(this.basicData.id.value);
                            this.processing = false;
                        }
                    });
                    break;
                case 'cast':
                    this.$http.post('/input/cast', {data: this.castMembers}).then(function (r) {
                        if (r.status == 200) {
                            this.showAnime(this.basicData.id.value);
                            this.processing = false;
                        }
                    });
                    break;
                case 'onair':
                    this.$http.post('/input/onair', {data: this.onair}).then(function (r) {
                        if (r.status == 200) {
                            this.showAnime(this.basicData.id.value);
                            this.processing = false;
                        }
                    });
                    break;
            }
        },

        editData: function (pos, id) {

            let animeID = id;

            this.processing = true;

            switch (pos) {
                case 'basicData':
                    for (let i = 0; i < this.basicData.title.length; i++) {
                        let title        = this.basicData.title[i];
                        title.orderIndex = i;
                    }

                    this.basicData.oriWorks = this.oriWorksInsertPrepare(this.basicData.oriWorks);

                    for (let j = 0; j < this.basicData.links.length; j++) {
                        let link        = this.basicData.links[j];
                        link.orderIndex = j;
                    }

                    this.$http.put('/input/' + animeID, {data: this.basicData}).then(function (r) {
                        if (r.status == 200) {
                            this.showAnime(animeID);
                            this.processing = false;
                        }
                    });
                    break;
                case 'states':
                    this.$http.put('/input/states/' + animeID, {data: this.dataStates}).then(function (r) {
                        if (r.status == 200) {
                            this.showAnime(r.data.animeID);
                            this.processing = false;
                        }
                    });
                    break;
                case 'staff':
                    for (let i = 0; i < this.staffMembers.length; i++) {
                        let staff        = this.staffMembers[i];
                        staff.orderIndex = i;
                        if (staff.haschild && staff.child.length > 0) {
                            for (let j = 0; j < staff.child.length; j++) {
                                let staffChild        = staff.child[j];
                                staffChild.orderIndex = j;
                            }
                        } else if (staff.haschild == true && staff.child.length == 0) {
                            staff.haschild = false;
                        }
                    }

                    this.$http.put('/input/staff/' + animeID, {data: this.staffMembers}).then(function (r) {
                        if (r.status == 200) {
                            this.showAnime(r.data.animeID);
                            this.processing = false;
                        }
                    });
                    break;
                case 'cast':
                    for (let i = 0; i < this.castMembers.length; i++) {
                        let cast        = this.castMembers[i];
                        cast.orderIndex = i;
                    }
                    this.$http.put('/input/cast/' + animeID, {data: this.castMembers}).then(function (r) {
                        if (r.status == 200) {
                            //alert('更新成功!!');
                            this.showAnime(r.data.animeID);
                            this.processing = false;
                        }
                    });
                    break;
                case 'onair':
                    for (let i = 0; i < this.onair.length; i++) {
                        let oa        = this.onair[i];
                        oa.orderIndex = i;
                    }
                    this.$http.put('/input/onair/' + animeID, {data: this.onair}).then(function (r) {
                        if (r.status == 200) {
                            //alert('更新成功!!');
                            this.showAnime(r.data.animeID);
                            this.processing = false;
                        }
                    });
                    break;
            }
        },

        removeData: function (pos, id, arr, index) {
            let res = 0;
            this.$http.delete('/input/' + pos + '/' + id)
                .then(function (r) {
                    if (r.status == 200) {
                        alert('删除成功！！');
                        arr.splice(index, 1);
                    } else {
                        alert('删除失败！！');
                    }
                });
        },

        showAnime: function (id, data) {

            let inject = function (r, self) {
                const bD = r.data.basicData;
                const dS = r.data.dataStates;
                const sM = r.data.staffMembers;
                const cM = r.data.castMembers;
                const oa = r.data.onairs;

                let basicData    = bD.id.value        != 0     ? bD : JSON.parse(JSON.stringify(basicDataTmp));
                let dataStates   = dS.id              != null  ? dS : JSON.parse(JSON.stringify(dataStatesTmp));
                let staffMembers = sM.length          != 0     ? sM : JSON.parse(JSON.stringify(staffMembersTmp));
                let castMembers  = cM.length          != 0     ? cM : JSON.parse(JSON.stringify(castMembersTmp));
                let onairs       = oa.length          != 0     ? oa : JSON.parse(JSON.stringify(onairTmp));

                dataStates.anime_id = basicData.id.value;

                //当 oriWorks 未有数据时, 重置 oriWorks.
                if (basicData.oriWorks.length === 0) {
                    basicData.oriWorks = JSON.parse(JSON.stringify(basicDataTmp.oriWorks));
                }

                self.$set('basicData', basicData);
                self.$set('dataStates', dataStates);
                self.$set('staffMembers', staffMembers);
                self.$set('castMembers', castMembers);
                self.$set('onair', onairs);
            };

            if (data != undefined) {
                inject(data, this);
            } else {
                this.$http.get('/input/' + id).then(function (res) {
                    inject(res, this);
                });
            }
        },
        /**
         * Get the Formated Text from sourceBox
         */
        toArray:   function (data, pos) {

            let item, items, oldArr;
            let res = [];

            switch (pos) {

                case 'staff':

                    items = formatedTextToArray(data, pos);

                    this.$http.post('/input/stafftrans', {data: items}).then(function (r) {
                        if (r.status == 200) {
                            items = r.data;

                            for (let i = 0; i < items.length; i++) {
                                item = {
                                    'id':                 0,
                                    'animeID':            vue.basicData.id.value,
                                    'staffPostOri':       items[i].ori,
                                    'staffPostZhCN':      items[i].zhcn,
                                    'staffMemberName':    items[i].name,
                                    'staffBelongsToName': '',
                                    'isImportant':        items[i].isImportant,
                                    'orderIndex':         i,
                                    'lv':                 0,
                                    'haschild':           false,
                                    'pid':                0,
                                    'child':              []
                                };

                                res.push(item);
                            }

                            if (vue.staffMembers[0].id == 0) {
                                vue.staffMembers = res;
                            } else {
                                vue.staffMembers = vue.staffMembers.concat(res);
                            }
                        }
                    });

                    break;
                case 'cast':

                    items = formatedTextToArray(data, pos);

                    for (let j = 0; j < items.length; j++) {
                        item = {
                            'id':           0,
                            'animeID':      vue.basicData.id.value,
                            'charaNameOri': items[j][0],
                            'cvNameOri':    items[j][1],
                            'isImportant':  false,
                            'orderIndex':   j
                        };

                        res.push(item);
                    }

                    if (vue.castMembers[0].id == 0) {
                        vue.castMembers = res;
                    } else {
                        vue.castMembers = vue.castMembers.concat(res);
                    }
                    break;
                case 'onair':
                    items = data.replace(/\t/g, '\,');

                    res = onairFormatedTextToArray(items, vue.basicData.id.value);

                    if (vue.onair[0].id == 0) {
                        vue.onair = res;
                    } else {
                        vue.onair = vue.onair.concat(res);
                    }
                    break;
            }
        },

        resetData: function (pos) {
            switch (pos) {
                case 'staff':
                    this.staffMembers = JSON.parse(JSON.stringify(staffMembersTmp));
                    break;
                case 'cast':
                    this.staffMembers = JSON.parse(JSON.stringify(castMembersTmp));
                    break;
                case 'onair':
                    this.staffMembers = JSON.parse(JSON.stringify(onairTmp));
                    break;
            }
        },

        /**
         * Input Focus Move
         */
        focusMove: function (id, index, e) {

            const key = e.keyCode;

            const preIndex = Number(index) - 1;

            const nextIndex = Number(index) + 1;

            switch (key) {
                // Down
                case 40:
                    var item = document.getElementById(id + nextIndex.toString());
                    if (item) item.focus();
                    break;
                // Up
                case 38:
                    var item = document.getElementById(id + preIndex.toString());
                    if (item) item.focus();
                    break;
            }
        },

        /**
         * Clean the sourceBox
         */
        cleanSource: function () {
            vue.sourceBox     = "";
            vue.formatedReady = false
        },
        outputData:  function () {
            vue.member = JSON.stringify(vue.staffMembers)
        }
    }
});
