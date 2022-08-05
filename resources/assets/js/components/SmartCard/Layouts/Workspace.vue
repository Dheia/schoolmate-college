<template>
    <div>
        
        <!-- FRONT CARD -->
      
          <vue-fabric ref="frontCard" :width="width" :height="height"></vue-fabric>
    
        
        <!-- BACK CARD -->
        
          <vue-fabric ref="backCard" :width="width" :height="height"></vue-fabric>
       

    </div>
</template>

<style type="text/css">
  .vue-pan-zoom-scene > div {
    transform: matrix(0.5, 0, 0, 0.5, 0, 0);
  }
</style>

<script>

  import fabric from 'fabric';
  import 'fabric-customise-controls';

  export default {
    data() {
      return {
        height: 720,
        width: 449,
        baseUrl: location.protocol + '//' + location.host,
      }
    },
    computed: {
      cardType () {
        return this.$store.state.cardType;
      }
    },
    methods: {

      resetSize: function() {
        var workspace = $('.workspace');

        // $('.vue-pan-zoom-scene').height(workspace.height())
        // $('.vue-pan-zoom-scene').width(workspace.width());
      },

      onInit: function(panzoomInstance, id) {
        panzoomInstance.on('panstart', function(e){
        panzoomInstance.pause();
          // console.log(e);
        });
      },
      zoom: function (e) {
        // console.log('zoom ', e);
        this.resetSize();
      }
    },
    created () {
      this.resetSize();
      var _self = this;

    },
    mounted () {
      var _self = this;
      setTimeout(function () {
        _self.resetSize();
      }, 500)

      this.$store.state.frontCardRef = this.$refs.frontCard;
      this.$store.state.backCardRef = this.$refs.backCard;
    },
    components: {}
  }

</script>
