<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WipWorking extends Model
{
    use HasFactory;

    // กำหนดชื่อตารางในฐานข้อมูล (หากชื่อไม่ตรงกับ `wip_workings`)
    protected $table = 'wip_workings'; // ชื่อตารางจริงในฐานข้อมูล

    // กำหนด Primary Key (ถ้าใช้ชื่ออื่นที่ไม่ใช่ `id`)
    protected $primaryKey = 'ww_id';

    // หากตารางไม่มี `created_at` และ `updated_at`
    public $timestamps = false;

    // กำหนดฟิลด์ที่สามารถกรอกข้อมูลได้
    protected $fillable = [
        'ww_id',
        'ww_line',
        // เพิ่มคอลัมน์อื่น ๆ หากจำเป็น
    ];
}
