
<style scoped>
  .box{
    box-shadow: #ccc 0 0 2px;
    background: white;
    padding: 10px;
  }
  .questions{
      margin-left:15px;
      font-size: 15px;
  }
  .choices{
      margin-left:5px;
      font-size: 14px;
  }
  .answer{
      margin-left:30px;
  }
  .correctAnswer{
     width: 100px; 
     height: 20px;
     background: rgb(18, 201, 18);
     color: #fff;
     text-align: center;
     border-radius: 10px;
     margin-left:10px;
     font-size: 13px;
  }
  .answer-row{
     display: flex;
  } 
  .print{
    position: absolute;
    right: 0;
    margin-right:20px;
  }
</style>
<template>
    <div class="col-md-12 p-l-0" >
          <!-- Loading GIF -->
      <div class="row" v-if="isLoading">
          <div style="padding-top: 80px;">
            <img class="img-responsive" v-bind:src="'/vendor/backpack/crud/img/ajax-loader.gif'" alt="Loading..." style="margin: auto;">
          </div>
      </div>

             <div class="box m-b-20"  v-if="!isLoading">
                <div class="print" v-if="!isLoading">
                <a align="center"  v-bind:href="'print/'+this.id"> <i class="fa fa-print fa-2x"></i></a>
            </div> 
           
            <div v-for="(question,index) in questions" style="margin-top:30px;">

            <!-- Choose one  -->
                <div v-if="question.question_type == 'choose_one'">
                    <div style="display: flex; flex-direction: row;">
                        <h6 class="questions">
                        {{ index+1}}.
                        </h6>
                         <h6  class="questions" v-html="question.title"> 
                            ( <b> {{ question['points']}} points</b> ) </h6>
                    </div>
                        
                    <div v-for="choice in question.choices" id="v-model-radiobutton" style="margin-bottom:10px;">
                        <div v-for="correctAnswer in correctAnswers" v-if="correctAnswer.question_id === question.id">
                            <div class="answer-row">
                                <input class="answer" type="radio" v-model="correctAnswer.choose_one"  value="a" disabled>
                                <span class="choices" v-html="choice.a"></span>
                                <div v-if="correctAnswer.choose_one === 'a'" class="correctAnswer"><b>Correct</b></div>
                            </div>
                            <br> 

                            <div class="answer-row">
                                <input class="answer" type="radio" v-model="correctAnswer.choose_one"  value="b" disabled>
                                <span class="choices" v-html="choice.b"></span>
                                <div v-if="correctAnswer.choose_one === 'b'" class="correctAnswer"><b>Correct</b></div>
                            </div>
                            <br>

                            <div class="answer-row">
                                <input class="answer" type="radio" v-model="correctAnswer.choose_one"  value="c" disabled>
                                <span class="choices" v-html="choice.c"></span>
                                <div v-if="correctAnswer.choose_one === 'c'" class="correctAnswer"><b>Correct</b></div>
                            </div>
                            <br>
                            
                            <div class="answer-row">
                                <input class="answer" type="radio" v-model="correctAnswer.choose_one"  value="d" disabled>
                                <span class="choices" v-html="choice.d"></span>
                                <div v-if="correctAnswer.choose_one === 'd'"  class="correctAnswer"><b>Correct</b></div>
                            </div>
                            <br>

                        </div>
                    </div>
                </div>	

                        <!-- CHOOSE MANY -->
                <div v-if="question.question_type == 'choose_many'">
                    <div style="display: flex; flex-direction: row;">
                        <h6 class="questions">
                        {{ index+1}}.
                        </h6>
                         <h6  class="questions" v-html="question.title"> 
                            ( <b> {{ question['points']}} points</b> ) </h6>
                    </div>

                        <div v-for="choice in question.choices" id="v-model-checkboxButton" style="margin-bottom:10px;">
                            <div v-for="correctAnswer in correctAnswers" v-if="correctAnswer.question_id === question.id">
                                <div v-for="multiple_choice in correctAnswer.multiple_choice">
                                    <div class="answer-row">
                                        <input class="answer" type="checkbox" v-model="multiple_choice.a"  value="a" disabled>
                                        <span class="choices" v-html="choice.a"></span>
                                        <div v-if="multiple_choice.a === true"  class="correctAnswer"><b>Correct</b></div>
                                    </div>
                                    <br> 

                                    <div class="answer-row">
                                        <input class="answer" type="checkbox" v-model="multiple_choice.b"  value="b" disabled>
                                        <span class="choices" v-html="choice.b"></span>
                                        <div v-if="multiple_choice.b === true"  class="correctAnswer"><b>Correct</b></div>
                                    </div>
                                    <br>

                                    <div class="answer-row">
                                        <input class="answer" type="checkbox" v-model="multiple_choice.c"  value="c" disabled>
                                        <span class="choices" v-html="choice.c"></span>
                                        <div v-if="multiple_choice.c === true"  class="correctAnswer"><b>Correct</b></div>
                                    </div>
                                    <br>

                                    <div class="answer-row">
                                        <input class="answer" type="checkbox" v-model="multiple_choice.d"  value="d" disabled>
                                        <span class="choices" v-html="choice.d"></span>
                                        <div v-if="multiple_choice.d === true"  class="correctAnswer"><b>Correct</b></div>
                                    </div>
                                    <br>
                                    
                                </div>
                            </div>
                        </div>
                    </div>

                        <!-- TRUE OR FALSE -->
                        <div v-else-if="question.question_type == 'true_false'" >
                           <div style="display: flex; flex-direction: row;">
                                <h6 class="questions">
                                {{ index+1}}.
                                </h6>
                                <h6  class="questions" v-html="question.title"> 
                                    ( <b> {{ question['points']}} points</b> ) </h6>
                            </div>
                            

                                <div v-for="correctAnswer in correctAnswers"  v-if="correctAnswer.question_id === question.id" style="width:82%;">
                                <input class="answer" type="radio" v-model="correctAnswer.true_false" value="true"  disabled>
                                <span class="choices">True</span>
                                <br> 
                                <input class="answer" type="radio" v-model="correctAnswer.true_false" value="false" disabled>
                                 <span class="choices">False</span>
                            </div>
                        </div>

                        <!-- FILL IN THE BLANK -->
                        <div v-else-if="question.question_type == 'fill_blank'">
                            <div style="display: flex; flex-direction: row;">
                                <h6 class="questions">
                                {{ index+1}}.
                                </h6>
                                <h6  class="questions" v-html="question.title"> 
                                    ( <b> {{ question['points']}} points</b> ) </h6>
                            </div>

                                <div v-for="correctAnswer in correctAnswers" v-if="correctAnswer.question_id === question.id"  style="margin-bottom:10px;">
                                <span class="choices" style="margin-left:35px;">{{ correctAnswer.fill_blank }}</span>
                                </div>
                        </div>

                        <!-- ESSAY -->
                        <div v-else-if="question.question_type == 'essay'">
                            <div style="display: flex; flex-direction: row;">
                                <h6 class="questions">
                                {{ index+1}}.
                                </h6>
                                <h6  class="questions" v-html="question.title"> 
                                    ( <b> {{ question['points']}} points</b> ) </h6>
                            </div>
                           
                                <textarea
                                v-model="question.essay"
                                cols="30" rows="3" style="width:90%; margin-left:30px;" disabled></textarea>
                            </div>
                        </div>
                    </div>
    </div>
