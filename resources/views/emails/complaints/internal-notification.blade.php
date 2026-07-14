<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>New Complaint Submitted</title>
</head>
<body style="margin:0;padding:20px;background:#f4f6f8;font-family:Arial,Helvetica,sans-serif;color:#333;">
	<table width="100%" cellpadding="0" cellspacing="0" role="presentation">
		<tr>
			<td align="center">
				<table width="600" cellpadding="0" cellspacing="0" role="presentation" style="background:#ffffff;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.08);overflow:hidden;">
					<tr>
						<td style="padding:20px 24px;border-bottom:1px solid #eef1f4;display:flex;align-items:center;">
							<img src="http://misc.tradesmartzm.com/logo.png" alt="Logo" style="height:40px;margin-right:12px;display:block;">
							<h2 style="margin:0;font-size:18px;color:#0b3b5a;">New Complaint Submitted</h2>
						</td>
					</tr>
					<tr>
						<td style="padding:20px 24px;">
							<div style="background:#f8fbfd;border:1px solid #eef6fa;padding:14px;border-radius:6px;margin-bottom:16px;">
								<strong style="display:block;margin-bottom:6px;color:#0b3b5a;">Ticket: <span style="font-weight:600;color:#111;">#{{ $complaint->ticket_number }}</span></strong>
								<span style="color:#666;">Priority: <strong>{{ ucfirst($complaint->priority) }}</strong></span>
							</div>

							<p style="margin:0 0 8px 0;"><strong>From:</strong> {{ $complaint->name }} &lt;{{ $complaint->email }}&gt;</p>
							@if ($complaint->phone)
							<p style="margin:0 0 8px 0;"><strong>Phone:</strong> {{ $complaint->phone }}</p>
							@endif
							@if ($complaint->client)
							<p style="margin:0 0 8px 0;"><strong>Linked client:</strong> {{ $complaint->client->name }}</p>
							@endif

							<p style="margin:12px 0 6px 0;"><strong>Subject:</strong> {{ $complaint->subject }}</p>

							<div style="margin:0 0 18px 0;padding:12px;background:#ffffff;border:1px solid #eef1f4;border-radius:6px;color:#444;">
								<strong style="display:block;margin-bottom:8px;color:#0b3b5a;">Details</strong>
								<div style="white-space:pre-wrap;line-height:1.5;font-size:14px;">{{ $complaint->description }}</div>
							</div>

							<p style="text-align:center;margin:0 0 18px 0;">
								<a href="{{ route('complaints.show', $complaint) }}" style="background:#0b3b5a;color:#ffffff;padding:10px 18px;border-radius:6px;text-decoration:none;display:inline-block;font-weight:600;">View &amp; Respond</a>
							</p>

							<p style="margin:0;color:#8a98a6;font-size:13px;">&copy; {{ date('Y') }} {{ config('app.name') }}</p>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</body>
</html>
