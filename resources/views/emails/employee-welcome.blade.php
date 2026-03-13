<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 8px; overflow: hidden; }
        .header { background: #16a34a; padding: 24px; text-align: center; }
        .header h1 { color: white; margin: 0; font-size: 24px; }
        .body { padding: 32px; }
        .body h2 { color: #1e293b; }
        .body p { color: #475569; line-height: 1.6; }
        .credentials { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 16px; margin: 20px 0; }
        .credentials p { margin: 6px 0; color: #1e293b; }
        .btn { display: inline-block; background: #16a34a; color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: bold; margin-top: 16px; }
        .footer { background: #f8fafc; padding: 16px; text-align: center; font-size: 12px; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome to EMS</h1>
        </div>
        <div class="body">
            <h2>Hello, {{ $firstName }}!</h2>
            <p>Your employee account has been successfully created in the <strong>Employee Management System</strong>.</p>
            <p>Here are your login credentials:</p>
            <div class="credentials">
                <p><strong>Email:</strong> {{ $email }}</p>
                <p><strong>Password:</strong> {{ $email }} (your email is your default password)</p>
                <p><strong>Role:</strong> {{ ucfirst($role) }}</p>
            </div>
            <p>Please log in and change your password immediately after your first login.</p>
            <a href="{{ url('/') }}" class="btn">Login to EMS</a>
        </div>
        <div class="footer">
            <p>This is an automated message from EMS. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>