<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WipWorktime extends Model
{
    protected $table = 'wip_worktimes';

    protected $primaryKey = 'wwt_id';

    // เปิดใช้งาน timestamps และกำหนดชื่อฟิลด์ถ้าจำเป็น
    public $timestamps = true;

    // หากต้องการอนุญาตให้ Mass Assignment สำหรับฟิลด์ที่ระบุ
    protected $fillable = [
        'wwt_index',
        'wwt_status',
        'wwt_line',
        'wwt_date',
        'created_at',
        'updated_at',
    ];
}
