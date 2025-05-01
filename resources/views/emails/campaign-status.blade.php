<!DOCTYPE html>
<html>
<head>
    <title>Campaign Status Update</title>
</head>
<body>
    <h2>Campaign Status Update</h2>
    <p>Dear {{ $campaign->user->name }},</p>
    
    <p>Your campaign "{{ $campaign->title }}" has been {{ $campaign->status }}.</p>
    
    @if($campaign->status === 'rejected')
        <div style="background-color: #fff3f3; padding: 20px; margin: 20px 0; border-radius: 5px; border-left: 4px solid #dc3545;">
            <h3>Rejection Reason:</h3>
            <p>{{ $campaign->rejection_reason }}</p>
        </div>
    @endif

    @if($campaign->status === 'approved')
        <div style="background-color: #f0fff4; padding: 20px; margin: 20px 0; border-radius: 5px; border-left: 4px solid #28a745;">
            <p>Your campaign is now live and accepting donations!</p>
        </div>
    @endif

    <p>You can view your campaign details by clicking the link below:</p>
    <p><a href="{{ route('campaigns.show', $campaign->id) }}">View Campaign</a></p>
    
    <p>Best regards,<br>ACME CSR Platform Team</p>
</body>
</html> 