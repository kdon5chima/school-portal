<div class="space-y-6">
    @php 
        $list = $getState() ?? []; 
        // 1. Fetch the scales once at the top to avoid hitting the DB in the loop
        $scales = \App\Models\GradeScale::orderBy('min_score', 'desc')->get();
    @endphp

    <div class="overflow-x-auto border border-gray-200 rounded-lg shadow-sm">
        <table class="w-full text-left border-collapse bg-white">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-gray-700">
                    <th class="p-2 border-r w-12 text-center font-bold">S/N</th>
                    <th class="p-2 border-r font-bold">Admission No</th>
                    <th class="p-2 border-r font-bold">Student Name</th>
                    <th class="p-2 border-r w-24 font-bold text-center">CA (40)</th>
                    <th class="p-2 border-r w-24 font-bold text-center">Exam (60)</th>
                    <th class="p-2 border-r w-24 font-bold text-center bg-blue-50">Total</th>
                    <th class="p-2 w-20 font-bold text-center bg-indigo-50">Grade</th>
                </tr>
            </thead>
            <tbody>
                @foreach($list as $uuid => $row)
                    @php
                        $caValue = (float)($row['ca_score'] ?? 0);
                        $examValue = (float)($row['exam_score'] ?? 0);
                        $total = $caValue + $examValue;
                        
                        $isCaInvalid = $caValue > 40;
                        $isExamInvalid = $examValue > 60;

                        // 2. Dynamic Real-time Grade Preview Logic
                        $match = $scales->first(function($scale) use ($total) {
                            return $total >= $scale->min_score && $total <= $scale->max_score;
                        });
                        
                        $gradeLetter = $total > 0 ? ($match->grade_letter ?? 'F9') : '-';
                    @endphp
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                        <td class="p-2 border-r text-center text-sm text-gray-600">
                            {{ $loop->iteration }}
                        </td>
                        <td class="p-2 border-r text-sm font-mono text-gray-700">
                            {{ $row['admission_number'] }}
                        </td>
                        <td class="p-2 border-r text-sm text-gray-800">
                            {{ $row['student_name'] }}
                        </td>
                        <td class="p-1 border-r {{ $isCaInvalid ? 'bg-red-50' : '' }}">
                            <input 
                                type="number" 
                                wire:model.live.debounce.500ms="data.grades_list.{{ $uuid }}.ca_score"
                                class="w-full border-none focus:ring-2 focus:ring-primary-500 text-center p-1 bg-transparent text-sm {{ $isCaInvalid ? 'text-red-600 font-bold' : 'text-gray-900' }}"
                                placeholder="0"
                            />
                        </td>
                        <td class="p-1 border-r {{ $isExamInvalid ? 'bg-red-50' : '' }}">
                            <input 
                                type="number" 
                                wire:model.live.debounce.500ms="data.grades_list.{{ $uuid }}.exam_score"
                                class="w-full border-none focus:ring-2 focus:ring-primary-500 text-center p-1 bg-transparent text-sm {{ $isExamInvalid ? 'text-red-600 font-bold' : 'text-gray-900' }}"
                                placeholder="0"
                            />
                        </td>
                        <td class="p-2 border-r text-center font-bold bg-blue-50 text-sm text-blue-700">
                            {{ $total }}
                        </td>
                        <td class="p-2 text-center font-extrabold bg-indigo-50 text-sm {{ $gradeLetter === 'F9' ? 'text-red-500' : 'text-indigo-700' }}">
                            {{ $gradeLetter }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if(count($list) > 0)
        <div class="flex flex-col items-end gap-3">
            @php
                $anyInvalid = collect($list)->contains(fn($row) => ($row['ca_score'] ?? 0) > 40 || ($row['exam_score'] ?? 0) > 60);
            @endphp
            
            @if($anyInvalid)
                <div class="flex items-center gap-2 text-sm text-red-600 font-semibold animate-pulse">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <span>Invalid scores detected. Please fix the red fields before saving.</span>
                </div>
            @endif

            <button 
                type="button" 
                wire:click="submit" 
                wire:loading.attr="disabled"
                @if($anyInvalid) disabled @endif
                class="px-10 py-3 {{ $anyInvalid ? 'bg-gray-400 cursor-not-allowed' : 'bg-primary-600 hover:bg-primary-700 shadow-md hover:shadow-lg' }} text-white rounded-lg font-bold transition-all flex items-center gap-2 group"
            >
                <div wire:loading.remove wire:target="submit" class="flex items-center gap-2">
                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                    <span>Update All Grades & Save</span>
                </div>
                
                <div wire:loading wire:target="submit" class="flex items-center gap-2">
                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <span>Saving to Database...</span>
                </div>
            </button>
        </div>
    @endif
</div>