<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Mail\CampaignStatusUpdate;
use Illuminate\Support\Facades\Mail;

class Campaign extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'target_amount',
        'current_amount',
        'start_date',
        'end_date',
        'status',
        'category',
        'image_url',
        'approved_by',
        'rejection_reason'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'target_amount' => 'decimal:2',
        'current_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function approve(User $approver, ?string $reason = null)
    {
        $this->update([
            'status' => 'approved',
            'approved_by' => $approver->id,
            'rejection_reason' => null
        ]);

        Mail::to($this->user)->send(new CampaignStatusUpdate($this));

        return $this;
    }

    public function reject(User $approver, string $reason)
    {
        $this->update([
            'status' => 'rejected',
            'approved_by' => $approver->id,
            'rejection_reason' => $reason
        ]);

        Mail::to($this->user)->send(new CampaignStatusUpdate($this));

        return $this;
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function getProgressPercentageAttribute()
    {
        if ($this->target_amount <= 0) {
            return 0;
        }

        return min(100, round(($this->current_amount / $this->target_amount) * 100, 2));
    }

    public function getDaysRemainingAttribute()
    {
        return max(0, now()->diffInDays($this->end_date, false));
    }

    public function getIsActiveAttribute()
    {
        return $this->status === 'active' && $this->end_date->isFuture();
    }
} 