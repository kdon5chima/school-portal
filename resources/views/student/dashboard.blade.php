<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div class="min-h-screen bg-gray-100 flex">

    <div class="w-64 bg-blue-900 text-white flex-shrink-0 hidden md:flex flex-col">
        <div class="p-6 text-2xl font-black border-b border-blue-800">
            UGS PORTAL
        </div>
        <nav class="flex-1 p-4 space-y-2">
            <a href="#" class="block p-3 bg-blue-800 rounded-xl font-bold">Dashboard</a>
            <a href="{{ route('report.generate', ['id' => $student->id]) }}" target="_blank" class="block p-3 hover:bg-blue-800 rounded-xl transition">View Report Card</a>
            <a href="#" class="block p-3 hover:bg-blue-800 rounded-xl transition">Attendance</a>
            <a href="#" class="block p-3 hover:bg-blue-800 rounded-xl transition">Subject List</a>
        </nav>
        <div class="p-4 border-t border-blue-800">
            <a href="{{ route('student.login') }}" class="text-sm text-blue-300 hover:text-white">Sign Out</a>
        </div>
    </div>

    <div class="flex-1 flex flex-col">
        <header class="bg-white shadow-sm p-4 flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-800 uppercase">Student Dashboard</h2>
            <div class="flex items-center space-x-4">
                <span class="text-gray-600 font-medium">{{ $student->first_name }} {{ $student->last_name }}</span>
                <div class="w-10 h-10 bg-amber-500 rounded-full flex items-center justify-center text-white font-bold">
                    {{ substr($student->first_name, 0, 1) }}
                </div>
            </div>
        </header>
@if($isBirthdaySoon)
<div class="bg-gradient-to-r from-amber-400 to-orange-500 rounded-2xl p-6 text-white shadow-lg mb-6 flex items-center justify-between animate-pulse">
    <div class="flex items-center space-x-4">
        <div class="text-4xl">🎂</div>
        <div>
            <h4 class="text-xl font-bold uppercase">Early Birthday Wishes!</h4>
            <p class="opacity-90">The Unique Group of Schools family celebrates you this week!</p>
        </div>
    </div>
    <div class="hidden md:block">
        <svg class="w-12 h-12 opacity-50" fill="currentColor" viewBox="0 0 20 20"><path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM5.884 6.68a1 1 0 10-1.415-1.414l-.707.707a1 1 0 101.414 1.415l.707-.708zm11.314-1.414a1 1 0 00-1.414 1.414l.707.707a1 1 0 001.414-1.414l-.707-.707zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-12 0a1 1 0 100-2H4a1 1 0 100 2h1zM12.707 15.586a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM6.586 14.172a1 1 0 111.414 1.414l-.707.707a1 1 0 11-1.414-1.414l.707-.707z"></path></svg>
    </div>
</div>
@endif
        <main class="p-6 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
        <h3 class="text-lg font-bold text-blue-900 uppercase">Continuous Assessment & Grades</h3>
        <span class="text-xs font-bold px-3 py-1 bg-blue-100 text-blue-700 rounded-full">Current Term</span>
    </div>
    <div class="overflow-x-auto">
        <div class="bg-white p-6 rounded-2xl shadow-sm mb-6">
    <h3 class="text-lg font-bold text-blue-900 mb-4 uppercase">Performance Analysis</h3>
    <div class="h-64">
        <canvas id="performanceChart"></canvas>
    </div>
