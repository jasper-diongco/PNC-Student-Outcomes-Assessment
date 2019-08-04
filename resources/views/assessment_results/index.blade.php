@extends('layout.app', ['active' => 'assessments'])

@section('title', 'Assessment Results')


@section('content')
<div id="app" v-cloak>
    <div class="card">
        <div class="card-body pt-4">
            <div class="d-flex justify-content-between align-items-baseline">
                <div>
                    <h1 class="page-header"><i class="fa fa-poll"></i> Assessment Results</h1>
                </div>
                <div class="d-flex align-items-baseline">
                    <label class="mr-2 text-dark">Program</label>
                    <select v-model="program_id" class="form-control">
                        <option value="" class="d-none">Select Program</option>
                        <option v-for="program in programs" :key="program.id" :value="program.id">@{{ program.program_code }}</option>
                    </select>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="input-group mb-3" id="search-input">
                    
                        <input v-on:input="searchStudent" v-model="search" type="search" class="form-control" placeholder="Search student...">
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="fa fa-search"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-borderless">
                    <thead>
                        <th>Asessment ID</th>
                        <th>Student ID</th>
                        <th>Student</th>
                        <th>Program</th>
                        <th>Student Outcome</th>
                        <th>Score</th>
                        <th>Remark</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        <tr v-for="assessment in assessments">
                            <td>@{{ assessment.assessment_code }}</td>
                            <td>@{{ assessment.student.student_id }}</td>
                            <td>@{{ assessment.student.user.first_name + ' ' + assessment.student.user.last_name }}</td>
                            <td>@{{ assessment.student_outcome.program.program_code }}</td>
                            <th class="text-center">@{{ assessment.student_outcome.so_code }}</th>
                            <td>@{{ assessment.score | score }}</td>
                            <th v-if="assessment.is_passed" class="text-success">Passed</th>
                            <th V-else class="text-danger">Failed</th>
                            <th><a :href="'assessment_results/' + assessment.id + '?college_id=' + college_id" class="btn btn-sm btn-info">View <i class="fa fa-search"></i></a></th>
                        </tr>
                    </tbody>
                </table>
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
            programs: @json($programs),
            program_id: '',
            assessments: @json($assessments),
            college_id: '{{ request('college_id') }}'
        },
        filters: {
            score(value) {
                return Math.round(value) + "%";
            }
        },
        created() {
            this.program_id
        }
    });
</script>
@endpush

