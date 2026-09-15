<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ClimbSphere Meeting Confirmation</title>
</head>
<body style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f3f4f6; color: #1f2937; margin: 0; padding: 20px; -webkit-font-smoothing: antialiased;">
    <div style="max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025); border: 1px solid #e5e7eb;">
        <!-- Header -->
        <div style="background-color: #001747; padding: 24px 32px; text-align: center;">
            <h2 style="margin: 0; color: #ffffff; font-size: 20px; letter-spacing: 0.05em; text-transform: uppercase;">ClimbSphere Technologies</h2>
        </div>
        
        <!-- Hero Banner Section -->
        <div style="background: linear-gradient(135deg, #001f5f 0%, #c25e2e 100%); color: #ffffff; padding: 36px 32px; text-align: center;">
            <div style="display: inline-block; background-color: rgba(255,255,255,0.2); padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; text-transform: uppercase; margin-bottom: 12px; letter-spacing: 0.05em;">Session Confirmed</div>
            <h1 style="margin: 0; font-size: 24px; font-weight: 800; color: #ffffff; line-height: 1.2;">Your Strategy Call Is Scheduled</h1>
            <p style="margin: 8px 0 0; font-size: 14px; color: #fdfbf7; font-weight: 500; opacity: 0.95;">{{ $meeting->scheduled_date->format('l, F j, Y') }} at {{ $meeting->scheduled_time }} ({{ $meeting->timezone }})</p>
        </div>
        
        <!-- Main Body -->
        <div style="padding: 36px 32px; line-height: 1.7; font-size: 15px;">
            <p style="margin: 0 0 16px; color: #374151;">Hi {{ $meeting->name }},</p>
            <p style="margin: 0 0 20px; color: #4b5563;">Thank you for scheduling a strategy consultation with ClimbSphere! Your appointment has been reserved on our calendar.</p>
            
            <!-- Appointment Card -->
            <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #fbf8f1; border: 2px dashed #d5cfbf; border-radius: 12px; padding: 20px; margin: 20px 0; font-family: inherit;">
                <tr>
                    <td colspan="2" style="padding-bottom: 12px; font-size: 13px; font-weight: 800; text-transform: uppercase; color: #c25e2e; letter-spacing: 0.05em;">Appointment Particulars</td>
                </tr>
                <tr>
                    <td style="padding: 6px 0; font-weight: 700; color: #64748b; width: 130px; font-size: 14px;">Date:</td>
                    <td style="padding: 6px 0; color: #1e293b; font-weight: 600; font-size: 14px;">{{ $meeting->scheduled_date->format('l, F j, Y') }}</td>
                </tr>
                <tr>
                    <td style="padding: 6px 0; font-weight: 700; color: #64748b; font-size: 14px;">Time:</td>
                    <td style="padding: 6px 0; color: #1e293b; font-weight: 600; font-size: 14px;">{{ $meeting->scheduled_time }} ({{ $meeting->timezone }})</td>
                </tr>
                <tr>
                    <td style="padding: 6px 0; font-weight: 700; color: #64748b; font-size: 14px;">Session Type:</td>
                    <td style="padding: 6px 0; color: #1e293b; font-size: 14px;">{{ ucwords(str_replace('_', ' ', $meeting->meeting_type)) }}</td>
                </tr>
                @if($meeting->topic)
                <tr>
                    <td style="padding: 6px 0; font-weight: 700; color: #64748b; font-size: 14px;">Focus Topic:</td>
                    <td style="padding: 6px 0; color: #1e293b; font-size: 14px;">{{ $meeting->topic }}</td>
                </tr>
                @endif
                @if($meeting->company)
                <tr>
                    <td style="padding: 6px 0; font-weight: 700; color: #64748b; font-size: 14px;">Company:</td>
                    <td style="padding: 6px 0; color: #1e293b; font-size: 14px;">{{ $meeting->company }}</td>
                </tr>
                @endif
            </table>

            <p style="margin: 0 0 16px; color: #4b5563;">You will be meeting with our digital transformation advisory team. A calendar invitation with meeting link will be sent to your inbox shortly.</p>
            <p style="margin: 0 0 16px; color: #4b5563;">Need to reschedule, prepare questions, or invite colleagues? Simply <strong>reply directly to this email</strong> and our assistant will update your booking immediately.</p>
            <p style="margin: 0; color: #374151; font-weight: 600;">Warm regards,<br>The ClimbSphere Advisory Team</p>
        </div>
        
        <!-- Footer -->
        <div style="background-color: #f9fafb; padding: 24px 32px; text-align: center; border-top: 1px solid #f3f4f6; font-size: 13px; color: #6b7280;">
            <p style="margin: 0 0 6px;">&copy; {{ date('Y') }} ClimbSphere Technologies. All rights reserved.</p>
            <p style="margin: 0;">Sent from <a href="mailto:devloper@adhithanr.space" style="color: #c25e2e; text-decoration: none;">devloper@adhithanr.space</a></p>
        </div>
    </div>
</body>
</html>
