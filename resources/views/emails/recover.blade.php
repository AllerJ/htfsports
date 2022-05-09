<!DOCTYPE html>

<html>

<head>

	<title>HTFSports Password Recovery</title>

</head>

<body>

	<h1>Recovery Code</h1>

	<p>Code: {{ $user->remember_token }}</p>
	
<p>	Click here to change your password: https://api.htfsports.com/api/resetpassword/{!! $user->remember_token !!}</p>

</body>

</html>