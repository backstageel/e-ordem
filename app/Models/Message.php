<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $guarded = false;

    protected $casts = [
        'read' => 'boolean',
        'read_at' => 'datetime',
    ];

    /**
     * Get the sender of the message.
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Get the recipient of the message.
     */
    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    /**
     * Mark the message as read.
     */
    public function markAsRead()
    {
        $this->read = true;
        $this->read_at = now();
        $this->save();

        return $this;
    }

    /**
     * Mark the message as unread.
     */
    public function markAsUnread()
    {
        $this->read = false;
        $this->read_at = null;
        $this->save();

        return $this;
    }
}
