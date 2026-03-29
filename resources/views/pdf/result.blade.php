<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; }
        .header { text-align: center; border-bottom: 2px solid #000; margin-bottom: 20px; }
        .student-info { margin-bottom: 20px; width: 100%; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2 f2 f2; }
        .total-box { margin-top: 20px; text-align: right; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>UNIQUE GROUP OF SCHOOLS</h1>
        <p>Student Academic Performance Report - {{ $term }}</p>
    </div>

    <table class="student-info">
        <tr>
            <td><strong>Name:</strong> {{ $student->full_name }}</td>
            <td><strong>Admission No:</strong> {{ $student->admission_number }}</td>
        </tr>
        <tr>
            <td><strong>Class:</strong> {{ $student->schoolClass->name }} ({{ $student->schoolClass->arm }})</td>
            <td><strong>Date:</strong> {{ $date }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>Subject</th>
                <th>Score</th>
                <th>Grade</th>
            </tr>
        </thead>
        <tbody>
            @foreach($student->grades as $grade)
            <tr>
                <td>{{ $grade->subject->name }}</td>
                <td>{{ $grade->score }}</td>
                <td>
                    @php
                        $g = $grade->score >= 75 ? 'A (Excellent)' : 
                           ($grade->score >= 65 ? 'B (Very Good)' : 
                           ($grade->score >= 55 ? 'C (Credit)' : 
                           ($grade->score >= 45 ? 'D (Pass)' : 'F (Fail)')));
                    @endphp
                    {{ $g }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-box">
        Term Average: {{ number_format($average, 1) }}%
    </div>
    <div style="margin-top: 30px;">
    <p><strong>Class Teacher's Remark:</strong> {{ $teacher_remark ?? 'A diligent and focused student.' }}</p>
    <p><strong>Head of School's Remark:</strong> {{ $head_remark ?? 'Impressive performance, keep it up.' }}</p>
</div>

<div style="margin-top: 50px; width: 100%;">
    <div style="float: left; width: 200px; border-top: 1px solid #000; text-align: center;">
        Class Teacher's Signature
    </div>
    <div style="float: right; width: 200px; border-top: 1px solid #000; text-align: center;">
        Principal's Stamp & Date
    </div>
</div>
</body>
</html>