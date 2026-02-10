<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h2>Forgot your password?</h2>
    <p>We received a request to reset your password.</p>
    <p>Copy this token and paste it into the app to create a new password:</p>
    
    <div style="background: #f4f4f4; padding: 15px; text-align: center; border-radius: 5px;">
        <h1 style="letter-spacing: 2px; color: #333;">{{ $token }}</h1>
    </div>

    <p>If you did not request this, please ignore this email.</p>
</body>
</html>