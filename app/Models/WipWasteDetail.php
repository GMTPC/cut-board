<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WipWasteDetail extends Model
{
    use HasFactory;

    // กำหนดชื่อของตาราง
    protected $table = 'wip_waste_detail';

    // กำหนด Primary Key
    protected $primaryKey = 'wwd_id';

    // เปิดใช้งาน timestamps
    public $timestamps = true;

    // กำหนดฟิลด์ที่อนุญาตให้ทำ Mass Assignment
    protected $fillable = [
        'wwd_line',
        'wwd_index',
        'wwd_barcode',
        'wwd_lot',
        'wwd_amount',
        'wwd_date'
    ];
}
