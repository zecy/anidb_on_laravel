<style>
    .v-select {
        width: 100%;
        position: relative;
        height: 30px;
        border: 1px solid #ccc;
        padding: 4px 0 5px 0;
        cursor: pointer;
    }

    .v-select div {
        float: none;
    }

    .vs-arrow {
        position: absolute;
        display: block;
        top:8px;
        right:1em;
        cursor: pointer;
        pointer-events: all;
    }

    .vs-arrow::before {
        content: '';
        vertical-align: middle;
        box-sizing: border-box;
        display: block;
        border: 3px solid rgba(60,60,60,.5);
        border-left: none;
        border-bottom: none;
        height: 8px;
        width: 8px;
        transform: rotate(135deg);
        transition: all 150ms cubic-bezier(0.3, 0.1, 0.8, 0.5);
    }

    .vs-arrow.open {
        top:12px;
    }

    .vs-arrow.open::before {
        transform: rotate(315deg);
    }

    .v-selected-tag,
    .v-single-selected {
        display: inline-block;
        height: 20px;
        line-height: 20px;
        cursor: pointer;
    }

    .v-selected-tag {
        padding: 0 5px;
        margin-left: 5px;
        background-color: #EEE;
        border-radius: 3px;
    }

    .v-single-selected {
        padding-left: 1em;
    }

    .v-selected-tag span {
        color: #aaa;
    }

    .v-selected-tag:hover {
        background-color: #ddd;
    }

    .v-selected-tag:hover span {
        color: #000;
    }

    .vs-dropdown {
        z-index: 10;
        min-width:100%;
        position: absolute;
        top: 30px;
        background-color: #fff;
        box-shadow: 0 0 0 1px #ccc,
        inset 0 1px 0 0 rgba(0,0,0,.05),
        inset 0 2px 0 0 rgba(0,0,0,.025);
    }

    .vs-dropdown-search {
        margin: 5px 0;
        padding:0 5px;
        width: 100%;
    }

    .v-select > .vs-dropdown > .vs-dropdown-search > input[type=search] {
        width:100%;
    }

    .vs-dropdown-menu {
        margin:5px 0;
    }

    .vs-dropdown-menu li {
        padding: 5px 10px;
        cursor: pointer;
    }

    .v-opt-selected {
        background-color: #ebf2fc;
    }

    .vs-dropdown-menu li:hover,
    .vs-dropdown-menu li.pointed
    {
        box-shadow: inset 0 0 0 1px dodgerblue;
    }
</style>

<template id="v-select">
    <div class="v-select"
    >
        <div v-el:vs_content
             v-on:mousedown.prevent="toggleDropdown"
        >
            <span class="v-selected-tag"
                  v-if="multiple"
                  v-for="selected in vs_value | orderBy vs_value_label"
                  track-by="$index"
                  v-on:click="select(selected)"
            >
                @{{ getLabel(selected) }}
                <span aria-hidden="true">&times;</span>
            </span>
            <span v-if="!multiple"
                  class="v-single-selected"
            >
                @{{ getLabel(vs_value) }}
            </span>
            <i class="vs-arrow" :class="{ 'open': open }"></i>
        </div>

        <div class="vs-dropdown"
             v-show="open"
             v-on:focus="open = true"
             v-on:blur="open = false"
        >
            <div class="vs-dropdown-search">
                <input v-el:vs_search
                       v-model="search"
                       type="search"
                       v-on:blur="open = false"
                       v-on:keyup.esc="search = ''"
                       v-on:keydown.enter="enterSelect"
                       v-on:keydown.up="pointerMove('up')"
                       v-on:keydown.down="pointerMove('down')"
                >
            </div>
            <ul class="vs-dropdown-menu">
                <li v-for="option in options | filterBy search | filtRes"
                    v-bind:class="{'v-opt-selected' : option.selected, 'pointed' : (pointer === $index)}"
                    v-on:mousedown.prevent="select(option)"
                >@{{ option[keyLabel] }}</li>
            </ul>
        </div>
    </div>
</template>

