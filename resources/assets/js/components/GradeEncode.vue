<template>
    <div>
            <JqxTabs ref="myTabs" width="100%" :height="jqxTabsHeight" :initTabContent="initTabContent" :theme="'bootstrap'" @tabclick="onTabClick($event)" :reorder="true">
                <ul>
                    <li>Class Roster</li>
                    <li>Submitted Grades</li>                    
                </ul>

                <div>
                    <JqxGrid 
                        ref="roster"
                        :source="dataAdapterClassRoster"
                        :columns="headerroster"
                        :pageable="true" 
                        :height="'100%'" 
                        :width="width" 
                        :sortable="true"
                        :filterable="true"
                        :altrows="true" 
                        :theme="'bootstrap'"
                        :enabletooltip="true" 
                        :showaggregates="true"
                        :editable="false"
                        :selectionmode="'multiplecellsadvanced'">
                    </JqxGrid>
                </div>
                <div>
                    <JqxGrid 
                        ref="roster"
                        :source="dataAdapterSubmittedGrades"
                        :columns="submittedGradesColumn"
                        :pageable="true" 
                        :height="'600'" 
                        :width="'100%'" 
                        :filterable="true"
                        :sortable="true"
                        :altrows="true" 
                        :theme="'bootstrap'"
                        :enabletooltip="true" 
                        :showaggregates="true"
                        :editable="false"
                        :selectionmode="'multiplecellsadvanced'">
                    </JqxGrid>
                </div>
            </JqxTabs>

        <!-- </JqxResponsivePanel> -->

    </div>
</template>

<script>
import JqxGrid from "jqwidgets-scripts/jqwidgets-vue/vue_jqxgrid.vue";
import JqxWindow from "jqwidgets-scripts/jqwidgets-vue/vue_jqxwindow.vue";
import JqxInput from "jqwidgets-scripts/jqwidgets-vue/vue_jqxinput.vue";
import JqxDropDownList from "jqwidgets-scripts/jqwidgets-vue/vue_jqxdropdownlist.vue";
import JqxButton from "jqwidgets-scripts/jqwidgets-vue/vue_jqxbuttons.vue";
import JqxTabs from "jqwidgets-scripts/jqwidgets-vue/vue_jqxtabs.vue";
import JqxNavBar from "jqwidgets-scripts/jqwidgets-vue/vue_jqxnavbar.vue";
import JqxResponsivePanel from "jqwidgets-scripts/jqwidgets-vue/vue_jqxresponsivepanel.vue";
import CreateNewGrid from "./CreateNewGrid.vue";
import SummaryGrid from "./EncodeGrade/SummaryGrid.vue"

