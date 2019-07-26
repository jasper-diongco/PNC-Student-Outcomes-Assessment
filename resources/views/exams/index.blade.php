@extends('layout.app', ['active' => 'test_questions'])

@section('title', 'Exams')

@section('content')
    
    <div id="app">
        
    
        {{-- <a href="{{ url('test_bank/'. request('program_id') .'/list_student_outcomes') }}" class="text-success"><i class="fa fa-arrow-left"></i> Back</a> --}}

                

        <div class="card">
            <div class="card-body pt-4">
                <a href="{{ url('test_bank?program_id='. request('program_id')) }}" class="text-success"><i class="fa fa-arrow-left"></i> Back</a>
                <div class="d-flex justify-content-between mt-3">

                    <div>
                        <h1 class="page-header mb-3">List of Exams</h1>
                    </div>          

                    <div>
                        <a href="{{ url('/exams/create?program_id='. request('program_id') .'&student_outcome_id=' . request('student_outcome_id') . '&curriculum_id='. request('curriculum_id')) }}" class="btn btn-success-b">Add New Exam</a>
                    </div>


                    
                   
                </div>
                
                <div class="d-flex flex-wrap mb-3">

                    <div class="mr-3"><label>Program: </label> <span class="text-info fs-19">{{ $program->program_code }}</span></div>
                    <div class="mr-3"><label>Student Outcome: </label> <span class="text-info fs-19">{{ $student_outcome->so_code }}</span></div>
                    <div class="mr-3"><label>Curriculum: </label> <span class="text-info fs-19">{{ $curriculum->name . ' ' . $curriculum->year . ' - revision no.' . $curriculum->revision_no }}.0</span></div>
                </div>

                <table class="table table-borderless">
                    <thead>
                        <tr>
                            <th>
                                ID
                            </th>
                            <th>
                                Description
                            </th>
                            <th>
                                Time Limit
                            </th>
                            <th>
                                Passing Grade
                            </th>
                            <th>
                                No of Questions
                            </th>
                            <th>
                                Date Created
                            </th>
                            <th>
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($exams->count() > 0)
                            @foreach($exams as $exam)
                                <tr>
                                    <td>{{ $exam->id }}</td>
                                    <td>{{ $exam->description }}</td>
                                    <td>{{ $exam->time_limit }} minutes</td>
                                    <td>{{ $exam->passing_grade }}%</td>
                                    <td>{{ $exam->examTestQuestions->count() }}</td>
                                    <td>{{ $exam->created_at->format('M d, Y') }}</td>
                                    <td><a href="{{ url('/exams/' . $exam->id .'?program_id='. request('program_id') .'&student_outcome_id=' . request('student_outcome_id') . '&curriculum_id='. request('curriculum_id')) }}" class="btn btn-sm btn-secondary"><i class="fa fa-search"></i></a></td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="text-center">No Record Found In Database.</td>
                            </tr>
                        @endif


                    </tbody>
                </table>
                <div class="text-muted">Showing {{ $exams->count() }} records.</div>
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