<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wipbarcode extends Model
{
    use HasFactory;

    protected $table = 'wipbarcodes'; // ชื่อตาราง
    protected $primaryKey = 'wip_id'; // กำหนด Primary Key

    public $timestamps = true; // ใช้ timestamps (created_at, updated_at)

    protected $fillable = [
        'wip_barcode',
        'wip_amount',
        'wip_working_id',
        'wip_empgroup_id',
        'wip_sku_name',
        'wip_index',
    ];

    /**
     * ความสัมพันธ์แบบ One-to-One กับ WipProductDate
     */
    public function wipProductDate()
    {
        return $this->hasOne(WipProductDate::class, 'wp_wip_id', 'wip_id');
    }

    /**
     * ความสัมพันธ์แบบ BelongsTo กับ GroupEmp
     */
    public function groupEmp()
    {
        return $this->belongsTo(GroupEmp::class, 'wip_empgroup_id', 'id');
    }

    /**
     * ความสัมพันธ์แบบ BelongsTo กับ WorkProcessQC
     */
    public function workProcessQC()
    {
        return $this->belongsTo(WorkProcessQC::class, 'wip_working_id', 'id');
    }
}
