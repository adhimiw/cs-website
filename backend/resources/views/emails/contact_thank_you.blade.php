<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>We have received your message</title>
    <style>
        body { font-family: 'Urbanist', 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f3f4f6; color: #1f2937; margin: 0; padding: 0; -webkit-font-smoothing: antialiased; }
        .email-container { max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025); border: 1px solid #e5e7eb; }
        .branding-header { background-color: #001747; padding: 24px 32px; text-align: center; }
        .branding-logo { height: 40px; display: inline-block; vertical-align: middle; }
        .hero-banner { background: linear-gradient(135deg, #001f5f 0%, #0055ff 100%); color: #ffffff; padding: 36px 32px; text-align: center; }
        .hero-banner h1 { margin: 0; font-size: 24px; font-weight: 800; letter-spacing: -0.02em; }
        .hero-banner p { margin: 8px 0 0; font-size: 14px; color: #e8f0ff; opacity: 0.9; }
        .email-body { padding: 40px 32px; line-height: 1.7; }
        .email-body p { margin: 0 0 18px; font-size: 16px; color: #4b5563; }
        .email-body strong { color: #111827; }
        .cta-container { text-align: center; margin: 32px 0; }
        .cta-btn { display: inline-block; padding: 14px 28px; background-color: #0055ff; color: #ffffff !important; text-decoration: none; border-radius: 50px; font-weight: 700; font-size: 14px; text-transform: uppercase; letter-spacing: 0.05em; transition: background-color 0.2s ease-in-out; box-shadow: 0 4px 6px -1px rgba(0, 85, 255, 0.2); }
        .cta-btn:hover { background-color: #0045d0; }
        .footer { background-color: #f9fafb; padding: 32px; text-align: center; border-top: 1px solid #f3f4f6; font-size: 14px; color: #6b7280; }
        .footer p { margin: 0 0 8px; }
        .footer a { color: #0055ff; text-decoration: none; font-weight: 600; }
        .social-links { margin-top: 16px; font-size: 12px; }
        .social-links a { color: #9ca3af; margin: 0 8px; }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="branding-header">
            @if(isset($message))
                <img src="{{ $message->embed(public_path('images/climbsphere-logo-header.png')) }}" alt="ClimbSphere" class="branding-logo" />
            @else
                <img src="https://climbsphere.ai/images/climbsphere-logo-header.png" alt="ClimbSphere" class="branding-logo" />
            @endif
        </div>
        <div class="hero-banner">
            <h1>We've Received Your Message</h1>
            <p>Thank you for reaching out to ClimbSphere</p>
        </div>
        <div class="email-body">
            <p>Hi {{ $submission->name }},</p>
            <p>Thank you for reaching out to ClimbSphere. We have received your submission regarding <strong>{{ $submission->subject ?? 'General Inquiry' }}</strong>.</p>
            <p>Our team is currently reviewing your message and will get back to you within 24 business hours.</p>
            <p>In the meantime, feel free to explore our capabilities and professional services:</p>
            
            <div class="cta-container">
                <a href="https://climbsphere.ai/services" class="cta-btn">Explore Services</a>
            </div>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} ClimbSphere. All rights reserved.</p>
            <p>Need immediate support? Email us at <a href="mailto:{{ config('mail.admin_recipient', 'sales@climbsphere.ai') }}">{{ config('mail.admin_recipient', 'sales@climbsphere.ai') }}</a>.</p>
            <div class="social-links">
                <a href="https://linkedin.com/company/climbsphere">LinkedIn</a> &bull;
                <a href="https://twitter.com/climbsphere">Twitter</a>
            </div>
        </div>
    </div>
</body>
</html>
