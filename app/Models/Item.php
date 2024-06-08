<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $fillable = ['ItemName', 'TypeID'];

    public function type()
    {
        return $this->belongsTo(Type::class, 'TypeID');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'ItemID');
    }
   
}
