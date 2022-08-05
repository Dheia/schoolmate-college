<style>
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
        width: 130px; 
        height: 20px;
        background: rgb(18, 201, 18);
        color: #fff;
        text-align: center;
        border-radius: 10px;
        margin-left:10px;
        font-size: 13px;
    }
    .incorrectAnswer{
        width: 130px; 
        height: 20px;
        background: rgb(245, 47, 47);
        color: #fff;
        text-align: center;
        border-radius: 10px;
        margin-left:10px;
        font-size: 13px;
    }
    .quizcorrectAnswer{
        width: 130px; 
        height: 20px;
        background: rgb(233, 135, 23);
        color: #fff;
        text-align: center;
        border-radius: 10px;
        margin-left:10px;
        font-size: 13px;
    }
    .answer-row{
        display: flex;
    } 
    .student-answer-row{
        display: flex;
    } 
    .print{
        position: absolute;
        right: 0;
        margin-right:20px;
    }
</style>
<template>
  <div class="col-md-12">
          <!-- Loading GIF -->
      <div class="row" v-if="isLoading">
          <div style="padding-top: 80px;">
            <img class="img-responsive" v-bind:src="'/vendor/backpack/crud/img/ajax-loader.gif'" alt="Loading..." style="margin: auto;">
          </div>
      </div>
        <!-- STUDENT SCORE -->
            <div v-if="!isLoading">
              <h4 class="pt-5"><b>Total Score:</b> {{this.scores}} / {{ Total}}</h4>
            </div>
        <!-- END STUDENT SCORE -->
      
        <div v-for="(question,index) in questions" style="margin-top:10px;" v-if="!isLoading">
        <!-- Choose one  -->
        <div v-if="question.question_type == 'choose_one'">
            
            <div class="student-answer-row" v-for="final_score in final_scores" v-if="question['id'] === final_score['question_id']">
              <b>Score :</b>  <input :disabled="final_score['question_type'] !== 'essay'" v-model="final_score['score']" type="number" style="width:40px; margin-left:10px; margin-bottom:0px; border:none; font-size:12px;"> <b> / {{ final_score['points'] }} Points</b>
            </div>

             <div style="display: flex; flex-direction: row; flex: 1;">
                <h6>{{ index+1}}.</h6>
                <h6 class="questions"> 
                <div v-html="question['title']"></div></h6>
             </div>

            <div v-for="choice in question.choices" id="v-model-radiobutton" style="margin-bottom:10px;">
                <div v-for="correctAnswer in correctAnswers" v-if="correctAnswer.question_id === question.id">
                      <div v-for="studentAnswer in studentAnswers" v-if="studentAnswer.question_id === question.id">
                        <div class="answer-row">
                            <input class="answer" type="radio" v-model="studentAnswer.choose_one"  value="a" disabled>
                            <span class="choices" v-html="choice.a"></span>
                            <div v-if="studentAnswer.choose_one === 'a' && correctAnswer.choose_one === 'a'" class="correctAnswer">&#10004; <b>Correct</b></div>
                            <div v-else-if="studentAnswer.choose_one === 'a' && correctAnswer.choose_one != 'a'" class="incorrectAnswer">&#10006; <b>Incorrect</b></div>
                             <div v-else-if="studentAnswer.choose_one != 'a' && correctAnswer.choose_one == 'a'" class="quizcorrectAnswer">&#10004; <b>Correct Answer</b></div>
                            <div  v-else ></div>
                        </div>
                        <br> 

                        <div class="answer-row">
                            <input class="answer" type="radio" v-model="studentAnswer.choose_one"  value="b" disabled>
                            <span class="choices" v-html="choice.b"></span>
                            <div v-if="studentAnswer.choose_one === 'b' && correctAnswer.choose_one === 'b'" class="correctAnswer">&#10004; <b>Correct</b></div>
                            <div v-else-if="studentAnswer.choose_one === 'b' && correctAnswer.choose_one != 'b'" class="incorrectAnswer">&#10006; <b>Incorrect</b></div>
                             <div v-else-if="studentAnswer.choose_one != 'b' && correctAnswer.choose_one == 'b'" class="quizcorrectAnswer">&#10004; <b>Correct Answer</b></div>
                            <div  v-else ></div>
                        </div>
                        <br>

                        <div class="answer-row">
                            <input class="answer" type="radio" v-model="studentAnswer.choose_one"  value="c" disabled>
                            <span class="choices" v-html="choice.c"></span>
                            <div v-if="studentAnswer.choose_one === 'c' && correctAnswer.choose_one === 'c'" class="correctAnswer">&#10004; <b>Correct</b></div>
                            <div v-else-if="studentAnswer.choose_one === 'c' && correctAnswer.choose_one != 'c'" class="incorrectAnswer">&#10006; <b>Incorrect</b></div>
                            <div v-else-if="studentAnswer.choose_one != 'c' && correctAnswer.choose_one == 'c'" class="quizcorrectAnswer">&#10004; <b>Correct Answer</b></div>
                            <div  v-else ></div>
                        </div>
                        <br>
                        
                        <div class="answer-row">
                            <input class="answer" type="radio" v-model="studentAnswer.choose_one"  value="d" disabled>
                            <span class="choices" v-html="choice.d"></span>
                            <div v-if="studentAnswer.choose_one === 'd' && correctAnswer.choose_one === 'd'" class="correctAnswer">&#10004; <b>Correct</b></div>
                            <div v-else-if="studentAnswer.choose_one === 'd' && correctAnswer.choose_one != 'd'" class="incorrectAnswer">&#10006; <b>Incorrect</b></div>
                            <div v-else-if="studentAnswer.choose_one != 'd' && correctAnswer.choose_one == 'd'" class="quizcorrectAnswer">&#10004; <b>Correct Answer</b></div>
                            <div  v-else ></div>
                        </div>
                        <br>
                      </div>
                </div>
            </div>
        </div>	

                <!-- CHOOSE MANY -->
        <div v-if="question.question_type == 'choose_many'">
            <div class="student-answer-row" v-for="final_score in final_scores" v-if="question['id'] === final_score['question_id']">
              <b>Score :</b>  <input :disabled="final_score['question_type'] !== 'essay'" v-model="final_score['score']" type="number" style="width:40px; margin-left:10px; margin-bottom:0px; border:none; font-size:12px;"> <b> / {{ final_score['points'] }} Points</b>
            </div>

            <div style="display: flex; flex-direction: row; flex: 1;">
                <h6>{{ index+1}}.</h6>
                <h6 class="questions"> 
                <div v-html="question['title']"></div></h6>
             </div>

                <div v-for="choice in question.choices" id="v-model-checkboxButton" style="margin-bottom:10px;">
                    <div v-for="correctAnswer in correctAnswers" v-if="correctAnswer.question_id === question.id">
                        <div v-for="correct_multiple_choice in correctAnswer.multiple_choice">
                             <div v-for="studentAnswer in studentAnswers" v-if="studentAnswer.question_id === question.id">
                                  <div v-for="student_multiple_choice in studentAnswer.multiple_choice">
                                    <div class="answer-row">
                                        <input class="answer" type="checkbox" v-model="student_multiple_choice.a"  value="a" disabled>
                                        <span class="choices" v-html="choice.a"></span>
                                        <div v-if="student_multiple_choice.a === true && correct_multiple_choice.a === true"  class="correctAnswer">&#10004; <b>Correct</b></div>
                                        <div v-else-if="student_multiple_choice.a === true && correct_multiple_choice.a !== true"  class="incorrectAnswer">&#10006; <b>Incorrect</b></div>
                                        <div v-else-if="student_multiple_choice.a !== true && correct_multiple_choice.a === true"  class="quizcorrectAnswer">&#10004;  <b>Correct Answer</b></div>
                                        <div  v-else ></div>
                                    </div>
                                    <br> 

                                    <div class="answer-row">
                                        <input class="answer" type="checkbox" v-model="student_multiple_choice.b"  value="b" disabled>
                                        <span class="choices" v-html="choice.b"></span>
                                         <div v-if="student_multiple_choice.b === true && correct_multiple_choice.b === true"  class="correctAnswer">&#10004; <b>Correct</b></div>
                                        <div v-else-if="student_multiple_choice.b === true && correct_multiple_choice.b !== true"  class="incorrectAnswer">&#10006; <b>Incorrect</b></div>
                                        <div v-else-if="student_multiple_choice.b !== true && correct_multiple_choice.b === true"  class="quizcorrectAnswer">&#10004;  <b>Correct Answer</b></div>
                                        <div  v-else ></div>
                                    </div>
                                    <br>

                                    <div class="answer-row">
                                        <input class="answer" type="checkbox" v-model="student_multiple_choice.c"  value="c" disabled>
                                        <span class="choices" v-html="choice.c"></span>
                                        <div v-if="student_multiple_choice.c === true && correct_multiple_choice.c === true"  class="correctAnswer">&#10004; <b>Correct</b></div>
                                        <div v-else-if="student_multiple_choice.c === true && correct_multiple_choice.c !== true"  class="incorrectAnswer">&#10006; <b>Incorrect</b></div>
                                        <div v-else-if="student_multiple_choice.c !== true && correct_multiple_choice.c === true"  class="quizcorrectAnswer">&#10004;  <b>Correct Answer</b></div>
                                        <div  v-else ></div>
                                    </div>
                                    <br>

                                    <div class="answer-row">
                                        <input class="answer" type="checkbox" v-model="student_multiple_choice.d"  value="d" disabled>
                                        <span class="choices" v-html="choice.d"></span>
                                        <div v-if="student_multiple_choice.d === true && correct_multiple_choice.d === true"  class="correctAnswer">&#10004; <b>Correct</b></div>
                                        <div v-else-if="student_multiple_choice.d === true && correct_multiple_choice.d !== true"  class="incorrectAnswer">&#10006; <b>Incorrect</b></div>
                                        <div v-else-if="student_multiple_choice.d !== true && correct_multiple_choice.d === true"  class="quizcorrectAnswer">&#10004;  <b>Correct Answer</b></div>
                                        <div  v-else ></div>
                                    </div>
                                    <br>
                                  </div>
                             </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TRUE OR FALSE -->
            <div v-else-if="question.question_type == 'true_false'" style="margin-bottom:10px;">
                <div class="student-answer-row" v-for="final_score in final_scores" v-if="question['id'] === final_score['question_id']">
                    <b>Score :</b>  <input :disabled="final_score['question_type'] !== 'essay'" v-model="final_score['score']" type="number" style="width:40px; margin-left:10px; margin-bottom:0px; border:none; font-size:12px;"> <b> / {{ final_score['points'] }} Points</b>
                </div>
               
                <div style="display: flex; flex-direction: row; flex: 1;">
                    <h6>{{ index+1}}.</h6>
                    <h6 class="questions"> 
                    <div v-html="question['title']"></div></h6>
                </div>

                    <div v-for="correctAnswer in correctAnswers"  v-if="correctAnswer.question_id === question.id" >
                        <div v-for="studentAnswer in studentAnswers"  v-if="studentAnswer.question_id === question.id">
                            <div class="answer-row">
                            <input class="answer" type="radio" v-model="studentAnswer.true_false" value="true"  disabled>
                                <span class="choices">True</span>
                                <div v-if="studentAnswer.true_false === 'true' && correctAnswer.true_false === 'true'"  class="correctAnswer">&#10004; <b>Correct</b></div>
                                <div v-else-if="studentAnswer.true_false === 'true' && correctAnswer.true_false !== 'true'"  class="incorrectAnswer">&#10006; <b>Incorrect</b></div>
                                <div v-else-if="studentAnswer.true_false !== 'true' && correctAnswer.true_false === 'true'" class="quizcorrectAnswer">&#10004;  <b>Correct Answer</b></div>
                                <div  v-else ></div>
                            </div>
                            <br> 
                            <div class="answer-row">
                            <input class="answer" type="radio" v-model="studentAnswer.true_false" value="false" disabled>
                                <span class="choices">False</span>
                                <div v-if="studentAnswer.true_false === 'false' && correctAnswer.true_false === 'false'"  class="correctAnswer">&#10004; <b>Correct</b></div>
                                <div v-else-if="studentAnswer.true_false === 'false' && correctAnswer.true_false !== 'false'"  class="incorrectAnswer">&#10006; <b>Incorrect</b></div>
                                <div v-else-if="studentAnswer.true_false !== 'false' && correctAnswer.true_false === 'false'" class="quizcorrectAnswer">&#10004;  <b>Correct Answer</b></div>
                                <div  v-else ></div>
                            </div>
                        </div>
                </div>
            </div>

            <!-- FILL IN THE BLANK -->
            <div v-else-if="question.question_type == 'fill_blank'">
                <div class="student-answer-row" v-for="final_score in final_scores" v-if="question['id'] === final_score['question_id']">
                    <b>Score :</b>  <input :disabled="final_score['question_type'] !== 'essay'" v-model="final_score['score']" type="number" style="width:40px; margin-left:10px; margin-bottom:0px; border:none; font-size:12px;"> <b> / {{ final_score['points'] }} Points</b>
                </div>
                <div style="display: flex; flex-direction: row; flex: 1;">
                    <h6>{{ index+1}}.</h6>
                    <h6 class="questions"> 
                    <div v-html="question['title']"></div></h6>
                </div>
                  
                    <div v-for="correctAnswer in correctAnswers" v-if="correctAnswer.question_id === question.id"  style="margin-bottom:10px;">
                        <div v-for="studentAnswer in studentAnswers" v-if="studentAnswer.question_id === question.id" >
                            <div class="answer-row">
                                <b  style="font-size:13px; margin-left:33px;">Student Answer: </b><span class="choices">{{ studentAnswer.fill_blank }}</span>
                                <div v-if="studentAnswer.fill_blank === correctAnswer.fill_blank " class="correctAnswer">&#10004; <b>Correct</b></div>
                                <div v-else-if="studentAnswer.fill_blank !== correctAnswer.fill_blank " class="incorrectAnswer">&#10006; <b>Incorrect</b></div>
                                 
                            </div>
                            <div class="answer-row">
                                <div class="answer" v-if="studentAnswer.fill_blank !== correctAnswer.fill_blank " ><b style="font-size:13px;">Correct Answer: </b><span class="choices">{{ correctAnswer.fill_blank }}</span></div>
                            </div>
                        </div>
                    </div>
            </div>

            <!-- ESSAY -->
            <div v-else-if="question.question_type == 'essay'">
                <div class="student-answer-row" v-for="final_score in final_scores" v-if="question['id'] === final_score['question_id']">
                    <b>Score :</b>  
                    <input min="0" 
                    :max="final_score['points']" 
                    :disabled="is_check === 1 && final_score['question_type'] === 'essay'" 
                    v-model="final_score['score']" 
                    type="number" 
                    style="width:40px; margin-left:10px; margin-bottom:0px; border:none; font-size:12px;"> 
                    <b> / {{ final_score['points'] }} Points</b>
                    <!-- <h5 style="color:#F8673E;">(Not Checked)</h5> -->
                </div>
                <div style="display: flex; flex-direction: row; flex: 1;">
                    <h6>{{ index+1}}.</h6>
                    <h6 class="questions"> 
                    <div v-html="question['title']"></div></h6>
                </div>
                    <div v-for="studentAnswer in studentAnswers" v-if="studentAnswer.question_id === question.id" >
                        <textarea
                        v-model="studentAnswer.essay"
                        cols="30" rows="3" style="width:90%; margin-left:30px;" disabled></textarea>
                    </div>
                </div>
            </div>
            <br>
            <div v-if="this.is_check === 0">
                <button v-if="!isLoading"  v-on:click="submitFinalScore()" style="width:100%; height:30px; font-size:bold;" class="btn btn-xs btn-primary action-btn">
                    <b>Submit Final Score</b>
                </button>
            </div>
  </div>
