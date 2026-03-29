<?php

namespace App\Exports;

use App\Models\Grade;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class GradesExport implements FromQuery, WithHeadings, WithMapping
{
    protected $class_level;

    public function __construct($class_level = null)
    {
        $this->class_level = $class_level;
    }

    public function query()
    {
        // Use our custom "ForTeacher" scope so teachers only export THEIR data
        $query = Grade::query()->forTeacher();

        if ($this->class_level) {
            $query->where('class_level', $this->class_level);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'Student Name',
            'Admission No',
            'Class',
            'Subject',
            'CA (40)',
            'Exam (60)',
            'Total',
            'Grade',
        ];
    }

    public function map($grade): array
    {
        return [
            $grade->student_name,
            $grade->admission_number,
            $grade->class_level,
            $grade->subject,
            $grade->ca_score,
            $grade->exam_score,
            $grade->total_score,
            $grade->grade_letter,
        ];
    }
}