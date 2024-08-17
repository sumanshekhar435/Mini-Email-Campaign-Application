<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campaign Email</title>
    <style>
        /* Inline Tailwind CSS (for email compatibility) */
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            background-color: #f3f4f6;
            color: #333;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 24px;
            color: #333;
        }
        .content {
            margin-bottom: 20px;
        }
        .footer {
            text-align: center;
            font-size: 14px;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Campaign Notification</h1>
        </div>
        <div class="content">
            <p>Hello {{ $contactName }},</p>
            <p>We are excited to inform you about our latest campaign: <strong>{{ $campaignName }}</strong>.</p>
            <p>Thank you for being a part of our community!</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Your Company. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
