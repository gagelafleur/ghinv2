<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getHandicap(){

      $scores = Score::where('user_id', '=', $this->id)->orderBy('played_on', 'desc')->get();

      foreach($scores as &$score){

        $course = Course::where('id', '=', $score->course_id)->first();

        if($course){

        $diffScore = ($score->score - $course->course_rating) * 113/$course->slope;
        $score->diffScore = round($diffScore, 1);
        $score->course = $course->course_name;
        $score->tees = $course->tee_color;
        $score->course_info = $course->course_rating.'/'.$course->slope;

        }
      }

      $handicapScores = Score::where('user_id', '=', $this->id)->orderBy('played_on', 'desc')->take(20)->get();

      $diffArray = array();

      foreach($handicapScores as $postedScore){

        $course = Course::where('id', '=', $postedScore->course_id)->first();
        if($course){
          $diffScore = ($postedScore->score - $course->course_rating) * 113/$course->slope;
          $diffArray[] = $diffScore;
        }

      }

      $handicap = 'N/A';

      if(sizeof($diffArray) < 5){

        $handicap = 'N/A';

      }else{

        $roundsPosted = sizeof($diffArray);
        $numDiffs = 1;
        switch($roundsPosted)
        {

            case 5:
            case 6:
                $numDiffs = 1;
                break;
            case 7:
            case 8:
                $numDiffs = 2;
                break;
            case 9:
            case 10:
                $numDiffs = 3;
                break;
            case 11:
            case 12:
                $numDiffs = 4;
                break;
            case 13:
            case 14:
                $numDiffs = 5;
                break;
            case 15:
            case 16:
                $numDiffs = 6;
                break;
            case 17:
                $numDiffs = 7;
                break;
            case 18:
                $numDiffs = 8;
                break;
            case 19:
                $numDiffs = 9;
                break;
            case 20:
                $numDiffs = 10;
                break;
            default:
                break;
        }

        sort($diffArray);
        $diffArray = array_slice($diffArray, 0, $numDiffs, true);
        $sum = 0;
        foreach($diffArray as &$adjustedScore){

          $adjustedScore = $adjustedScore * .96;
          $sum += $adjustedScore;
        }
        $average = $sum/$numDiffs;
        $handicap = round($average, 2);


      }

      return $handicap;
    }
}
