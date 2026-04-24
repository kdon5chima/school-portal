<style>
    .summary-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }
    .summary-table td {
        border: 1px solid #000;
        padding: 10px;
        font-size: 13px;
        width: 50%;
    }
    .summary-label {
        font-weight: bold;
        text-transform: uppercase;
        color: #1e3a8a; /* Deep blue for professional look */
    }
</style>

<table class="summary-table">
    <tr>
        <td>
            <span class="summary-label">Performance Grade:</span> 
            @php
                if($average >= 75) echo 'A (Excellent)';
                elseif($average >= 70) echo 'B (Very Good)';
                elseif($average >= 60) echo 'C (Good)';
                elseif($average >= 50) echo 'D (Pass)';
                else echo 'F (Fail)';
            @endphp
        </td>
        <td><span class="summary-label">Student Total Score:</span> {{ $studentTotal }}/{{ $obtainable }} </td>
    </tr>
    <tr>
        <td><span class="summary-label">Class Size:</span> {{ $classSize }} </td>
        <td><span class="summary-label">Student Average (%):</span> {{ number_format($average, 2) }}% </td>
    </tr>
    <tr>
        <td><span class="summary-label">No. of Subjects:</span> {{ $totalSubjects }} </td>
       
        <td><span class="summary-label">Grade Point Average:</span> {{ number_format($gpa ?? 0, 2) }} </td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: center; background-color: #f9fafb;">
            <span class="summary-label">Result Summary:</span> 
            <span class="font-bold" style="color: {{ $average >= 50 ? '#16a34a' : '#dc2626' }}">
                {{ $average >= 60 ? 'VERY GOOD' : ($average >= 50 ? 'PASS' : 'HELD BACK') }}
            </span>
        </td>
    </tr>
    @foreach($grades as $grade)
<tr>
    <td class="font-bold">
        <span style="color: #666; font-size: 10px;">{{ $grade->subject_code ?? 'N/A' }}</span> <br>
        {{ $grade->subject }}
    </td>
    <td class="text-center">{{ $grade->ca_score }}</td>
    <td class="text-center">{{ $grade->exam_score }}</td>
    <td class="text-center font-bold bg-gray-50">{{ $grade->total_score }}</td>
</tr>
@endforeach
</table>
<table class="report-table">
    <thead>
        <tr>
            <th>Subject</th>
            <th>Mid-Term (40)</th>
            <th>Examination (60)</th>
            <th>Total (100)</th>
        </tr>
    </thead>
    <tbody>
        @forelse($grades as $grade)
        <tr>
            <td>
                <strong>{{ $grade->subject_code }}</strong><br>
                <span style="text-transform: uppercase;">{{ $grade->subject_name }}</span>
            </td>
            <td class="text-center">{{ $grade->ca_score ?? 0 }}</td>
            <td class="text-center">{{ $grade->exam_score ?? 0 }}</td>
            <td class="text-center font-bold">{{ $grade->total_score ?? 0 }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="4" class="text-center italic">No grades found for this student.</td>
        </tr>
        @endforelse
    </tbody>
</table>
<div class="chart-container" style="margin-top: 30px;">
    <h3 style="font-size: 14px; font-weight: bold; text-align: center; text-transform: uppercase; margin-bottom: 10px;">
        Subjects Performance Chart
    </h3>
    <div class="chart-row" style="display: flex; align-items: flex-end; justify-content: center; gap: 10px; height: 220px; padding: 20px; border: 1px solid #000; background-color: #fff;">
        @foreach($grades as $grade)
            <div class="bar-group" style="display: flex; flex-direction: column; align-items: center; width: 40px;">
                <span class="score-top" style="font-size: 10px; font-weight: bold; margin-bottom: 4px;">{{ round($grade->total_score) }}</span>
                
                <div class="bar" 
                     style="width: 25px; 
                            height: {{ $grade->total_score * 1.5 }}px; 
                            background-color: {{ $grade->total_score >= 75 ? '#16a34a' : ($grade->total_score >= 50 ? '#1e3a8a' : '#dc2626') }};
                            border: 1px solid #000;">
                </div>
                
                <div class="bar-label" style="font-size: 8px; font-weight: bold; text-transform: uppercase; margin-top: 8px; text-align: left; writing-mode: vertical-rl; transform: rotate(180deg); height: 80px;">
                    {{ $grade->subject }}
                </div>
            </div>
        @endforeach
    </div>
</div>