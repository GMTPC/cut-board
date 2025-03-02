<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WipColordate1 extends Model
{
    use HasFactory;

    protected $table = 'wip_colordate'; // กำหนดชื่อตาราง
    protected $primaryKey = 'wcd_id'; // กำหนด Primary Key
    public $timestamps = true; // ใช้งาน created_at, updated_at

    protected $fillable = [
        'wcd_color',
        'wcd_month_no',
        'wcd_remark',
        'wcd_date',
        'created_at',
        'updated_at'
    ];

    /**
     * ฟังก์ชันค้นหาสีของเดือน
     */
    public static function getcolor($monthno) {
        return self::where('wcd_month_no', $monthno)->value('wcd_color') ?? 'gray';
    }
}
