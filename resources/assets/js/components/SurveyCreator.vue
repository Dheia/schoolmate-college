<template>
  <div id="surveyCreatorContainer"></div>
</template>

<script>
import * as SurveyCreator from "survey-creator";
import "survey-creator/survey-creator.css";
import * as SurveyKo from "survey-knockout";
import * as widgets from "surveyjs-widgets";
import { init as customWidget } from "./customwidget";
// widgets.icheck(SurveyKo);
widgets.select2(SurveyKo);
widgets.inputmask(SurveyKo);
widgets.jquerybarrating(SurveyKo);
widgets.jqueryuidatepicker(SurveyKo);
widgets.nouislider(SurveyKo);
widgets.select2tagbox(SurveyKo);
widgets.sortablejs(SurveyKo);
widgets.ckeditor(SurveyKo);
widgets.autocomplete(SurveyKo);
widgets.bootstrapslider(SurveyKo);
// SurveyKo.Serializer.addProperty("question", "tag:number");

SurveyKo.Serializer.addProperty("question", {
      name: "maxScore:number",
      default: 1,
      category: "general"
  });

// Remove Default Properties From Layout  
// SurveyKo.Serializer.removeProperty("question", 'titleLocation');
SurveyKo.Serializer.removeProperty("question", 'descriptionLocation');
SurveyKo.Serializer.removeProperty("question", 'indent');
SurveyKo.Serializer.removeProperty("question", 'width');
SurveyKo.Serializer.removeProperty("question", 'minWidth');
SurveyKo.Serializer.removeProperty("question", 'maxWidth');
SurveyKo.Serializer.removeProperty("question", 'columnCount');

customWidget(SurveyKo);


var CkEditor_ModalEditor = {
  afterRender: function(modalEditor, htmlElement) {
    var editor = window["CKEDITOR"].replace(htmlElement);
    editor.on("change", function() {
      modalEditor.editingValue = editor.getData();
    });
    editor.setData(modalEditor.editingValue);
  },
  destroy: function(modalEditor, htmlElement) {
    var instance = window["CKEDITOR"].instances[htmlElement.id];
    if (instance) {
      instance.removeAllListeners();
      window["CKEDITOR"].remove(instance);
    }
  }
};
SurveyCreator.SurveyPropertyModalEditor.registerCustomWidget(
  "html",
  CkEditor_ModalEditor
);

export default {
  name: "survey-creator",
  data() {
    return {
      qID: this.quizId,
      json: null
    };
  },

  methods: {
    async loadQuiz () {
      let _this = this;
      await axios.get('/admin/quiz/api/get/questions/'+this.quizId)
        .then(response => {
          if(response.data.json == null){
            //Change default options and choices only
            let options = { 
              showEmbededSurveyTab: false,
              showLogicTab: true,
              showJSONEditorTab: false,
              questionTypes: ["comment", "text", "checkbox", "radiogroup", "dropdown", "image", "imagepicker"]
            };
            
            this.surveyCreator = new SurveyCreator.SurveyCreator(
              "surveyCreatorContainer",
              options
            );

            this.surveyCreator.showToolbox = "right";
            this.surveyCreator.showPropertyGrid = "right";
            this.surveyCreator.rightContainerActiveItem('toolbox');

            this.surveyCreator.saveSurveyFunc = function() {
              axios.post('/admin/quiz/save', {
                quiz_id: _this.qID,
                questions: JSON.stringify(this.text)
            })
            .then(response => {
              new PNotify({
                  title: response.data.title,
                  text: response.data.message,
                  type: response.data.error ? 'warning' : 'success'
              });
            })
            .catch(e => {
              this.errors.push(e);
              new PNotify({
                  title: 'Error',
                  text: 'Error, Something Went Wrong, Please Try To Reload The Page.',
                  type: 'warning'
              });
            })
            };
          } else {
            _this.json = response.data.json;
          }
        });
    }
  },

  mounted() {
    this.loadQuiz();
  },
  watch: {
    json: function (val) {
      let _this = this;
      //Change default options and choices only
      let options = { 
        showEmbededSurveyTab: false,
        showLogicTab: true,
        showJSONEditorTab: false,
        questionTypes: ["comment", "text", "checkbox", "radiogroup", "dropdown", "image", "imagepicker"]
      };
      
      this.surveyCreator = new SurveyCreator.SurveyCreator(
        "surveyCreatorContainer",
        options
      );

      this.surveyCreator.showToolbox = "right";
      this.surveyCreator.showPropertyGrid = "right";
      this.surveyCreator.rightContainerActiveItem('toolbox');

      this.surveyCreator.JSON = this.json;

      this.surveyCreator.saveSurveyFunc = function() {
        axios.post('/admin/quiz/save', {
          quiz_id: _this.qID,
          questions: JSON.stringify(this.text)
      })
      .then(response => {
        new PNotify({
            title: response.data.title,
            text: response.data.message,
            type: response.data.error ? 'warning' : 'success'
        });
      })
      .catch(e => {
        this.errors.push(e);
        new PNotify({
            title: 'Error',
            text: 'Error, Something Went Wrong, Please Try To Reload The Page.',
            type: 'warning'
        });
      })
      };
    }
  },

  props: ['quizId']
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
</style>