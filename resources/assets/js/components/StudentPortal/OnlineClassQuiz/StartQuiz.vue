<template>
  <div>

    <div class="col-md-6 mx-auto">
      <div id="surveyElement" style="display:inline-block;width:100%;">
        <survey :survey="survey"></survey>
      </div>
    </div>
    <div id="surveyResult"></div>

  </div> 
</template>

<script>

  import * as SurveyVue from "survey-vue";
  // import "bootstrap/dist/css/bootstrap.css";
  var Survey = SurveyVue.Survey;
  


  export default {
    props: ['quizItem', 'id'],
    components: {
      Survey
    },
    data() {
      var json = {}
      var model = new SurveyVue.Model(json);
      
      return {
        survey: model,
        url: window.location.protocol + '//' + window.location.host + '/student'
      }
    },

    beforeMount() {
        var model = new SurveyVue.Model(JSON.parse(this.quizItem).json);
        var _this = this;
        this.survey = model;

        this.survey.onComplete.add(function(result) {
          // document.querySelector('#surveyResult').textContent = "Result JSON:\n" + JSON.stringify(result.data, null, 3);
          axios.post(_this.url + '/online-class-quizzes/' + _this.id, {
            class_quiz_id: _this.id,
            result: JSON.stringify(result.data, null, 3),
            questionnaire: JSON.stringify(JSON.parse(_this.quizItem).json),
            answers: _this.getAnswers()
          });
        });
    },
    computed: {

    },
    methods: {
      getAnswers() {
        var questions = this.survey.getQuizQuestions();
        var correct = 0; var incorrect = 0;
        questions.forEach(function(question) {
          if(!question.isEmpty()) {
          if(question.isAnswerCorrect())
            correct ++;
          else incorrect ++;
          }
        })
        return {correct: correct, incorrect: incorrect};
      }
    },
    created() {
      $('body').bind('copy paste',function(e) {
        e.preventDefault(); return false; 
      })

      // document.addEventListener('contextmenu', function(e) {
      //   e.preventDefault();
      // });

      document.onkeydown = function(e) {
        if(event.keyCode == 123) {
           return false;
        }
        if(e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)) {
           return false;
        }
        if(e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0)) {
           return false;
        }
        if(e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)) {
           return false;
        }
        if(e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)) {
           return false;
        }
        if ((e.which || e.keyCode) == 116 || ((e.which || e.keyCode) == 82 && ctrlKeyDown)) e.preventDefault();
      }
    }
  }
</script>
