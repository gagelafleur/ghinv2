<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tee;

class TeeController extends Controller
{
  public function view(Request $request)
  {

      $query = $request->course_id;

      $data = Tee::where("course_id",$query)->get();

      return response()->json($data);
  }
}
