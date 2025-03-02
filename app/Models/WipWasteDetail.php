<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WipWasteDetail extends Model
{
    use HasFactory;

    protected $table = 'wip_waste_detail';
    protected $primaryKey = 'wwd_id';
    public $timestamps = true;

    protected $fillable = [
        'wwd_line',
        'wwd_index',
        'wwd_barcode',
        'wwd_lot',
        'wwd_amount',
        'wwd_date',
        'wwt_id', // ✅ เพิ่ม wwt_id ให้สามารถใช้ Mass Assignment ได้
    ];

    // 🔹 เชื่อมกับ WipWorktime (แต่ละ WipWasteDetail มี WipWorktime หนึ่งรายการ)
    public function worktime()
    {
        return $this->belongsTo(WipWorktime::class, 'wwt_id', 'wwt_id');
    }
}
