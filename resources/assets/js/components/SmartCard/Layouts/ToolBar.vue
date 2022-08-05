<template>

<div>

  <div class="gt-toolbar">

    <div id="toolbar-undo" class="gt-button" :style="{'background-image': 'url(/smartcard/gToolbars/icons/undo.png)'}"></div>
    <div class="mdl-tooltip mdl-tooltip--large gt-noselect" for="toolbar-undo">Undo</div>

    <div id="toolbar-redo" class="gt-button" :style="{'background-image': 'url(/smartcard/gToolbars/icons/redo.png)'}"></div>
    <div class="mdl-tooltip mdl-tooltip--large gt-noselect" for="toolbar-redo">Redo</div>

    <div class="gt-separator"></div>

    <div id="toolbar-text" class="gt-button" :style="{'background-image': 'url(/smartcard/gToolbars/icons/insert-text.png)'}"></div>
    <div class="mdl-tooltip mdl-tooltip--large gt-noselect" for="toolbar-text">Insert Text</div>

    <div id="toolbar-shape" class="gt-button gt-dropdown" :style="{'background-image': 'url(/smartcard/gToolbars/icons/shapes.png)'}">
      <div class="gt-arrow"></div>

      <div class="gt-submenu gt-noselect gt-noshow">
        <div class="gt-submenu-item" id="toolbar-circle">
          <img :src="'/smartcard/gToolbars/icons/circle.png'" class="gt-submenu-icon">
          Circle
        </div>
        <div class="gt-submenu-item" id="toolbar-rectangle">
          <img :src="'/smartcard/gToolbars/icons/rectangle.png'" class="gt-submenu-icon">
          Rectangle
        </div>
      </div><!-- /toolbar-submenu -->
    </div><!-- /toolbar-shape -->
    <div class="mdl-tooltip mdl-tooltip--large gt-noselect" for="toolbar-shape">Insert Shape</div>

    <div class="gt-separator"></div>
    

    <!-- ARRANGE -->
    <div id="toolbar-arrange" class="gt-button gt-dropdown gt-noselect">
      <span class="gt-title">Arrange</span>
      <div class="gt-arrow"></div>

      <div class="gt-submenu gt-noselect gt-noshow">
        <div class="gt-submenu-item" id="toolbar-send-backward">
          <img :src="'/smartcard/gToolbars/icons/send-backward.png'" class="gt-submenu-icon">
          Send Backward
        </div>
        <div class="gt-submenu-item" id="toolbar-bring-forward">
          <img :src="'/smartcard/gToolbars/icons/bring-forward.png'" class="gt-submenu-icon">
          Bring Forward
        </div>
      </div><!-- /toolbar-submenu -->
    </div><!-- /toolbar-arrange -->
    <div class="mdl-tooltip mdl-tooltip--large gt-noselect" for="toolbar-arrange">Arrange Objects</div>
    <!-- /ARRANGE -->


    <div class="gt-separator"></div>
    
    <!-- FONT RESIZER -->
    <div id="toolbar-font-size" class="gt-button gt-input gt-dropdown">
      <input type="text" id="font-size" maxlength="3" class="gt-text-input gt-title gt-autoupdate">
      <div class="gt-arrow"></div>

      <div class="gt-submenu gt-noselect gt-scrolling gt-noshow">
        <div class="gt-submenu-item">
          <span>10</span>
        </div>
        <div class="gt-submenu-item gt-default">
          <span>12</span>
        </div>
        <div class="gt-submenu-item">
          <span>14</span>
        </div>
      </div><!-- /toolbar-submenu -->
    </div>
    <div class="mdl-tooltip mdl-tooltip--large gt-noselect" for="toolbar-font-size">Font Size</div>
    <!-- /FONT RESIZER -->

    <div class="gt-separator"></div>

    <!-- FONT FAMILY -->
    <div id="toolbar-font-family" class="gt-button gt-dropdown gt-noselect">
      <span class="gt-title gt-autoupdate" id="current-font"></span>
      <div class="gt-arrow"></div>

      <div class="gt-submenu gt-noselect gt-scrolling gt-noshow">
        <div class="gt-submenu-item">
          <span>Tahoma</span>
        </div>
        <div class="gt-submenu-item gt-default">
            <span>Arial</span>
        </div>
      </div><!-- /toolbar-submenu -->
    </div>
    <div class="mdl-tooltip mdl-tooltip--large gt-noselect" for="toolbar-font-family">Font</div>
    <!-- /FONT FAMILY -->

    <div class="gt-separator"></div>
    

    <div id="toolbar-bold" class="gt-button" :style="{'background-image': 'url(/smartcard/gToolbars/icons/bold.png)'}"></div>
    <div class="mdl-tooltip mdl-tooltip--large gt-noselect" for="toolbar-bold">Bold</div>

    <div id="toolbar-italics" class="gt-button" :style="{'background-image': 'url(/smartcard/gToolbars/icons/italics.png)'}"></div>
    <div class="mdl-tooltip mdl-tooltip--large gt-noselect" for="toolbar-italics">Italics</div>

    <div id="toolbar-underline" class="gt-button" :style="{'background-image': 'url(/smartcard/gToolbars/icons/underline.png)'}"></div>
    <div class="mdl-tooltip mdl-tooltip--large gt-noselect" for="toolbar-underline">Underline</div>

    <div class="gt-separator"></div>

    <div id="toolbar-fill-color" class="gt-button gt-dropdown" :style="{'background-image': 'url(/smartcard/gToolbars/icons/fill-color.png)'}">
      <div class="gt-arrow"></div>
        <div class="gt-submenu color-picker gt-noshow gt-no-auto-close">
        <!-- <input type='text' id="color-picker"/> -->
        <photoshop-picker  
          @input="updateColorPickerValue"
          @ok="onOkColorPicker"
          @cancel="onCancelColorPicker"
          :value="colorPicker.defautProps"
          :preset-colors="[ 
            '#f00', '#00ff00', '#00ff0055', 'rgb(201, 76, 76)', 'rgba(0,0,255,1)', 'hsl(89, 43%, 51%)', 'hsla(89, 43%, 51%, 0.6)'
          ]">
        </photoshop-picker>
      </div>
    </div>

    <div class="mdl-tooltip mdl-tooltip--large gt-noselect" for="toolbar-fill-color">Fill Color</div>

    <div id="toolbar-effects" class="gt-button gt-dropdown" :style="{'background-image': 'url(/smartcard/gToolbars/icons/effects.png)'}">
      <div class="gt-arrow"></div>
      <div class="gt-submenu gt-noselect gt-noshow gt-no-auto-close">
        <p>You can put anything <br/> you want in here.</p>
      </div>
    </div>
    <div class="mdl-tooltip mdl-tooltip--large gt-noselect" for="toolbar-effects">Effects</div>

    <div class="gt-separator"></div>

    <!-- CARD TYPE SELECTED -->
    <div id="toolbar-card-type" class="gt-button gt-dropdown gt-noselect" style="float: right;">
      <span class="gt-title gt-autoupdate" id="current-card"></span>
      <div class="gt-arrow"></div>

      <div class="gt-submenu gt-noselect gt-scrolling gt-noshow">
        <div class="gt-submenu-item gt-default" @click="changeCardType('front')">
          <img :src="'/smartcard/gToolbars/icons/bring-forward.png'" class="gt-submenu-icon">
          <span>Front Card</span>
        </div>
        <div class="gt-submenu-item" @click="changeCardType('back')">
            <img :src="'/smartcard/gToolbars/icons/send-backward.png'" class="gt-submenu-icon">
            <span>Back Card</span>
        </div>
      </div><!-- /toolbar-submenu -->
    </div>
    <div class="mdl-tooltip mdl-tooltip--large gt-noselect" for="toolbar-card-type">Selected Card Type</div>    
    <!-- CARD TYPE SELECTED -->
  </div><!-- /toolbar -->
