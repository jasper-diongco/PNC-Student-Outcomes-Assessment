@extends('layout.app', ['active' => 'curriculum_mapping'])

@section('title', 'Curriculum Mapping Index')


@section('content')
    <div id="app">
        <div>
            <div>
                <h1 class="page-header">Curriculum Mapping Index</h1>

                @if(count($curricula) > 0)
                  <div class="list-group">
                    
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