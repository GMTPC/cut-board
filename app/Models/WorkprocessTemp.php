<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkprocessTemp extends Model
{
    use HasFactory;

    protected $table = 'workprocess_temps'; // ชื่อตารางที่ถูกต้อง

    protected $fillable = [
        'workprocess_id',
        'line',
        'wwt_id',
    ];

    // 🔹 ความสัมพันธ์กับ WorkProcessQC
    public function workprocess()
    {
        return $this->belongsTo(WorkProcessQC::class, 'workprocess_id', 'id');
    }
}

