@extends('layout.app', ['active' => 'student_outcomes'])

@section('title', 'Student Outcomes - ' . $program->description)

@section('content')



<div id="app" class="mt-3" v-cloak>
  
  <div class="card p-3 mb-3">
    <a href="{{ url('/student_outcomes/list_program?college_id='. Session::get('college_id')) }}" class="text-success"><i class="fa fa-arrow-left"></i> Back</a>
    {{-- <div class="mx-auto" style="width: 400px">
      <img src="{{ asset('svg/goals.svg') }}" class="w-100">
    </div> --}}
      <div class="d-flex justify-content-between mb-0 mt-3">
        <div>
          <h1 class="page-header mt-0">Student Outcomes</h1>
        </div>
        <div>
          @if(!$program->so_is_saved)
            @if(Gate::check('isDean') || Gate::check('isSAdmin'))
              <student-outcome-modal :programs='@json($programs)' :program-id="{{ $program->id }}"></student-outcome-modal>
            @endif
          @else
            <button :disabled="isLoading" class="btn btn-primary btn-sm" v-on:click="confirmRevise">
              <div v-if="isLoading" class="spinner-border spinner-border-sm text-light" role="status">
                <span class="sr-only">Loading...</span>
              </div> Revise <i class="fa fa-edit"></i></button>
          @endif
        </div>
      </div>
    <label class="text-dark"><i class="fa fa-graduation-cap"></i> Program: <span class="text-info fs-19">{{ $program->description }}</span></label> 
    <label class="text-dark"><i class="fa fa-code-branch"></i> Revision Number: <span class="text-info fs-19">{{ $program->so_rev_no }}.0</span></label>


  </div>
  
  
  @if(count($program->studentOutcomes) > 0) 
    <div v-if="!so_is_saved" class="alert alert-warning">
      <strong>Note:</strong> Once you clicked and submited the save button. You cannot modify <i>(add, update, remove student outcomes)</i> this version anymore. Instead you can revise to do so.
    </div>
  @endif
  
  <div id="main-nav-tabs">
    <ul  class="nav nav-tabs" id="myTab" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" id="student-outcomes-tab" data-toggle="tab" href="#student_outcomes" role="tab" aria-controls="home" aria-selected="true">Student Outcomes</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="deactivated-tab" data-toggle="tab" href="#deactivated" role="tab" aria-controls="profile" aria-selected="false">Deactivated</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="archive-tab" data-toggle="tab" href="#archive" role="tab" aria-controls="contact" aria-selected="false">Archive</a>
      </li>
    </ul>
    <div class="tab-content" id="myTabContent">
      <div class="tab-pane fade show active" id="student_outcomes" role="tabpanel">

        @if(count($program->studentOutcomes) > 0) 
        <div class="list-group list-student-outcomes">
            <a v-for="student_outcome in student_outcomes" :key="student_outcome.id" :href="'student_outcomes/' + student_outcome.id +  '?program_id=' + program_id" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
              <div class="d-flex justify-content-between align-items-center mr-3">
                <div>
                  <span class="avatar-student-outcome mr-3 bg-success">@{{ student_outcome.so_code }}</span>
                </div>
              <div>
                <span>@{{ student_outcome.description }}</span>
                <button v-if="!so_is_saved" v-on:click.prevent="confirmRemove(student_outcome.id)" class="btn btn-sm">Remove <i class="fa fa-archive text-warning"></i></button>
              </div>
                
                
              </div>
              <div>
                <i class="fa fa-chevron-right"></i>
              </div>
            </a>
        </div>
        
        @if(!$program->so_is_saved)
          <div class="d-flex justify-content-end mt-3">
            <button :disabled="isLoading" class="btn btn-primary" v-on:click="confirmSave">
              <div v-if="isLoading" class="spinner-border spinner-border-sm text-light" role="status">
                <span class="sr-only">Loading...</span>
              </div>
              Save
            </button>
          </div>
        @endif
      @else
        <div class="text-center bg-white p-3">No Student Outcome Found in Database.</div>
      @endif
      </div>
      <div class="tab-pane fade" id="deactivated" role="tabpanel">
        
        @if(count($program->deactivated_student_outcomes()) > 0) 
          <div class="list-group list-student-outcomes">
              <a v-for="student_outcome in deactivated_student_outcomes" :key="student_outcome.id" :href="'student_outcomes/' + student_outcome.id +  '?program_id=' + program_id" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                <div class="d-flex justify-content-between align-items-center mr-3">
                  <div>
                    <span class="avatar-student-outcome mr-3 bg-success">@{{ student_outcome.so_code }}</span>
                  </div>
                <div>
                  <span>@{{ student_outcome.description }}</span>
                  <button v-if="!so_is_saved"  v-on:click.prevent="confirmActivate(student_outcome.id)" class="btn btn-sm">Activate <i class="fa fa-arrow-left text-success"></i></button>
                </div>
                  
                  
                </div>
                <div>
                  <i class="fa fa-chevron-right"></i>
                </div>
              </a>
          </div>
        @else
          <div class="text-center bg-white p-3">No Student Outcome Found in Database.</div>
        @endif
      </div>
      <div class="tab-pane fade" id="archive" role="tabpanel">
        {{-- Archives --}}
        <div class="accordion" id="accordionArchive">
          @if(count($so_archive_versions) > 0)
            @foreach($so_archive_versions as $so_archive_version)
              <div class="card">
                <div class="card-header" id="headingOne">
                  <h2 class="mb-0">
                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse{{ $so_archive_version->revision_no }}" aria-expanded="true" aria-controls="collapseOne">
                      Revision Number: #{{ $so_archive_version->revision_no }}.0
                    </button>
                  </h2>
                </div>

                <div id="collapse{{ $so_archive_version->revision_no }}" class="collapse" aria-labelledby="headingOne" data-parent="#accordionArchive">
                  <div class="card-body">
                    <ul class="list-group list-student-outcomes">
                      @foreach($so_archive_version->student_outcome_archives() as $so_archive)
                        <li class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                          <div class="d-flex justify-content-between align-items-center mr-3">
                            <div>
                              <span class="avatar-student-outcome mr-3 bg-success">{{ $so_archive->so_code }}</span>
                            </div>
                          <div>
                            <span>{{ $so_archive->description }}</span>
                          </div>
                            
                            
                          </div>
                        </li>
                      @endforeach
                    </ul>
                  </div>
                </div>
              </div>
            @endforeach
          @else
            <div class="text-center">No Archive</div>
          @endif
        </div>
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
        student_outcomes: @json($program->studentOutcomes),
        deactivated_student_outcomes: @json($program->deactivated_student_outcomes()) ,
        program_id: '{{request('program_id')}}',
        so_is_saved: {{ $program->so_is_saved }},
        isLoading: false
      },
      methods: {
        confirmRemove(id) {
          swal.fire({
            type: 'question',
            title: 'Please confirm',
            text: 'do you want to remove this student outcome?',
            showCancelButton: true,
            width: '400px',
            confirmButtonColor: '#11c26d'
          }).
          then(isConfirmed => {
            if(isConfirmed.value) {
              ApiClient.delete('/student_outcomes/' + id)
              .then(response => {
                toast.fire({
                  type: 'success',
                  title: 'Successfully Removed!'
                });
                window.location.reload();
              }) 
            }
          })
          .catch(error => {
            alert("An Error Has Occured. Please try again.");
            console.log(error);
          })
        },
        confirmActivate(id) {
          swal.fire({
            type: 'question',
            title: 'Please confirm',
            text: 'do you want to activate this student outcome?',
            showCancelButton: true,
            width: '400px',
            confirmButtonColor: '#11c26d'
          }).
          then(isConfirmed => {
            if(isConfirmed.value) {
              ApiClient.post('/student_outcomes/' + id + "/activate")
              .then(response => {
                toast.fire({
                  type: 'success',
                  title: 'Successfully Activated!'
                });
                window.location.reload();
              }) 
            }
          })
          .catch(error => {
            alert("An Error Has Occured. Please try again.");
            console.log(error);
          })
        },
        confirmSave() {

          swal.fire({
            type: 'question',
            title: 'Please confirm',
            text: 'do you want to save?',
            showCancelButton: true,
            width: '400px',
            confirmButtonColor: '#11c26d'
          }).
          then(isConfirmed => {
            if(isConfirmed.value) {
              this.isLoading = true;
              ApiClient.post('/programs/' + this.program_id + "/save_student_outcomes")
              .then(response => {
                toast.fire({
                  type: 'success',
                  title: 'Sucessfully Saved!'
                });
                this.isLoading = true;
                window.location.reload();
              }) 
            }
          })
          .catch(error => {
            this.isLoading = false;
            alert("An Error Has Occured. Please try again.");
            console.log(error);
          })
        },
        confirmRevise() {
          swal.fire({
            type: 'question',
            title: 'Please confirm',
            text: 'do you want to revise?',
            showCancelButton: true,
            width: '400px',
            confirmButtonColor: '#11c26d'
          }).
          then(isConfirmed => {
            if(isConfirmed.value) {
              this.isLoading = true;
              ApiClient.post('/programs/' + this.program_id + "/revise_student_outcomes")
              .then(response => {
                toast.fire({
                  type: 'success',
                  title: 'You can now update the student outcomes.'
                });
                this.isLoading = false;
                window.location.reload();
              }) 
            }
          })
          .catch(error => {
            this.isLoading = false;
            alert("An Error Has Occured. Please try again.");
            console.log(error);
          })
        }
      }
    });
  </script>
@endpush
