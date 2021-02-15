<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Tee;
use Auth;
use Illuminate\Support\Facades\Response;
use App\Http\Requests;
use App\Models\User;
use App\Models\Score;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function scrape()
    {
      //https://ncrdb.usga.org/courseTeeInfo.aspx?CourseID=30767
      //$start = 0;
      //$end = 10;

      $maxID = \DB::table('courses')->where('id', \DB::raw("(select max(`id`) from courses)"))->first();
      //dd($maxID);
      if(isset($maxID->id)){
        $start = $maxID->id+1;
      }else{
        $start = 0;
      }

      $end = $start+750;

      if(is_numeric($start) && is_numeric($end)){


        for($j = $start;$j<=$end;$j++){
          //echo 'start/end loop';
          $course = new Course();

          $pagecontent = @file_get_contents('https://ncrdb.usga.org/courseTeeInfo.aspx?CourseID='.$j);
          if($pagecontent){
            $doc = new \DOMDocument();
            $doc->loadHTML($pagecontent);
            $courseInfo = $doc->getElementById('gvCourseTees');
          }

          if(isset($doc) && $courseInfo !=null){

            $courseMaster = array();
            $courseCells = $courseInfo->getElementsByTagName('td');

            if(empty($courseCells[0]->nodeValue) || !isset($courseCells[0]->nodeValue)){
              exit();
            }

            $teeInfo = $doc->getElementById('gvTee');
            if(isset($teeInfo)){
              $teeRows = $teeInfo->getElementsByTagName('tr');
              $courseNum = 1;
                foreach($teeRows as $tr){
                  $teeCells = $tr->getElementsByTagName('td');
                  //echo sizeof($teeCells);
                  $tee = new Tee();
                  $tee->course_id = $j;
                  if(sizeof($teeCells) > 0){

                  for($i = 0;$i<sizeof($teeCells);$i++){

                    for($k = 0;$k<3;$k++){
                      //echo 'courseinfo loop';
                      $course->id = $j;
                      $courseMaster['courseID'] = $j;
                      if($k==0){
                        $courseMaster['course'] = $courseCells[$k]->nodeValue;
                        $course->name = $courseCells[$k]->nodeValue;
                      }else if($k==1){
                        $courseMaster['city'] = $courseCells[$k]->nodeValue;
                        $course->city = $courseCells[$k]->nodeValue;
                      }else if($k==2){
                        $courseMaster['state'] = $courseCells[$k]->nodeValue;
                        $course->state = $courseCells[$k]->nodeValue;
                      }
                    }

                    //dd($course);


                    if(!empty($teeCells[$i]->nodeValue) && isset($teeCells[$i]->nodeValue) && isset($courseNum)){
                      if($i==0 ){
                        $courseMaster['teename'] = $teeCells[$i]->nodeValue;
                        $tee->tee_name = $teeCells[$i]->nodeValue;
                      }else if($i==1){
                        $courseMaster['gender'] = $teeCells[$i]->nodeValue;
                        $tee->gender = $teeCells[$i]->nodeValue;
                      }else if($i==2){
                        $courseMaster['par'] = $teeCells[$i]->nodeValue;
                        $tee->par = $teeCells[$i]->nodeValue;
                      }else if($i==3){
                        $courseMaster['rating18'] = $teeCells[$i]->nodeValue;
                        $tee->rating = $teeCells[$i]->nodeValue;
                      }else if($i==4){
                        $courseMaster['bogeyrating18'] = $teeCells[$i]->nodeValue;
                        $tee->bogey_rating = $teeCells[$i]->nodeValue;
                      }else if($i==5){
                        $courseMaster['sloperating'] = $teeCells[$i]->nodeValue;
                        $tee->slope = $teeCells[$i]->nodeValue;
                      }else if($i==6){
                        $ratingSlope = explode("/", $teeCells[$i]->nodeValue);
                        $ratingSlope[0] = trim($ratingSlope[0]);
                        $ratingSlope[1] = trim($ratingSlope[1]);
                        if(isset($ratingSlope[0]) && !empty($ratingSlope[0])){
                          $courseMaster['front9rating'] = trim($ratingSlope[0]);
                          $tee->front_rating = trim($ratingSlope[0]);
                        }
                        if(isset($ratingSlope[1]) && !empty($ratingSlope[1])){
                          $courseMaster['front9slope'] = trim($ratingSlope[1]);
                          $tee->front_slope = trim($ratingSlope[1]);
                        }
                      }else if($i==7){
                        $ratingSlope = explode("/", $teeCells[$i]->nodeValue);
                        $ratingSlope[0] = trim($ratingSlope[0]);
                        $ratingSlope[1] = trim($ratingSlope[1]);
                        //dd($ratingSlope);
                        //$courseMaster['back9'] = trim($teeCells[$i]->nodeValue);
                        if(isset($ratingSlope[0]) && !empty($ratingSlope[0])){
                          $courseMaster['back9rating'] = trim($ratingSlope[0]);
                          $tee->back_rating = trim($ratingSlope[0]);
                        }
                        if(isset($ratingSlope[1]) && !empty($ratingSlope[1])){
                          $courseMaster['back9slope'] = trim($ratingSlope[1]);
                          $tee->back_slope = trim($ratingSlope[1]);
                        }
                      }
                    }else{
                      if(!isset($tee->tee_name)){
                        $tee->tee_name = 'N/A';
                      }
                    }



                  }


                  print '<pre>';print_r($courseMaster);print '</pre>';
                  $tee->save();
                  $courseMaster = [];
                  $courseNum++;
                }
              }
              $course->save();
            }else{
              echo "<div>bad course id</div>";
            }

          }


        }

      }

        return view('scrape');
    }

    /*public function index_replace()
    {
      return view('home');
    }*/

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
     public function index(Request $request)
     {

          if ($request->user()) {
            $user = $request->user();
          }

         $scores = Score::where('user_id', '=', $user->id)->orderBy('played_on', 'desc')->get();
         //dd($scores);
         foreach($scores as &$score){

           $course = Course::where('id', '=', $score->course_id)->first();

           $tee =  Tee::where('id', '=', $score->tee)->first();

           if($course && $tee){

             $diffScore = ($score->score - $tee->rating) * 113/$tee->slope;
             $score->diffScore = round($diffScore, 1);
             $score->course = $course->name;
             $score->tees = $tee->gender. " - " . $tee->tee_name . " - " . $tee->rating.'/'.$tee->slope;
             //$score->course_info = $course->rating.'/'.$course->slope;

           }
         }

         $handicapScores = Score::where('user_id', '=', $user->id)->orderBy('played_on', 'desc')->take(20)->get();

         $diffArray = array();

         foreach($handicapScores as $postedScore){

           $course = Tee::where('id', '=', $postedScore->tee)->first();
           if($course){
             $diffScore = ($postedScore->score - $course->rating) * 113/$course->slope;
             $diffArray[] = $diffScore;
           }

         }
         $handicap = $user->getHandicap();


         return view('home',['user' => $user, 'scores' => $scores, 'diffScores' => $diffArray, 'handicap' => $handicap]);
     }
}