<script>
    Vue.component('vselect', {
        template: '#v-select',
        props:    ['vs_options', 'vs_value', 'vs_placeholder', 'multiple', 'vs_label', 'vs_value_label', 'opt_is_value'],
        data:     function () {
            return {
                'open':       false,
                'search':     '',
                'pointer':    0,
                'filtResult': [],
                'options':    [],
                'keyLabel':   '',
                'valueLabel': '',
                'optIsValue': false
            }
        },
        computed: {
            options:    function () {
                // 为所有选项增加 'selected' 项
                const arr = JSON.parse(JSON.stringify(this.vs_options));
                const val = this.vs_value;
                for (let i = 0; i < arr.length; i++) {
                    if (this.optIsValue) {
                        if (val != undefined && val[this.valueLabel] === arr[i][this.valueLabel]) {
                            arr[i].selected = true
                        } else {
                            arr[i].selected = false
                        }
                    } else {
                        if (val != undefined && val === arr[i][this.valueLabel]) {
                            arr[i].selected = true
                        } else {
                            arr[i].selected = false
                        }
                    }
                }
                return arr
            },
            keyLabel:   function () {
                return this.vs_label === undefined ? 'label' : this.vs_label;
            },
            valueLabel: function () {
                return this.vs_value_label === undefined ? 'value' : this.vs_value_label;
            },
            optIsValue: function () {
                return this.opt_is_value === undefined ? false : this.opt_is_value;
            }
        },
        filters:  {
            filtRes: function (arr) {
                this.$set('filtResult', arr); // 让经过 filter 的数组内容同步到 this.options 里面
                return arr
            }
        },
        methods:  {
            select:         function (opt) {
                let self = this;

                const multiple = this.multiple;

                const valueLabel = this.valueLabel;

                let value    = this.vs_value;
                let optValue = this.optIsValue ? opt : opt[valueLabel];    // <options value="optValue">

                if (multiple) {
                    // 多选
                    if (value.length > 0) {
                        for (let i = 0; i < value.length; i++) {
                            if (optValue === value[i]) {
                                if (opt.selected) {
                                    value.splice(i, 1);
                                }
                                opt.selected = !opt.selected;
                                return
                            }
                        }
                    }
                    opt.selected = true;
                    value.push(optValue);
                    this.vs_value = value;
                } else {
                    // 单选
                    const oldOpt = value;
                    for (let i = 0; i < self.options.length; i++) {
                        const curOptValue = this.optIsValue ? self.options[i] : self.options[i][valueLabel];
                        if (oldOpt === curOptValue) {
                            self.options[i].selected = false
                        }
                    }
                    opt.selected  = true;
                    this.vs_value = optValue;
                    this.closeDropdown();
                }
            }
            ,
            toggleDropdown: function () {
                if (this.open) {
                    this.open = false;
                } else {
                    const self = this;
                    this.open  = true;
                    this.$nextTick(function () {
                        self.$els.vs_search.focus();
                    })
                }
            }
            ,
            closeDropdown:  function () {
                if (!this.multiple) {
                    this.open = false
                }
            }
            ,
            getLabel:       function (value) {
                let res          = '';
                const options    = this.options;
                const valueLabel = this.valueLabel;
                const keyLabel   = this.keyLabel;

                if (value === undefined || value === '') {
                    res = this.vs_placeholder;
                } else {
                    for (let i = 0; i < options.length; i++) {
                        const optIsValue = this.opt_is_value;
                        const rawValue   = optIsValue ? value[valueLabel] : value;
                        const item     = options[i];
                        const optValue = item[valueLabel];

                        console.group('getLabel');
                        console.log('optIsValue: ' + this.opt_is_value);
                        console.log('vs_value: ' + this.opt_is_value ? value[valueLabel] : value);
                        console.log('optValue: ' + optValue);
                        console.groupEnd();

                        if (rawValue === optValue) {
                            res = item[keyLabel];
                            break;
                        } else {
                            res = this.vs_placeholder;
                        }
                    }
                }
                return res;
            }
            ,
            enterSelect:    function () {
                this.select(this.filtResult[this.pointer]);
                this.$set('pointer', 0);
                this.$set('search', "");
                this.closeDropdown();
            },
            pointerMove:    function (act) {
                const max = this.vs_options.length - 1;
                if (act === 'up' && this.pointer <= 0) {
                    this.pointer = max;
                }
                else if (act === 'down' && this.pointer >= max) {
                    this.pointer = 0;
                }
                else {
                    switch (act) {
                        case 'up':
                            this.pointer--;
                            break;
                        case 'down':
                            this.pointer++;
                            break
                    }
                }
            }
        }
    });

</script>
