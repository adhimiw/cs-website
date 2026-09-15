<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Internal Alert: New Meeting Scheduled via Chat</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f5f7; padding: 20px; color: #1e293b;">
    <div style="max-width: 600px; margin: auto; background: #ffffff; padding: 24px; border-radius: 8px; border-left: 5px solid #c25e2e;">
        <h2 style="color: #c25e2e; margin-top: 0;">New Meeting Booked via Chatbot</h2>
        <p>A new consultation call has been booked through the AI Chat Agent on ClimbSphere website.</p>
        
        <table style="width: 100%; border-collapse: collapse; margin: 16px 0;">
            <tr><td style="padding: 6px; font-weight: bold; width: 140px;">Name:</td><td style="padding: 6px;">{{ $meeting->name }}</td></tr>
            <tr><td style="padding: 6px; font-weight: bold;">Email:</td><td style="padding: 6px;"><a href="mailto:{{ $meeting->email }}">{{ $meeting->email }}</a></td></tr>
            <tr><td style="padding: 6px; font-weight: bold;">Phone:</td><td style="padding: 6px;">{{ $meeting->phone ?? 'Not provided' }}</td></tr>
            <tr><td style="padding: 6px; font-weight: bold;">Company:</td><td style="padding: 6px;">{{ $meeting->company ?? 'Not provided' }}</td></tr>
            <tr><td style="padding: 6px; font-weight: bold;">Scheduled Date:</td><td style="padding: 6px; font-weight: bold; color: #0055ff;">{{ $meeting->scheduled_date->format('l, F j, Y') }}</td></tr>
            <tr><td style="padding: 6px; font-weight: bold;">Scheduled Time:</td><td style="padding: 6px; font-weight: bold; color: #0055ff;">{{ $meeting->scheduled_time }} ({{ $meeting->timezone }})</td></tr>
            <tr><td style="padding: 6px; font-weight: bold;">Session Type:</td><td style="padding: 6px;">{{ $meeting->meeting_type }}</td></tr>
            <tr><td style="padding: 6px; font-weight: bold;">Topic / Goal:</td><td style="padding: 6px;">{{ $meeting->topic ?? 'N/A' }}</td></tr>
        </table>
        
        <p style="font-size: 13px; color: #64748b; margin-top: 20px;">View and manage this booking in the <a href="{{ url('/admin/meetings') }}" style="color: #c25e2e;">Filament Admin Control Center</a>.</p>
    </div>
</body>
</html>
