<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Mail\DonationConfirmation;
use Illuminate\Support\Facades\Mail;

class Donation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'campaign_id',
        'amount',
        'status',
        'payment_method',
        'transaction_id',
        'message',
        'is_anonymous',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_anonymous' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function getDonorNameAttribute()
    {
        if ($this->is_anonymous) {
            return 'Anonymous';
        }

        return $this->user->name;
    }

    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2);
    }

    protected static function booted()
    {
        static::created(function ($donation) {
            // Send confirmation email
            Mail::to($donation->user)->send(new DonationConfirmation($donation));

            // Update campaign current amount
            $donation->campaign->increment('current_amount', $donation->amount);
        });
    }
} 