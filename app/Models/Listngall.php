<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listngall extends Model
{
    use HasFactory;

    // ✅ กำหนดชื่อตาราง
    protected $table = 'listngalls'; // เพิ่ม s ให้ตรงกับในฐานข้อมูล

    // ✅ กำหนด Primary Key
    protected $primaryKey = 'lng_id';

    // ✅ กำหนดฟิลด์ที่สามารถบันทึกข้อมูลได้
    protected $fillable = [
        'lng_name',
        'lng_status',
    ];
}
