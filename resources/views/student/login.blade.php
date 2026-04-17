<script src="https://cdn.tailwindcss.com"></script>
<div class="min-h-screen bg-slate-200 flex flex-col items-center justify-center p-6">
    <div class="mb-6 text-center">
        <h1 class="text-3xl font-black text-blue-900 tracking-tight">UNIQUE GROUP OF SCHOOLS</h1>
        <div class="h-1 w-20 bg-amber-500 mx-auto mt-2"></div>
    </div>

    <div class="bg-white p-8 rounded-2xl shadow-2xl w-full max-w-md border-b-8 border-blue-900">
        <h2 class="text-xl font-bold text-gray-800 mb-2 text-center">STUDENT & PARENT PORTAL</h2>
        <p class="text-sm text-gray-500 mb-6 text-center">Access academic results and student profile</p>
        
        <form action="{{ route('student.check') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Admission Number</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </span>
                    <input type="text" name="admission_number" required placeholder="e.g. 2201" 
                           class="w-full pl-10 p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-900 focus:border-transparent outline-none transition">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Access Key (Surname)</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </span>
                    <input type="password" name="surname" required placeholder="Enter student surname" 
                           class="w-full pl-10 p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-900 focus:border-transparent outline-none transition">
                </div>
            </div>

            <button type="submit" class="w-full bg-blue-900 text-white font-bold py-4 rounded-xl hover:bg-blue-800 shadow-lg transform hover:-translate-y-1 transition-all duration-200">
                ENTER PORTAL
            </button>
        </form>

        @if(session('error'))
            <div class="mt-4 p-3 bg-red-100 text-red-700 text-sm rounded-lg text-center font-medium">
                {{ session('error') }}
            </div>
        @endif
    </div>

    <p class="mt-8 text-gray-500 text-xs">© 2026 Unique Group of Schools • ICT Department</p>
</div>