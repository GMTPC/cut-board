<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WipProductDate extends Model
{
    use HasFactory;

    // ระบุฟิลด์ที่สามารถทำ mass assignment ได้
    protected $fillable = [
        'wp_working_id',
        'wp_wip_id',
        'wp_empdate_index_id',
        'wp_date_product',
        'wp_empgroup_id',
    ];

    public function wipBarcode()
    {
        return $this->belongsTo(Wipbarcode::class, 'wp_wip_id', 'wip_id');
    }
}
