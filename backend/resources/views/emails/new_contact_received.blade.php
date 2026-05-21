<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Contact Submission Received</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f8fafc; color: #334155; margin: 0; padding: 0; }
        .wrapper { max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05); border: 1px solid #e2e8f0; }
        .header { background: #0f172a; color: #ffffff; padding: 24px; text-align: center; }
        .header h1 { margin: 0; font-size: 20px; font-weight: 700; }
        .content { padding: 32px; }
        .details-table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        .details-table th, .details-table td { padding: 12px; border-bottom: 1px solid #e2e8f0; text-align: left; vertical-align: top; }
        .details-table th { width: 35%; color: #64748b; font-weight: 600; font-size: 14px; }
        .details-table td { color: #0f172a; font-size: 15px; }
        .msg-box { background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 16px; font-style: italic; color: #475569; }
        .badge { display: inline-block; padding: 2px 8px; background-color: #e0f2fe; color: #0369a1; border-radius: 4px; font-size: 12px; font-weight: 600; }
        .footer { background-color: #f1f5f9; padding: 20px 32px; text-align: center; border-top: 1px solid #e2e8f0; font-size: 13px; color: #64748b; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>New Contact Form Submission</h1>
        </div>
        <div class="content">
            <table class="details-table">
                <tr>
                    <th>Form Name</th>
                    <td><span class="badge">{{ $submission->form_name }}</span></td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td>{{ $submission->name }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><a href="mailto:{{ $submission->email }}">{{ $submission->email }}</a></td>
                </tr>
                <tr>
                    <th>Phone</th>
                    <td>{{ $submission->phone ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Subject</th>
                    <td>{{ $submission->subject ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>IP Location</th>
                    <td>{{ $submission->city ?? 'Unknown City' }}, {{ $submission->country ?? 'Unknown Country' }} ({{ $submission->ip_address }})</td>
                </tr>
                <tr>
                    <th>Attribution</th>
                    <td>
                        Source: <strong>{{ $submission->referrer_source ?? 'direct' }}</strong><br>
                        Medium: {{ $submission->utm_medium ?? 'N/A' }}<br>
                        Campaign: {{ $submission->utm_campaign ?? 'N/A' }}
                    </td>
                </tr>
            </table>

            <h3 style="color: #0f172a; margin-top: 0; font-size: 16px;">Visitor Message:</h3>
            <div class="msg-box">
                {!! nl2br(e($submission->message)) !!}
            </div>
        </div>
        <div class="footer">
            <p>Sent from the ClimbSphere Lead Engine.</p>
        </div>
    </div>
</body>
</html>
