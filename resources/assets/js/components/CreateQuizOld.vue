<template>
    <div class="col-md-12">
        <div class="box no-padding no-border">
            <div class="box" style="border-radius: 5px;">
                <div class="box-body with-border" style="padding: 20px 0 !important;">
                    <div id="quiz-editor-window-panel-pane">
                        <splitpanes style="height: 400px">
                            <pane min-size="3" max-size="20">
                                <left-tool-box></left-tool-box>  
                            </pane>
                            <pane>
                                <div class="scroll" style="overflow: auto; width: 100%;">
                                    <work-space></work-space> 
                                </div>
                            </pane>
                            <pane min-size="3" max-size="20" >
                                <right-tool-box></right-tool-box> 
                            </pane>
                        </splitpanes>
                    </div>
                </div>
            </div>
        </div><!-- /.box-body -->
    </div>


</template>

<style>
    #middleBox {
        padding-top: 10px;
    }
    .splitpanes__pane {
      display: flex;
      background-color: #f0f1f3;
    }
    .splitpanes--vertical>.splitpanes__splitter {
        width: 3px;
        color: #ccc;
        background-color: #ccc;
    }
</style>

<script>
    import { Splitpanes, Pane } from 'splitpanes'
    import LeftToolBox from './Quiz/LeftToolBox.vue';
    import WorkSpace from './Quiz/WorkSpace.vue';
    import RightToolBox from './Quiz/RightToolBox.vue';
    import 'splitpanes/dist/splitpanes.css'

    export default {
        props: ['id'],
        data: function () {
            return {
                
            }
        },
        methods: {
            
        },
        components: {
            Splitpanes, 
            Pane,
            LeftToolBox,
            WorkSpace,
            RightToolBox
        },
         mounted () {
          console.log(this);
        }
    };
</script>


