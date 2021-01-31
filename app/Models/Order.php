<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'user_id', 'customer_name', 'customer_mail', 'customer_phone', 'form_order', 'payment', 'delivery', 'created_at'];

    public function user()
    {
        return $this->belongsTo('App\Models\User')->withDefault();
    }
}