</template>
<script>
export default {
      props: ['id','quiz_id'],
    data() {
          return{
            scores:0,
            total_points: 0,
            is_check:0,
            isLoading: true,
            isType:false,
            questions:[],
            correctAnswers:[],
            studentAnswers:[],
            final_scores:[],
        }
    },
    computed: {
      Total(){
         let total = 0;
      for (let item in this.questions) {
        total = total + parseInt(this.questions[item].points)
      }
      return total;
      }

    }, 
    methods: {
         fetchQuestion(){
          axios.get('/admin/online-class/student-quiz-result/question/',{
              params: {
                  id : this.id,
                  quiz_id : this.quiz_id,
                },
              }) .then(response => {
                    this.isLoading    = true;
                    this.questions = response.data.quiz.questions;
                    this.correctAnswers = response.data.quiz.isCorrect;
                    this.studentAnswers = response.data.student_quiz_result.results;
                    this.final_scores = response.data.student_quiz_result.final_score;
                    this.is_check = response.data.student_quiz_result.is_check;
                    this.scores = response.data.student_quiz_result.score;
                    this.isLoading    = false;
            });
            
         },
         save(){
              axios.post('/admin/online-class/student-quiz-result/api/submit-final-score', {
                      student_result_quiz_id  : this.id,
                      final_scores            : this.final_scores,

                        }).then(function (response) {
                        
                            new PNotify({
                                title: 'Success',
                                text: 'Student Final Score submitted!',
                                type: "success"
                                });
                        
                        }).catch(error => {
                            new PNotify({
                                title: 'Error',
                                text: 'Error, Something Went Wrong saving the Question Data, Please Try To Reload The Page.'+error,
                                type: "error"
                            });
                        });
                        this.fetchQuestion();
         },
         submitFinalScore(){

            this.final_scores.forEach(element => {
                if(element['question_type'] == 'essay'){
                    if(parseInt(element['score']) > parseInt(element['points'])) {
                            new PNotify({
                                title: 'Error',
                                text: 'Input score '+element['score'] + element['points'],
                                type: "error"
                            });

                    }else if(element['score'] < 0){
                            new PNotify({
                                title: 'Error',
                                text: 'Input score '+element['score'],
                                type: "error"
                            });
                    }
                    else{
                        this.save();
                    }

                }
            });
       
        }
         
    },
    mounted() {
        this.fetchQuestion();
    },
}
</script>