export default {
    components: {
        JqxGrid,
        JqxWindow,
        JqxInput,
        JqxDropDownList,
        JqxButton,
        JqxTabs,
        JqxNavBar,
        JqxResponsivePanel,
        JqxTabs,
        CreateNewGrid,
        SummaryGrid
    },
    data() {
        return {
            requestParam: '?template_id=' + this.getUrlVars().template_id + '&subject_id=' + this.getUrlVars().subject_id + '&section_id=' + this.getUrlVars().section_id + '&term_type=' + this.getUrlVars().term_type + '&school_year_id=' + this.getUrlVars().school_year_id,
            dataAdapterClassRoster: new jqx.dataAdapter(this.sourceRoster),
            dataAdapterSubmittedGrades: new jqx.dataAdapter(this.sourceSubmittedGrades),
            transmutationTable: null,
            headerroster: [
                { text: 'LRN', columngroup: 'StudentInfo', datafield: 'lrn', width: 150 },
                { text: 'Student Number', columngroup: 'StudentInfo', datafield: 'studentnumber', width: 150 },
                { text: 'Learners Name', columngroup: 'StudentInfo', datafield: 'fullname', width: 250 },
                { text: 'Gender', columngroup: 'StudentInfo', datafield: 'gender', width: 150 }
            ],
            submittedGradesColumn: [
                { text: 'Period', columngroup: 'SubmittedGrade', datafield: 'period_name'},
                { text: 'Date Submitted', columngroup: 'SubmittedGrade', datafield: 'submitted_at'},
            ],
            dataCompleteLoaded: false,
            columns: null,
            jqxTabsHeight: 600,
            tabLoaded: [],
            width: $('.box').width(),
            tabsPeriod: null,
            summaryLoaded: false,
        }
    }, // data()
    beforeCreate() {
        // Initialize The API Roster
        this.$nextTick();
        self = this;
        let urlRoster = '/admin/api/setup-grade/studentroster' + allParameters;
        this.sourceRoster = {
            datatype: 'json',
            url: urlRoster,
            async: true,
            id: "idRoster",
            root: "rootRoster",
            updaterow: (rowid, rowdata, commit) => {
                commit(false);
            }
        };
        this.sourceSubmittedGrades = {
            datatype: 'json',
            url: '/admin/encode-grade/submitted-grades' + allParameters,
            id: "idsubmittedGrades",
            async: true,
            root: "submittedGrades",
            updaterow: (rowid, rowdata, commit) => {
                commit(false);
            }
        };
        axios.get('/admin/transmutation/get-active')
            .then(function (response) {
                if(response.data !== null) {
                    self.transmutationTable = response.data.transmutation_table;
                }
            }).catch(error => {
                $.alert("Network/Server Error");
            });
    
    }, // beforeCreate()
    methods: {
        getUrlVars() {
            var vars = {};
            var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
                vars[key] = value;
            });
            return vars;
        },
        async initTabContent() {
            self = this;
            await axios.get( "fetch-tabs-period" + this.requestParam)
                    .then(response => {
                        self.tabsPeriod = response.data;
                        self.dataCompleteLoaded = true;
                    }).catch(error => {
                        $.alert("Network Error/Server Error");
                    });
        },
        async CreateJqxGrid (period_id) {
            // Check If Data Is Already Exist Or Not
            let url = '';
            let hasExistingRecord = self.hasExistingRecord(period_id);
            url = hasExistingRecord.message ? 'encode/load-data' + allParameters + '&period_id=' + period_id : '/admin/api/setup-grade/studentdata' + allParameters + '&period_id=' + period_id;
            let source = {
                datatype: 'json',
                url: url,
                id: "GradeID",
                root: "Grade",
                async: true,
                cache: false,
                updaterow: (rowid, rowdata, commit) => {
                    commit(true);
                }
            };
            var topcolumnheader = [];
            await axios.get("/admin/api/setup-grade/topcolumnheader" + allParameters + '&period_id=' + period_id)
            .then(function(response) {
                var data = response.data[0]
                $.each(data.columns, function (index, value){
                     topcolumnheader.push(value);
                });
            }).catch(error => {
                $.alert("Network/Server Error");
            });

            let data;
            let subcolumnheader = new Array();
            let thisParent = self;
            var tmpVar = [];
            var editable = false;

            await axios.get( "/admin/api/setup-grade/subcolumnheader" + allParameters + '&period_id=' + period_id)
            .then(function(response)  {
                data = response.data;
                if(data.length < 1) { return; }
                var counterForEditable    = 1;                    
                var counterForNonEditable = 1;      
                editable = data[0].submitted;
                for(var i = 1; i <= data[0].counter - 1; i++) {
                    let data = [];
                    tmpVar.push(data);
                }
                $.each(data[0].columns, function (index, value) {
                    let hasAggregates = _.has(value, 'aggregates');
                    if(hasAggregates) {
                        value.aggregates = [{
                            '':
                                (aggregatedValue, currentValue, column, record) => {
                                    if(typeof value.max === "number") {
                                        return value.max
                                    }
                                    return '';
                                }
                        }];
                    }
                    // If Item Is Editable Or Inputtable
                    if(value.itemEditableCell) {
                        value.validation = function (cell, val) {
                            if (val < 0 || val > value.max) {
                                return { result: false, message: "[0 - " + value.max + "]" };
                            }
                            return true;
                        };
                        
                        tmpVar[value.order - 1].push(value.datafield);
                    }
                    if(value.editable == false) { 
                    
                        $.each(tmpVar, function (key, val)  {
                            //  TOTAL
                            if(value.datafield.indexOf('total-') !== -1 && (value.totalOrder - 1) === key) {
                                value.cellsrenderer = function (index, datafield, val, defaultvalue, column, rowdata) {
                                    var total = 0;
                                    for(var i = 0; i < tmpVar[key].length; i++) {
                                        total += parseFloat(rowdata[tmpVar[key][i]]) || 0;
                                    }
                                    rowdata['total_' + key] = total; 
                                    return "<div style='margin: 4px;' class='jqx-center-align'>" + total.toFixed(2)  + "</div>";
                                };
                            }
                            //  PS
                            if(value.datafield.indexOf('ps-') !== -1 && (value.psOrder - 1) === key) {
                                value.cellsrenderer = function (index, datafield, val, defaultvalue, column, rowdata) {
                                    var total = 0;
                                    for(var i = 0; i < data[0].counter; i++) {
                                        total = (parseFloat(rowdata['total_' + parseInt(value.psOrder - 1)]) / parseFloat(value['hps-' + value.psOrder])) * 100 || 0;
                                    }
                                    rowdata['ps_' + key] = total;
                                    return "<div style='margin: 4px;' class='jqx-center-align'>" + total.toFixed(2)  + "</div>";
                                };
                            }
                            //  WS
                            if(value.datafield.indexOf('ws-') !== -1 && (value.wsOrder - 1) === key) {
                                value.cellsrenderer = function (index, datafield, val, defaultvalue, column, rowdata) {
                                    var total = 0,
                                          hps = parseInt(data[0].hps_ws[value.wsOrder - 1].max),
                                           ps = rowdata['ps_' + parseInt(value.wsOrder - 1)],
                                        total = (ps / 100 * 1) * hps;
                                    
                                    rowdata['ws_' + key] = total;
                                    return "<div style='margin: 4px;' class='jqx-center-align'>" + total.toFixed(2)  + "</div>";
                                };
                            }
                            //  INITIAL GRADE
                            if(value.datafield.indexOf('initial-grade') !== -1) {
                                value.cellsrenderer = function (index, datafield, val, defaultvalue, column, rowdata) {
                                    var total = 0;
                                    var failedColor = '';
                                    for(var i = 0; i < data[0].counter; i++) {
                                        total += parseFloat(rowdata['ws_' + i]) || 0;
                                    }
                                    if(total < 75) { failedColor = 'color: red;'; }
                                    rowdata['initial_grade'] = parseFloat(total.toFixed(2));
                                    return "<div style='margin: 4px;" + failedColor + "' class='jqx-right-align'>" + total.toFixed(3)  + "</div>";
                                };
                            }
                            //  QUARTERLY GRADE / (TRANSMUTED GRADE)
                            if(value.datafield.indexOf('quarterly-grade') !== -1) {
                                value.cellsrenderer = function (index, datafield, val, defaultvalue, column, rowdata) {
                                    var transmuted_grade = '-';
                                    var failedColor = '';
                                    $.each(thisParent.transmutationTable, function (key, val) {
                                        if(parseFloat(val.min) <= rowdata['initial_grade'] || rowdata['initial_grade'] >= parseFloat(val.max)) {
                                            rowdata['quarterly_grade'] = val.transmuted_grade;
                                            transmuted_grade = val.transmuted_grade;
                                            return false;
                                        }
                                    });
                                    if(transmuted_grade < 75) { failedColor = 'color: red;'; }
                                    return "<div style='margin: 4px;" + failedColor + "' class='jqx-right-align'>" + transmuted_grade  + "</div>";
                                };
                            }
                        });
                    }
             
                    subcolumnheader.push(value);
                });
            }).catch(error => {
                $.alert("Network/Server Error");
            });
    
            // Create New Component Grid
            var Grid = Vue.extend(CreateNewGrid); // GRID
            var instance = new Grid({
                components: { JqxGrid, JqxButton },
                propsData: {
                    type: 'primary',
                    period_id: period_id,
                    dataSource: source,
                    editable: editable,
                    topColumnHeader: topcolumnheader,
                    subColumnHeader: subcolumnheader
                }
            });
            instance.$mount('#period' + period_id); 
        },

        async CreateSummaryGrid () {
            let self = this;

            var columns = [{
                text: "Learner's Name",
                datafield: "learnersname",
                editable: false,
            }];

            var dFields = [{
                name: 'learnersname', 
                type: 'string',
            }];

            $.each(self.tabsPeriod, function (key, val) {
                let newDataCols = {
                    text: val.name, 
                    datafield: 'period' + val.id,
                    editable: false
                };

                columns.push(newDataCols);

                let newDataFields = {
                    name: 'period' + val.id,
                    type: 'string'
                };

                dFields.push(newDataFields);
            });

            var finalGradeColumn = {
                text: "Final Grade",
                datafield: "finalgrade",
                editable: false,
                cellsrenderer: function (index, datafield, val, defaultvalue, column, rowdata) {
                    var total = 0;
                    var failedColor = '';

                    $.each(self.tabsPeriod, function(key, val) {
                        if(rowdata !== null) {
                            if(rowdata['period' + val.id] !== null) {
                                total += parseFloat(rowdata['period' + val.id]);
                            }
                        }
                    });

                    var finalGrade = (total / self.tabsPeriod.length).toString();
                    rowdata['finalgrade'] = finalGrade;
                    return "<div style='margin: 4px;' class='jqx-center-align'>" + 
                                finalGrade +
                            "</div>";
                }
            };

            var finalGradeField = {
                name: 'finalgrade', 
                type: 'string',
            }

            columns.push(finalGradeColumn);
            dFields.push(finalGradeField);

            // Final Grade

            let url = '/admin/summary-grade' + allParameters;
            let source = {
                datatype: 'json',
                url: url,
                id: "SummaryID",
                datafields: dFields,
            };


            // Create New Component Grid
            var Grid = Vue.extend(SummaryGrid); // GRID
            var instance = new Grid({
                components: { JqxGrid, JqxButton },
                propsData: {
                    type: 'primary',
                    dataSource: source,
                    // topColumnHeader: topcolumnheader,
                    cols: columns,
                }
            });
            instance.$mount('#summary');
        },

        onTabClick(event) {
            var selectedItem    = this.$refs.myTabs.selectedItem; 
            var selectedTab     = event.args.item;
            var title           = this.$refs.myTabs.getTitleAt(selectedTab);

            if(_.some(this.tabLoaded, { tab_id: selectedTab, loaded: false })) {
                var index = _.findIndex(this.tabLoaded, { tab_id: selectedTab });
                var data = _.find(this.tabLoaded, { tab_id: selectedTab });
                new this.CreateJqxGrid(data.period_id);
                this.tabLoaded[index].loaded = true;
            }

            // Summary Grid
            if(event.args.item == (this.tabLoaded.length + 1) && !this.summaryLoaded) {
                this.CreateSummaryGrid();
                this.summaryLoaded = true;
            }
        },
        hasExistingRecord(period_id) {
            var result = null;
            var url = "/admin/encode-grade/encode/is-data-exist"  + allParameters + '&period_id=' + period_id;
             $.ajax({
                url: url,
                type: 'get',
                dataType: 'json',
                async: false,
                success: function(data) {
                    result = data;
                },
                error: function(xhr, status, error){
                    // var errorMessage = xhr.status + ': ' + xhr.statusText
                    $.alert('Network/Server Error');
                }
             });
             return result;
        },
    }, // methods()
    watch: {
        tabLoaded: function (val) {},
        async dataCompleteLoaded() {
            let self = this;
            if(self.dataCompleteLoaded) {
                let i = 1;
                $.each(self.tabsPeriod, function (key, val) {
                    self.period_id = val.id;
                    let content = '<div id="period' + val.id + '" period-id="' + val.id + '">Fetching data...</div>';
                    // Add Tabs
                    self.$refs.myTabs.addAt(i,val.name, content);
                    self.$refs.myTabs.ensureVisible(-1);
                    self.tabLoaded.push({
                        tab_id: i,
                        period_id: val.id,
                        loaded: false
                    });
                   i++;
                });

                // Add Tabs
                self.$refs.myTabs.addAt(self.tabLoaded.length + 1, "Summary", '<div id="summary">Fetching Data...</div>');
                self.$refs.myTabs.ensureVisible(-1);

                self.$refs.myTabs.select(0);
            }
            
        }
    },
    mounted () { }, // mounted()
}
</script>

<style>
    .jqx-grid-column-header {
        z-index: 1 !important;
    }
    .jqx-tabs-content-element {
        overflow: hidden;
    }
</style>