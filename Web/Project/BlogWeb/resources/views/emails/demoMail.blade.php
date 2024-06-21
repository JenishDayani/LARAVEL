<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <meta http-equiv="X-UA-Compatible" content="ie=edge">
 <title>Blogs</title>
</head>
<body>
 
 <h1>{{$mailData['title']}}</h1>
 <p>{{$mailData['body']}}</p>
 <p>For Login please click this link http://localhost:8000/login  follwing are the email and password to login</p>
 <p>Email :- {{$mailData['email']}}</p>
 <p>Password :- {{$mailData['password']}}</p>
 <p>Thank You</p>
</body>
</html>