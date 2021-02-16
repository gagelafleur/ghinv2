<?php

namespace App\Http\Controllers;

use App\Models\Score;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Course;
use App\Models\Tee;
use Auth;

class ScoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Score  $score
     * @return \Illuminate\Http\Response
     */
    public function show(Score $score)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Score  $score
     * @return \Illuminate\Http\Response
     */
    public function edit(Score $score)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Score  $score
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Score $score)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Score  $score
     * @return \Illuminate\Http\Response
     */
    public function destroy(Score $score)
    {
        //
    }

    public function getAddScore(){

    $courses = Course::orderBy('name')->where('state','NY')->get();
    $accessingUser = Auth::User();

    $sticky = [];
    $others = [];

    foreach($courses as $course){

      if($course->course_name === 'Winged Pheasant'){

        $sticky[] = $course;

      }else{

        $others[] = $course;

      }

    }

    $courses = array_merge($sticky, $others);

    return view('score.add', ['user' => $accessingUser, 'courses' => $courses]);


  }

  public function postSaveCourse(Request $request){
    //dd($request);
    $this->validate($request, [

      'score' => 'required|numeric|max:200',
      'date' => 'required|date',
      'courseHidden' => 'required|numeric',
      'tee' => 'required|numeric',

    ]);

    $accessingUser = Auth::User();

    $score = new Score();
    $score->score = $request->score;
    $score->played_on = $request->date;
    $score->course_id = $request->courseHidden;
    $score->tee = $request->tee;
    $score->user_id = $accessingUser->id;
    $score->save();

    //Activity::log($accessingUser->name." Posted a Score.");

    return redirect()->route('home')->with(['success' => 'Score Successfuly Added']);


  }
}