</div>
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider">
                    <th class="p-4 font-bold border-b">Subject</th>
                    <th class="p-4 font-bold border-b text-center">CA 1 (20)</th>
                    <th class="p-4 font-bold border-b text-center">CA 2 (20)</th>
                    <th class="p-4 font-bold border-b text-center">Exam (60)</th>
                    <th class="p-4 font-bold border-b text-center">Total</th>
                    <th class="p-4 font-bold border-b text-center">Grade</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 divide-y divide-gray-100">
                @foreach($student->grades as $grade)
                <tr class="hover:bg-blue-50/50 transition">
                    <td class="p-4 font-semibold">{{ $grade->subject->name }}</td>
                    <td class="p-4 text-center">{{ $grade->ca1 ?? '-' }}</td>
                    <td class="p-4 text-center">{{ $grade->ca2 ?? '-' }}</td>
                    <td class="p-4 text-center">{{ $grade->exam ?? '-' }}</td>
                    <td class="p-4 text-center font-bold text-blue-900">
                        {{ ($grade->ca1 ?? 0) + ($grade->ca2 ?? 0) + ($grade->exam ?? 0) }}
                    </td>
                    <td class="p-4 text-center">
                        <span class="px-3 py-1 rounded-md font-bold text-sm 
                            {{ (($grade->ca1 + $grade->ca2 + $grade->exam) >= 70) ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                            {{-- Logic to determine grade letter --}}
                            @php
                                $total = ($grade->ca1 ?? 0) + ($grade->ca2 ?? 0) + ($grade->exam ?? 0);
                                if($total >= 75) echo 'A1';
                                elseif($total >= 70) echo 'B2';
                                elseif($total >= 65) echo 'B3';
                                elseif($total >= 60) echo 'C4';
                                elseif($total >= 55) echo 'C5';
                                elseif($total >= 50) echo 'C6';
                                else echo 'F9';
                            @endphp
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if($student->grades->isEmpty())
        <div class="p-10 text-center text-gray-400">
            <p>No results have been uploaded for this term yet.</p>
        </div>
    @endif
</div>
            <div class="bg-gradient-to-r from-blue-900 to-blue-700 rounded-2xl p-8 text-white shadow-lg">
                <h1 class="text-3xl font-bold">Welcome back, {{ $student->first_name }}!</h1>
                <p class="mt-2 opacity-90">Academic Session: {{ $setting->session ?? '2025/2026' }} | Term: {{ $setting->term ?? 'First Term' }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-amber-500">
                    <p class="text-sm text-gray-500 font-bold uppercase">Current Class</p>
                    <h3 class="text-2xl font-black text-blue-900">{{ $student->schoolClass->name }} {{ $student->schoolClass->arm }}</h3>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-blue-900">
                    <p class="text-sm text-gray-500 font-bold uppercase">Admission No.</p>
                    <h3 class="text-2xl font-black text-blue-900">{{ $student->admission_number }}</h3>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-green-500">
                    <p class="text-sm text-gray-500 font-bold uppercase">Account Status</p>
                    <h3 class="text-2xl font-black text-green-600">Active</h3>
                </div>
            </div>

            <div class="bg-white p-8 rounded-2xl shadow-sm">
                <h3 class="text-lg font-bold mb-4">Academic Reports</h3>
                <div class="flex items-center justify-between p-4 border rounded-xl hover:bg-gray-50 transition">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 bg-red-100 text-red-600 rounded-lg">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"></path></svg>
                        </div>
                        <div>
                            <p class="font-bold text-gray-800">{{ $setting->term ?? 'Current' }} Report Card</p>
                            <p class="text-xs text-gray-500">Download your terminal assessment results</p>
                        </div>
                    </div>
                    <a href="{{ route('report.generate', ['id' => $student->id]) }}" target="_blank" class="px-6 py-2 bg-blue-900 text-white rounded-lg font-bold hover:bg-blue-800 transition">
                        View PDF
                    </a>
                </div>
            </div>

        </main>
    </div>
</div>
<script>
    const ctx = document.getElementById('performanceChart').getContext('2d');
    
    // Pulling the data from Laravel
    const subjects = @json($student->grades->map(fn($g) => $g->subject->name));
    const scores = @json($student->grades->map(fn($g) => ($g->ca1 ?? 0) + ($g->ca2 ?? 0) + ($g->exam ?? 0)));

    // Logic to assign colors dynamically
    const barColors = scores.map(score => {
        if (score >= 75) return '#16a34a'; // Green-600 (Excellent)
        if (score >= 50) return '#1e3a8a'; // Blue-900 (Good/Average)
        return '#dc2626';                // Red-600 (Needs Improvement)
    });

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: subjects,
            datasets: [{
                label: 'Total Score',
                data: scores,
                backgroundColor: barColors, // Using our dynamic array
                borderRadius: 6,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        stepSize: 20,
                        callback: function(value) { return value + '%'; }
                    },
                    grid: { color: '#f3f4f6' }
                },
                x: {
                    grid: { display: false }
                }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return ` Score: ${context.parsed.y}/100`;
                        }
                    }
                }
            }
        }
    });
</script>