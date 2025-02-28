<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WipZiptape extends Model
{
    use HasFactory;

    // กำหนดชื่อของตาราง
    protected $table = 'wip_ziptape';

    // กำหนด Primary Key
    protected $primaryKey = 'wz_id';

    // เปิดใช้งาน timestamps
    public $timestamps = true;

    // กำหนดฟิลด์ที่อนุญาตให้ Mass Assignment ได้
    protected $fillable = [
        'wz_line',
        'wz_worktime_id',
        'wz_amount'
    ];
}
