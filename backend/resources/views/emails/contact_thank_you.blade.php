<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>We have received your message</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f8fafc; color: #334155; margin: 0; padding: 0; }
        .wrapper { max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05); border: 1px solid #e2e8f0; }
        .header { background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #ffffff; padding: 32px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 700; letter-spacing: -0.025em; }
        .content { padding: 40px 32px; line-height: 1.6; }
        .content p { margin: 0 0 16px; font-size: 16px; color: #475569; }
        .content strong { color: #0f172a; }
        .cta-btn { display: inline-block; padding: 12px 24px; background-color: #2563eb; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: 600; margin-top: 8px; }
        .footer { background-color: #f1f5f9; padding: 24px 32px; text-align: center; border-top: 1px solid #e2e8f0; font-size: 14px; color: #64748b; }
        .footer a { color: #2563eb; text-decoration: none; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>ClimbSphere</h1>
        </div>
        <div class="content">
            <p>Hi {{ $submission->name }},</p>
            <p>Thank you for reaching out to ClimbSphere. We have received your submission regarding <strong>{{ $submission->subject ?? 'General Inquiry' }}</strong>.</p>
            <p>Our team is currently reviewing your message and will get back to you within 24 business hours.</p>
            <p>In the meantime, feel free to explore our capabilities and professional services:</p>
            <a href="https://climbsphere.com/our-services" class="cta-btn">View Services</a>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} ClimbSphere. All rights reserved.</p>
            <p>Need immediate support? Email us at <a href="mailto:hello@climbsphere.com">hello@climbsphere.com</a>.</p>
        </div>
    </div>
</body>
</html>
