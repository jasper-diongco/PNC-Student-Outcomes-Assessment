<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Assessment Result</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- CSS-->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    
    <style>
        body {
            background: white;
            padding: 0;
            padding: 10px;
            color: black;
            max-width: 1020px;
            margin: 0 auto;
            background: #ededed;
        }
        .wrapper {
            border: 1px solid #e4e4e4;
            padding: 10px 25px;
            background: white;
        }
        p {
            margin: 0;
        }
        .curriculum-name {
            font-weight: 600;
        }
        @media print {
            body {
              margin: 0;
            }
            .wrapper {
              padding: 0;
              border: 0;
            }
        }
        @page {
            /*margin: 20px;*/
            margin-bottom: 50px;
            margin-top: 50px;
            padding-top: 60px;
        }
        .pnc {
            font-weight: 600;
        }
        .year-sem {
            font-weight: 600 !important;
            text-decoration: underline;
            padding-bottom: 10px !important;
            font-size: 19px;
        }
        td {
            padding: 5px !important;
        }
        .important {
            font-weight: 600 !important;
            font-size: 19px;
        }
        .important-info {
            font-weight: 600 !important;
        }
        .text-underline {
            text-decoration: underline;
        }
        .list-group .list-group-item {
            padding: 4px;
            padding-left: 10px;
        }
    </style>

</head>
<body onload="window.print();">
    <div class="wrapper">
        <!-- Main content -->
        <section class="curriculum">
            <div class="d-flex justify-content-between align-items-baseline">
                <h4 class="pnc pt-4">Pamantasan ng Cabuyao</h4>
                <h5>Assessment #{{ $assessment->assessment_code }}</h5>
            </div>
            
            <p>Katapatan Subd., Banaybanay, City of Cabuyao, Laguna</p>
            <div class="d-flex justify-content-between mt-2">
                <div>
                    Name: <span class="important-info ">{{ $assessment->student->user->getFullName() }}</span>
                </div>
                <div>
                    Student ID: <span class="important-info ">{{ $assessment->student->student_id }}</span>
                </div>
            </div>



            <div class="d-flex justify-content-between align-items-baseline mb-3">
                <p class="important">BSIT</p>
                <p>Student Outcome {{ $assessment->studentOutcome->so_code }} &mdash; {{ $assessment->exam->exam_code }}  &mdash; {{ $assessment->exam->description }} </p>
            </div>

            <ul class="list-group mb-3">
              <li class="list-group-item"><strong>Total Score:</strong> {{ $assessment->computeScore() }}%</li>
              <li class="list-group-item"><strong>Remarks:</strong> {{ $assessment->checkIfPassed() ? 'Passed' : 'Failed' }}</li>
              <li class="list-group-item ">
                <strong>Date:</strong> {{ $assessment->created_at->format('M d Y h:m a') }} &mdash; {{ $assessment->created_at->diffForHumans() }}
              </li>
              <li class="list-group-item"> <strong>Total items: </strong> {{ $assessment->assessmentDetails->count() }} test questions </li>

              <li class="list-group-item"> <strong>Correct answers: </strong> {{ $assessment->getCorrectAnswers()->count() }} </li>
              <li class="list-group-item"><strong>Incorrect answers: </strong> {{ $assessment->getIncorrectAnswers()->count() }}</li>
              <li class="list-group-item"><strong>Passing grade:</strong> {{ $answer_sheet->passing_grade }}% </li>
              <li class="list-group-item"><strong>Exam Duration: </strong> {{ $assessment->getDuration() }}</li>
              <li class="list-group-item"><strong>Answered test questions: </strong> {{ $assessment->getAnsweredTestQuestions()->count() }} </li>
              <li class="list-group-item"><strong>Unanswered test questions: </strong> {{ $assessment->getUnansweredTestQuestions()->count() }}</li>
            </ul>

            <table class="table table-bordered">
                <thead>
                    <th width="5%">Number</th>
                    <th>Course</th>
                    <th width="15%">Test Question ID</th>
                    <th width="45%">Title</th>
                    <th>Answer</th>
                    <th>Correct</th>
                </thead>
                <tbody>
                    @foreach($answer_sheet->getAnswerSheetTestQuestionsOrig() as $answer_sheet_test_question)
                        <tr>
                            <td>
                                {{ $answer_sheet_test_question->pos_order }}
                            </td>
                            <td>{{ $answer_sheet_test_question->testQuestion->course->course_code }}</td>
                            <td>
                                {{ $answer_sheet_test_question->testQuestion->tq_code }}
                            </td>
                            <td>
                                {{ $answer_sheet_test_question->testQuestion->title }}
                            </td>
                            {{-- <td>
                                {{ chr($answer_sheet_test_question->testQuestion->getCorrectAnswer()->pos_order + 64) }}
                            </td> --}}
                            <td>
                                {{ $answer_sheet_test_question->getAnswer() ? chr($answer_sheet_test_question->getAnswer()->pos_order + 64) : 'No Answer'  }}
                            </td>
                            <td>
                                {{ $answer_sheet_test_question->checkIfCorrect() ? 'YES': 'NO' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table> 
        </section>
        <!-- /.content -->
    </div>
    <!-- ./wrapper -->
</body>
</html>
