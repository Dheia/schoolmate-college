<template>
    <div>
      
      <splitpanes :style="'height:' + mainPanelHeight + 'px'" :push-other-panes="false">
        <pane size="13" min-size="13" max-size="15" class="left-pane" style="padding: 10px;">
          <left-panel></left-panel>  
        </pane>
        <pane>
          <splitpanes horizontal>
              <pane class="center-top-pane workspace">
                <workspace></workspace>
              </pane>
              <pane size="3" min-size="3" max-size="3">
                3
              </pane>
          </splitpanes>
        </pane>
        <pane size="13" min-size="13" max-size="13">
          4
        </pane>
      </splitpanes>

    </div>
</template>


<script>
  
  import { Splitpanes, Pane } from 'splitpanes'
  import Workspace from './Workspace'
  import LeftPanel from './LeftPanel.vue';

  import 'splitpanes/dist/splitpanes.css'

  export default {
    data() {
      return {
        baseUrl: location.protocol + '//' + location.host,
        mainPanelHeight: 0,
      }
    },
    computed: {

    },
    mounted () {
      this.resizeEventHandler();
    },
    methods: {
      resizeEventHandler(e) {
        console.log('resizing...');
        this.mainPanelHeight = $(window).height() - $('#smartcard-editor-header').height();

      }
    },
    created () {
      window.addEventListener("resize", this.resizeEventHandler);
    },
    destroyed () {
      window.removeEventListener("resize", this.resizeEventHandler);
    },
    components: {
      Splitpanes, 
      Pane,
      Workspace,
      LeftPanel,
    }
  }
</script>
