<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupQC extends Model
{
    use HasFactory;

    // ระบุชื่อของตารางที่เชื่อมโยง
    protected $table = 'groupQC';

    // ระบุคอลัมน์ที่สามารถบันทึกลงฐานข้อมูลได้
    protected $fillable = [
        'group',    // ชื่อกลุ่ม
        'line',     // ไลน์งาน
    ];
}
