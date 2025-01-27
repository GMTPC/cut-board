<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionColor extends Model
{
    use HasFactory;

    // ระบุชื่อของตาราง
    protected $table = 'production_colors';

    // ระบุ Primary Key (ถ้าไม่ใช่ id)
    protected $primaryKey = 'pcs_id';

    // ถ้าตารางไม่มีฟิลด์ timestamps เช่น created_at, updated_at ให้ตั้ง false
    public $timestamps = true;

    // ระบุฟิลด์ที่สามารถเพิ่มข้อมูลได้ (fillable)
    protected $fillable = [
        'pcs_id',
        'pcs_color',
        'pcs_remark',
        'pcs_date',
        'created_at',
        'updated_at',
    ];

    // ระบุฟิลด์ที่ไม่ต้องการให้แก้ไข (guarded) ถ้ามี
    // protected $guarded = [];
}
