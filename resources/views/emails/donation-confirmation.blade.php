<!DOCTYPE html>
<html>
<head>
    <title>Donation Confirmation</title>
</head>
<body>
    <h2>Thank You for Your Donation!</h2>
    <p>Dear {{ $donation->user->name }},</p>
    
    <p>Thank you for your generous donation to the campaign "{{ $donation->campaign->title }}".</p>
    
    <div style="background-color: #f8f9fa; padding: 20px; margin: 20px 0; border-radius: 5px;">
        <h3>Donation Details:</h3>
        <p><strong>Amount:</strong> ${{ number_format($donation->amount, 2) }}</p>
        <p><strong>Date:</strong> {{ $donation->created_at->format('F j, Y') }}</p>
        <p><strong>Campaign:</strong> {{ $donation->campaign->title }}</p>
        @if($donation->message)
            <p><strong>Your Message:</strong> {{ $donation->message }}</p>
        @endif
    </div>

    <p>Your contribution makes a real difference. Thank you for being part of our community's positive impact!</p>
    
    <p>Best regards,<br>ACME CSR Platform Team</p>
</body>
</html> 