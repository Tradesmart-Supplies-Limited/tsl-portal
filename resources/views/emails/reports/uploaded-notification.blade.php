<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>New Report Available</title>
	<style>
		body { background-color:#f4f6f8; margin:0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; }
		.email-wrapper { width:100%; padding:24px 0; }
		.email-center { max-width:600px; margin:0 auto; }
		.card { background:#ffffff; border-radius:8px; box-shadow:0 2px 6px rgba(0,0,0,0.06); overflow:hidden; }
		.header { padding:20px; display:flex; align-items:center; gap:12px; border-bottom:1px solid #ececec; }
		.logo { height:40px; }
		.content { padding:24px; color:#111827; }
		.title { font-size:20px; margin:0 0 8px 0; }
		.muted { color:#6b7280; font-size:14px; }
		.panel { background:#f8fafc; border:1px solid #eef2f7; padding:16px; border-radius:6px; margin:16px 0; }
		.meta { font-weight:600; margin:0 0 6px 0; }
		.description { margin:0 0 18px 0; white-space:pre-wrap; }
		.button { display:inline-block; background:#1f6feb; color:#fff; text-decoration:none; padding:12px 18px; border-radius:6px; font-weight:600; }
		.footer { padding:16px; text-align:center; color:#9ca3af; font-size:13px; }
		@media (max-width:480px){ .header { padding:16px } .content { padding:16px } }
	</style>
</head>
<body>
	<div class="email-wrapper">
		<div class="email-center">
			<div class="card">
				<div class="header">
					<img src="http://misc.tradesmartzm.com/logo.png" alt="Logo" class="logo">
					<div>
						<div style="font-weight:700;">{{ config('app.name') }}</div>
						<div class="muted">Automated Report Notification</div>
					</div>
				</div>
				<div class="content">
					<h1 class="title">New Report Available</h1>
					<p class="muted">A new report has been uploaded for <strong>{{ $report->client->name }}</strong>.</p>

					<div class="panel">
						<div class="meta">Title</div>
						<div>{{ $report->title }}</div>
						<div style="height:8px"></div>
						<div class="meta">Uploaded by</div>
						<div>{{ $report->uploader->name ?? 'System' }}</div>
					</div>

					@if ($report->description)
						<div class="description">{{ $report->description }}</div>
					@endif

					<a href="{{ route('reports.show', $report) }}" class="button">View Report</a>
				</div>
				<div class="footer">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</div>
			</div>
		</div>
	</div>
</body>
</html>
