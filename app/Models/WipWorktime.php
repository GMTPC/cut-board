<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WipWorktime extends Model
{
    protected $table = 'wip_worktimes';
    protected $primaryKey = 'wwt_id';
    public $timestamps = true;

    protected $fillable = [
        'wwt_index',
        'wwt_status',
        'wwt_line',
        'wwt_date',
        'created_at',
        'updated_at',
    ];

    // 🔹 เชื่อมกับ WipWasteDetail (หนึ่ง WipWorktime มีหลาย WipWasteDetail)
    public function wasteDetails()
    {
        return $this->hasMany(WipWasteDetail::class, 'wwt_id', 'wwt_id');
    }
}
