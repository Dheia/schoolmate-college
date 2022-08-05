<template>
    <div>
        <JqxGrid 
            ref="GradeTable"
            :showtoolbar="true"
            :theme="'bootstrap'" 
            :width="'100%'" 
            :height="'565'" 
            :max="150" 
            :columnsresize="true" 
            :source="source"
            :groupable="true"
            :pageable="true"
            :showaggregates="true" 
            :statusbarheight="25" 
            :selectionmode="'singlerow'"
            :showgroupaggregates="true"
            :showstatusbar="true"
            :altrows="true"
            :columns="columns"
            :columngroups="columnGroups">
        </JqxGrid>

    </div>
</template>

<script>
import JqxGrid from "jqwidgets-scripts/jqwidgets-vue/vue_jqxgrid.vue";
import JqxDataTable from 'jqwidgets-scripts/jqwidgets-vue/vue_jqxdatatable.vue';


export default {
    components: {
        JqxGrid,
    },
    props: ['transmutation-table'],
    data: function () {
      return {
        columnGroups: window.topcolumnheader[0].columns,
        columns: this.SubColumnHeader(),
        source: new jqx.dataAdapter(this.source),
        transmutation: JSON.parse(this.transmutationTable),
      }
    },
    beforeCreate: function () {
        this.source = {
            localdata: window.rows,
            // datafields: [
            //   { name: 'ContactName', type: 'string', map: '0' },
            //   { name: 'Title', type: 'string', map: '1' },
            //   { name: 'City', type: 'string', map: '2' },
            //   { name: 'Country', type: 'string', map: '3' }
            // ],
            datatype: 'array'
        };

      
    },

    methods: {
        SubColumnHeader (period_id) {
            let data;
            let subColumnHeaderArray = new Array();
            let thisParent = this;
            var tmpVar = [];
            var editable = false;
         
            data = window.subcolumnheader;
            if(data.length < 1) { return; }
            var counterForEditable    = 1;                    
            var counterForNonEditable = 1;      
            editable = false;
            for(var i = 1; i <= data[0].counter - 1; i++) {
                let data2 = [];
                tmpVar.push(data2);
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

                                $.each(thisParent.transmutation.transmutation_table, function (key, val) {
                                    if(parseFloat(val.min) <= rowdata['initial_grade'] || rowdata['initial_grade'] >= parseFloat(val.max)) {
                                        rowdata['quarterly_grade'] = val.transmuted_grade;
                                        transmuted_grade = val.transmuted_grade;
                                        return false;
                                    }
                                });

                                if(parseInt(rowdata['quarterly_grade']) < 75) { failedColor = 'color: red;'; }
                                return "<div style='margin: 4px;" + failedColor + "' class='jqx-right-align'>" + rowdata['quarterly_grade']  + "</div>";
                            };
                        }
                    });
                }
         
                subColumnHeaderArray.push(value);
            });

            return subColumnHeaderArray;
        },

        GetTransmutationGrade () {

        }
    }
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