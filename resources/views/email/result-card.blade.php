<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
        .container { width: 100%; max-width: 600px; margin: 20px auto; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; }
        .header { background-color: #1e40af; color: white; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; letter-spacing: 1px; }
        .content { padding: 40px; background-color: #ffffff; }
        .student-box { background-color: #f8fafc; border-left: 4px solid #2563eb; padding: 15px; margin: 20px 0; }
        .footer { background-color: #f1f5f9; color: #64748b; padding: 20px; text-align: center; font-size: 12px; }
        .button { background-color: #2563eb; color: #ffffff !important; padding: 12px 25px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold; margin-top: 10px; }
        .notice { font-size: 13px; color: #ef4444; margin-top: 20px; font-style: italic; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>UNIQUE GROUP OF SCHOOLS</h1>
            <p>Academic Excellence & Character</p>
        </div>
        
        <div class="content">
            <p>Dear Parent/Guardian,</p>
            
            <p>We are pleased to inform you that the academic report card for the current term has been processed.</p>
            
            <div class="student-box">
                <strong>Student:</strong> {{ strtoupper($student_name) }} <br>
                <strong>Term:</strong> {{ $settings->current_term }} <br>
                <strong>Session:</strong> {{ $settings->academic_year }}
            </div>

            <p>Please find the <strong>Official Report Card attached</strong> to this email as a PDF document. This document contains a detailed breakdown of scores, affective skill ratings, and teacher remarks.</p>

            <p style="text-align: center;">
                <a href="{{ config('app.url') }}/login" class="button">Access Parent Portal</a>
            </p>
            
            <p class="notice">Note: You will need a PDF reader to view the attached result.</p>
            
            <p>If you have any observations regarding your ward's performance, feel free to contact the school administration or the class teacher.</p>
            
            <p>Warm regards,<br>
            <strong>The Administration</strong><br>
            Unique Group of Schools</p>
        </div>

        <div class="footer">
            <p>Block 12, Plot 350 Norus Close, Omole Estate Phase 1</p>
            <p>&copy; {{ date('Y') }} Unique Group of Schools. All rights reserved.</p>
        </div>
    </div>
</body>
</html>