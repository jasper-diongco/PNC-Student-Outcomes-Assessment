@extends('layout.app', ['active' => 'assessments'])


@section('title', 'Assessments')

@section('content')
    <div id="app">
        
        <div class="card">
            

            <div class="card-body pt-4">
                <h1 class="page-header"><i class="fa fa-edit text-info"></i> Assessment</h1>
                <h5 class="text-muted mb-2">List of Student Outcomes</h5>

                <ul class="list-group list-student-outcomes">
                    @foreach($student_outcomes as $student_outcome)
                    <li class="list-group-item mb-3">
                        <div class="d-flex align-items-center mr-3">
                            <div>
                              <span class="avatar-student-outcome mr-3" style="background:#86db67">{{ $student_outcome->so_code }}</span>
                            </div>
                            <span>{{ $student_outcome->description }}</span>

                        </div>
                        <div class="d-flex justify-content-between mt-3">
                            <div>
                                <button data-toggle="modal" data-target="#gradeModal{{ $student_outcome->id }}" class="btn btn-light text-warning btn-sm">View requisite courses</button>

                                <!-- Modal -->
                                <div class="modal fade" id="gradeModal{{ $student_outcome->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                  <div class="modal-dialog modal-xl" role="document">
                                    <div class="modal-content ">
                                      <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">{{ $student_outcome->so_code }} requisite courses</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                      </div>
                                      <div class="modal-body">
                                        <div class="table-responsive">
                                            <table class="table table-borderless">
                                                <thead>
                                                    <tr>
                                                        <th>Course Code</th>
                                                        <th >Description</th>
                                                        <th>Units</th>
                                                        <th>Grade</th>
                                                        <th>Remarks</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody style="font-weight: 400">
                                                    @foreach($student_outcome->getCoursesGrade($student->curriculum_id, $student_outcome->id, $student->id) as $course_grade)
                                                    <tr class="{{ $course_grade['is_passed'] ? 'bg-success-light' : '' }} {{ $course_grade['remarks'] == 'Failed' ? 'bg-danger-light' : '' }}">
                                                        <td>{{ $course_grade['course_code'] }}</td>
                                                        <td>{{ $course_grade['course_desc'] }}</td>
                                                        <td>{{ $course_grade['lec_unit'] +  $course_grade['lab_unit'] }}</td>
                                                        <td>{{ $course_grade['grade_text'] }}</td>
                                                        <td>{{ $course_grade['remarks'] }}</td>
                                                        
                                                        <td>
                                                            @if($course_grade['is_passed'])
                                                            <i class="fa fa-check text-success"></i>
                                                            @else
                                                            <i class="fa fa-times"></i>
                                                            @endif
                                                        </td>

                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                            </div>
                            <div>
                                @if($student_outcome->assessment_type_id == 2)
                                    @if($student_outcome->checkIfHasCustomRecordedAssessment(Auth::user()->getStudent()->id))
                                        <a href="{{ url('s/assessments/show_custom_recorded_assessment_score?student_id=' . $student->id . '&student_outcome_id=' . $student_outcome->id) }}" class="btn btn-success btn-sm">View Result <i class="fa fa-file-alt"></i></a>
                                    @endif
                                @elseif($student_outcome->checkIfExamOngoing($student->id))
                                    <a href="{{ url('/s/assessments/' . $student_outcome->id) }}" class="btn btn-info btn-sm">Continue Assessment <i class="fa fa-arrow-right"></i></a>
                                @elseif($student_outcome->checkIfTaken($student->id))
                                    <a href="{{ url('s/assessments/show_score?student_id=' . $student->id . '&student_outcome_id=' . $student_outcome->id) }}" class="btn btn-success btn-sm">View Result <i class="fa fa-file-alt"></i></a>
                                @elseif($student_outcome->checkIfAvailableForExam())
                                    
                                    @if($student_outcome->getExams(Auth::user()->getStudent()->curriculum_id)->count() > 0)
                                        <button v-on:click="confirmAssessment({{ $student_outcome->id }})"  class="btn btn-sm mt-2 btn-info">Take assessment <i class="fa fa-edit"></i></button>
                                    @else
                                        <button disabled="true" class="btn btn-sm mt-2 {{ $student_outcome->checkIfAvailableForExam() ? 'btn-info' : 'btn-secondary' }}">Take Assessment <i class="fa fa-edit"></i>
                                            </button>
                                        <div class="text-danger text-center" style="font-size: 13px">No Available Exam</div>
                                        
                                    @endif
                                @else
                                    <button disabled="true" class="btn btn-sm mt-2 btn-secondary">Take Assessment <i class="fa fa-edit"></i></button>
                                @endif
                            </div>
                        </div>
                        
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        new Vue({
            el: '#app',
            data: {
            },
            methods: {
                confirmAssessment(student_outcome_id) {
                    swal.fire({
                        type: 'question',
                        title: 'Please confirm',
                        text: 'do you want to take the assessment?',
                        showCancelButton: true,
                        width: '400px',
                        confirmButtonColor: '#11c26d'
                      }).
                      then(isConfirmed => {
                        if(isConfirmed.value) {
                            window.location.replace(myRootURL + '/s/assessments/' + student_outcome_id);
                        }
                      })
                      .catch(error => {
                        alert("An Error Has Occured. Please try again.");
                        console.log(error);
                      })
                    //href="@{{ url('/s/assessments/' . $student_outcome->id) }}"
                }
            }
        });
    </script>
@endpush