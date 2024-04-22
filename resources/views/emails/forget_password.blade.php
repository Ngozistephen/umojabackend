<!DOCTYPE html>
<html>
<head>
 <title>Umoja </title>
</head>
<body style="max-width">
 
    <p>Hello!</p>

    <p>You are receiving this email because we received a password reset request for your account.</p>
    
    <p>Please click the following link to reset your password:</p>
    
    {{-- <a href="{{ $resetLink }}">{{ $resetLink }}</a> --}}
    <a href="{{ $resetLink }}" class="button">Reset Password</a>
    
    <p>If you did not request a password reset, no further action is required.</p>
    
    <p>Thank you!</p>
 
</body>
</html> 