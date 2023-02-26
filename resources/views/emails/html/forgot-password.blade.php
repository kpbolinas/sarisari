<!DOCTYPE html>
<html>

<head>
    <title>Sari Sari Store - Forgot Password</title>
</head>

<body>
    <h2>Important Notice!</h2>
    <p>Your new and temporary account credentials are as follows:</p>
    <p>
        Email: {{ $data['email'] }}
        <br>
        Username: {{ $data['username'] }}
        <br>
        Password: {{ $data['temporary_password'] }}
    </p>
    <p>Please make sure to change your password after logging in for the better account security.</p>
    <p>Thank you!</p>
</body>

</html>