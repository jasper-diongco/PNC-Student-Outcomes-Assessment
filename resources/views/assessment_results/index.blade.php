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
                    <select v-on:change="getAssessmentResults" v-model="program_id" class="form-control">
                        <option value="" class="d-none">Select Program</option>
                        <option v-for="program in programs" :key="program.id" :value="program.id">@{{ program.program_code }}</option>
                    </select>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="input-group mb-3" id="search-input">
                    
                        <input v-on:input="searchAssessment" v-model="search" type="search" class="form-control" placeholder="Search student...">
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
                        <th>SO</th>
                        <th>Score</th>
                        <th>Remarks</th>
                        <th>Date</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        <template v-if="tableLoading">
                            <td colspan="9">
                                <table-loading></table-loading>
                            </td>
                        </template>
                        <template v-else>
                            <template v-if="assessments.length > 0">                                              
                                <tr v-for="assessment in assessments">
                                    <td>@{{ assessment.assessment_code }}</td>
                                    <td>@{{ assessment.student.student_id }}</td>
                                    <td>@{{ assessment.student.user.first_name + ' ' + assessment.student.user.last_name }}</td>
                                    <td>@{{ assessment.student_outcome.program.program_code }}</td>
                                    <th class="text-center">@{{ assessment.student_outcome.so_code }} - @{{ assessment.student_outcome.program.program_code }}</th>
                                    <td>@{{ assessment.score | score }}</td>
                                    <th v-if="assessment.is_passed" class="text-success">Passed</th>
                                    <th V-else class="text-danger">Failed</th>
                                    <td>@{{ assessment.created_at | date }}</td>
                                    <th><a :href="'assessment_results/' + assessment.id + '?college_id=' + college_id" class="btn btn-sm btn-info">View <i class="fa fa-search"></i></a></th>
                                </tr>
                            </template> 
                            <template v-else>
                                <tr>
                                    <td colspan="9" class="text-center">
                                        No Record Found.
                                    </td>
                                </tr>
                            </template>
                        </template>
                    </tbody>
                </table>
                <!-- Pagination -->
                  <div>Showing @{{ assessments.length }} records</div>
                  <nav v-show="search.trim() == ''">
                    <ul class="pagination justify-content-end">
                      <li class="page-item" :class="{ disabled: meta.current_page == 1 }">
                        <a class="page-link" href="#" v-on:click.prevent="paginate(meta.current_page - 1)">Previous</a>
                      </li>
                      <template v-for="num in totalPagination">
                        <li class="page-item" :class="{ active : num == meta.current_page }">
                          <a class="page-link" href="#" v-on:click.prevent="paginate(num)">@{{ num }}</a>
                        </li>
                      </template>
                      <li class="page-item" :class="{ disabled: meta.current_page == meta.last_page }">
                        <a class="page-link" href="#" v-on:click.prevent="paginate(meta.current_page + 1)">Next</a>
                      </li>
                    </ul>
                  </nav>
               <!-- End Pagination -->
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
            college_id: '{{ request('college_id') }}',
            tableLoading: true,
            search: '',
            meta: {
              total: 0,
              per_page: 0,
              current_page: 1
            },
            links: {},
            totalPagination: 0
        },
        filters: {
            score(value) {
                return Math.round(value) + "%";
            },
            date(value) {
                return moment(value).format('MMM D, YYYY');
            }
        },
        methods: {
            getAssessmentResults(page=1) {
                ApiClient.get('/assessment_results/get_assessments?page=' + page + '&program_id=' + this.program_id)
                    .then(response => {
                      this.assessments = response.data.data;
                      this.tableLoading = false;
                      this.meta.total = response.data.total;
                      this.meta.per_page = response.data.per_page;
                      this.meta.last_page = response.data.last_page;
                      this.meta.current_page = response.data.current_page;
                      // this.links = response.data.links;
                      this.totalPagination = Math.ceil(this.meta.total / this.meta.per_page);
                    }).
                    catch(err => {
                      console.log(err);
                      //serverError();
                      this.tableLoading = false;
                    })
            },
            paginate(page) {
                this.getAssessmentResults(page);
            },
            searchAssessment : _.debounce(() => {
                if(vm.search == '') {
                  return vm.getAssessmentResults();
                }
                //vm.filter_by_college = '';
                //vm.filter_by_privacy = '';
                vm.tableLoading = true;
                ApiClient.get('/assessment_results/get_assessments/?q=' + vm.search)
                .then(response => {
                  vm.assessments = response.data.data;
                  vm.tableLoading = false;
                }).
                catch(err => {
                  console.log(err);
                  vm.tableLoading = false;
                })
              }, 400)
        },

        created() {
            if(this.programs.length > 0) {
                this.program_id = this.programs[0].id;
            }
            this.getAssessmentResults();
        }
    });
</script>
@endpush

