<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkProcessQC extends Model
{
    use HasFactory;

    protected $table = 'workprocess_qc'; // ชื่อตาราง
    protected $fillable = ['line', 'group', 'date', 'status']; // ฟิลด์ที่อนุญาตให้แก้ไขได้
}
