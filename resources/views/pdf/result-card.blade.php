<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11px; color: #333; margin: 0; padding: 0; }
        .container { padding: 20px; }
        
        /* Header Section */
        .header-table { width: 100%; border-bottom: 2px solid #3498db; padding-bottom: 10px; }
        .school-logo { width: 80px; height: 80px; }
        .school-details { text-align: center; }
        .school-name { font-size: 20px; font-weight: bold; color: #2c3e50; margin-bottom: 5px; }
        .report-title { font-size: 14px; color: #34495e; font-weight: bold; text-transform: uppercase; }
        
        /* Student Passport */
        .passport-photo { width: 90px; height: 90px; border: 1px solid #ccc; float: right; }
        
        /* Info Tables */
        .student-info { width: 100%; margin-top: 15px; border-collapse: collapse; clear: both; }
        .student-info td { padding: 4px; border: 1px solid #eee; }
        .label { font-weight: bold; background-color: #f8f9fa; width: 20%; }

        /* Grades Table */
        .results-table { width: 100%; margin-top: 15px; border-collapse: collapse; text-align: center; }
        .results-table th { background-color: #3498db; color: white; padding: 6px; font-size: 12px; }
        .results-table td { border: 1px solid #ccc; padding: 5px; }

        /* Skills & Attendance */
        .stats-container { width: 100%; margin-top: 15px; }
        .box { width: 48%; border: 1px solid #ccc; padding: 8px; min-height: 80px; }
        .left-box { float: left; }
        .right-box { float: right; }
        .clearfix { clear: both; }

        /* Performance Chart */
        .chart-container { text-align: center; margin-top: 20px; border-top: 1px solid #eee; padding-top: 15px; }
        .bar-wrapper { display: inline-block; text-align: center; margin: 0 4px; }
        .bar { width: 20px; background-color: #3498db; vertical-align: bottom; }
        .subject-label { font-size: 8px; writing-mode: vertical-rl; transform: rotate(90deg); margin-top: 5px; display: block; }
        
        /* Footer */
        .footer { margin-top: 30px; font-size: 10px; }
        .signature-row { width: 100%; margin-top: 40px; }
        .sig-box { width: 40%; border-top: 1px solid #000; text-align: center; display: inline-block; padding-top: 5px; }
    </style>
</head>
<body>

<div class="container">
    <table class="header-table">
        <tr>
            <td width="15%">
                @if($settings->school_logo)
                    <img src="{{ public_path('storage/' . $settings->school_logo) }}" class="school-logo">
                @endif
            </td>
            <td class="school-details">
                <div class="school-name">UNIQUE GROUP OF SCHOOLS</div>
                <div style="font-size: 10px;">BLOCK 12, PLOT 350 NORUS CLOSE, OMOLE ESTATE PHASE 1</div>
                <div class="report-title">{{ $settings->current_term }}, {{ $settings->academic_year }} REPORT CARD</div>
            </td>
            <td width="15%">
                @php $student = \App\Models\Student::where('admission_number', $admission_number)->first(); @endphp
                @if($student && $student->student_image)
                    <img src="{{ public_path('storage/' . $student->student_image) }}" class="passport-photo">
                @else
                    <div class="passport-photo" style="text-align:center; line-height:90px; font-size:10px; color:#ccc;">NO PHOTO</div>
                @endif
            </td>
        </tr>
    </table>

    <table class="student-info">
        <tr>
            <td class="label">NAME:</td><td>{{ strtoupper($student_name) }}</td>
            <td class="label">REG NO:</td><td>{{ $admission_number }}</td>
        </tr>
        <tr>
            <td class="label">CLASS:</td><td>{{ $grades->first()->class_level }}</td>
            <td class="label">TERM:</td><td>{{ $settings->current_term }}</td>
        </tr>
    </table>

    <table class="results-table">
        <thead>
            <tr>
                <th style="text-align: left;">SUBJECT</th>
                <th>TEST (40)</th>
                <th>EXAM (60)</th>
                <th>TOTAL (100)</th>
                <th>GRADE</th>
            </tr>
        </thead>
        <tbody>
            @foreach($grades as $grade)
            <tr>
                <td style="text-align: left;">{{ $grade->subject }}</td>
                <td>{{ $grade->ca_score }}</td>
                <td>{{ $grade->exam_score }}</td>
                <td>{{ $grade->total_score }}</td>
                <td><strong>{{ $grade->grade_letter }}</strong></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="stats-container">
        <div class="box left-box">
            <strong>AFFECTIVE SKILLS</strong><hr>
            Punctuality: {{ $grades->first()->punctuality ?? '-' }} / 5<br>
            Neatness: {{ $grades->first()->neatness ?? '-' }} / 5<br>
            Honesty: {{ $grades->first()->honesty ?? '-' }} / 5
        </div>
        <div class="box right-box">
            <strong>ATTENDANCE</strong><hr>
            Total Days: {{ $settings->total_school_days }}<br>
            Present: {{ $grades->first()->days_present ?? '-' }}<br>
            Attendance: {{ number_format(($grades->first()->days_present / max($settings->total_school_days, 1)) * 100, 1) }}%
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="chart-container">
        <strong style="font-size: 10px;">SUBJECTS PERFORMANCE CHART (%)</strong><br><br>
        @foreach($grades as $grade)
            <div class="bar-wrapper">
                <div class="bar" style="height: {{ $grade->total_score * 0.8 }}px;"></div>
                <span class="subject-label">{{ substr($grade->subject, 0, 6) }}</span>
            </div>
        @endforeach
    </div>

    <div class="footer">
        <p><strong>Next Term Resumption:</strong> {{ \Carbon\Carbon::parse($settings->next_term_begins)->format('l, jS F Y') }}</p>
        
        <div class="footer" style="margin-top: 20px;">
    <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 20px;">
        <p><strong>Class Teacher's Remark:</strong> {{ $grades->first()->teacher_remark ?? 'No comment provided.' }}</p>
        <p><strong>Principal's Remark:</strong> {{ $grades->first()->principal_remark ?? 'Your performance is encouraging.' }}</p>
    </div>

    <p><strong>Next Term Resumption:</strong> {{ \Carbon\Carbon::parse($settings->next_term_begins)->format('l, jS F Y') }}</p>
    
    <div class="signature-row">
        <div class="sig-box" style="float: left;">
            Class Teacher's Signature
        </div>
        <div class="sig-box" style="float: right;">
            Principal's Signature ({{ $settings->head_of_school }})
        </div>
    </div>
</div>
    </div>
</div>

</body>
</html>