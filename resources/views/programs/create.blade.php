@extends('layouts.master');

@section('title', 'Add Faculty')


@section('content')

  <div class="row">
    <div class="col-md-8 mx-auto">
      <div class="card">
        <div class="card-header">
          <h3>Add new Program</h3>
        </div>
        <div class="card-body">  
          <form action="" autocomplete="off">

            {{-- Field for program code --}}
            <div class="form-group row">
                <label for="program_code" class="col-md-3 col-form-label text-md-right">Program Code</label>

                <div class="col-md-6">
                    <input 
                      id="program_code" 
                      type="text" 
                      class="form-control"  
                      autofocus>
                </div>
            </div>
            {{-- /end Field for program code --}}

            {{-- Field for program description --}}
            <div class="form-group row">
                <label for="description" class="col-md-3 col-form-label text-md-right">Description</label>

                <div class="col-md-6">
                    <input 
                      id="description" 
                      type="text" 
                      class="form-control"  
                      autofocus>
                </div>
            </div>
            {{-- /end Field for program description --}}
          </form>
        </div>
      </div>
    </div>
  </div>

@endsection