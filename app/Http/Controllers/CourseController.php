<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use DB;

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
      //"name","city","state")
      $data = Course::select("id",DB::raw("CONCAT(courses.name, ' - ' , courses.city, ', ', courses.state) as name"))
              ->where("name","LIKE","%".$query."%")
              ->get();

      //$data->name = $data->name . " - " . $data->city . ", " .  $data->state;

      return response()->json($data);
  }
}
