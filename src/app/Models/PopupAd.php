<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class PopupAd extends Model
{
    use HasFactory;
    protected $fillable=['title','image','url','is_active'];
    protected $casts=['is_active'=>'boolean'];
}
