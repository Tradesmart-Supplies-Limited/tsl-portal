<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background-color: #f8f9fa;
            padding: 30px;
            text-align: center;
            border-bottom: 1px solid #e9ecef;
        }
        .logo {
            max-width: 150px;
            height: auto;
            margin-bottom: 20px;
        }
        .content {
            padding: 30px;
        }
        .title {
            color: #333333;
            font-size: 24px;
            font-weight: 600;
            margin: 0 0 20px 0;
        }
        .greeting {
            color: #555555;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        .info-card {
            background-color: #f8f9fa;
            border-left: 4px solid #007bff;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .info-label {
            color: #666666;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        .info-value {
            color: #333333;
            font-size: 18px;
            font-weight: 600;
        }
        .detail-section {
            margin: 20px 0;
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 15px;
        }
        .detail-section:last-child {
            border-bottom: none;
        }
        .detail-label {
            color: #666666;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
        }
        .detail-value {
            color: #555555;
            font-size: 14px;
            line-height: 1.6;
            white-space: pre-wrap;
        }
        .note {
            background-color: #fffbea;
            border: 1px solid #ffe8a8;
            padding: 15px;
            border-radius: 4px;
            color: #856404;
            font-size: 14px;
            line-height: 1.6;
            margin: 20px 0;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .button {
            background-color: #007bff;
            color: #ffffff;
            padding: 12px 30px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            display: inline-block;
            transition: background-color 0.2s;
        }
        .button:hover {
            background-color: #0056b3;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid #e9ecef;
            color: #666666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="http://misc.tradesmartzm.com/logo.png" alt="{{ config('app.name') }} Logo" class="logo">
        </div>
        
        <div class="content">
            <h1 class="title">Complaint Received</h1>
            
            <p class="greeting">Hi {{ $complaint->name }},</p>
            
            <p class="greeting">Thanks for reaching out. We've logged your complaint and a member of our team will follow up shortly.</p>
            
            <div class="info-card">
                <div class="info-label">Tracking Number</div>
                <div class="info-value">{{ $complaint->ticket_number }}</div>
            </div>
            
            <div class="detail-section">
                <div class="detail-label">Subject</div>
                <div class="detail-value">{{ $complaint->subject }}</div>
            </div>
            
            <div class="detail-section">
                <div class="detail-label">What You Told Us</div>
                <div class="detail-value">{{ $complaint->description }}</div>
            </div>
            
            <div class="note">
                Keep your tracking number handy — you'll need it (along with this email address) to check the status at any time.
            </div>
            
            <div class="button-container">
                <a href="{{ route('complaints.public.track.form') }}" class="button">Track Your Complaint</a>
            </div>
        </div>
        
        <div class="footer">
            <p>{{ config('app.name') }}</p>
        </div>
    </div>
</body>
</html>
