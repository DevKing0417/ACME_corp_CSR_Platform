<?php

namespace App\Mail;

use App\Models\Campaign;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CampaignStatusUpdate extends Mailable
{
    use Queueable, SerializesModels;

    public $campaign;

    public function __construct(Campaign $campaign)
    {
        $this->campaign = $campaign;
    }

    public function build()
    {
        $status = ucfirst($this->campaign->status);
        return $this->subject("Campaign {$status} - ACME CSR Platform")
                    ->view('emails.campaign-status');
    }
} 