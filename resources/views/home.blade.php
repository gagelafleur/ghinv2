@extends('layouts.app')

@section('content')
  <div class="container">
      <div class="row">
          <div class="col-md-10 col-md-offset-1">
              <div class="panel panel-default">
                  <div class="panel-heading">Dashboard</div>

                  <div class="panel-body">

                      <h1>Your Ghin Handicap: {{$handicap}}</h1>
                      @if(Auth::user()->hasRole('score_poster') || Auth::user()->hasRole('admin'))
                      <p>
                        <a href="{{route('score.add')}}"><button class="btn-success btn-sm" id="btn-add"><i class="fa fa-plus-circle"></i> Add Score</button></a>
                      </p>
                      @endif
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
                                Tee
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
@section('scripts')
<script type="text/javascript">
$(document).ready(function() {
  $.noConflict();
  var table = $('#example').DataTable({

    responsive: true,
    autoWidth: false

  });
  table.order( [[ 0, 'desc' ]] ).draw();
});
</script>
@endsection
