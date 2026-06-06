<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['game_id','name','description','price','points','is_active','sort_order'];
    protected $casts = ['is_active'=>'boolean','price'=>'integer','points'=>'integer'];
    public function game(): BelongsTo { return $this->belongsTo(Game::class); }
}
