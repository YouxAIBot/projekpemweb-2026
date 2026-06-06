<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;
    protected $fillable=['invoice_number','order_id','user_id','game_id','product_id','payment_method_id','customer_name','whatsapp','topup_type','user_identifier','server','game_email','game_password','subtotal','fee','discount','total','status','payment_reference','payment_url','qr_code','notes','paid_at','completed_at'];
    protected $casts=['subtotal'=>'integer','fee'=>'integer','discount'=>'integer','total'=>'integer','paid_at'=>'datetime','completed_at'=>'datetime'];
    public const STATUS_UNPAID='unpaid';
    public const STATUS_PROCESSING='processing';
    public const STATUS_SUCCESS='success';
    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            $order->order_id ??= 'YOUX'.now()->format('YmdHis').strtoupper(Str::random(4));
            $order->invoice_number ??= 'INV'.now()->format('Ymd').strtoupper(Str::random(8));
        });
    }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function game(): BelongsTo { return $this->belongsTo(Game::class); }
    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
    public function paymentMethod(): BelongsTo { return $this->belongsTo(PaymentMethod::class); }
}
