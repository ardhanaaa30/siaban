<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordResetRequest extends Model
{
    protected $fillable = [
        'email',
        'status',
        'admin_note',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }
}
