<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaint Received</title>
</head>
<body style="margin:0; padding:0; background:#f4f4f4; font-family:Arial, Helvetica, sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f4; padding:40px 0;">
    <tr>
        <td align="center">

            <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:8px; overflow:hidden;">

                <!-- Header -->
                <tr>
                    <td style="background:#0d6efd; color:#ffffff; padding:25px; text-align:center;">
                        <h2 style="margin:0;">Complaint Received</h2>
                    </td>
                </tr>

                <!-- Body -->
                <tr>
                    <td style="padding:30px; color:#333333; font-size:15px; line-height:1.7;">

                        <p>Hi <strong>{{ $complaint->name }}</strong>,</p>

                        <p>
                            Thank you for contacting us. We have successfully received your complaint.
                            A member of our team will review it and get back to you as soon as possible.
                        </p>

                        <table width="100%" cellpadding="10" cellspacing="0" style="background:#f8f9fa; border:1px solid #dddddd; border-radius:6px;">
                            <tr>
                                <td>
                                    <strong>Tracking Number</strong><br>
                                    {{ $complaint->ticket_number }}
                                </td>
                            </tr>
                        </table>

                        <br>

                        <p>
                            <strong>Subject</strong><br>
                            {{ $complaint->subject }}
                        </p>

                        <p>
                            <strong>Description</strong><br>
                            {{ $complaint->description }}
                        </p>

                        <p>
                            Please keep your tracking number safe. You will need it together with your email address to check the status of your complaint.
                        </p>

                        <div style="text-align:center; margin:35px 0;">
                            <a href="{{ route('complaints.public.track.form') }}"
                               style="background:#0d6efd; color:#ffffff; text-decoration:none; padding:14px 28px; border-radius:5px; display:inline-block;">
                                Track Your Complaint
                            </a>
                        </div>

                        <p>
                            Thank you,<br>
                            <strong>{{ config('app.name') }}</strong>
                        </p>

                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td style="background:#f8f9fa; padding:20px; text-align:center; font-size:12px; color:#777777;">
                        This is an automated email. Please do not reply directly to this message.
                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>

</body>
</html>