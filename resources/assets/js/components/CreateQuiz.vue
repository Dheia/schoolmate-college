<template>

    <div >
        <!-- If you want to show survey, uncomment the line below -->
        <!-- <survey :survey="survey"></survey> -->
        <!-- If you want to hide Survey Creator, comment the line below -->
        <survey-creator :quiz-id="id"></survey-creator>
    </div>

</template>


<script>

import SurveyCreator from './SurveyCreator'
import * as SurveyVue from 'survey-vue'

window.Survey = SurveyVue.Survey
var mainColor = "#7ff07f";
var mainHoverColor = "#6fe06f";
var textColor = "#4a4a4a";
var headerColor = "#7ff07f";
var headerBackgroundColor = "#4a4a4a";
var bodyContainerBackgroundColor = "#f8f8f8";

// var defaultThemeColorsSurvery = Survey.StylesManager.ThemeColors["default"];
// defaultThemeColorsSurvey["$main-color"] = mainColor;
// defaultThemeColorsSurvey["$main-hover-color"] = mainHoverColor;
// defaultThemeColorsSurvey["$text-color"] = textColor;
// defaultThemeColorsSurvey["$header-color"] = headerColor;
// defaultThemeColorsSurvey["$header-background-color"] = headerBackgroundColor;
// defaultThemeColorsSurvey["$body-container-background-color"] = bodyContainerBackgroundColor;
// Survey.StylesManager.applyTheme();

Survey.cssType = "bootstrap";

//If you want to add custom widgets package
//Add these imports
import * as widgets from "surveyjs-widgets";

//And initialize widgets you are want ti use
widgets.select2(SurveyVue);
widgets.inputmask(SurveyVue);
widgets.jquerybarrating(SurveyVue);
widgets.jqueryuidatepicker(SurveyVue);
widgets.nouislider(SurveyVue);
widgets.select2tagbox(SurveyVue);
widgets.sortablejs(SurveyVue);
widgets.ckeditor(SurveyVue);
widgets.autocomplete(SurveyVue);
widgets.bootstrapslider(SurveyVue);

export default {
  components: {
    Survey,
    SurveyCreator
  },
  data () {
    //Define Survey JSON
    //Here is the simplest Survey with one text question
    var json = {
      surveyId: this.id
    };
    
    //Create the model and pass it into VueSJ Survey component
    var model = new SurveyVue.Model(json)
    //You may set model properties
    // model.mode="display"

    return {
        survey: model
    }
  },
  methods: {
    
  },
  props: ['id']
}

</script>


