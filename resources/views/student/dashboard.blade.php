<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="min-h-screen bg-gray-100 flex">
    <div class="w-64 bg-blue-900 text-white flex-shrink-0 hidden md:flex flex-col">
        <div class="p-6 text-2xl font-black border-b border-blue-800">UGS PORTAL</div>
        <nav class="flex-1 p-4 space-y-2">
            <a href="#" class="block p-3 bg-blue-800 rounded-xl font-bold">Dashboard</a>
            <a href="#subject-list" class="block p-3 hover:bg-blue-800 rounded-xl transition">Subject List</a>
            <a href="{{ route('report.generate', ['id' => $student->id]) }}" target="_blank" class="block p-3 hover:bg-blue-800 rounded-xl transition">View Report Card</a>
            <a href="#" class="block p-3 hover:bg-blue-800 rounded-xl transition">Attendance</a>
        </nav>
        <div class="p-4 border-t border-blue-800">
            <a href="{{ route('student.login') }}" class="text-sm text-blue-300 hover:text-white">Sign Out</a>
        </div>
    </div>

    <div class="flex-1 flex flex-col">
        <header class="bg-white shadow-sm p-4 flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-800 uppercase">Student Dashboard</h2>
            <div class="flex items-center space-x-4">
                <span class="text-gray-600 font-medium">{{ $student->full_name }}</span>
                <div class="w-10 h-10 bg-amber-500 rounded-full flex items-center justify-center text-white font-bold">
                    {{ substr($student->full_name, 0, 1) }}
                </div>
            </div>
        </header>

        <main class="p-6 space-y-6">
            
            @if($announcements->count() > 0)
            <div class="space-y-3">
                @foreach($announcements as $news)
                <div class="flex items-center p-4 rounded-xl shadow-sm border-l-4 
                    {{ $news->type == 'warning' ? 'bg-red-50 border-red-500 text-red-800' : 
                       ($news->type == 'success' ? 'bg-green-50 border-green-500 text-green-800' : 'bg-blue-50 border-blue-500 text-blue-800') }}">
                    <div class="mr-4 text-xl">
                        @if($news->type == 'warning') 🔔 @else 📢 @endif
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold uppercase text-xs tracking-wider opacity-75">{{ $news->title }}</h4>
                        <p class="text-sm font-medium">{{ $news->message }}</p>
                    </div>
                    <span class="text-[10px] opacity-50 font-bold uppercase">{{ $news->created_at->diffForHumans() }}</span>
                </div>
                @endforeach
            </div>
            @endif

            @if($isBirthdaySoon)
            <div class="bg-gradient-to-r from-amber-400 to-orange-500 rounded-2xl p-6 text-white shadow-lg flex items-center justify-between animate-pulse">
                <div class="flex items-center space-x-4">
                    <div class="text-4xl">🎂</div>
                    <div>
                        <h4 class="text-xl font-bold uppercase">Early Birthday Wishes!</h4>
                        <p class="opacity-90">The Unique Group of Schools family celebrates you this week!</p>
                    </div>
                </div>
            </div>
            @endif

            <div class="bg-gradient-to-r from-blue-900 to-blue-700 rounded-2xl p-8 text-white shadow-lg">
                <h1 class="text-3xl font-bold text-white">Welcome back, {{ explode(' ', $student->full_name)[0] }}!</h1>
                <p class="mt-2 opacity-90">Academic Session: {{ $setting->academic_year ?? '2025/2026' }} | Term: {{ $setting->term ?? 'First Term' }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-amber-500">
                    <p class="text-sm text-gray-400 font-bold uppercase">Current Class</p>
                    <h3 class="text-2xl font-black text-blue-900">{{ $student->class_level }}</h3>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-blue-900">
                    <p class="text-sm text-gray-400 font-bold uppercase">Admission No.</p>
                    <h3 class="text-2xl font-black text-blue-900">{{ $student->admission_number }}</h3>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-green-500">
                    <p class="text-sm text-gray-400 font-bold uppercase">Status</p>
                    <h3 class="text-2xl font-black text-green-600">{{ $student->status ?? 'Active' }}</h3>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm">
                <h3 class="text-lg font-bold text-blue-900 mb-4 uppercase">Performance Analysis</h3>
                <div class="h-64">
                    <canvas id="performanceChart"></canvas>
                </div>
            </div>

            <div id="subject-list" class="bg-white rounded-2xl shadow-sm p-6">
                <h3 class="text-lg font-bold text-blue-900 mb-4 uppercase">Offered Subjects</h3>
                @if($subjects->isEmpty())
                    <div class="p-8 text-center border-2 border-dashed rounded-xl text-gray-400">
                        No subjects registered in the system yet.
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        @foreach($subjects as $subject)
                            <div class="flex items-center space-x-3 p-4 bg-gray-50 rounded-xl border border-gray-100 hover:border-blue-300 transition group">
                                <div class="bg-blue-100 text-blue-900 p-2 rounded-lg group-hover:bg-blue-900 group-hover:text-white transition">
                                    <span class="text-xs font-black">{{ $subject->code ?? 'SUB' }}</span>
                                </div>
                                <span class="font-bold text-gray-700 text-sm truncate">{{ $subject->name }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-blue-900 uppercase">Current Term Grades</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider">
                                <th class="p-4 font-bold border-b">Subject</th>
                                <th class="p-4 font-bold border-b text-center">CA (40)</th>
                                <th class="p-4 font-bold border-b text-center">Exam (60)</th>
                                <th class="p-4 font-bold border-b text-center">Total</th>
                                <th class="p-4 font-bold border-b text-center">Grade</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 divide-y divide-gray-100">
                            @foreach($student->grades as $grade)
                            <tr class="hover:bg-blue-50/50 transition">
                                <td class="p-4 font-semibold">{{ $grade->subject }}</td>
                                <td class="p-4 text-center">{{ $grade->ca_score ?? '0' }}</td>
                                <td class="p-4 text-center">{{ $grade->exam_score ?? '0' }}</td>
                                <td class="p-4 text-center font-bold text-blue-900">{{ $grade->total_score }}</td>
                                <td class="p-4 text-center">
                                    <span class="px-3 py-1 rounded-md font-bold text-sm 
                                        {{ ($grade->total_score >= 50) ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        @php
                                            $total = $grade->total_score;
                                            if($total >= 75) echo 'A1';
                                            elseif($total >= 70) echo 'B2';
                                            elseif($total >= 65) echo 'B3';
                                            elseif($total >= 60) echo 'C4';
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
            </div>
        </main>
    </div>
</div>

<script>
    const ctx = document.getElementById('performanceChart').getContext('2d');
    const subjects = @json($student->grades->pluck('subject'));
    const scores = @json($student->grades->pluck('total_score'));

    const barColors = scores.map(score => {
        if (score >= 75) return '#16a34a'; // Green
        if (score >= 50) return '#1e3a8a'; // Blue
        return '#dc2626';                // Red
    });

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: subjects,
            datasets: [{
                data: scores,
                backgroundColor: barColors,
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, max: 100, ticks: { stepSize: 20 } },
                x: { grid: { display: false } }
            },
            plugins: { legend: { display: false } }
        }
    });
</script>