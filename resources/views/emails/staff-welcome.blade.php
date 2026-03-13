<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 8px; overflow: hidden; }
        .header { background: #1d4ed8; padding: 24px; text-align: center; }
        .header h1 { color: white; margin: 0; font-size: 24px; }
        .header p { color: #bfdbfe; margin: 6px 0 0; font-size: 14px; }
        .body { padding: 32px; }
        .body h2 { color: #1e293b; }
        .body p { color: #475569; line-height: 1.6; }
        .role-badge { display: inline-block; background: #1d4ed8; color: white; padding: 4px 14px; border-radius: 20px; font-size: 13px; font-weight: bold; text-transform: uppercase; margin-bottom: 16px; }
        .credentials { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 16px; margin: 20px 0; }
        .credentials p { margin: 6px 0; color: #1e293b; }
        .highlight { background: #eff6ff; border-left: 4px solid #1d4ed8; padding: 12px 16px; border-radius: 4px; margin: 16px 0; color: #1e40af; font-size: 14px; }
        .btn { display: inline-block; background: #1d4ed8; color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: bold; margin-top: 16px; }
        .footer { background: #f8fafc; padding: 16px; text-align: center; font-size: 12px; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Employee Management System</h1>
            <p>You have been selected to join the EMS team</p>
        </div>
        <div class="body">
            <h2>Congratulations, {{ $firstName }}! 🎉</h2>

            <span class="role-badge">{{ $role }}</span>

            @if($role === 'hr')
                <p>We are pleased to inform you that you have <strong>passed all the requirements</strong> and have been selected to manage the Employee Management System as a <strong>Human Resources (HR) Officer</strong>.</p>
                <div class="highlight">
                    As an HR Officer, you will be responsible for managing employee attendance, leave requests, salary records, and overall workforce management.
                </div>
            @else
                <p>We are pleased to inform you that you have been <strong>selected and trusted</strong> to manage the Employee Management System as an <strong>Administrator</strong>.</p>
                <div class="highlight">
                    As an Administrator, you have full access to manage employees, departments, leaves, and system settings.
                </div>
            @endif

            <p>Here are your login credentials:</p>
            <div class="credentials">
                <p><strong>Email:</strong> {{ $email }}</p>
                <p><strong>Password:</strong> {{ $email }} (your email is your default password)</p>
                <p><strong>Role:</strong> {{ ucfirst($role) }}</p>
            </div>
            <p>Please log in immediately and change your password to keep your account secure.</p>
            <a href="{{ url('/') }}" class="btn">Access EMS System</a>
        </div>
        <div class="footer">
            <p>This is an automated message from EMS. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>