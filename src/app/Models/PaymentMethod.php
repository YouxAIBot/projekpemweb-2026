<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class PaymentMethod extends Model
{
    use HasFactory;
    protected $fillable=['name','code','category','logo','fee_type','fee_value','is_active','sort_order','instructions'];
    protected $casts=['is_active'=>'boolean','fee_value'=>'integer'];
}
