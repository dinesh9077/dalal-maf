<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorKYC extends Model
{
    use HasFactory;

    protected $table = 'vendor_kycs';

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    public function user()
    {
        return $this->belongsTo('User', 'user_id');
    }
}
