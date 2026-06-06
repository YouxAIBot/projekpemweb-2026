<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Game extends Model
{
    use HasFactory;
    protected $fillable = ['name','slug','short_description','description','image','banner_image','type','is_popular','is_active','sort_order'];
    protected $casts = ['is_popular'=>'boolean','is_active'=>'boolean'];
    public function products(): HasMany { return $this->hasMany(Product::class)->orderBy('price'); }
    public function activeProducts(): HasMany { return $this->products()->where('is_active', true); }
}
