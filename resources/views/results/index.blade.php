<script src="https://cdn.tailwindcss.com"></script>
<div class="min-h-screen bg-gray-100 flex flex-col items-center justify-center p-6">
    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold text-blue-900 mb-4 text-center uppercase">Student Result Checker</h2>
        <form action="{{ route('results.show') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Admission Number</label>
                <input type="text" name="admission_number" required placeholder="e.g. 2201" 
                       class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-900 outline-none">
            </div>
            <button type="submit" class="w-full bg-blue-900 text-white font-bold py-3 rounded-lg hover:bg-blue-700 transition">
                View Report Card
            </button>
        </form>
    </div>
</div>