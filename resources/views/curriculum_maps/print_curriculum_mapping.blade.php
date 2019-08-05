<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $curriculum->name }} Curriculum Mapping</title>
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

            margin: 50px auto;
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
        ul {
            list-style: none;
        }
    </style>

</head>
<body onload="window.print();">
    <div class="wrapper">
        <!-- Main content -->
        <section class="curriculum">
            <div class="text-center">
                <h4 class="pnc py-1 bg-light mt-4">Pamantasan ng Cabuyao</h4>
                <h6 class="mt-2" style="font-weight: 400">{{ $curriculum->program->college->name }}</h6>
                <p class="text-underline">{{ $curriculum->program->description }}</p>

                <h5 class="mt-1 important">CURRICULUM MAPPING</h5>
            </div>

            
            <p class="mb-3" style="font-size: 14px">{{ $curriculum->name }} &mdash; {{ $curriculum->year }} &mdash; Revision No. {{ $curriculum->revision_no }}.0</p>
            

            @foreach($curriculum->program->studentOutcomes as $student_outcome)
                <p class="mb-2">
                    <span class="mr-2 important-info" >{{ $student_outcome->so_code }}</span>
                    <span>{{ $student_outcome->description }}</span>
                </p>
            @endforeach
            

            <h6 class="mt-3 text-underline">Learning Levels</h6>
            <ul>
                @foreach($curriculum->program->learningLevels as $learning_level)
                    <li>
                        <strong>{{ $learning_level->letter }}</strong> &mdash; {{ $learning_level->name }}
                    </li>
                @endforeach
            </ul>

            <table class="table table-bordered mt-4">
                <thead>
                    <tr>
                        <th width="15%" rowspan="2">Course<br>Code</th>
                        <th rowspan="2">Course <br> Title</th>
                        <th colspan="{{ $so_count }}" class="text-center">{{ $curriculum->program->program_code }} Student Outcomes</th>
                    </tr>
                    <tr>
                        @foreach($curriculum->program->studentOutcomes as $student_outcome)
                        <th>
                            {{ $student_outcome->so_code }}
                        </th>
                        @endforeach
                    </tr>
                    
                </thead>
                <tbody>
                    @foreach($curriculum_mapping_templates as $template)
                        @if(count($template['curriculum_courses']) > 0)
                            <tr>
                               <td class="year-sem" colspan="{{ $so_count + 2 }}">
                                   <span >{{ $template['year_sem'] }}</span>
                               </td> 
                            </tr>
                            @foreach($template['curriculum_courses'] as $curriculum_course)
                                <tr>
                                    <td>{{ $curriculum_course->course->course_code }}</td>
                                    <td>{{ $curriculum_course->course->description }}</td>
                                    @foreach($curriculum_course->curriculum_maps as $curriculum_map)
                                        @if($curriculum_map)
                                            <td>{{ $curriculum_map->learningLevel->letter }}</td>
                                        @else
                                            <td></td>
                                        @endif
                                    @endforeach
                                </tr>
                            @endforeach
                            {{-- <tr>
                                <td colspan="2" align="right" class="important">Total Units</td>
                                <td align="center" class="important">{{ $template['total_lec_units'] }}</td>
                                <td align="center" class="important">{{ $template['total_lab_units'] }}</td>
                            </tr> --}}

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
