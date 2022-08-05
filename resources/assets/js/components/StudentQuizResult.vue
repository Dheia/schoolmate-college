<template>
  <div class="col-md-12">

    <!-- Loading GIF -->
    <div class="row" v-if="isLoading">
      <div style="padding-top: 80px;">
        <img class="img-responsive" v-bind:src="'/vendor/backpack/crud/img/ajax-loader.gif'" alt="Loading..." style="margin: auto;">
      </div>
    </div>

    <!-- Invalid Score Input -->
    <div v-if="invalidInputs.length > 0" class="callout callout-danger">
      <div v-for="invalidInput in invalidInputs">
        <p>Invalid score for question <b>no. {{ invalidInput.no}}</b></p>
      </div>
    </div>

    <!-- If you want to hide survey, comment the lines below -->
    <!-- <h2>SurveyJS Library - a sample survey below</h2> -->
    <div v-if="!isLoading" id="surveyElement" style="display:inline-block;width:100%;">
        <survey :survey="survey"></survey>
        <br>
        <!-- Submit Score Button -->
        <div v-model="isInputInvalid">
          <button v-if="!isChecked && !isInputInvalid" class="btn btn-success w-100" v-on:click="submitScore()"> Submit Score </button>
        </div>
    </div>

    <!-- Quiz Result -->
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
  SurveyVue.Serializer.addProperty("question", {name: "score:number"});
  SurveyVue.Serializer.addProperty("question", {name: "maxScore:number"});
  // console.log('SurveyVue: ', SurveyVue);
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
        surveyId: this.id,
        results: null,
        studentScore: [],
        newStudentScore: [],
        qName: null,
        nScore: 0,
        isChecked: false,
        finalScore: null,

        inputScores: [],
        latestInput: null,
        inputIndex: null,
        isInputInvalid: false,
        invalidInputs: [],

        currentScore: 0,
        totalScore: 0
      };
    },
    methods: {
      /**
      * Load Quiz Questionnaires
      **/
      async loadQuiz () {
        let _this = this;
        await axios.get('/admin/online-class/student-quiz-result/api/get/student-quiz-result/'+this.id)
          .then(response => {
            _this.isChecked   =  response.data.is_check == 1 ? true : false;
            _this.finalScore  = response.data.final_score ? response.data.final_score : null;

            _this.results     = response.data.results;
            _this.json    = response.data.class_quiz.quiz.json;
            var model     = new SurveyVue.Model(_this.json);
            
            _this.survey      = model;
            _this.survey.data = JSON.parse(response.data.results);

            // Display JSON Result
            _this.survey
              .onComplete
              .add(function (result) {
                  document
                      .querySelector('#surveyResult')
                      .textContent = "Result JSON:\n" + JSON.stringify(result.data, null, 3);
              });

            _this.survey.checkForErrors="";
            
          });

          this.isLoading    = false;

          _this.survey.mode                   = 'display';
          _this.survey.questionsOnPageMode    = "singlePage";
          _this.survey.showNavigationButtons  = "none";
          _this.survey.showProgressBar        = "off";
          _this.survey.showTimerPanel         = "none";
          _this.survey.maxTimeToFinishPage    = 0;
          _this.survey.maxTimeToFinish        = 0;

          _this.survey.title                  = '';
          _this.survey.description            = 'none';

          _this.survey.onAfterRenderQuestion.add(function (survey, options) {

            var span          = document.createElement("span");
            var correctAnswer = options.question.correctAnswer;
            var questionValue = options.question.questionValue;
            var isAnswerArray = Array.isArray(correctAnswer);
            var isQuestArray  = Array.isArray(questionValue);

            
            if(isAnswerArray || isQuestArray) {
              var isCorrect   = _.isEqual(correctAnswer, questionValue);
            } else {
              // Convert Correct Answer to Uppercase
              var correctAnswerCaps = correctAnswer ? correctAnswer.toUpperCase() : correctAnswer;
              var questionValueCaps = questionValue ? questionValue.toUpperCase() : questionValue;

              // Check if Answer is Correct (Case Insenstive)
              var isCorrect     = questionValueCaps == correctAnswerCaps ? true : false;
            }

            span.innerHTML    = isCorrect ? "(Correct)" : "(Incorrect)";
            span.style.color  = isCorrect ? "green" : "red";
            var header        = options.htmlElement.querySelector("h5");

            // If Answer is Not Correct
            if (!isCorrect) {
                header.style.backgroundColor = "salmon";

                if(correctAnswer == undefined) {
                  header.style.backgroundColor  = "";
                  span.innerHTML                = _this.isChecked ? "(Checked)" : "(To be check)";
                  span.style.color              = "blue";
                  _this.studentScore.push({name: options.question.name, score: 0, maxScore: options.question.maxScore});
                } else {
                  _this.studentScore.push({name: options.question.name, score: 0, maxScore: options.question.maxScore});
                }
                var radio = options
                    .htmlElement
                    .querySelector('input[value="' + options.question.correctAnswer + '"]');
                if (!!radio) {
                    radio.parentElement.style.color = "green";
                }
            } else {
              _this.studentScore.push({name: options.question.name, score: options.question.maxScore, maxScore: options.question.maxScore});
            }

            header.appendChild(span);
          });

          _this.survey
            .onAfterRenderQuestion
            .add(function (survey, options) {

              var correctAnswer = options.question.correctAnswer;
              var questionValue = options.question.questionValue;
              var isAnswerArray = Array.isArray(correctAnswer);
              var isQuestArray  = Array.isArray(questionValue);

              if(isAnswerArray || isQuestArray) {
                var isCorrect   = _.isEqual(correctAnswer, questionValue);
              } else {
                // Convert Correct Answer to Uppercase
                var correctAnswerCaps = correctAnswer ? correctAnswer.toUpperCase() : correctAnswer;
                var questionValueCaps = questionValue ? questionValue.toUpperCase() : questionValue;

                // Check if Answer is Correct (Case Insenstive)
                var isCorrect     = questionValueCaps == correctAnswerCaps ? true : false;
              }

              //Return if there is max score 
              if (!options.question.maxScore)  return;
              var maxScore            = document.createElement("span"); 
              maxScore.innerHTML      = ' / '+options.question.maxScore;

              var score               = document.createElement("input");
              score.value             = _this.GetScoreValue(options.question.name);
              options.question.score  = score.value;
              score.style             = "width:40px";
              score.type              = "number";
              score.max               = options.question.maxScore;
              score.min               = 0;
              score.id                = options.question.name;
              score.name              = options.question.name;

              if(!_this.isChecked) {
                // If Answer is Not Correct
                if(!isCorrect) {
                  if(correctAnswer == undefined) {
                    score.classList.add("scorable");
                  } else {
                    score.readOnly  = true;
                    score.disabled  = true;
                  }
                } else {
                  score.readOnly  = true;
                  score.disabled  = true;
                }
              } else {
                score.readOnly  = true;
                score.disabled  = true;
              }
              
              //Add a score input;
              var question    = options.question;

              score.onchange  = function() {
                options.question.score = score.value;
                //fire validation
                options.question.hasErrors(true);

                _this.changeScoreValue(options.question.name, options.question.score);
              };
              
              var header = options.htmlElement.querySelector("div");
              header.prepend(maxScore);
              header.prepend(score);
              // var header        = options.htmlElement.querySelector("h5");
              // header.appendChild(score);
              // header.appendChild(maxScore);
              
              options.question.readOnly = true;
            });
           
      },

      GetScoreValue(qName){
        let _this = this;
        var result;
        // var score = [{"name":"question1","score":2}, {"name":"question3","score":1}];
        if(_this.isChecked) {
          var score = JSON.parse(_this.finalScore);
        } else {
          var score = _this.studentScore;
        }
        _this.newStudentScore = score;

        result = score.filter(function(data) {return data.name == qName});
        return result.length>0?result[0].score:0;
      },

      /**
      * Change Student Score in Specific Question
      **/
      changeScoreValue(qName, score) {
        let _this = this;
        _this.qName = qName;
        // console.log(_this.studentScore);
        // Assign New Score
        _this.latestInput = null;
        _this.inputIndex = _.findIndex(_this.studentScore, {name: qName}) + 1;

        const newStudentScore = _.map(_this.studentScore, function(o) {
                                  var newItem = o;
                                  // Validate Input Score if Valid
                                  if(o.name == _this.qName) {
                                    if(Number(score) > o.maxScore || Number(score) < 0) {
                                      _this.latestInput = {no: _this.inputIndex, name: _this.qName, score: Number(score), error: true};
                                    } else {
                                      _this.latestInput = {no: _this.inputIndex, name: _this.qName, score: Number(score), error: false};
                                    }
                                  }
                                  return o.name == _this.qName ? {name: _this.qName, score: Number(score)} : o;
                                });
        _this.newStudentScore = newStudentScore;

        if(_this.inputScores.length <= 0) {
          _this.inputScores.push(_this.latestInput);
        } else {
          if(_this.latestInput) {
            if(!_.find(_this.inputScores, {name: _this.latestInput.name})) {
              _this.inputScores.push(_this.latestInput);
            } else {
              if(_this.latestInput) {
                var index = _.findIndex(_this.inputScores, {name: _this.latestInput.name});
                // Replace item at index using native splice
                _this.inputScores.splice(index, 1, _this.latestInput);
              }
            }
          }
          _this.inputScores   = _.orderBy(_this.inputScores, ['no'],['asc']);
        }
        _this.invalidInputs   = _.filter(_this.inputScores, { error: true });
        _this.isInputInvalid  = _this.invalidInputs.length > 0 ? true : false;


        console.log(_.sum(_.pluck(_this.newStudentScore, 'score')));

      },
      
      /**
      * Submit Student New Score
      **/
      submitScore: function () {
        let _this = this;
        // console.log(_this.studentScore, _this.id);

        if(!_this.isInputInvalid && !_this.isChecked){
          axios.post("/admin/online-class/student-quiz-result/" + _this.id + "/submit-score", {
              id: _this.id,
              // score: _this.studentScore
              score: _this.newStudentScore
            })
            .then(function (response) {
              new PNotify({
                title: response.data.title,
                text: response.data.message,
                type: response.data.error ? "warning" : "success",
              });
              location.reload();
            })
            .catch(error => {
              new PNotify({
                title: "Checking failed",
                text: "The item could not be checked. Please try again.",
                type: 'warning'
              });
            });

            // Reload the Page
            location.reload();
        } 
      }
    },
    mounted() {
      this.loadQuiz();
    },
    props: ['id']
  };
</script>

<style type="text/css" media="screen">
  .sv_main .sv_custom_header {
    background-color: unset; 
  }  
</style>
