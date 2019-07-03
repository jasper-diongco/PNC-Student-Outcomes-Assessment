@extends('layout.app', ['active' => 'curriculum_mapping'])

@section('title', 'Curriculum Mapping Index')


@section('content')
    <div id="app">
        <div>
            <div>
                <h1 class="page-header">Curriculum Mapping &mdash; Select Curriculum</h1>

                @if(count($curricula) > 0)
                  {{-- <div class="list-group">
                    
                    @foreach($curricula as $curriculum)
                      <a href="{{ url('/curriculum_mapping/' . $curriculum->id) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                      <div>
                        {{ $curriculum->program->program_code . ' - ' . $curriculum->name }} {{ $curriculum->year }}
                        <span class="text-primary">v{{ $curriculum->revision_no }}.0</span>
                        <br>
                        <small class="text-muted">{{ $curriculum->program->college->name }}</small>
                      </div>
                        <i class="fa fa-chevron-right"></i>
                      </a>
                    @endforeach  
                  </div> --}}
                  <div class="card">
                    <div class="card-body">
                      <h5 class="text-info">List of Curriculum</h5>
                      <table class="table table-borderless">
                        <thead>
                          <tr>
                            <th>ID</th>
                            <th>Program</th>
                            <th>Name</th>
                            <th>Year</th>
                            <th>Revision No.</th>
                            <th>College</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($curricula as $curriculum)
                            <tr>
                              <td>{{ $curriculum->id }}</td>
                              <td>{{ $curriculum->program->program_code }}</td>
                              <td>{{ $curriculum->name }}</td>
                              <td>{{ $curriculum->year }}</td>
                              <td>{{ $curriculum->revision_no }}</td>
                              <td>{{ $curriculum->program->college->name }}</td>
                              <td><a href="{{ url('/curriculum_mapping/' . $curriculum->id) }}" class="btn btn-sm btn-success"><i class="fa fa-search"></i></a></td>
                            </tr>
                          @endforeach
                        </tbody>
                      </table>
                    </div>
                  </div>
                  
                @else
                  <div class="text-center bg-white p-3">No Curriculum Found in Database.</div>
                @endif

                <div class="my-3 d-flex justify-content-end">
                  {{ $curricula->appends(request()->input())->links() }}
                </div>

                <div class="mb-4"></div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
  <script>
    
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