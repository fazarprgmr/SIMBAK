<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'parent_id',
        'kode_rekening',
        'uraian',
        'sub_uraian',
        'jumlah',
        'satuan',
        'harga',
        'total'
    ];

    public function parent()
    {
        return $this->belongsTo(Item::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Item::class, 'parent_id');
    }

    // Hitung total otomatis kalau punya anak
    public function getComputedTotalAttribute()
    {
        if ($this->children->count() > 0) {
            return $this->children->sum(fn($c) => $c->jumlah * $c->harga);
        }
        return $this->total ?? ($this->jumlah * $this->harga);
    }
}
