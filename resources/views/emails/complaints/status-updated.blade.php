<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .logo {
            max-width: 150px;
            height: auto;
            margin-bottom: 15px;
        }
        .content {
            padding: 30px 20px;
            color: #333333;
        }
        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
            line-height: 1.6;
        }
        .card {
            background-color: #f9f9f9;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .card-row {
            margin: 12px 0;
            display: flex;
            justify-content: space-between;
        }
        .card-label {
            font-weight: 600;
            color: #555555;
            margin-right: 10px;
        }
        .card-value {
            color: #333333;
            word-break: break-word;
        }
        .status-message {
            margin: 20px 0;
            padding: 15px;
            border-radius: 4px;
            font-size: 14px;
            line-height: 1.6;
        }
        .status-resolved {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .status-in_progress {
            background-color: #cfe2ff;
            color: #084298;
            border: 1px solid #b6d4fe;
        }
        .status-closed {
            background-color: #f8d7da;
            color: #842029;
            border: 1px solid #f5c2c7;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 4px;
            margin: 20px 0;
            font-weight: 600;
            text-align: center;
        }
        .button:hover {
            opacity: 0.9;
        }
        .footer {
            background-color: #f5f5f5;
            padding: 20px;
            text-align: center;
            font-size: 13px;
            color: #666666;
            border-top: 1px solid #eeeeee;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="http://misc.tradesmartzm.com/logo.png" alt="Logo" class="logo">
            <h1 style="margin: 0; font-size: 24px;">Complaint Status Updated</h1>
        </div>

        <div class="content">
            <p class="greeting">Hi {{ $complaint->name }},</p>

            <p>The status of your complaint has changed.</p>

            <div class="card">
                <div class="card-row">
                    <span class="card-label">Ticket:</span>
                    <span class="card-value">{{ $complaint->ticket_number }}</span>
                </div>
                <div class="card-row">
                    <span class="card-label">Subject:</span>
                    <span class="card-value">{{ $complaint->subject }}</span>
                </div>
                <div class="card-row">
                    <span class="card-label">Previous Status:</span>
                    <span class="card-value">{{ ucfirst(str_replace('_', ' ', $previousStatus)) }}</span>
                </div>
                <div class="card-row">
                    <span class="card-label">New Status:</span>
                    <span class="card-value"><strong>{{ ucfirst(str_replace('_', ' ', $complaint->status)) }}</strong></span>
                </div>
            </div>

            @if ($complaint->status === 'resolved')
            <div class="status-message status-resolved">
                ✓ We consider this complaint resolved. If you feel it still needs attention, just let us know via the tracking page below.
            </div>
            @elseif ($complaint->status === 'in_progress')
            <div class="status-message status-in_progress">
                ⏳ Our team is actively working on this now.
            </div>
            @elseif ($complaint->status === 'closed')
            <div class="status-message status-closed">
                ✕ This complaint has been closed.
            </div>
            @endif

            <div style="text-align: center;">
                <a href="{{ route('complaints.public.track.form') }}" class="button">Track Your Complaint</a>
            </div>
        </div>

        <div class="footer">
            <p style="margin: 0;">Thanks,<br>{{ config('app.name') }}</p>
        </div>
    </div>
</body>
</html>
