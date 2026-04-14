<table class="summary-container">
    <tr>
        <td><strong>Performance Grade:</strong> {{ $this->calculateLetter($average) }} </td>
        <td><strong>Student Total Score:</strong> {{ $studentTotal }}/{{ $obtainable }} </td>
    </tr>
    <tr>
        <td><strong>Class Size:</strong> {{ $classSize }} </td>
        <td><strong>Student Average (%):</strong> {{ number_format($average, 2) }}% </td>
    </tr>
    <tr>
        <td><strong>No. of Subjects:</strong> {{ $totalSubjects }} </td>
        <td><strong>Result Summary:</strong> {{ $average >= 60 ? 'Very Good' : 'Pass' }} </td>
    </tr>
</table>

<div class="chart-row">
    @foreach($grades as $grade)
        <div class="bar-group">
            <span class="score-top">{{ round($grade->total_score) }} [cite: 15-24]</span>
            <div class="bar" style="height: {{ $grade->total_score * 1.5 }}px; background-color: #5db3ff;"></div>
            <div class="bar-label">{{ $grade->subject }} [cite: 26-34]</div>
        </div>
    @endforeach
</div>