<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandList extends Model
{
    use HasFactory;

    // กำหนดชื่อของตารางในฐานข้อมูล
    protected $table = 'brandlist';

    // กำหนดคีย์หลัก
    protected $primaryKey = 'bl_id';

    // กำหนดให้สามารถเพิ่มหรือแก้ไขคอลัมน์เหล่านี้ได้
    protected $fillable = [
        'bl_name',
        'bl_code',
        'bl_status',
        'created_at',
        'updated_at'
    ];

    // หากไม่ใช้ timestamps ให้ปิด
    public $timestamps = true;
}
