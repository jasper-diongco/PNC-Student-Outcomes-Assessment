<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Answer Key</title>
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
        .text-underline {
            text-decoration: underline;
        }
    </style>

</head>
<body onload="window.print();">
    <div class="wrapper">
        <!-- Main content -->
        <section class="curriculum">
            <div class="d-flex justify-content-between align-items-baseline">
                <h4 class="pnc pt-4">Pamantasan ng Cabuyao</h4>
                <h5>Answer Key</h5>
            </div>
            
            <p>Katapatan Subd., Banaybanay, City of Cabuyao, Laguna</p>
            <div class="d-flex justify-content-between align-items-baseline">
                <p class="important">BSIT</p>
                <p>Student Outcome {{ $exam->studentOutcome->so_code }} &mdash; {{ $exam->description }}</p>
            </div>
            <h5 class="mt-2">Answer key for exam #{{ $exam->exam_code }}</h5>

            <table class="table table-bordered">
                <thead>
                    <th width="5%">Number</th>
                    <th>Test Question ID</th>
                    <th width="35%">Title</th>
                    <th width="5%">Choices</th>
                    <th>Correct Answer</th>
                    <th>Correct Answer ID</th>
                </thead>
                <tbody>
                    @foreach($exam->examTestQuestions as $exam_test_question)
                        <?php $correct_answer = $exam_test_question->testQuestion->getCorrectAnswer();  ?>
                        <tr>
                            <td>{{ $exam_test_question->pos_order }}</td>
                            <td>{{ $exam_test_question->testQuestion->tq_code }}</td>
                            <td>{{ $exam_test_question->testQuestion->title }}</td>
                            <td>{{ $exam_test_question->testQuestion->choices->count() }}</td>
                            <td>{{ chr($correct_answer->pos_order + 64) }}</td>
                            <td>{{ $correct_answer->ch_code }}</td>
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
