<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Qualified Chat Lead Captured</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f8fafc; color: #334155; margin: 0; padding: 0; }
        .wrapper { max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05); border: 1px solid #e2e8f0; }
        .header { background: #0284c7; color: #ffffff; padding: 24px; text-align: center; }
        .header h1 { margin: 0; font-size: 20px; font-weight: 700; }
        .content { padding: 32px; }
        .details-table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        .details-table th, .details-table td { padding: 12px; border-bottom: 1px solid #e2e8f0; text-align: left; vertical-align: top; }
        .details-table th { width: 35%; color: #64748b; font-weight: 600; font-size: 14px; }
        .details-table td { color: #0f172a; font-size: 15px; }
        .msg-box { background-color: #f0f9ff; border: 1px solid #bae6fd; border-radius: 6px; padding: 16px; font-style: italic; color: #0369a1; }
        .badge { display: inline-block; padding: 2px 8px; background-color: #f0fdf4; color: #166534; border-radius: 4px; font-size: 12px; font-weight: 600; }
        .footer { background-color: #f1f5f9; padding: 20px 32px; text-align: center; border-top: 1px solid #e2e8f0; font-size: 13px; color: #64748b; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>Qualified Chat Lead Captured</h1>
        </div>
        <div class="content">
            <table class="details-table">
                <tr>
                    <th>Lead Status</th>
                    <td><span class="badge">{{ $lead->lead_status }}</span></td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td>{{ $lead->name }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><a href="mailto:{{ $lead->email }}">{{ $lead->email }}</a></td>
                </tr>
                <tr>
                    <th>Phone</th>
                    <td>{{ $lead->phone ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Company</th>
                    <td>{{ $lead->company ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Project Type</th>
                    <td>{{ $lead->project_type ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Budget</th>
                    <td>{{ $lead->budget ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Timeline</th>
                    <td>{{ $lead->timeline ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>IP Location</th>
                    <td>{{ $lead->city ?? 'Unknown City' }}, {{ $lead->country ?? 'Unknown Country' }} ({{ $lead->ip_address }})</td>
                </tr>
                <tr>
                    <th>Attribution</th>
                    <td>
                        Source: <strong>{{ $lead->referrer_source ?? 'direct' }}</strong><br>
                        Medium: {{ $lead->utm_medium ?? 'N/A' }}<br>
                        Campaign: {{ $lead->utm_campaign ?? 'N/A' }}
                    </td>
                </tr>
            </table>

            <h3 style="color: #0f172a; margin-top: 0; font-size: 16px;">Project Idea/Plan Summary:</h3>
            <div class="msg-box">
                {!! nl2br(e($lead->plan_or_idea)) !!}
            </div>
        </div>
        <div class="footer">
            <p>Sent from the ClimbSphere Lead Engine.</p>
        </div>
    </div>
</body>
</html>
