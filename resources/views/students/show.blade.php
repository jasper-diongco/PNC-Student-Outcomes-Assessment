@extends('layouts.sb_admin')

@section('title', 'Add new Student')


@section('content')

    <a href="{{ url('/students') }}" class="text-success"><i class="fa fa-arrow-left"></i> Back</a>
    
    <div id="app">
        <div class="row">
            <div class="col-md-8 mx-auto mt-3">
                <div class="card shadow">
                    <div class="card-header">
                        <h3>Student Information</h3>
                    </div>

                    <div class="card-body">
                        <img src="{{ asset('img/user.svg') }}" alt="user-icon" style="width: 40px" class="mb-2">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <label><b>Last Name: </b></label>
                            <span>{{ $student->user->last_name }}</span>
                        </li>
                        <li class="list-group-item">
                            <label><b>First Name: </b></label>
                            <span>{{ $student->user->first_name }}</span>
                        </li>
                        <li class="list-group-item">
                            <label><b>Middle Name: </b></label>
                            <span>{{ $student->user->middle_name }}</span>
                        </li>
                        <li class="list-group-item">
                            <label><b>Sex: </b></label>
                            <span>{{ $student->user->sex == 'M' ? 'Male' : 'Female' }}</span>
                        </li>
                        <li class="list-group-item">
                            <label><b>Date of Birth: </b></label>
                            <span>@{{ date_of_birth | date  }}</span>
                        </li>
                        
                    </ul>
                </div>
            </div>
        </div>
        
    </div>
@endsection

@push('scripts')
    <script>
        new Vue({
            el: '#app',
            data: {
                date_of_birth: '{{ $student->user->date_of_birth }}'
            },
            filters: {
                date(value) {
                    return moment(value).format("MMM D, YYYY");
                }
            }
        });
    </script>
@endpush

{{-- @push('scripts')
    <script>
        new Vue({
            el: '#app',
            data: {
                form: new Form({
                    student_id: '',
                    last_name: '',
                    first_name: '',
                    middle_name: '',
                    sex: '',
                    date_of_birth: '',
                    college: '',
                    program: '',
                    curriculum: '',
                    email: '',
                    password: 'DefaultPass123'
                }),
                colleges: @json($colleges),
                programs: @json($programs),
                curriculums: @json($curriculums)
            },
            methods: {
                selectProgramsByCollege(college_id) {
                    return this.programs.filter(program => {
                        return program.college_id == college_id;
                    });
                },
                selectCurriculumsByProgram(program_id) {
                    return this.curriculums.filter(curriculum => {
                        return curriculum.program_id == program_id;
                    });
                },
                addStudent() {
                    this.form.post('../students')
                        .then(response => {
                            window.location.replace(myRootURL + '/students');
                        })
                        .catch(err => {
                            console.log(err);
                        });
                }
            }
        })
    </script>
@endpush --}}