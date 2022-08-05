<template>
	<div>
		<JqxGrid 
				ref="summaryGrid"
				:theme="'bootstrap'" 
				:height="'565px'"
				:width="'100%'" 
				:source="dataAdapter"
				:filterable="true"
				:columnsresize="true" 
				:pageable="true"
				:max="150"
				:statusbarheight="25" 
				:showstatusbar="true"
				:altrows="true"
				:showaggregates="true"
				:rendertoolbar="rendertoolbar"
				:showtoolbar="true"
             	:columns="columns">
	    </JqxGrid>
	    
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

	props: [ 'dataSource', 'cols'],
	
	data: function () {
	    return {
			dataAdapter: this.dataSource,
			columns: this.cols,
        }
	},

	beforeCreate() {},

    methods: {
    	export () {
    		console.log("export");
    	},

    	rendertoolbar: function (toolbar) {
	    	let self = this;
            let buttonsContainer                = document.createElement('div');
                buttonsContainer.style.padding  = '5px';
                buttonsContainer.style.display  = 'flex';
                buttonsContainer.id             = 'buttonsContainer' + this.periodId;

                 // Submit Grades
            let exportButtonContainer                  = document.createElement('div');
                exportButtonContainer.id               = 'exportButtonContainer';
                // exportButtonContainer.style.float    = 'right';
                buttonsContainer.appendChild(exportButtonContainer);
                toolbar[0].appendChild(buttonsContainer);

            let exportButton = jqwidgets.createInstance(
                                        '#exportButtonContainer', 
                                        'jqxButton', 
                                        { 
                                            theme: 'bootstrap', 
                                            width: 135, value: 
                                            'Export' 
                                        }
                                    );

            exportButton.addEventHandler('click', () => {
            	const value = this.$refs.summaryGrid.exportdata('xls','jqxGrid');
            });
	    }

    },

    mounted () {
    	console.log("Summary Grade: ", this);
    },

    watch: {
        dataAdapter: function () {

        }
    },
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