<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>New Complaint Submitted</title>
</head>
<body>
    <h1>New Complaint Submitted</h1>
    <div style="border:1px solid #ddd; padding:15px; margin-bottom:20px;">
        <p><strong>Ticket:</strong> #{{ $complaint->ticket_number }}</p>
        <p><strong>Priority:</strong> {{ ucfirst($complaint->priority) }}</p>
    </div>
    <p><strong>From:</strong> {{ $complaint->name }} ({{ $complaint->email }})</p>
    @if ($complaint->phone)
        <p><strong>Phone:</strong> {{ $complaint->phone }}</p>
    @endif
    @if ($complaint->client)
        <p><strong>Linked client:</strong> {{ $complaint->client->name }}</p>
    @endif
    <p><strong>Subject:</strong> {{ $complaint->subject }}</p>
    <p><strong>Details:</strong></p>
    <p>{{ $complaint->description }}</p>
    <p>
        <a href="{{ route('complaints.show', $complaint) }}" style="display:inline-block;padding:10px 15px;background:#3490dc;color:#ffffff;text-decoration:none;border-radius:4px;">View & Respond</a>
    </p>
    <p>{{ config('app.name') }}</p>
</body>
</html>
