@extends('layout.app', ['active' => 'application_settings'])

@section('title', 'Add new Student')


@section('content')
    
    <div id="app">
        <h1 class="page-header"><i class="fa fa-cogs text-info"></i> Application Settings</h1> 

        <div class="card shadow">
            <div class="card-body">
                <h4>Assessments Setting</h4>

                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between">
                        <div class="font-weight-bold">Show assessment details to student</div>
                        <div>
                            <div class="custom-control custom-checkbox">
                                <input v-model="show_assessment_details_to_student.value" type="checkbox" id="show_assessment_details_to_student" class="custom-control-input" style="cursor: pointer;">
                                <label class="custom-control-label" for="show_assessment_details_to_student"></label>
                              </div>
                        </div>
                    </li>
                </ul>

                <div class="d-flex mt-3 justify-content-end">
                    <button :disabled="is_loading" class="btn btn-info" v-on:click="saveSettings">Save <i class="fa fa-cog"></i></button>
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
                settings: @json($settings),
                show_assessment_details_to_student: '',
                is_loading: false
            },
            methods: {
                init_show_assessment_details_to_student() {
                    for(var i = 0; i < this.settings.length; i++) {
                        if(this.settings[i].name == 'show_assessment_details_to_student') {
                            this.show_assessment_details_to_student = {
                                id: this.settings[i].id,
                                value: this.settings[i].value == 'true'
                            }
                        }
                    }
                },
                saveSettings() {
                    this.is_loading = true;
                    ApiClient.post('/application_settings/update_settings', {
                        show_assessment_details_to_student: this.show_assessment_details_to_student
                    })
                    .then(response => {
                        swal.fire({
                            type: 'success',
                            title: 'Successful',
                            text: 'Settings has been updated successfully.'
                        });
                        this.is_loading = false;
                    })
                }
            },
            created() {
                this.init_show_assessment_details_to_student();
            }
        });
    </script>
@endpush