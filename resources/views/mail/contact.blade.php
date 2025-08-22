<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Contact</title>
</head>

<body>
    <p>You have a contact query.</p> <br>
    <p>Name : {{ $mailData['name'] }}</p>
    <p>Email : {{ $mailData['email'] }}</p>
    <p>Subject : {{ $mailData['subject'] }}</p>
    <p>message : <br> {{ $mailData['message'] }}</p>
    <p>Time : {{ carbon\Carbon::now() }}</p>


</body>

</html>
