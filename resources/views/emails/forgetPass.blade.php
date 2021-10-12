<!DOCTYPE html>
<html>
<head>
 <title>Reset Password Link</title>
</head>
<body>
 
 <p>Hi,</br> Please use below link to reset your Password.</p>
 <p>http://dev.bccicms.epicon.in/resetpassword?email={{ $details['to'] }}</p>
 <p>Please use this Otp for reset password {{ $details['otp'] }}</p>
 
</body>
</html> 