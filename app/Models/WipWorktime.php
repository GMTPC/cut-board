<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WipWorktime extends Model
{
    // ชื่อตาราง
    protected $table = 'wip_worktimes';

    // Primary Key
    protected $primaryKey = 'wwt_id';

    // ป้องกันฟิลด์ที่ไม่อนุญาตให้ Mass Assignment
    protected $guarded = [];

    // ฟิลด์ที่อนุญาตให้ทำ Mass Assignment
    protected $fillable = [
        'wwt_id',
        'wwt_index',
        'wwt_status',
        'wwt_line',
        'wwt_date',
        'created_at',
        'updated_at',
    ];

    // Timestamps (เปิดใช้งาน created_at, updated_at)
    public $timestamps = true;

    // กำหนดชื่อของ created_at และ updated_at หากใช้ชื่อที่ต่างจากค่าเริ่มต้น
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
}
