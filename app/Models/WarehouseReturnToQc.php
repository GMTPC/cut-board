<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class WarehouseReturnToQc extends Model
{
    use HasFactory;

    // กำหนดชื่อตารางในฐานข้อมูล
    protected $table = 'warehouse_return_to_qc';

    // กำหนด Primary Key
    protected $primaryKey = 'wrtc_id';

    // ใช้ timestamps (created_at, updated_at)
    public $timestamps = true;

    // กำหนดฟิลด์ที่สามารถกรอกข้อมูลได้
    protected $fillable = [
        'wrtc_barcode',
        'wrtc_description',
        'wrtc_remark',
        'wrtc_date',
        'created_at',
        'updated_at'
    ];

    // Mutator: กำหนดค่า `wrtc_date` เป็นวันที่ปัจจุบันอัตโนมัติหากไม่ได้ส่งค่า
    public function setWrtcDateAttribute($value)
    {
        $this->attributes['wrtc_date'] = $value ?? Carbon::now();
    }
}
