<script src="https://cdn.tailwindcss.com"></script>

<div class="min-h-screen bg-slate-100 flex flex-col items-center justify-center p-6">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-black text-blue-900 tracking-tight uppercase">Unique Group of Schools</h1>
        <p class="text-gray-600 mt-2 font-medium">Academic Management & Student Information System</p>
        <div class="h-1.5 w-32 bg-amber-500 mx-auto mt-4 rounded-full"></div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 w-full max-w-6xl">
        
        <a href="{{ route('student.login') }}" class="group bg-white p-8 rounded-2xl shadow-md hover:shadow-2xl transition-all duration-300 border-b-4 border-amber-500 transform hover:-translate-y-2 text-center">
            <div class="bg-amber-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:bg-amber-500 transition-colors">
                <svg class="w-8 h-8 text-amber-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-blue-900">Result Checker</h3>
            <p class="text-sm text-gray-500 mt-3">Quickly access and print terminal report cards using your Admission Number.</p>
        </a>

        <a href="{{ route('student.login') }}" class="group bg-white p-8 rounded-2xl shadow-md hover:shadow-2xl transition-all duration-300 border-b-4 border-blue-900 transform hover:-translate-y-2 text-center">
            <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:bg-blue-900 transition-colors">
                <svg class="w-8 h-8 text-blue-900 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-blue-900">Student Portal</h3>
            <p class="text-sm text-gray-500 mt-3">Login to view your full profile, attendance, class news, and academic history.</p>
        </a>

        <a href="/admin" class="group bg-white p-8 rounded-2xl shadow-md hover:shadow-2xl transition-all duration-300 border-b-4 border-slate-800 transform hover:-translate-y-2 text-center">
            <div class="bg-slate-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:bg-slate-800 transition-colors">
                <svg class="w-8 h-8 text-slate-800 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-blue-900">Staff Admin</h3>
            <p class="text-sm text-gray-500 mt-3">Authorized access for teachers and administrators to manage school data.</p>
        </a>

    </div>

    <div class="mt-16 text-gray-400 text-xs font-medium uppercase tracking-widest">
        &copy; 2026 Unique Group of Schools • Powered by ICT Dept
    </div>
</div>