<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'name',
        'email',
        'status'
    ];

    // Relationships
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function emailLogs()
    {
        return $this->hasOne(CampaignEmailLog::class);
    }
}
