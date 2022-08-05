<style>
  .box {
    display: flex;
    flex-wrap: wrap;
    padding: 10px;
    margin-bottom:10px;
  }
  .row-box{
    flex-direction: row;
  }
  .answers{
    padding: 20px;
    padding-top: 0px;
    width: 82%;
  }

  ::placeholder {
    color: #000;
    font-size: medium;
    font-weight:500;
  }
  .input{
     font-size: medium;
    font-weight:500;
  }
  input:hover {
  background-color: #F2F2F3;
  cursor: pointer;
  }

  input:focus {
  background-color: #fff;
  color: #000;
  outline: none;
  cursor: auto;
  }

  textarea:hover {
  background-color: #F2F2F3;
  }
  
  textarea:focus{
    background-color: #fff;
    color: #000;
    outline: none;
  }
  .btn-save{
   padding: 20px;
  }
  .sortable{
  }
  .sortable-drag{
     opacity: 0;
  }
  .flip-list-move{
    transition: transform 0.5s;
  }
  .ghost{
    border-left: 6px solid rgb(0, 183, 255);
    box-shadow: 10px 10px 5px -1px rgba(0, 0, 0, 0.14);
    border-radius: 20px;
  }
  .btn-remove{
    margin-left: auto;
    margin-right: 0px;
    margin-top:auto;
  }
  .question-box{
    font-weight: 700;
    background: #3c8dbc;
    border: none;
    color: #fff;
    font-size: 14px;
    box-shadow: #ccc 0 0 2px;
    padding: 10px;
    margin-right:10px;
    height: 70px;
  }
  .quiz-input{
    margin-top:20px;
    border-radius: 5px; 
    background: #3c8dbc;
    padding: 12px;
    height: 100px;
  }  
  .mune-quiz{
    display: flex;
    overflow: auto;
    padding: 1px;
  } 
  .tabs-component-tabs{
    display: flex !important;
  }
  .question-box:hover{
    background: #1C6DB3;
  }
  ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
  }
  li.tabs-component-tab.is-active{
    text-align: center;
    padding-top: 9px;
    background: #3c8dbc;
    width: 70px;
    margin-bottom: -20px;
    margin-left: 5px;
    font-size: 14px;
    font-weight: 700;
    border-radius: 3px;
    border-bottom-left-radius: 0px;
    border-bottom-right-radius: 0px;
    box-shadow: #ccc 0 0 2px;
  }
  .tabs-component-tab{
    text-align: center;
    padding-top: 9px;
    background: #999;
    box-shadow: #ccc 0 0 2px;
    width: 70px;
    margin-bottom: -20px;
    margin-left: 5px;
    font-size: 14px;
    font-weight: 700;
    border-radius: 3px;
    border-bottom-left-radius: 0px;
    border-bottom-right-radius: 0px;
  }
  li.tabs-component-tab.is-active:hover{
     background: #1C6DB3;
  }
  .tabs-component-tab-a{
    color: #fff;
  }
  .tabs-component-tab-a:hover{
    color: #fff;
    text-decoration: none;
  }
  .points{
    float:right; 
    margin-top:10px; 
    width:60px; 
    height:35px;
    text-align: right;
    border: none;
    border-bottom: 1px solid #000;
    font-size: 14px;
  }
  /* two classes, decided on scroll */
.nav {
  transition: 100ms;
  padding: 25px;
}

.sticky-nav{
  transition: 100ms;
  padding: 20px;
}

#nav {
  width: 78.5%;
  position: fixed;
  bottom: 0;
}

/* have to add the ID nav (#nav) otherwise the backgrnd color won't change as the previous background color is set in an ID and ID trumps class notation */
#nav.sticky{
  transition: 150ms;
  box-shadow: 0px 15px 10px -15px #111;
  background-color:  #ccd6dd;
  }
  .drag{
    cursor: n-resize;
    padding-top:9px;
    padding-left:5px;
  }
  .cke_chrome {
    border: 0px;
  }
  .select{
    width: 150px;
    font-size:14px;
  }
  .cke_textarea_inline{
    font-size: 15px;
  }
  .btn-delete{
    border:0px; 
    width:100px; 
    margin-top:5px; 
    color:#fff; 
    background:#FF0000; 
    font-size: 13px;
    font-weight: bold;
  }
