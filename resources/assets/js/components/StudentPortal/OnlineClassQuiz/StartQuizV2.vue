
<style scoped>
  .box{
    box-shadow: #ccc 0 0 2px;
    background: white;
    margin: 20px auto;
    padding: 15px;
  }
  .p{
    margin-bottom: 0px !important;
  }
</style>
<template>
    <div class="col-md-12" style="margin-bottom:50px;" >
        <div v-if="!isHidden">
          <p class="text-right">Duration: {{ displayMinutes}} : {{displaySecond}}</p>
        </div>
      
         <div class="col-md-6" style="margin:auto;" v-for="(question,index) in questions " v-if="!isHidden">
             <div style="display: flex; flex-direction: column;" v-if="question.question_type == 'choose_one'"  class="box" >
                <div style="display: flex; flex-direction: row; flex: 1;">
                    <h6><b style="padding-right:15px;">{{ index+1}}. </b></h6>
                    <h6 v-html="question.title"></h6>&nbsp;
                     <b style="float:right; font-size:13px; color:#228B22; width:80px"> [{{ question.points }} points ]</b> 
                   
                </div>
                
                <div  v-for="choice in question.choices"  id="v-model-radiobutton">
                    <div v-for="stud_answer in student_answers" v-if="stud_answer.question_id === question.id" >
                       <div style="display: flex; flex-direction: row; margin-bottom: -15px;">
                          <input :id="`a${index}`" type="radio" v-model="stud_answer.choose_one" value="a" style="margin-left:20px; margin-top:10px;">
                          <label :for="`a${index}`" v-html="choice.a" style="margin-left:5px;cursor: pointer; text-center"></label>
                       </div>
                       <div style="display: flex; flex-direction: row; margin-bottom: -15px;">
                          <input :id="`b${index}`" type="radio" v-model="stud_answer.choose_one" value="b" style="margin-left:20px; margin-top:10px;">
                          <label :for="`b${index}`" v-html="choice.b" style="margin-left:5px;cursor: pointer;"></label>
                       </div>
                        <div style="display: flex; flex-direction: row; margin-bottom: -15px;">
                          <input :id="`c${index}`" type="radio" v-model="stud_answer.choose_one" value="c" style="margin-left:20px; margin-top:10px;">
                          <label :for="`c${index}`" v-html="choice.c" style="margin-left:5px;cursor: pointer;"></label>
                       </div>
                        <div style="display: flex; flex-direction: row; margin-bottom: -15px;">
                          <input :id="`d${index}`" type="radio" v-model="stud_answer.choose_one" value="d" style="margin-left:20px; margin-top:10px;">
                          <label :for="`d${index}`" v-html="choice.d" style="margin-left:5px;cursor: pointer;"></label>
                       </div>
                    </div>
                </div>
             </div>

             <div style="display: flex; flex-direction: column;" v-if="question.question_type == 'choose_many'"  class="box" >
                <div style="display: flex; flex-direction: row; flex: 1;">
                    <h6><b style="padding-right:15px;">{{ index+1}}. </b></h6>
                    <h6 v-html="question.title"></h6>&nbsp;
                     <b style="float:right; font-size:13px; color:#228B22; width:80px"> [{{ question.points }} points ]</b> 
                   
                </div>
                 <div v-for="choice in question.choices"  id="v-model-radiobutton">
                    <div v-for="stud_answer in student_answers" v-if="stud_answer.question_id === question.id" >
                      <div v-for="multiple_choice in stud_answer.multiple_choice">

                        <div style="display: flex; flex-direction: row; margin-bottom: -15px;">
                          <input :id="`a${index}`" type="checkbox" v-model="multiple_choice.a" value="a" style="margin-left:20px; margin-top:10px;">
                          <label :for="`a${index}`" v-html="choice.a" style="margin-left:5px;cursor: pointer;"></label>
                        </div>
                         <div style="display: flex; flex-direction: row; margin-bottom: -15px;">
                          <input :id="`b${index}`" type="checkbox" v-model="multiple_choice.b" value="b" style="margin-left:20px; margin-top:10px;">
                          <label :for="`b${index}`" v-html="choice.b" style="margin-left:5px;cursor: pointer;"></label>
                        </div>
                         <div style="display: flex; flex-direction: row; margin-bottom: -15px;">
                          <input :id="`c${index}`" type="checkbox" v-model="multiple_choice.c" value="c" style="margin-left:20px; margin-top:10px;">
                          <label :for="`c${index}`" v-html="choice.c" style="margin-left:5px;cursor: pointer;"></label>
                        </div>
                         <div style="display: flex; flex-direction: row; margin-bottom: -15px;">
                          <input :id="`d${index}`" type="checkbox" v-model="multiple_choice.d" value="d" style="margin-left:20px; margin-top:10px;">
                          <label :for="`d${index}`" v-html="choice.d" style="margin-left:5px;cursor: pointer;"></label>
                        </div>
                      </div>
                    </div>
              </div>
             </div>

             <div style="display: flex; flex-direction: column;" v-if="question.question_type == 'true_false'"  class="box" >
                <div style="display: flex; flex-direction: row; flex: 1;">
                    <h6><b style="padding-right:15px;">{{ index+1}}. </b></h6>
                    <h6 v-html="question.title"></h6>&nbsp;
                    <b style="float:right; font-size:13px; color:#228B22; width:80px"> [{{ question.points }} points ]</b> 
                  
                </div>

                <div v-for="choice in question.choices" id="v-model-radiobutton">
                   <div v-for="stud_answer in student_answers" v-if="stud_answer.question_id === question.id" >
                      <input :id="`true${index}`" type="radio" v-model="stud_answer.true_false" value="true" style="margin-left:20px; margin-top:10px;">
                       <label :for="`true${index}`" style="margin-left:5px;cursor: pointer;">True</label>
                       <br>
                       <input :id="`false${index}`" type="radio" v-model="stud_answer.true_false" value="false" style="margin-left:20px; margin-top:10px;">
                       <label :for="`false${index}`" style="margin-left:5px;cursor: pointer;">False</label>
                   </div>
                </div>
             </div>

             <div style="display: flex; flex-direction: column;" v-if="question.question_type == 'fill_blank'"  class="box" >
               <div style="display: flex; flex-direction: row; flex: 1;">
                  <h6><b style="padding-right:15px;">{{ index+1}}. </b></h6>
                  <h6 v-html="question.title"></h6>&nbsp;
                  <b style="float:right; font-size:13px; color:#228B22; width:80px"> [{{ question.points }} points ]</b> 
                
                </div>

                 <div v-for="choice in question.choices">
                    <div v-for="stud_answer in student_answers" v-if="stud_answer.question_id === question.id" >
                        <input type="text"
                         v-model="stud_answer.fill_blank"
                          placeholder=" Answer "
                          style="border:0px; width:100%; margin-top:10px; padding:5px;" />
                    </div>
                 </div>
             </div>

             <div style="display: flex; flex-direction: column;" v-if="question.question_type == 'essay'"  class="box" >
                <div style="display: flex; flex-direction: row; flex: 1;">
                  <h6><b style="padding-right:15px;">{{ index+1}}. </b></h6>
                  <h6 v-html="question.title"></h6>&nbsp;
                  <b style="float:right; font-size:13px; color:#228B22; width:80px"> [{{ question.points }} points ]</b> 
                
                </div>

                <div v-for="choice in question.choices">
                  <div v-for="stud_answer in student_answers" v-if="stud_answer.question_id === question.id" >
                      <textarea
                        v-model="stud_answer.essay"
                      cols="100" rows="3" style="width:100%;"></textarea>
                  </div>
                </div>
             </div>

         </div>
         <div  class="text-center">
           <button v-if="!isHidden" v-on:click="submitAnswer()" style="border:0px; width:120px; margin-top:5px; color:#fff; background:#32CD32; font-weight: bold;">Submit</button>
         </div>
         <br>
         <div v-if="isHidden" class="text-center">
             <h3>Quiz is submitted!</h3> 
         </div>
    </div>
