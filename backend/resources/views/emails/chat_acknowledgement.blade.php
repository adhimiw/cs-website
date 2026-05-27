<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your ClimbSphere Consultation Request</title>
</head>
<body style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f3f4f6; color: #1f2937; margin: 0; padding: 20px; -webkit-font-smoothing: antialiased;">
    <div style="max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025); border: 1px solid #e5e7eb;">
        <!-- Header Brand Logo -->
        <div style="background-color: #001747; padding: 24px 32px; text-align: center;">
            @if(isset($message))
                <img src="{{ $message->embed(public_path('images/climbsphere-logo-header.png')) }}" alt="ClimbSphere" style="height: 40px; display: inline-block; border: 0;" />
            @else
                <img src="https://climbsphere.ai/images/climbsphere-logo-header.png" alt="ClimbSphere" style="height: 40px; display: inline-block; border: 0;" />
            @endif
        </div>
        
        <!-- Hero Banner Section -->
        <div style="background-color: #001f5f; background: linear-gradient(135deg, #001f5f 0%, #0055ff 100%); color: #ffffff; padding: 36px 32px; text-align: center;">
            <h1 style="margin: 0; font-size: 24px; font-weight: 800; color: #ffffff; letter-spacing: -0.02em; line-height: 1.2;">Consultation Request Registered</h1>
            <p style="margin: 8px 0 0; font-size: 14px; color: #e8f0ff; font-weight: 500; opacity: 0.9;">Your B2B transformation journey starts here</p>
        </div>
        
        <!-- Main Email Body -->
        <div style="padding: 40px 32px; line-height: 1.7; font-size: 16px;">
            <p style="margin: 0 0 18px; color: #4b5563;">Hi {{ $lead->name }},</p>
            <p style="margin: 0 0 18px; color: #4b5563;">Thanks for chatting with our ClimbSphere digital assistant! We've successfully received your project details and registered your consultation request.</p>
            
            <!-- Table-based Request Summary Card for robust cross-client styling -->
            <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px; margin: 28px 0; font-family: inherit;">
                <tr>
                    <td colspan="2" style="padding-bottom: 16px; font-size: 14px; font-weight: 800; text-transform: uppercase; color: #0055ff; letter-spacing: 0.05em;">Request Summary</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #f1f5f9; font-weight: 700; color: #64748b; font-size: 15px; width: 120px; vertical-align: top;">Project:</td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #f1f5f9; color: #1e293b; font-size: 15px; vertical-align: top;">{{ $lead->project_type ?? 'Software Consultancy' }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #f1f5f9; font-weight: 700; color: #64748b; font-size: 15px; vertical-align: top;">Timeline:</td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #f1f5f9; color: #1e293b; font-size: 15px; vertical-align: top;">{{ $lead->timeline ?? 'Flexible' }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: 700; color: #64748b; font-size: 15px; vertical-align: top;">Budget:</td>
                    <td style="padding: 8px 0; color: #1e293b; font-size: 15px; vertical-align: top;">{{ $lead->budget ?? 'Flexible' }}</td>
                </tr>
            </table>

            <p style="margin: 0 0 18px; color: #4b5563;">Our solutions architects are currently reviewing your details. One of our engineers will reach out to you directly via this email address within 24 business hours to discuss your transformation needs.</p>
            <p style="margin: 0; color: #4b5563;">We look forward to partnering with you on your system transformation!</p>
        </div>
        
        <!-- Footer -->
        <div style="background-color: #f9fafb; padding: 32px; text-align: center; border-top: 1px solid #f3f4f6; font-size: 14px; color: #6b7280;">
            <p style="margin: 0 0 8px;">&copy; {{ date('Y') }} ClimbSphere. All rights reserved.</p>
            <p style="margin: 0 0 16px;">Need to add more details? Reply directly to this email or contact us at <a href="mailto:{{ config('mail.admin_recipient', 'sales@climbsphere.ai') }}" style="color: #0055ff; text-decoration: none; font-weight: 600;">{{ config('mail.admin_recipient', 'sales@climbsphere.ai') }}</a>.</p>
            <div style="margin-top: 16px;">
                <a href="https://www.linkedin.com/company/climbsphere-technologies/" style="color: #9ca3af; text-decoration: none; margin: 0 8px; font-size: 13px;">LinkedIn</a> &bull;
                <a href="https://twitter.com/climbsphere" style="color: #9ca3af; text-decoration: none; margin: 0 8px; font-size: 13px;">Twitter</a>
            </div>
        </div>
    </div>
</body>
</html>
