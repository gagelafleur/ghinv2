<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;

class CourseController extends Controller
{
  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function autocomplete(Request $request)
  {

      $query = $request->q;

      $data = Course::select("id","name")
              ->where("name","LIKE","%".$query."%")
              ->get();

      return response()->json($data);
  }
}
