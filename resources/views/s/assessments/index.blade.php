@extends('layout.app', ['active' => 'assessments'])


@section('title', 'Assessments')

@section('content')
    <div id="app">
        <h1 class="page-header">Assessment</h1>
        <div class="card">
            <div class="card-body pt-4">
                <h5 class="mb-4" style="font-weight: 300">
                    <div class="d-flex align-items-center">
                        <div class="mr-2">List of Student Outcomes </div>
                        <div>
                            <img src="{{ asset('/img/list_md.svg') }}" style="width: 23px; height: 23px">
                        </div>
                    
                    </div>
                </h5>

                <ul class="list-group list-student-outcomes">
                    @foreach($student_outcomes as $student_outcome)
                    <li class="list-group-item mb-3">
                        <div class="d-flex align-items-center mr-3">
                            <div>
                              <span class="avatar-student-outcome mr-3 bg-success">{{ $student_outcome->so_code }}</span>
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
                                                @foreach($student_outcome->getCoursesGrade() as $course_grade)
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
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                            </div>
                            <div>
                                @if($student_outcome->checkIfAvailableForExam())
                                    <a href="{{ url('/s/assessments/' . $student_outcome->id) }}" class="btn btn-sm mt-2 btn-info">Take assessment <i class="fa fa-edit"></i></a>
                                @else
                                    <button disabled="true" class="btn btn-sm mt-2 {{ $student_outcome->checkIfAvailableForExam() ? 'btn-info' : 'btn-secondary' }}">Take Assessment <i class="fa fa-edit"></i></button>
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
            el: '#app'
        });
    </script>
@endpush