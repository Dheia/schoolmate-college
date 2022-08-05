<template>
    <div>

      <div id="smartcard-editor-container">
        
        <div id="smartcard-editor-header-tab-menu">
          <tab-menu></tab-menu>
        </div>
        <div id="smartcard-editor-header">
          <tool-bar></tool-bar>
        </div>

        <div id="smartcard-editor-left-panel-pane">
          <window-pane></window-pane>
        </div>
          

      </div>
    </div>
</template>

<script>

  import ToolBar from './Layouts/ToolBar.vue';
  import WindowPane from './Layouts/WindowPane.vue';
  import TabMenu from './Layouts/TabMenu.vue';

  export default {
    data() {
      return {
        baseUrl: location.protocol + '//' + location.host,
      }
    },
    computed: {

    },
    methods: {

    },
    components: {
      TabMenu,
      ToolBar,
      WindowPane,
    },
    mounted () {
      console.log(this.$store.getters.message);
    }
  }
</script>
