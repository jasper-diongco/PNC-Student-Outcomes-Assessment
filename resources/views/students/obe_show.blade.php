@extends('layout.app', ['active' => 'users'])

@section('title', 'OBE Curriculum')

@section('content')
    <div id="app" v-cloak>
        <grade-modal :student-id="student_id" :course-id="course_id" :course-code="course_code" :grade-values="grade_values" v-on:grade-added="refreshStudentGrades"></grade-modal>

        <a href="{{ url('/students') }}" class="text-success"><i class="fa fa-arrow-left"></i> Back</a>
        
        <div class="mt-3">
            <h1 class="page-header mb-3">OBE Curriculum &mdash; {{ $curriculum->name }}</h1>

        </div>
        
        <div class="mr-4"><i class="fa fa-user text-primary"></i> <label>Student:</label> <span class="text-success" style="font-size: 130%">{{ $student->user->getFullName() }}</span>, {{ $student->program->program_code }}</div>

        <div class="mr-4"><i class="fa fa-calendar-check text-primary"></i> <label>Year:</label> {{ $curriculum->year }}</div>

        <div class="mr-4"><i class="fa fa-file-alt text-primary"></i> <label>Description:</label> {{ $curriculum->description }}</div>
        
        {{-- <h5 class="mt-4 mb-2"><i class="fa fa-file-alt"></i> Grades</h5> --}}
        <div class="d-flex justify-content-end mt-4 mb-2">
           <div class="custom-control custom-switch">
              <input v-model="expand_all" type="checkbox" class="custom-control-input" id="customSwitch1">
              <label style="font-weight: 400" class="custom-control-label" for="customSwitch1">Expand all</label> 
            </div> 
        </div>
        
        <div class="accordion" id="accordionOBE">
            <div v-for="year_level in parseInt(curriculum_year_level)">
                <div v-for="semester in 3">
                  <div v-if="semester == 1 || semester == 2 || (semester == 3 && getSummerCourses(year_level).length > 0)"  :key="'card-' + year_level + '-' + semester" class="card">
                    <div class="card-header">
                      <h2 class="mb-0">
                        <button v-on:click="expand_all = false" class="text-success btn btn-link" type="button" data-toggle="collapse" :data-target="'#collapse' + year_level + '' + semester">
                          <b>@{{ indexNum(year_level) }} year / @{{ indexNum(semester) }} sem</b>
                        </button>
                      </h2>
                    </div>

                    <div :id="'collapse' + year_level + '' + semester" class="collapse" :class="{'show': (year_level == 1 && semester == 1) || expand_all }" data-parent="#accordionOBE">
                      <div class="card-body">

                        <table class="table table-borderless">
                            <thead>
                                <tr>
                                    <th>Course Code</th>
                                    <th width="30%">Description</th>
                                    <th>Units</th>
                                    <th>Grade</th>
                                    <th>Remarks</th>
                                    <th>Professor</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="curriculum_course in getCoursesBySemester(year_level, semester)" :class="{'bg-success-light': getGradeOfCourse(curriculum_course.course.id).is_passed, 'bg-danger-light': getGradeOfCourse(curriculum_course.course.id).remarks == 'Failed' }">
                                    <td>@{{ curriculum_course.course.course_code }}</td>
                                    <td>@{{ curriculum_course.course.description }}</td>
                                    <td>@{{ curriculum_course.course.lec_unit + curriculum_course.course.lab_unit  }}</td>
                                    <td>@{{ getGradeOfCourse(curriculum_course.course.id).grade_text }}</td>
                                    <td>@{{ getGradeOfCourse(curriculum_course.course.id).remarks }}</td>
                                    <td>@{{ getGradeOfCourse(curriculum_course.course.id).professor_name }}</td>
                                    <td>
                                        <i class="fa fa-check text-success" v-if="getGradeOfCourse(curriculum_course.course.id).is_passed"></i>
                                        <button v-else v-on:click="openGradeModal(curriculum_course.course.id, curriculum_course.course.course_code)" class="btn btn-success btn-sm"><i class="fa fa-edit"></i></button>
                                        
                                    </td>
                                    {{-- <td><i class="fa fa-check text-success"></i></td> --}}
                                </tr>
                            </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
        </div>
        
    </div>
@endsection

@push('scripts')
<script>
    var vm = new Vue({
        el: '#app',
        data: {
            curriculum_courses: @json($curriculum_courses),
            curriculum_year_level: '{{ $curriculum->year_level }}',
            grade_values: @json($grade_values),
            grades: @json($grades),
            student_grades: @json($student_grades) ,
            course_id: '',
            course_code: '',
            student_id: '{{ $student->id }}',
            tableLoading: false,
            expand_all: true
        },
        methods: {
            getCoursesBySemester(year_level, semester) {
                return this.curriculum_courses.filter(curriculum_course => {
                    return (curriculum_course.year_level == year_level && curriculum_course.semester == semester);
                });
            },
            getSummerCourses(year_level) {
                return this.curriculum_courses.filter(curriculum_course => {
                    return (curriculum_course.year_level == year_level && curriculum_course.semester == 3);
                });
            },
            indexNum(num) {
                var output = '';

                if(num == 1) {
                    output = '1st';
                } else if(num == 2) {
                    output = '2nd';
                } else if(num == 3) {
                    output = '3rd';
                } else if(num >= 4) {
                    output = num + 'th';
                }

                return output;
            },
            openGradeModal(course_id, course_code) {
                this.course_id = course_id;
                this.course_code = course_code;
                $('#gradeModal').modal('show');
            },
            getGradeOfCourse(course_id) {
                return this.student_grades.filter(student_grade => {
                    return student_grade.course_id == course_id;
                })[0];
            },
            refreshStudentGrades() {
                this.tableLoading = true;
                ApiClient.get('/students/'+ this.student_id  +'/refresh_student_grades')
                .then(response => {
                    this.student_grades = response.data;
                    this.tableLoading = false;
                    //console.log(response);
                })
                .catch(error => {
                    alert("An erro has occured. Try refreshing the page.");
                    this.tableLoading = false;
                })

            }
        }
    });
</script>
@endpush