<template>
  <div class="col-md-12 no-padding">
    <!-- Loading GIF -->
    <div class="row" v-if="isLoading">
      <div style="padding-top: 80px;">
        <img class="img-responsive" v-bind:src="'/vendor/backpack/crud/img/ajax-loader.gif'" alt="Loading..." style="margin: auto;">
      </div>
    </div>
    <!-- If you want to hide survey, comment the lines below -->
    <!-- <h2>SurveyJS Library - a sample survey below</h2> -->
    <div v-if="!isLoading" id="surveyElement" style="display:inline-block;width:100%;">
            <survey :survey="survey"></survey>
    </div>
    <div v-if="!isLoading" id="surveyResult"></div>
    
  </div>
</template>

<script>
import * as SurveyVue from "survey-vue";
// import "bootstrap/dist/css/bootstrap.css";
var Survey = SurveyVue.Survey;
Survey.cssType = "bootstrap";
import * as widgets from "surveyjs-widgets";
import { init as customWidget } from "../components/customwidget";
// widgets.icheck(SurveyVue);
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
customWidget(SurveyVue);
SurveyVue.Serializer.addProperty("question", "tag:number");
export default {
  components: {
    Survey
  },
  data() {
    var json = {}

    var model = new SurveyVue.Model(json);
    return {
      isLoading: true,
      survey: model,
      surveyId: this.id
    };
  },
  methods: {
    async loadQuiz () {
      let _this = this;
      await axios.get('/admin/quiz/api/get/questions/'+this.id)
        .then(response => { 
          _this.json = response.data.json;
          var model = new SurveyVue.Model(_this.json);
          
          _this.survey = model;

          // _this.survey.data = {
          //   'question1': 'item1'
          // }
          _this.survey.data = response.data.correct_answers;

          _this.survey.mode = 'display';
          _this.survey.questionsOnPageMode = "singlePage";
          _this.survey.showNavigationButtons = "none";
          _this.survey.showProgressBar = "off";
          _this.survey.showTimerPanel = "none";
          _this.survey.maxTimeToFinishPage = 0;
          _this.survey.maxTimeToFinish = 0;
          // Display JSON Result
          _this.survey
              .onComplete
              .add(function (result) {
                  document
                      .querySelector('#surveyResult')
                      .textContent = "Result JSON:\n" + JSON.stringify(result.data, null, 3);
              });
        });
        this.isLoading    = false;
    }
  },
  mounted() {
    this.loadQuiz();
  },
  props: ['id']
};
</script>
