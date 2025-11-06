<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'stripe_session_id',
        'item_id',
        'buyer_id',
        'payment_method',
        'shipping_postcode',
        'shipping_address',
        'shipping_building',
        'status',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function chat()
    {
        return $this->hasOne(Chat::class);
    }

    public function rating()
    {
        return $this->hasOne(Rating::class);
    }
}
