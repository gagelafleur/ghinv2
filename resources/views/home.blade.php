@extends('layouts.app')

@section('content')
  <div class="container">
      <div class="row">
          <div class="col-md-10 col-md-offset-1">
              <div class="panel panel-default">
                  <div class="panel-heading">Dashboard</div>

                  <div class="panel-body">

                      <h1>Your Ghin Handicap: {{$handicap}}</h1>
                      @role('score_poster')
                      <p>
                        <a href="{--route('add.score')--}"><button class="btn-success btn-sm" id="btn-add"><i class="fa fa-plus-circle"></i> Add Score</button></a>
                      </p>
                      @endrole
                      <div class="table-responsive">
                        <table id="example" class="display dt-responsive table-responsive nowrap" cellspacing="0" width="100%">
                          <thead>
                            <tr>
                              <th>
                                Date
                              </th>
                              <th>
                                Score
                              </th>
                              <th>
                                Diff. Score
                              </th>
                              <th>
                                Course
                              </th>
                              <th>
                                Tees
                              </th>
                              <th>
                                CR/Slope
                              </th>
                            </tr>
                          </thead>
                          <tbody>
                          @foreach($scores as $score)

                            <tr>
                              <td>
                                {{$score->played_on}}
                              </td>
                              <td>
                                {{$score->score}}
                              </td>
                              <td>
                                {{$score->diffScore}}
                              </td>
                              <td>
                                {{$score->course}}
                              </td>
                              <td>
                                {{$score->tees}}
                              </td>
                              <td>
                                {{$score->course_info}}
                              </td>
                            </tr>

                          @endforeach
                          </tbody>
                        </table>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
@endsection