</template>
<script>
import moment from 'moment'
export default {
    props: ['quiz_id','id','online_quiz_id'],
    data() {
        return{
          date: '',
          timer:null,
          isLoading: true,
          start_time :'',
          start_quiz_date:null,
          isHidden: false,
          quiz_info: [],
          questions:[],
          student_answers:[],
          student_result:[],
          displayMinutes:0,
          displaySecond:0,
          duration:0,
          
        }
    },
    created() {
      if(this.student_result == null){
          this.isHidden =  true;
      }else{
          this.isHidden =  false;
      }
      if(localStorage.getItem('start_time') != null){
          this.start_time       = localStorage.getItem('start_time');
          this.start_quiz_date  = localStorage.getItem('start_quiz_date');
      }else{
          var currentTime = moment().format('HH:mm')
          var datenow = new Date()
          localStorage.setItem('start_quiz_date',datenow)
          localStorage.setItem('start_time',currentTime)
      }
            
      
         $('body').bind('copy paste',function(e) {
        e.preventDefault(); return false; 
      })

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
      
    },
    
    computed: {
        _seconds:() =>1000,
        _minutes(){
          return this._seconds * 60;
        },
        _hours(){
          return this._minutes * 60;
        }
    },
    
    mounted(){
      this.fetchQuestion();

        //minutes
        this.timer = setInterval(() => {
        var start = new Date(new Date(localStorage.getItem('start_quiz_date')).getTime() + parseInt(this.duration)*60000);
        var now  = new Date();
        var end = new Date(start);
        
          const distance = end.getTime() - now.getTime();

        if(distance < 0){
          this.submitAnswer(2)
        
        }
        
        const minutes = Math.floor((distance % this._hours)/ this._minutes);
        const seconds = Math.floor((distance % this._minutes)/ this._seconds);
        this.displayMinutes = minutes < 10 ? "0" + minutes : minutes;
        this.displaySecond = seconds < 10 ? "0" + seconds : seconds;
      
      }, 1000);
     
    },

    methods: {
        doneQuiz(){
          this.isHidden= true;
        },
        fetchQuestion(){
            axios.get('/student/online-class-quizzes/question', {
                params: {
                  quiz_id : this.quiz_id,
                  class_quiz_id : this.id,
                  online_quiz_id : this.online_quiz_id,
                },
              }) .then(response => {
                this.questions = response.data.quiz.questions;
                //SHUFFLE QUESTION
                if(response.data.shuffle.shuffle == 1){
                  this.questions.sort(() => Math.random() - 0.5);
                }
               
                this.student_answers = response.data.quiz.temp_answers;
                this.student_result = response.data.student_quiz_result;
                console.log(response.data.quiz.duration);
                this.duration = response.data.quiz.duration;
            });
      	},

        submitAnswer(){
          this.isHidden= true;
          this.start_time = localStorage.getItem('start_time');
          axios.post('/student/online-class-quizzes/submit', {
          quiz_id                 : this.quiz_id,
          class_quiz_id           : this.id,
          start_time              : this.start_time ,
          questions               : this.questions,
          student_answers         : this.student_answers, 

          }).then(function (response) {
            
          })
          
          clearInterval(this.timer);
           localStorage.removeItem('student_answers')
          localStorage.removeItem('start_time')
          localStorage.removeItem('start_quiz_date')
      }
    }
    
}
</script>