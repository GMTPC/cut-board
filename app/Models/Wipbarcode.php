<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\WipProductDate;
use App\Models\WorkProcessQC;

class Wipbarcode extends Model
{
    use HasFactory;

    protected $table = 'wipbarcodes';  // กำหนดชื่อตาราง
    protected $primaryKey = 'wip_id';  // ✅ กำหนด Primary Key ให้ถูกต้อง

    public $timestamps = true;  // ถ้ามี created_at และ updated_at

    protected $fillable = [
        'wip_barcode',
        'wip_amount',
        'wip_working_id',
        'wip_empgroup_id',
        'wip_sku_name',
        'wip_index',
    ];

    public function wipProductDate()
    {
        return $this->hasOne(WipProductDate::class, 'wp_wip_id', 'wip_id');
    }

    public function groupEmp()
    {
        return $this->belongsTo(GroupEmp::class, 'wip_empgroup_id', 'id');
    }

    public function workProcessQC()
    {
        return $this->belongsTo(WorkProcessQC::class, 'wip_working_id', 'id');
    }
}


