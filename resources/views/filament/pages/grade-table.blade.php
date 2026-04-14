<div wire:loading class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-20">
    <div class="flex flex-col items-center">
        <svg class="w-12 h-12 text-primary-600 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <p class="mt-2 text-sm font-semibold text-primary-600">Processing Data...</p>
    </div>
</div>
<div class="space-y-6">
    <div class="overflow-x-auto border border-gray-200 rounded-lg shadow-sm">
        <table class="w-full text-left border-collapse bg-white">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="p-2 border-r w-12 text-center font-bold">S/N</th>
                    <th class="p-2 border-r font-bold">Admission No</th>
                    <th class="p-2 border-r font-bold">Student Name</th>
                    <th class="p-2 border-r w-24 font-bold text-center">CA (40)</th>
                    <th class="p-2 border-r w-24 font-bold text-center">Exam (60)</th>
                    <th class="p-2 w-24 font-bold text-center bg-blue-50">Total</th>
                </tr>
            </thead>
            <tbody>
                @php $list = $getState() ?? []; @endphp
                @foreach($list as $uuid => $row)
                    @php
                        $caValue = (float)($row['ca_score'] ?? 0);
                        $examValue = (float)($row['exam_score'] ?? 0);
                        $isCaInvalid = $caValue > 40;
                        $isExamInvalid = $examValue > 60;
                    @endphp
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="p-2 border-r text-center text-sm text-gray-600">
                            {{ $loop->iteration }}
                        </td>
                        <td class="p-2 border-r text-sm">
                            {{ $row['admission_number'] }}
                        </td>
                        <td class="p-2 border-r text-sm">
                            {{ $row['student_name'] }}
                        </td>
                        <td class="p-1 border-r {{ $isCaInvalid ? 'bg-red-50' : '' }}">
                            <input 
                                type="number" 
                                wire:model.live.debounce.500ms="data.grades_list.{{ $uuid }}.ca_score"
                                class="w-full border-none focus:ring-0 text-center p-1 bg-transparent text-sm {{ $isCaInvalid ? 'text-red-600 font-bold' : '' }}"
                                placeholder="0"
                            />
                        </td>
                        <td class="p-1 border-r {{ $isExamInvalid ? 'bg-red-50' : '' }}">
                            <input 
                                type="number" 
                                wire:model.live.debounce.500ms="data.grades_list.{{ $uuid }}.exam_score"
                                class="w-full border-none focus:ring-0 text-center p-1 bg-transparent text-sm {{ $isExamInvalid ? 'text-red-600 font-bold' : '' }}"
                                placeholder="0"
                            />
                        </td>
                        <td class="p-2 text-center font-bold bg-blue-50 text-sm text-blue-700">
                            {{ $caValue + $examValue }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if(count($list) > 0)
        <div class="flex flex-col items-end gap-2">
            @php
                $anyInvalid = collect($list)->contains(fn($row) => ($row['ca_score'] ?? 0) > 40 || ($row['exam_score'] ?? 0) > 60);
            @endphp
            
            @if($anyInvalid)
                <span class="text-xs text-red-600 font-medium">⚠️ Some scores exceed the maximum limit. Please correct them before saving.</span>
            @endif

            <button 
                type="button" 
                wire:click="submit" 
                wire:loading.attr="disabled"
                @if($anyInvalid) disabled @endif
                class="px-10 py-3 {{ $anyInvalid ? 'bg-gray-400 cursor-not-allowed' : 'bg-success-600 hover:bg-success-700' }} text-white rounded-lg font-bold shadow-lg transition-all flex items-center gap-2"
            >
                <span wire:loading.remove>💾 Save All Grades for this Class</span>
                <span wire:loading>⏳ Processing...</span>
            </button>
        </div>
    @endif
</div>