</style>
<template>
  <div class="col-md-12 question">
       <!-- Loading GIF -->
      <div class="row" v-if="isLoading">
          <div style="padding-top: 80px;">
            <img class="img-responsive" v-bind:src="'/vendor/backpack/crud/img/ajax-loader.gif'" alt="Loading..." style="margin: auto;">
          </div>
      </div>
     
    <draggable v-model="questions" ghost-class="ghost" :disabled="!lockQuiz" :options="{handle:'.drag'}" >
      <transition-group type="transition" name="flip-list">
      <div class="sortable" :id="question.id" v-for="(question,index) in questions" :key="question.id" v-if="!isLoading">

        <!-- Choose one  -->
          <div style="display: flex; flex-direction: column;" v-if="question.question_type == 'choose_one'"  class="box no-border " >
             <div style="display: flex; flex-direction: row; flex: 1;">
              <div class="drag"><i class="fa fa-arrows-v"></i></div>
                <h5 style="padding-top:3px; padding-left:10px;"><b>{{ index + 1}}. </b></h5> 
                <ckeditor :contentEditable="false"  :editor-url="editorUrl" :config="editorConfig" type="inline" v-model="question.title" style="width:85%; margin-top:10px;margin-left:15px; margin-right:15px;"></ckeditor>
                
                <input  class="points" min="0" type="number" v-model="question.points" :disabled="!lockQuiz">
                  <h6 style="padding-top:10px; padding-left:10px;">Points</h6>
             </div>

            <div v-for="choice in question.choices" id="v-model-radiobutton" class="answers" >
              <div v-for="correctAnswer in correctAnswers" v-if="correctAnswer.question_id === question.id" :id="question.id" :key="question.id">
                <div style="display: flex; flex-direction: row;">
                  <input :disabled="!lockQuiz"  type="radio" v-model="correctAnswer.choose_one"  value="a" style="margin-left:20px; margin-top:5px;">
                  <ckeditor :editor-url="editorUrl" :disabled="!lockQuiz" :config="editorConfig" type="inline" v-model="choice.a" style="margin-left:15px; border:0px; width:100%;"></ckeditor>
                </div>
                <div style="display: flex; flex-direction: row;">
                  <input :disabled="!lockQuiz" type="radio" v-model="correctAnswer.choose_one" value="b" style="margin-left:20px; margin-top:5px;">
                  <ckeditor :editor-url="editorUrl" :disabled="!lockQuiz" :config="editorConfig" type="inline" v-model="choice.b" style="margin-left:15px; border:0px; width:100%;"></ckeditor>
                </div>
                <div style="display: flex; flex-direction: row;">
                  <input :disabled="!lockQuiz" type="radio" v-model="correctAnswer.choose_one" value="c" style="margin-left:20px; margin-top:5px;">
                  <ckeditor :editor-url="editorUrl" :disabled="!lockQuiz" :config="editorConfig" type="inline" v-model="choice.c" style="margin-left:15px; border:0px; width:100%;"></ckeditor>
                </div>
                <div style="display: flex; flex-direction: row;">
                  <input :disabled="!lockQuiz" type="radio" v-model="correctAnswer.choose_one" value="d" style="margin-left:20px; margin-top:5px;">
                  <ckeditor :editor-url="editorUrl" :disabled="!lockQuiz" :config="editorConfig" type="inline" v-model="choice.d" style="margin-left:15px; border:0px; width:100%;"></ckeditor>
                </div>
              </div>
            </div>

            <div class="btn-remove">
              <select class="select" name="" id="">
                <option value="">Multiple Choice</option>
              </select>
              <button class="btn-delete" :disabled="!lockQuiz" @click="removeTodo(question)" >Delete</button>
            </div>
        </div>	

          <!-- CHOOSE MANY -->
        <div style="display: flex; flex-direction: column;" v-if="question.question_type == 'choose_many'" class="box no-border">
          <div style="display: flex; flex-direction: row; flex: 1;">
            <div class="drag"><i class="fa fa-arrows-v"></i></div>
              <h5 style="padding-top:3px; padding-left:10px;"><b>{{ index + 1}}. </b></h5> 
              <ckeditor :contentEditable="false"  :editor-url="editorUrl" :config="editorConfig" type="inline" v-model="question.title" style="width:85%; margin-top:10px;margin-left:15px; margin-right:15px;"></ckeditor>
              
              <input  class="points" min="0" type="number" v-model="question.points" :disabled="!lockQuiz">
                <h6 style="padding-top:10px; padding-left:10px;">Points</h6>
          </div>

              <div v-for="choice in question.choices" id="v-model-checkboxButton" class="answers">
                <div v-for="correctAnswer in correctAnswers" v-if="correctAnswer.question_id === question.id" :id="question.id" :key="question.id">
                  <div v-for="multiple_choice in correctAnswer.multiple_choice">
                      <div style="display: flex; flex-direction: row;">
                        <input :disabled="!lockQuiz" type="checkbox" v-model="multiple_choice.a" value="a" style="margin-left:20px; margin-top:10px;" required>
                        <ckeditor :editor-url="editorUrl" :disabled="!lockQuiz" :config="editorConfig" type="inline" v-model="choice.a" style="margin-left:15px; border:0px; width:100%;"></ckeditor>
                      </div>
                      <div style="display: flex; flex-direction: row;">
                        <input :disabled="!lockQuiz" type="checkbox" v-model="multiple_choice.b" value="b" style="margin-left:20px; margin-top:10px;" required>
                        <ckeditor :editor-url="editorUrl" :disabled="!lockQuiz" :config="editorConfig" type="inline" v-model="choice.b" style="margin-left:15px; border:0px; width:100%;"></ckeditor>
                      </div>
                      <div style="display: flex; flex-direction: row;">
                        <input :disabled="!lockQuiz" type="checkbox" v-model="multiple_choice.c" value="c" style="margin-left:20px; margin-top:10px;" required>
                        <ckeditor :editor-url="editorUrl" :disabled="!lockQuiz" :config="editorConfig" type="inline" v-model="choice.c" style="margin-left:15px; border:0px; width:100%;"></ckeditor>
                      </div>
                      <div style="display: flex; flex-direction: row;">
                        <input :disabled="!lockQuiz" type="checkbox" v-model="multiple_choice.d" value="d" style="margin-left:20px; margin-top:10px;" required>
                        <ckeditor :editor-url="editorUrl" :disabled="!lockQuiz" :config="editorConfig" type="inline" v-model="choice.d" style="margin-left:15px; border:0px; width:100%;"></ckeditor>
                      </div>
                      
                  </div>
                </div>
              </div>

              <div class="btn-remove">
                <select class="select" name="" id="">
                <option value="">Multiple Respone</option>
                </select>
                <button class="btn-delete" :disabled="!lockQuiz" @click="removeTodo(question)" >Delete</button>
              </div>	
          </div>

          <!-- TRUE OR FALSE -->
          <div style="display: flex; flex-direction: column;" v-else-if="question.question_type == 'true_false'" class="box no-border" >
            <div style="display: flex; flex-direction: row; flex: 1;">
              <div class="drag"><i class="fa fa-arrows-v"></i></div>
                <h5 style="padding-top:3px; padding-left:10px;"><b>{{ index + 1}}. </b></h5> 
                <ckeditor :contentEditable="false"  :editor-url="editorUrl" :config="editorConfig" type="inline" v-model="question.title" style="width:85%; margin-top:10px;margin-left:15px; margin-right:15px;"></ckeditor>
                
                <input  class="points" min="0" type="number" v-model="question.points" :disabled="!lockQuiz">
                  <h6 style="padding-top:10px; padding-left:10px;">Points</h6>
              </div>

                <div v-for="correctAnswer in correctAnswers"  v-if="correctAnswer.question_id === question.id" style="width:82%;" :id="question.id" :key="question.id">
                    <input :disabled="!lockQuiz" type="radio" v-model="correctAnswer.true_false" value="true" style="margin-left:40px; margin-top:10px;" required>
                    <label for="radio" style="margin-left:15px; font-size: medium; font-weight:500;">True</label>
                    <br> 
                    <input :disabled="!lockQuiz" type="radio" v-model="correctAnswer.true_false" value="false" style="margin-left:40px; margin-top:10px;">
                    <label for="radio" style="margin-left:15px; font-size: medium; font-weight:500;">False</label>
                </div>

                <div class="btn-remove">
                <select class="select" name="" id="">
                <option value="">True/False</option>
                </select>
                <button class="btn-delete" :disabled="!lockQuiz" @click="removeTodo(question)" >Delete</button>
              </div>
          </div>

          <!-- FILL IN THE BLANK -->
          <div style="display: flex; flex-direction: column;" v-else-if="question.question_type == 'fill_blank'" class="box no-border">
             <div style="display: flex; flex-direction: row; flex: 1;">
                <div class="drag"><i class="fa fa-arrows-v"></i></div>
                  <h5 style="padding-top:3px; padding-left:10px;"><b>{{ index + 1}}. </b></h5> 
                  <ckeditor :contentEditable="false"  :editor-url="editorUrl" :config="editorConfig" type="inline" v-model="question.title" style="width:85%; margin-top:10px;margin-left:15px; margin-right:15px;"></ckeditor>
                  
                  <input  class="points" min="0" type="number" v-model="question.points" :disabled="!lockQuiz">
                    <h6 style="padding-top:10px; padding-left:10px;">Points</h6>
                </div>

                  <div v-for="correctAnswer in correctAnswers" v-if="correctAnswer.question_id === question.id" style="width:82%;">
                     <ckeditor :editor-url="editorUrl" :disabled="!lockQuiz" :config="editorConfig" type="inline" v-model="correctAnswer.fill_blank" style="border:0px; width:90%; margin-top:10px; padding:5px; margin-left:40px"></ckeditor>

                  </div>

                <div class="btn-remove">
                <select class="select" name="" id="">
                  <option value="">Fill in the Blank</option>
                </select>
                <button class="btn-delete" :disabled="!lockQuiz" @click="removeTodo(question)" >Delete</button>
              </div>
          </div>

          <!-- ESSAY -->
            <div style="display: flex; flex-direction: column;" v-else-if="question.question_type == 'essay'" class="box no-border">
              <div style="display: flex; flex-direction: row; flex: 1;">
                <div class="drag"><i class="fa fa-arrows-v"></i></div>
                  <h5 style="padding-top:3px; padding-left:10px;"><b>{{ index + 1}}. </b></h5> 
                  <ckeditor :contentEditable="false"  :editor-url="editorUrl" :config="editorConfig" type="inline" v-model="question.title" style="width:85%; margin-top:10px;margin-left:15px; margin-right:15px;"></ckeditor>
                  
                  <input  class="points" min="0" type="number" v-model="question.points" :disabled="!lockQuiz">
                    <h6 style="padding-top:10px; padding-left:10px;">Points</h6>
              </div>
                <div style="width:82%">
                  
                </div>
                <div class="btn-remove">
                  <select class="select" name="" id="">
                    <option value="">Essay</option>
                  </select>
                  <button class="btn-delete" :disabled="!lockQuiz" @click="removeTodo(question)" >Delete</button>
                </div>
            </div>
        </div>
      </transition-group>
    </draggable>

      <div style="margin-bottom:70px;" class="text-center m-t-30" v-if="this.questions != null" >
        <button v-on:click="save(0)" v-if="!isLoading"
          style="border:0px; 
                width:100px; 
                color:#fff; 
                background:#32CD32; 
                font-weight: bold;
                font-size:14px;
                margin-bottom:10px;">Save</button>
          <!-- Loading GIF -->
        <div v-if="!isSaving">
            <div style="padding-top: 10px; padding-bottom: 10px;" v-if="!isLoading">
              <img class="img-responsive" v-bind:src="'/vendor/backpack/crud/img/ajax-loader.gif'" alt="Loading..." style="margin: auto;">
            </div>
        </div>
            <p style="font-size:14px;" v-if="!isLoading">Last saved on {{ dateNow }} </p>
      </div>
         <!-- QUIZ INPUT -->

      <div class="col-md-12" >
          <tabs v-if="!isLoading" id="nav">
              <tab name="Graded" >
                  <div class="quiz-input">
                    <div class="mune-quiz">
                      <button v-on:click="addQuestion($event)" value="choose_one" class="question-box">  
                        Multiple
                        <br>
                        Choice
                      </button>
                      <button v-on:click="addQuestion($event)" value="choose_many" class="question-box">  
                        Multiple
                        <br>
                        Response
                      </button>
                      <button v-on:click="addQuestion($event)" value="true_false" class="question-box">  
                        True/
                        <br>
                        False
                      </button>
                      <button v-on:click="addQuestion($event)" value="fill_blank" class="question-box">  
                        Fill in
                        <br>
                        the Blank
                      </button>
                      <button v-on:click="addQuestion($event)" value="fill_blank" class="question-box">  
                        Short
                        <br>
                        Answer
                      </button>
                      <button v-on:click="addQuestion($event)" value="essay" class="question-box">  
                        Essay
                      </button>
                      <div class="question-box" style="margin-left:auto;">
                          <h5 style="flex:1; padding-right:5px;">Total Points : <b>{{ Total }} </b></h5>
                      </div>
                    </div>
                </div>
              </tab>
              <tab name="Other">
                  <div class="quiz-input" v-if="!isLoading">
                    <div class="mune-quiz">
                      <!-- <button v-on:click="addQuestion($event)" value="text_block" class="question-box">  
                              Text
                              <br>
                              Block
                      </button> -->
                       <div class="btn-save question-box">
                       <b style="color:#fff;">Duration : </b> 
                        <input 
                        :disabled="!lockQuiz" 
                        v-on:change="check($event)" type="number" 
                        v-model="duration"  style="border: 0px;color:#000000;border-radius:3px; height:30px; width:60px; padding:5px;" min="0" max="60"> <b style="color:#fff;">minutes</b> 
                      </div>
                    </div>
                </div>
              </tab>
          </tabs>
      </div>

  </div>
