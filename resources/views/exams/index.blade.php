@extends('layout.app', ['active' => 'test_questions'])

@section('title', 'Exams')

@section('content')
    
    <div id="app">
        
    
        <a href="{{ url('test_bank/'. request('program_id') .'/list_student_outcomes') }}" class="text-success"><i class="fa fa-arrow-left"></i> Back</a>

        {{-- <div class="row mb-3 mt-3">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h3>Easy</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h3>Average</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h3>Difficult</h3>
                    </div>
                </div>
            </div>
        </div> --}}

        <div class="d-flex justify-content-between mt-3">
            <div>
                <h1 class="page-header">List of Exams</h1>
            </div>
          

            <div>
                <a href="{{ url('/exams/create?program_id='. request('program_id') .'&student_outcome_id=' . request('student_outcome_id') . '&curriculum_id='. request('curriculum_id')) }}" class="btn btn-success-b">Add New Exam</a>
            </div>


            
           
        </div>

        

        <div class="card">
            <div class="card-body">
                <h1>Test</h1>
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