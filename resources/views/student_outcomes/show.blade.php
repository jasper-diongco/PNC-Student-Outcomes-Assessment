@extends('layout.app', ['active' => 'student_outcomes'])

@section('title', 'Student Outcomes - ' . $student_outcome->so_code)

@section('content')



<div id="app" v-cloak class="mt-3">
  
  <div class="d-flex justify-content-between mb-3">
    <a href="{{ url('/student_outcomes?program_id='. request('program_id')) }}" class="text-success"><i class="fa fa-arrow-left"></i> Back</a>
    <div>
      <h1 class="page-header">Student Outcome &mdash; {{ $student_outcome->program->program_code }}</h1>

    </div>
    @if($student_outcome->is_active && !$student_outcome->program->so_is_saved)
      <div>
        @if(Gate::check('isDean') || Gate::check('isSAdmin') )
          <student-outcome-modal 
            :is-update="true" 
            :programs='@json($programs)' 
            :student-outcome='@json($student_outcome)'
            :performance-criteria='@json($student_outcome->performanceCriterias[0])'
            :performance-indicators='@json($student_outcome->performanceCriterias[0]->performanceCriteriaIndicators)'></student-outcome-modal>
        @endif
      </div>
    @endif
  </div>

  <div class="card">
    <div class="card-header">
      <div class="d-flex align-items-center">
        <div class="mr-3">
          <div class="avatar-student-outcome bg-success">
          {{ $student_outcome->so_code }}
          </div>
        </div>
        <div><label>{{ $student_outcome->description }}</label></div>
      </div>
    </div>
    <div class="card-body">
      <h3>Performance Criteria</h3>
      <p>{{ $student_outcome->performanceCriterias[0]->description }}</p>
      

      <div class="assessment-type p-3">
        <h5 class="text-info">Assessment Type</h5>
        <div class="d-flex">
          <select class="form-control" v-model="assessment_type_id">
            <template v-for="assessment_type in assessment_types">
              <option v-if="checkIfAvailable(assessment_type.id)"  :value="assessment_type.id">@{{ assessment_type.description }}</option>
            </template>
          </select>
        </div>
        <div class="d-flex mt-3 justify-content-end">
          <button class="btn btn-success" v-on:click="changeAssessmentType">Save</button>
        </div>
        
      </div>
      <hr>
      <h3 class="mb-3">Performance Indicators</h3>
      <div class="row">
        @foreach ($student_outcome->performanceCriterias[0]->performanceCriteriaIndicators as $pci)
          <div class="col-md-3">
            <div class="card mb-3" style="height: 100%">
              {{-- @if ($pci->performance_indicator_id == 1)
                <div class="card-header text-white" style="background:#f8cc5e;">
              @elseif ($pci->performance_indicator_id == 2)
                <div class="card-header text-white" style="background:#51c2d3;">
              @elseif ($pci->performance_indicator_id == 3)
                <div class="card-header text-white" style="background:#6b8ae4;">
              @elseif ($pci->performance_indicator_id == 4)
                <div class="card-header text-white" style="background:#25e19d;">
              @endif --}}
              <div class="card-header text-success bg-gray-100">

                <strong>{{ $pci->performanceIndicator->description }} &mdash; {{ $pci->score_percentage }}%</strong>
              </div>
              <div class="card-body">{{ $pci->description }}</div>
            </div>
          </div>
        @endforeach
{{--         <div class="col-md-3">
          <div class="card mb-3">
            <div class="card-header text-white" style="background:#51c2d3;"><strong>Unsatisfactory &mdash; 25%</strong></div>
            <div class="card-body">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Pariatur quisquam quibusdam excepturi exercitationem voluptatem. Maiores, optio iusto dolore autem soluta nulla placeat provident similique officiis, labore, fugit sapiente architecto fugiat!</div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card mb-3">
            <div class="card-header text-white" style="background:#6b8ae4;"><strong>Unsatisfactory &mdash; 25%</strong></div>
            <div class="card-body">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Pariatur quisquam quibusdam excepturi exercitationem voluptatem. Maiores, optio iusto dolore autem soluta nulla placeat provident similique officiis, labore, fugit sapiente architecto fugiat!</div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card mb-3">
            <div class="card-header text-white" style="background:#25e19d;"><strong>Unsatisfactory &mdash; 25%</strong></div>
            <div class="card-body">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Pariatur quisquam quibusdam excepturi exercitationem voluptatem. Maiores, optio iusto dolore autem soluta nulla placeat provident similique officiis, labore, fugit sapiente architecto fugiat!</div>
          </div>
        </div> --}}
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
        student_outcome: @json($student_outcome),
        assessment_types: @json($assessment_types),
        assessment_type_id: '',
        college: @json($college)
      },
      methods: {
        changeAssessmentType() {
          ApiClient.post('/student_outcomes/' + this.student_outcome.id + '/change_assessment_type', {
            assessment_type_id: this.assessment_type_id
          })
          .then(response => {
            toast.fire({
              type:'success',
              title: 'Assessment Type Successfully Updated.'
            });
          })
        },
        checkIfAvailable(assessment_type_id) {
          if(assessment_type_id == 3 && this.college.college_code != 'CCS') {
            return false;
          }

          return true;
        }
      },
      created() {
        this.assessment_type_id = this.student_outcome.assessment_type_id;
      }
    });
  </script>
  @if(Session::has('message'))
    <script>
      toast.fire({
        type: 'success',
        title: '{{ Session::get('message') }}'
      })
    </script>
  @endif
@endpush
