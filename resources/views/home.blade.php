<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unique Group of Schools | Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans">

    <nav class="bg-white shadow-md p-4 flex justify-between items-center">
        <a href="{{ route('results.index') }}" class="border-2 border-blue-900 text-blue-900 px-5 py-2 rounded-lg font-bold hover:bg-blue-900 hover:text-white transition">
    Check Results
</a>
        <div class="flex items-center space-x-3">
            <img src="{{ asset('images/logo.png') }}" alt="School Logo" class="h-12 w-12 rounded-full border">
            <span class="text-xl font-bold text-blue-900 uppercase">Unique Group of Schools</span>
        </div>
        
        <div class="space-x-6 font-semibold">
            <a href="#" class="text-gray-700 hover:text-blue-600">About Us</a>
            <a href="#" class="text-gray-700 hover:text-blue-600">Admissions</a>
            <a href="/admin" class="bg-blue-900 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition">Portal Login (Admin)</a>
        </div>
    </nav>

    <header class="py-20 px-10 text-center bg-gradient-to-r from-blue-900 to-blue-700 text-white">
        <h1 class="text-5xl font-extrabold mb-4 uppercase tracking-wider">Excellence in Education</h1>
        <p class="text-lg mb-8 opacity-90">Building the next generation of leaders through technology and character.</p>
        <div class="flex justify-center space-x-4">
            <a href="/admin" class="bg-white text-blue-900 px-8 py-3 rounded-full font-bold shadow-lg hover:bg-gray-100 transition">Get Started</a>
            <a href="#features" class="border border-white px-8 py-3 rounded-full font-bold hover:bg-white hover:text-blue-900 transition">Learn More</a>
        </div>
    </header>

    <section id="features" class="py-16 px-10 grid md:grid-cols-3 gap-8 text-center">
        <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100">
            <h3 class="text-xl font-bold mb-2">Smart Grading</h3>
            <p class="text-gray-600">Automated result computation for all students across different arms.</p>
        </div>
        <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100">
            <h3 class="text-xl font-bold mb-2">Inventory Control</h3>
            <p class="text-gray-600">Seamless tracking of school resources and academic materials.</p>
        </div>
        <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100">
            <h3 class="text-xl font-bold mb-2">Teacher Dashboards</h3>
            <p class="text-gray-600">Empowering educators with data-driven insights into student performance.</p>
        </div>
    </section>

</body>
</html>