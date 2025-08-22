<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Outbid Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            background: #007bff;
            color: #ffffff;
            padding: 10px;
            font-size: 20px;
            font-weight: bold;
            border-radius: 5px 5px 0 0;
        }

        .content {
            padding: 20px;
            text-align: center;
            color: #333;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            color: #ffffff;
            background: #007bff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }

        .footer {
            text-align: center;
            padding: 10px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">Outbid Notification</div>
        <div class="content">
            <p>Dear {{ $mailData['name'] }},</p>
            <p>Someone placed a higher bid..</p>
            <p>You have been outbid on the following product:</p>
            <h3>{{ $mailData['product'] }}</h3>
            <p>Current Highest Bid: <strong>${{ $mailData['curent_bid'] }}</strong></p>
            <p>Don't lose your chance! Place a higher bid to win the product.</p>
            <a href="{{ $mailData['url'] }}" class="button">Place a New Bid</a>
        </div>
        <div class="footer">
            &copy; 2025
        </div>
    </div>
</body>

</html>
