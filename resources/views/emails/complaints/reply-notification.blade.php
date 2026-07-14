<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>Update on Your Complaint</title>
</head>
<body style="margin:0;padding:20px;font-family:Arial,Helvetica,sans-serif;background:#f4f6f8;color:#333;">
	<table width="100%" cellpadding="0" cellspacing="0" role="presentation">
		<tr>
			<td align="center">
				<table width="600" cellpadding="0" cellspacing="0" role="presentation" style="background:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 2px 6px rgba(0,0,0,0.08);">
					<tr style="background:#ffffff;">
						<td style="padding:20px 24px;border-bottom:1px solid #eef0f2;">
							<table width="100%" cellpadding="0" cellspacing="0" role="presentation">
								<tr>
									<td style="vertical-align:middle">
										<img src="http://misc.tradesmartzm.com/logo.png" alt="{{ config('app.name') }} logo" style="height:42px;display:block;">
									</td>
									<td style="text-align:right;vertical-align:middle;color:#98a0a6;font-size:13px">
										Update on Your Complaint
									</td>
								</tr>
							</table>
						</td>
					</tr>

					<tr>
						<td style="padding:28px 32px;">
							<h2 style="margin:0 0 12px 0;font-size:20px;font-weight:600;color:#1f2d3d">Hi {{ $complaint->name }},</h2>
							<p style="margin:0 0 18px 0;color:#54606a;line-height:1.5">Our team has posted an update on your complaint. Below is a summary — click the button to view the full conversation.</p>

							<div style="background:#f7fbff;border:1px solid #e6f0fb;padding:14px;border-radius:6px;margin:18px 0;color:#1f2d3d">
								<strong style="display:block;margin-bottom:6px">Ticket: <span style="font-weight:600">{{ $complaint->ticket_number }}</span></strong>
								<span style="display:block;margin-bottom:6px">Subject: <span style="font-weight:600">{{ $complaint->subject }}</span></span>
							</div>

							<div style="background:#ffffff;border:1px solid #eef1f4;padding:16px;border-radius:6px;margin-bottom:22px;color:#333;line-height:1.5">
								<strong style="display:block;margin-bottom:8px">Message from our team:</strong>
								<div style="white-space:pre-wrap">{{ $reply->message }}</div>
							</div>

							<p style="text-align:center;margin:0 0 20px 0">
								<a href="{{ route('complaints.public.track.form') }}" style="display:inline-block;padding:12px 22px;background:#2563eb;color:#ffffff;text-decoration:none;border-radius:6px;font-weight:600">View Full Conversation</a>
							</p>

							<p style="margin:0;color:#6b7785;font-size:14px;line-height:1.5">If you have anything to add, just reply on your tracking page and our team will see it.</p>
						</td>
					</tr>

					<tr>
						<td style="padding:18px 24px;background:#fafbfd;border-top:1px solid #eef0f2;color:#8b96a0;font-size:13px;text-align:center">
							Thanks,<br>{{ config('app.name') }}
						</td>
					</tr>
				</table>
				<p style="color:#9aa4ad;font-size:12px;margin-top:12px">If you did not request this update, please ignore this email.</p>
			</td>
		</tr>
	</table>
</body>
</html>
