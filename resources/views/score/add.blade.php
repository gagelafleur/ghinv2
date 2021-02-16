@extends('layouts.app')

@section('content')
  <style>
  .hideinit .typeahead.dropdown-menu {
    display:none !important;
  }
  </style>
  <div class="container">
      <div class="row">
          <div class="col-md-10 mx-auto">
              <div class="panel panel-default">
                  <div class="panel-heading">Dashboard</div>

                  <div class="panel-body">

                    <form action="{{route('score.save')}}" method = "post" class = "hideinit">


                      <label for="score">Score:</label>
                      <input class="form-control" type="text" name="score" id="score_field" value="">
                      <br /><br />

                      <label for="date">Date:</label>
                      <input class="form-control" type="date" name="date" value="" id="datePicker">
                      <br /><br />

                      <label for="course">Course</label> | <a href = "javascript:void(0)" class = "typeahead-clear">clear</a>
                      <input class="form-control typeahead " type="text" name="course" autocomplete="off">
                      <br /><br />

                      <label for="course">Tees</label>
                      <select class="form-control" id="tees" name="tee"></select>
                      <br /><br />

                      <button type="submit" class="btn-primary">Post Score</button>
                      <input type="hidden" name="courseHidden" id="hiddenCourse" value="">
                      <input type="hidden" name="_token" value="{{Session::token()}}">

                    </form>

                  </div>

                  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>
                  <script type="text/javascript">
                      var coursePath = "{{ route('autocomplete') }}";
                      var teesPath = "{{ route('tees.json') }}";
                      var defaultCourse = "The Pheasant Golf Links - The Pheasant/The Liberator";
                      var $input = $('input.typeahead');
                      var $teesSelect = $('#tees');
                      var adjustFocus = true;


                      $( document ).ready(function() {
                        var defaultSet = false;

                        $('#datePicker').val(new Date().toDateInputValue());

                        $input.typeahead({
                          source:  function (query, process) {
                          return $.get(coursePath, { q: query }, function (data) {
                                  return process(data);
                              });
                          }

                        });

                        $input.val(defaultCourse).typeahead("lookup").find('.typeahead.dropdown-menu').hide();
                        setTimeout(function() {
                          $('.typeahead.dropdown-menu li:first').trigger('click');
                          $('.hideinit').removeClass('hideinit');
                          $('#score_field').trigger('focus');
                          //$('.hideinit')

                          /*.each(function(){
                            this.style.setProperty('display', 'none', undefined);
                          });*/

                        }, 1000);
                        //.parent().find('.typeahead.dropdown-menu li:first a').trigger('click');
                        //console.log($('.typeahead.dropdown-menu').html());


                      });

                      /*$input.on( "focus", function() {
                        if(!adjustFocus){
                          $(this).val("");
                        }else{
                          adjustFocus = false;

                        }
                      });*/

                      $('.typeahead-clear').on( "click", function() {
                        $input.val("");
                        $teesSelect.html("");
                      });


                      $input.change(function() {
                        //console.log($input.val());
                        var current = $input.typeahead("getActive");

                        if (current) {
                          //console.log(current.id);
                          $('#hiddenCourse').val(current.id);

                          $.ajax({

                              url : teesPath,
                              type : 'GET',
                              data : {
                                  'course_id' : current.id
                              },
                              dataType:'json',
                              success : function(data) {
                                  //;alert('Data: '+data);
                                  //console.log(data);
                                  //$teesSelect
                                  $teesSelect.html("");
                                  $.each(data, function( index, value ) {
                                    //console.log( index + ": " + value.id );

                                    var optionText = value.gender + " - " + value.tee_name + " - " + value.rating + "/"+value.slope;
                                    var optionValue = value.id;
                                    if(value.id == 135688){
                                      var o = new Option(optionText, optionValue, false, true);
                                    }else{
                                      var o = new Option(optionText, optionValue);
                                    }

                                    //$(o).html("option text");
                                    $teesSelect.append(o);
                                  });
                              },
                              error : function(request,error)
                              {
                                  //alert("Request: "+JSON.stringify(request));
                              }
                          });


                          // Some item from your model is active!
                          if (current.name == $input.val()) {
                            // This means the exact match is found. Use toLowerCase() if you want case insensitive match.
                          } else {
                            // This means it is only a partial match, you can either add a new item
                            // or take the active if you don't want new items
                          }
                        } else {
                          // Nothing is active so it is a new value (or maybe empty value)
                        }
                      });

                      Date.prototype.toDateInputValue = (function() {
                          var local = new Date(this);
                          local.setMinutes(this.getMinutes() - this.getTimezoneOffset());
                          return local.toJSON().slice(0,10);
                      });
                  </script>
              </div>
          </div>
      </div>
  </div>
@endsection
