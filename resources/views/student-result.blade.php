<div class="p-6 bg-white border rounded-lg shadow-sm">
    <p class="text-gray-800 mb-4">Dear Parent/Guardian,</p>

    <p class="text-gray-800 mb-4">
        Please find below the 
        <strong>{{ $student->academicSetting->term ?? 'Current' }}</strong> result card for 
        <span class="text-blue-900 font-bold">{{ $student->full_name }}</span> 
        of <span class="font-semibold">{{ $student->schoolClass->name }} ({{ $student->schoolClass->arm }})</span>.
    </p>

    <p class="text-gray-800 mb-4">
        We are proud of our students' hard work this 
        <strong>{{ $student->academicSetting->session ?? 'academic' }}</strong> session at 
        <span class="font-bold text-blue-900 underline">Unique Group of Schools</span>. 
        If you have any questions regarding this report, please do not hesitate to contact the school office.
    </p>

    <div class="mt-8 pt-4 border-t border-gray-200">
        <p class="text-gray-700 italic">Best Regards,</p>
        <p class="font-bold text-blue-900">Administration Office</p>
        <p class="text-sm text-gray-500 uppercase tracking-tighter">Unique Group of Schools Portal</p>
    </div>
</div>