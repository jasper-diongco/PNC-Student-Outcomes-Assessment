@extends('layout.app', ['active' => 'test_questions'])

@section('title', 'Exam Details')

@section('content')
<div id="app" v-cloak>
    <add-exam-modal v-on:new-exam-added="getExams" :curriculum-id="curriculum_id" :student-outcome-id="student_outcome_id" :courses="requirements_template" :is_revised="true" :exam_id="{{ $item_analysis->exam_id }}" :item_analysis_id="{{ $item_analysis->id }}" :program_id="program_id"></add-exam-modal>
    <div class="card">
        <div class="card-body py-4">            
            <h1 class="page-header mb-0"><i class="fa fa-poll-h"></i> Item Analysis Summary</h1>
        </div>   
    </div>

    <div class="card mt-4">
        <div class="card-body pt-4">   
            {{-- <div class="d-flex">
                <div style="font-size: 22px" class="mr-5">
                    <i class="fa fa-check-circle text-success"></i> Items Retained: <span class="text-warning">{{ $item_analysis->getRetainedItem()->count() }}</span>
                </div> 
                <div style="font-size: 22px" class="mr-5">
                    <i class="fa fa-edit text-info"></i> Items Revised: <span class="text-warning">{{ $item_analysis->getRevisedItem()->count() }}</span>
                </div>           
                <div style="font-size: 22px" class="mr-5">
                    <i class="fa fa-times text-danger"></i> Items Rejected: <span class="text-warning">{{ $item_analysis->getRejectedItem()->count() }}</span>
                </div> 
            </div> --}}
            <ul class="list-group">
                <li class="list-group-item">
                    <i class="fa fa-check-circle text-success"></i> Items Retained: <span class="text-warning">{{ $item_analysis->getRetainedItem()->count() }}</span>
                </li>
                <li class="list-group-item">
                    <i class="fa fa-edit text-info"></i> Items Revised: <span class="text-warning">{{ $item_analysis->getRevisedItem()->count() }}</span>
                </li>
                <li class="list-group-item">
                    <i class="fa fa-times text-danger"></i> Items Rejected: <span class="text-warning">{{ $item_analysis->getRejectedItem()->count() }}</span>
                </li>
                <li class="list-group-item">
                    <span style="font-weight: bold;">Remaining Items:</span> <span class="text-warning">{{ $item_analysis->getRetainedItem()->count() + $item_analysis->getRevisedItem()->count()  }}</span>
                </li>
            </ul>


            

            
            
        </div>   
    </div>

    <div class="card mt-4">
        <div class="card-body py-4">
            <div class="row">
                <div class="col-md-6">
                    <h5>Items Requirements</h5>
                    <ul class="list-group">
                        <li v-for="(orig_requirement, index) in original_requirements_template" class="list-group-item" :key="orig_requirement.course.id">
                            <strong>@{{ orig_requirement.course.course_code }}</strong> &mdash; @{{ orig_requirement.course.description }} 
                            <div>Total Items: <span class="text-success">@{{ orig_requirement.test_question_count }}</span></div>
                            <ul >
                                <li >Easy: 
                                    <span class="text-success">@{{ orig_requirement.easy }}</span>
                                </li>
                                <li >Average: 
                                    <span class="text-success">@{{ orig_requirement.average }}</span>
                                </li>
                                <li >Difficult: 
                                    <span class="text-success">@{{ orig_requirement.difficult }}</span>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h5>Items Remaining &mdash; <strong>@{{ countRemaningItems() }}</strong></h5>
                    <ul class="list-group">
                        <li v-for="(items_remaining, index) in items_remaining_template" class="list-group-item" :key="items_remaining.course.id">
                            <strong>@{{ items_remaining.course.course_code }}</strong> &mdash; @{{ items_remaining.course.description }} 
                            <div>Total Items: <span class="text-success">@{{ items_remaining.easy +  items_remaining.average + items_remaining.difficult }}</span></div>
                            <ul >
                                <li>Easy: 
                                    <span class="text-success">
                                    @{{ items_remaining.easy }}
                                        <strong v-if="checkIfExceed(original_requirements_template[index].easy, items_remaining.easy)" class="text-danger">(@{{ countExceed(original_requirements_template[index].easy, items_remaining.easy) }} item(s) to be removed)</strong>
                                    </span>
                                </li>
                                <li >Average: 
                                    <span class="text-success">@{{ items_remaining.average }}
                                        <strong v-if="checkIfExceed(original_requirements_template[index].average, items_remaining.average)" class="text-danger">(@{{ countExceed(original_requirements_template[index].average, items_remaining.average) }} item(s) to be removed)</strong>
                                    </span>
                                </li>
                                <li >Difficult: 
                                    <span class="text-success">@{{ items_remaining.difficult }}
                                        <strong v-if="checkIfExceed(original_requirements_template[index].difficult, items_remaining.difficult)" class="text-danger">(@{{ countExceed(original_requirements_template[index].difficult, items_remaining.difficult) }} item(s) to be removed)</strong>
                                    </span>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                
            </div>

            @if (!$item_analysis->is_saved)
                <div class="d-flex justify-content-end mt-3">
                    <button data-toggle="modal" data-target="#addExamModal" class="btn btn-info btn-sm">
                        Get random items from test bank & Save
                    </button>
                </div>
            @else
                <div class="d-flex justify-content-end mt-3">
                    <button disabled data-toggle="modal" data-target="#addExamModal" class="btn btn-info btn-sm">
                        This is already saved!
                    </button>
                </div>
            @endif
        </div>


    </div>
</div>
@endsection

@push('scripts')
    <script>
        var vm = new Vue({
            el: '#app',
            data: {
                student_outcome_id: '{{ request('student_outcome_id') }}',
                curriculum_id: '{{ request('curriculum_id') }}',
                program_id: '{{ request('program_id') }}',
                requirements_template: @json($requirements_template),
                original_requirements_template: @json($original_requirements_template),
                items_remaining_template: @json($items_remaining_template)
            },
            methods: {
                getExams() {

                },
                countRemaningItems() {
                    var total = 0;
                    for(var i = 0; i < this.items_remaining_template.length; i++) {
                        total += this.items_remaining_template[i].easy;
                        total += this.items_remaining_template[i].average;
                        total += this.items_remaining_template[i].difficult;
                    }

                    return total;
                },
                checkIfExceed(requirement, remaining) {
                    return remaining > requirement;
                },
                countExceed(requirement, remaining) {
                    return remaining - requirement;
                }
            }
        });
    </script>
@endpush