</div><!-- /container -->
</template>


<style>
  /* --- button sizes --- */
  #toolbar-arrange {
    width: 70px;
  }
  #toolbar-font-family, 
  #toolbar-card-type {
    width: 100px;
  }

  .gt-submenu.color-picker {
    max-height: fit-content !important;
  }

  #smartcard-editor-container input:not([type]), 
  #smartcard-editor-container input[type=text]:not(.browser-default), 
  #smartcard-editor-container input[type=password]:not(.browser-default), 
  #smartcard-editor-container input[type=email]:not(.browser-default), 
  #smartcard-editor-container input[type=url]:not(.browser-default), 
  #smartcard-editor-container input[type=time]:not(.browser-default), 
  #smartcard-editor-container input[type=date]:not(.browser-default), 
  #smartcard-editor-container input[type=datetime]:not(.browser-default), 
  #smartcard-editor-container input[type=datetime-local]:not(.browser-default), 
  #smartcard-editor-container input[type=tel]:not(.browser-default), 
  #smartcard-editor-container input[type=number]:not(.browser-default), 
  #smartcard-editor-container input[type=search]:not(.browser-default), 
  #smartcard-editor-container textarea.materialize-textarea {
    height: 1.4rem !important;
  }

  /* --- toolbar icons --- */

    /*#toolbar-undo {
      background-image: url("smartcard/gToolbars/icons/undo.png");
    }

    #toolbar-shape {
      background-image: url("smartcard/gToolbars/icons/shapes.png");
    }*/
</style>

<script>
  
  import { Photoshop } from 'vue-color'

  export default {
    data() {
      return {
        baseUrl: location.protocol + '//' + location.host,
        selectedCardType: 'front',

        // COLOR PICKER
        colorPicker: {
          defautProps: {
            hex: '#194d33',
            hsl: { h: 150, s: 0.5, l: 0.2, a: 1 },
            hsv: { h: 150, s: 0.66, v: 0.30, a: 1 },
            rgba: { r: 25, g: 77, b: 51, a: 1 },
            a: 1
          } 
        }
      }
    },
    computed: {

    },
    methods: {
      changeCardType (type) {
        this.selectedCardType = type
        this.$store.state.cardType = type;
      },

      // COLOR PICKER
      updateColorPickerValue (value) {
        console.log('update ', value);
      },
      onOkColorPicker () {
        $(document).ready(function () {
          $('.gt-submenu.color-picker').addClass('gt-noshow');
          $('#toolbar-fill-color').removeClass('gt-button-active');
        })
        console.log('ok')
      },
      onCancelColorPicker () {
        $(document).ready(function () {
          $('.gt-submenu.color-picker').addClass('gt-noshow');
          $('#toolbar-fill-color').removeClass('gt-button-active');
        })
        console.log('cancel')
      },


    },

    mounted () {

    },

    components: {
      'photoshop-picker': Photoshop
    }
  }
</script>
