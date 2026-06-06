<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class SupportTicket extends Model
{
    use HasFactory;
    protected $fillable=['ticket_number','topic','type','name','whatsapp','email','message','status','admin_note'];
    protected static function booted(): void
    { static::creating(fn (SupportTicket $t) => $t->ticket_number ??= 'TICKET'.now()->format('YmdHis').random_int(100,999)); }
}
