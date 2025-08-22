<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site Under Maintenance</title>
    <style>
        body {
            text-align: center;
            padding: 50px;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: #f8f8f8;
            padding: 20px;
            border-radius: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>🚧 We're In Maintenance Mode 🚧</h1>
        <p>{!! $message ?? 'The website is currently undergoing maintenance. Please check back later.' !!}</p>
    </div>
</body>

</html>
