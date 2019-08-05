<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>OBE Curriculum</title>
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
        .obe {
            font-size: 25px;
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
    </style>

</head>
<body onload="window.print();">
    <div class="wrapper">
        <!-- Main content -->
        <section class="curriculum">
            <div class="d-flex justify-content-between align-items-center flex-column">
                <h4 class="pnc pt-1">Pamantasan ng Cabuyao</h4>
                <h5 class="obe">OBE Curriculum</h5>
            </div>
            <div class="d-flex justify-content-between">
                <div>
                    Name: <span class="important-info">{{ $student->user->getFullName() }}</span>
                </div>
                <div>
                    Student ID: <span class="important-info">{{ $student->student_id }}</span>
                </div>
            </div>
            <div class="mb-3">
                Program: <span class="important-info">{{ $student->program->description }}</span>
            </div>

            <table class="table table-bordered">
                <thead>
                    <th width="15%">Course Code</th>
                    <th>Description</th>
                    <th align="center">Units</th>
                    <th>Grade</th>
                    <th>Remarks</th>
                </thead>
                <tbody>
                    @foreach($templates as $template)
                        @if(count($template['curriculum_courses']) > 0)
                            <tr>
                               <td class="year-sem" colspan="5" align="center">
                                   <span>{{ $template['year_sem'] }}</span>
                               </td> 
                            </tr>
                            @foreach($template['curriculum_courses'] as $curriculum_course)
                                <tr>
                                    <td>{{ $curriculum_course->course->course_code }}</td>
                                    <td>{{ $curriculum_course->course->description }}</td>
                                    <td align="center">{{ $curriculum_course->course->lec_unit + $curriculum_course->course->lab_unit }}</td>
                                    <td>{{ $curriculum_course->student_grade['grade_text'] }}</td>
                                    <td>{{ $curriculum_course->student_grade['remarks'] }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="2" align="right" class="important">Total Units</td>
                                <td align="center" class="important">{{ $template['total_lec_units'] + $template['total_lab_units'] }}</td>
                                <td colspan="2"></td>
                            </tr>

                        @endif
                    @endforeach
                </tbody>
            </table>
        </section>
        <!-- /.content -->
    </div>
    <!-- ./wrapper -->
</body>
</html>
