<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Report Available</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333;">
    <h1>New Report Available</h1>
    <p>A new report has been uploaded for <strong>{{ $report->client->name }}</strong>.</p>

    <div style="border: 1px solid #ddd; padding: 16px; background-color: #f9f9f9;">
        <p><strong>Title:</strong> {{ $report->title }}</p>
        <p><strong>Uploaded by:</strong> {{ $report->uploader->name ?? 'System' }}</p>
    </div>

    @if ($report->description)
        <p>{{ $report->description }}</p>
    @endif

    <p>
        <a href="{{ route('reports.show', $report) }}" style="display: inline-block; padding: 12px 24px; background-color: #3490dc; color: #ffffff; text-decoration: none; border-radius: 4px;">
            View Report
        </a>
    </p>

    <p>{{ config('app.name') }}</p>
</body>
</html>
