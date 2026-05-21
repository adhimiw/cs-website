<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Your ClimbSphere Consultation Request</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f8fafc; color: #334155; margin: 0; padding: 0; }
        .wrapper { max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05); border: 1px solid #e2e8f0; }
        .header { background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); color: #ffffff; padding: 32px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 700; letter-spacing: -0.025em; }
        .content { padding: 40px 32px; line-height: 1.6; }
        .content p { margin: 0 0 16px; font-size: 16px; color: #475569; }
        .content strong { color: #0f172a; }
        .summary-box { background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; margin: 24px 0; }
        .summary-box h3 { margin: 0 0 12px; font-size: 15px; text-transform: uppercase; color: #64748b; letter-spacing: 0.05em; }
        .summary-item { margin-bottom: 8px; font-size: 15px; color: #334155; }
        .summary-item strong { width: 120px; display: inline-block; }
        .footer { background-color: #f1f5f9; padding: 24px 32px; text-align: center; border-top: 1px solid #e2e8f0; font-size: 14px; color: #64748b; }
        .footer a { color: #0284c7; text-decoration: none; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>Consultation Request Received</h1>
        </div>
        <div class="content">
            <p>Hi {{ $lead->name }},</p>
            <p>Thanks for chatting with our ClimbSphere digital assistant! We've successfully received your project details and registered your consultation request.</p>
            
            <div class="summary-box">
                <h3>Request Summary</h3>
                <div class="summary-item"><strong>Project:</strong> {{ $lead->project_type ?? 'Software Consultancy' }}</div>
                <div class="summary-item"><strong>Timeline:</strong> {{ $lead->timeline ?? 'Flexible' }}</div>
                <div class="summary-item"><strong>Budget:</strong> {{ $lead->budget ?? 'Flexible' }}</div>
            </div>

            <p>Our solutions architects are reviewing your details to prepare for our initial discussion. One of our engineers will reach out to you directly via this email address within 24 business hours.</p>
            <p>We look forward to partnering with you on your software journey!</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} ClimbSphere. All rights reserved.</p>
            <p>Need to add more details? Reply directly to this email or contact us at <a href="mailto:hello@climbsphere.com">hello@climbsphere.com</a>.</p>
        </div>
    </div>
</body>
</html>