</template>
<script>
export default {
    props: ['id'],
      data() {
        return{
            isLoading: true,
            quiz_title: '',
            quiz_description:'',
            questions:[],
            correctAnswers:[],
        }
      },
    methods: {
         fetchQuestion(){
          axios.get('/admin/quiz/api/get/questions/',{
              params: {
                  id : this.id,
                },
              }) .then(response => {
                    this.questions = response.data.questions;
                    this.correctAnswers = response.data.isCorrect;
                    this.isLoading    = false;
            });
           
         },
         printQuiz(){
            axios.post('/admin/quiz/report/', {
            params: {
                id : this.id,
                quiz_title : this.quiz_title,
                quiz_description : this.quiz_description,
                questions : this.questions,
                correctAnswers : this.correctAnswers,
            },
            }).then(function (response) {
                // console.log('response='+response);
                // new PNotify({
                //     title: 'Success',
                //     text: 'Question Data has been saved!',
                //     type: "success"
                // });
            }).catch(error => {
                // // console.log(error);
                // new PNotify({
                //     title: 'Error',
                //     text: 'Error, Something Went Wrong saving the Question Data, Please Try To Reload The Page.',
                //     type: "error"
                // });
            });
         },
         
    },
     created() {
    this.fetchQuestion();
  },

}
</script>