</template>
<script>

import draggable from 'vuedraggable' 
import moment from 'moment'
import {Tabs, Tab} from 'vue-tabs-component'
import CKEditor from 'ckeditor4-vue'

export default{
  components:{
    draggable,
    Tabs,
    Tab,
    ckeditor: CKEditor.component
  },
  props: ['quiz_id'],
  data () {
     
    return {

      isSaving:true,
      isLoading: true,
      inuseQuiz:'',
      lockQuiz: true,
      quizId: this.quiz_id,
      question_id: '',
      q_type: null,
      question_type : '',
      questions:[],
      correctAnswers:[],
      template_answers: [],
      dateNow:null,
      duration:60,
      editorUrl: location.protocol + '//' + location.host + '/packages/ckeditor/ckeditor.js',
      editorConfig: {
        extraPlugins:'autogrow',
        autoGrow_minHeight:30,
        uiColor:'#ADD8E6',
        toolbarCanCollapse:false,
        toolbar: [
                  {name:'basicstyles',items:['Bold','Italic','Strike','-','RemoveFormat']},
                  {name:'editing',items:['Scayt']},
                  {name:'insert',items:['Image','Table','HorizontalRule','SpecialChar']},
                  {name:'paragraph',items:['NumberedList','BulletedList','-','Outdent','Indent','-']},
                  {name:'styles',items:['Styles','Format']}
          ]
        },
    }
  },
  created () {
    if(!this.lockQuiz){
      this.editorConfig = Object.assign({readOnly:true});
    }
    this.fetchQuestion();
    window.addEventListener('beforeunload', function(event) {
    event.returnValue = 'Write something'
    })
        
  },
  computed: {
    //TOTAL SCORE
    Total(){
      let total = 0;
      for (let item in this.questions) {
        total = total + parseInt(this.questions[item].points)
      }
      return total;
    }

  },

  methods: {
    select(event){
    	event.target.setSelectionRange(0, this.text.length);
    },
    check(){
        if(this.duration < 1){
           new PNotify({
                title: 'Invalid Input',
                text: 'Duration less than one is not allowed.',
                type: "warning"
            });
          this.duration = 60;
        }else if(this.duration > 60){
           new PNotify({
                title: 'Invalid Input',
                text: 'Duration exceed 60 minutes.',
                type: "warning"
            });
            this.duration = 60;
        }
      },
      fetchQuestion(){
        axios.get('/admin/quiz/question?quiz_id='+ this.quizId, {
          
          }) .then(response => {
            if(response.data.question_null == 1){

            }else{
                this.questions        = response.data.quiz.questions;
                this.template_answers = response.data.quiz.temp_answers;
                this.correctAnswers   = response.data.quiz.isCorrect;
                
            }
             if(response.data.student_quiz_results != null){
                this.lockQuiz =  false;
             }else{
                this.lockQuiz =  true;
             }

            this.duration     = response.data.quiz.duration;
            this.isLoading    = false;
            this.dateNow      = moment(response.data.quiz.updated_at).format('dddd, MMMM DD, YYYY hh:mm A');

        });

      	
      },
      addQuestion(event) {
        this.isSaving = false;
        this.q_type = event.target.value;
        if( event.target.value == 'fill_blank'){
           this.questions.push({ id: this.question_id= Math.ceil(Math.random()*1000000),question_type: this.q_type,points:1, 
              title:'Question',
              choices: [{
                        a: 'Choice 1',
                        b: 'Choice 2',
                        c: 'Choice 3',
                        d: 'Choice 4',

                    }],
          });
        }
        else{
          this.questions.push({ id: this.question_id= Math.ceil(Math.random()*1000000),question_type: this.q_type,points:1, 
              title:'Question',
              choices: [{
                        a: 'Choice 1',
                        b: 'Choice 2',
                        c: 'Choice 3',
                        d: 'Choice 4',

                    }],
          });
        }
    

        this.correctAnswers.push({
            question_id: this.question_id,
            question_type: this.q_type,
                multiple_choice: [{
                a: '',
                b: '',
                c: '',
                d: '',
                }],
            fill_blank:'Answer',
        });
        this.template_answers.push({
          question_id: this.question_id,
          question_type: this.q_type,
          choose_one: '',
          true_false:'',
          fill_blank:'',
          essay:'',
          multiple_choice: [{
              a: '',
              b: '',
              c: '',
              d: '',
          }],
        });
        this.question_type = '';
        this.save(1);
     

      },
      removeTodo(question) {
        this.template_answers.splice(this.template_answers.findIndex(id => id.question_id === question.id),1);
        this.correctAnswers.splice(this.correctAnswers.findIndex(id => id.question_id === question.id),1);
        this.questions.splice(this.questions.findIndex(id => id.id === question.id),1);
        this.save(1);
      
      },
    save(event){

      this.dateNow = moment().format('dddd, MMMM DD, YYYY hh:mm A');
      this.isSaving = true;  
      axios.post('/admin/quiz/question/save', {
        quiz_id          : this.quizId,
        quiz             : this.questions,
        correctAnswers   : this.correctAnswers,
        template_answers : this.template_answers,
        duration         : this.duration,

      }).then(function (response) {
        if(event == 0){
            new PNotify({
            title: 'Success',
            text: 'Question Data has been saved!',
            type: "success"
            });
          }

      }).catch(error => {
          if(event == 0){
          new PNotify({
              title: 'Error',
              text: 'Error, Something Went Wrong saving the Question Data, Please Try To Reload The Page.',
              type: "error"
          });
        }
        
        this.isSaving = false;  

      });
    }

  }

}
</script>