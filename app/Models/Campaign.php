<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'user_id',
        'status',
        'total_contacts',
        'processed_contacts',
        'sent_emails',
        'failed_emails',
        'in_progress_emails',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    public function emailLogs()
    {
        return $this->hasMany(CampaignEmailLog::class);
    }
}
