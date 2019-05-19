@extends('layouts.sb_admin')

@section('title', 'Student Outcomes - ' . $student_outcome->so_code)

@section('content')

<a href="{{ url('/student_outcomes?program_id='. request('program_id')) }}" class="btn btn-success btn-sm mb-3"><i class="fa fa-arrow-left"></i> Back</a>

<div id="app">

  <div class="d-flex justify-content-between mb-3">
    <div>
      <h1 class="h3 mb-4 text-gray-800">Student Outcome &mdash; {{ $student_outcome->so_code }}</h1>

    </div>
    <div>
      @if(Gate::check('isDean') || Gate::check('isSAdmin'))
        <student-outcome-modal 
          :is-update="true" 
          :programs='@json($programs)' 
          :student-outcome='@json($student_outcome)'
          :performance-criteria='@json($student_outcome->performanceCriterias[0])'
          :performance-indicators='@json($student_outcome->performanceCriterias[0]->performanceCriteriaIndicators)'></student-outcome-modal>
      @endif
    </div>
  </div>

  <div class="card shadow">
    <div class="card-header">
      <div class="d-flex align-items-center">
        <div class="mr-3">
          <div class="avatar-student-outcome">
          {{ $student_outcome->so_code }}
          </div>
        </div>
        <div><strong>{{ $student_outcome->description }}</strong></div>
      </div>
    </div>
    <div class="card-body">
      <h3>Performance Criteria</h3>
      <p>{{ $student_outcome->performanceCriterias[0]->description }}</p>
      <hr>
      <h3 class="mb-3">Performance Indicators</h3>
      <div class="row">
        @foreach ($student_outcome->performanceCriterias[0]->performanceCriteriaIndicators as $pci)
          <div class="col-md-3">
            <div class="card mb-3" style="height: 100%">
              @if ($pci->performance_indicator_id == 1)
                <div class="card-header text-white" style="background:#f8cc5e;">
              @elseif ($pci->performance_indicator_id == 2)
                <div class="card-header text-white" style="background:#51c2d3;">
              @elseif ($pci->performance_indicator_id == 3)
                <div class="card-header text-white" style="background:#6b8ae4;">
              @elseif ($pci->performance_indicator_id == 4)
                <div class="card-header text-white" style="background:#25e19d;">
              @endif

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
    new Vue({
      el: '#app'
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
