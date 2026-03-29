<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    /**
     * Generate and download the Student Result PDF.
     */
    public function download(Student $student, Request $request)
    {
        // Get the term from the request, default to First Term
        $term = $request->query('term', 'First Term');
        
        // Load the student with specific term grades and class info
        $student->load(['grades' => function ($query) use ($term) {
            $query->where('term', $term)->with('subject');
        }, 'schoolClass']);

        // Calculate Average - Returns 0 if no grades found
        $average = $student->grades->avg('score') ?? 0;

        // Load the PDF view with all required data
        $pdf = Pdf::loadView('pdf.result', [
            'student' => $student,
            'term' => $term,
            'average' => $average,
            // These pull from the fields we discussed for the report card
            'teacher_remark' => $student->current_teacher_remark, 
            'head_remark' => $student->current_head_remark,       
            'date' => now()->format('d M, Y'),
        ]);

        // Clean filename: Name_Term_Result.pdf
        $fileName = str_replace(' ', '_', $student->full_name) . "_{$term}_Result.pdf";

        return $pdf->download($fileName);
    }
}