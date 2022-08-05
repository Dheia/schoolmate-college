<template>
	<div>
        <JqxGrid 
            ref="GradeTable"
            @cellvaluechanged="onCellvaluechange($event)"
            @rowselect="onRowselect($event)" 
            :rendertoolbar="rendertoolbar"
            :showtoolbar="true"
            :theme="'bootstrap'" 
            :editable="editable"
            :width="'100%'" 
            :height="'565'" 
            :filterable="true"
            :max="150" 
            :columnsresize="true" 
            :source="dataAdapterSource"
            :groupable="true"
            :pageable="true"
            :showaggregates="true" 
            :statusbarheight="25" 
            :selectionmode="'singlecell'"
            :showgroupaggregates="true"
            :showstatusbar="true"
            :altrows="true"
            :columns="subColumnHeader"
            :columngroups="topColumnHeader">
        </JqxGrid>
        
        <!-- Search Window -->
        <div :id="'searchWindow' + periodId">
            <div>
                Find Record
            </div>
            <div style="overflow: hidden;">
                <div>
                    Find what:
                </div>
                <div style='margin-top:5px;'>
                    <input :id="'inputField' + periodId" type="text" class="jqx-input" style="width: 200px; height: 23px;" />
                </div>
                <div style="margin-top: 7px; clear: both;">
                    Look in:
                </div>
                <div style='margin-top:5px;'>
                    <div :id="'dropdownlist' + periodId">
                    </div>
                </div>
                <div>
                    <input type="button" style='margin-top: 15px; margin-left: 50px; float: left;' value="Find" :id="'findButton' + periodId" />
                    <input type="button" style='margin-left: 5px; margin-top: 15px; float: left;' value="Clear" :id="'clearButton' + periodId" />
                </div>
            </div>
        </div>
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
	},
	name: 'GradeTable',
	props: [ 'period_id', 'dataSource', 'topColumnHeader', 'subColumnHeader', 'editable' ],
    // props: {
    //     period_id: Number,
    //     dataSource: Object,
    //     topColumnHeader: Array,
    //     subColumnHeader: Array,
    //     editable: Boolean
    // },
    data() {
        return {
            hps: this.hps,
            periodId: this.period_id,
            isEditable: this.editable,
            dataAdapterSource: this.dataSource,
        }
    },
    methods: {
    	getUrlVars() {
            var vars = {};
            var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
                vars[key] = value;
            });
            return vars;
        },
    	rendertoolbar: function (toolbar) {

            if(!this.isEditable) { return; }

            let self = this;
            let buttonsContainer                = document.createElement('div');
                buttonsContainer.style.padding  = '5px';
                buttonsContainer.style.display  = 'flex';
                buttonsContainer.id             = 'buttonsContainer' + this.periodId;

            // Submit Grades
            let submitGradeButtonContainer                  = document.createElement('div');
                submitGradeButtonContainer.id               = 'submitGradeButtonContainer' + this.periodId;
                // submitGradeButtonContainer.style.float    = 'right';
                buttonsContainer.appendChild(submitGradeButtonContainer);
                toolbar[0].appendChild(buttonsContainer);
            
            let submitGradeButton = null;
            
            if(!this.editable) {
                submitGradeButton = jqwidgets.createInstance(
                                        '#submitGradeButtonContainer' + this.periodId, 
                                        'jqxButton', 
                                        { 
                                            theme: 'bootstrap', 
                                            width: 135, value: 
                                            'Submitted Grades' 
                                        }
                                    );
            } else {
                submitGradeButton = jqwidgets.createInstance(
                                        '#submitGradeButtonContainer' + this.periodId, 
                                        'jqxButton', 
                                        { 
                                            theme: 'bootstrap', 
                                            width: 135, 
                                            value: 'Submit Grades' 
                                        }
                                    );
            }
            submitGradeButton.addEventHandler('click', () => {
                $.confirm({
                    title: 'Confirmation',
                    content: '' +
                    '<form action="" class="formName">' +
                        '<div class="form-group">' +
                            '<p>Are Sure You Want To Submit This Grades? <br><small>Once Grade Is Submitted You Will Not Able To Edit This Grade(s)</small></p>' +
                            '<label>Please Enter Your Password</label>' +
                            '<input type="password" id="user_password_confirmation" placeholder="password" class="user_password_confirmation form-control" required />' +
                        '</div>' +
                    '</form>',
                    buttons: {
                        formSubmit: {
                            text: 'Submit',
                            btnClass: 'btn-blue',
                            action: function () {
                                var password = this.$content.find('#user_password_confirmation').val();
                                if(!password){
                                    $.alert('Please Enter Your Password');
                                    return false;
                                }
                                axios.post('submit-grades', {
                                    template_id   : self.getUrlVars().template_id, 
                                    subject_id    : self.getUrlVars().subject_id, 
                                    section_id    : self.getUrlVars().section_id, 
                                    term_type     : self.getUrlVars().term_type, 
                                    school_year_id: self.getUrlVars().school_year_id, 
                                    period_id     : self.periodId,
                                    password      : this.$content.find('#user_password_confirmation').val(),
                                }).then(function (response) {
                                    if(response.data.status == "OK") { 
                                        self.$refs.GradeTable.editable = false 
                                        $('#submitGradeButtonContainer' + self.periodId).remove();
                                    }
                                    $.alert(response.data.message);
                                }).catch(error => {
                                    $.alert("Network Error/Server Error");
                                    console.log(error.response)
                                });                          
                            }
                        },
                        cancel: function () {
                            //close
                        },
                    },
                    onContentReady: function () {
                        // bind to events
                        var jc = this;
                        this.$content.find('form').on('submit', function (e) {
                            // if the user submits the form by pressing enter in the field.
                            e.preventDefault();
                            jc.$$formSubmit.trigger('click'); // reference the button and click it
                        });
                    }
                });
            });

            // Add Marker E
            // let remarksButtonContainer                  = document.createElement('div');
            //     remarksButtonContainer.id               = 'remarksButtonContainer' + this.periodId;
            //     // remarksButtonContainer.style.float    = 'right';
            //     buttonsContainer.appendChild(remarksButtonContainer);
            //     toolbar[0].appendChild(buttonsContainer);
            //     let remarksButton = jqwidgets.createInstance('#remarksButtonContainer' + this.periodId, 'jqxButton', { theme: 'bootstrap', width: 135, value: 'Add Remarks' })
            //     remarksButton.addEventHandler('click', () => {
            //         console.log(self.$refs.GradeTable.getselectedcell());
            //         var selectedCell = self.$refs.GradeTable.getselectedcell();
            //         self.$refs.GradeTable.setcolumnproperty(selectedCell.column, 'columntype','string');
            //         // self.$refs.GradeTable.setcellvalue(selectedCell.rowindex, selectedCell.column,'E');
            //     });
        }, // rendertoolbar

        onRowselect: function (event) {
            if(!this.isEditable) { return; }
        },

        onCellvaluechange: function (event) {
            let args = event.args;
            let columnDataField = args.datafield;
            let rowIndex = args.rowindex;
            let cellValue = args.value;
            let oldValue = args.oldvalue;
            let self = this;
            // let allParameters = '?template_id=' + this.getUrlVars().template_id + '&subject_id=' + this.getUrlVars().subject_id + '&section_id=' + this.getUrlVars().section_id + ' &period_id' + period_id;
        
            var tblHeader = $('.jqx-grid-groups-header > div > div');
            tblHeader.html("Status: <span class='badge'>Saving..</span>")
            let rows = self.$refs.GradeTable.getrows();

            // SAVE OR UPDATE
            axios.post('encode', 
            {
                'template_id'   : self.getUrlVars().template_id, 
                'subject_id'    : self.getUrlVars().subject_id, 
                'section_id'    : self.getUrlVars().section_id, 
                'term_type'     : self.getUrlVars().term_type, 
                'school_year_id': self.getUrlVars().school_year_id, 
                'period_id'     : self.periodId, 
                'state'         : 'open', 
                'rows'          : rows
            }).then(function (response) {
                // SUCCESS
                tblHeader.html("Status: <span class='badge'><i class='fa fa-check>'></i>Success</span>")  
                setTimeout(function (){
                    tblHeader.text("Drag a column and drop it here to group by that column")
                }, 3000);
            }).catch( function (error) {
                tblHeader.html("Status: <span class='badge'><i class='fa fa-check>'></i>" + error + "</span>")  
                alert(error);
            });
        }, 
        onCellbeginedit: function (event) {
            alert('do something...');
          }
    },
    watch: {
        dataAdapterSource: function () {

        }
    },
}

</script>