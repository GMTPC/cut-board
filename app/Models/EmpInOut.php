<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\WorkProcessQC;
use App\Models\Wipbarcode;

class EmpInOut extends Model
{
    use HasFactory;

    // ชื่อตารางในฐานข้อมูล
    protected $table = 'emp_in_outs';

    // Primary Key ของตาราง
    protected $primaryKey = 'eio_id';

    // ปิด timestamps หากไม่มีในตาราง
    public $timestamps = false;

    // กำหนดฟิลด์ที่สามารถกรอกข้อมูลได้
    protected $fillable = [
        'eio_emp_group',
        'eio_working_id',
        'eio_input_amount',
        'eio_line',
        'eio_division',
        'eio_output_amount', // เพิ่มฟิลด์ที่อยู่ในตาราง
    ];

    // ความสัมพันธ์ระหว่าง EmpInOut กับ Wipbarcode
    public function wipBarcode()
    {
        return $this->belongsTo(Wipbarcode::class, 'eio_working_id', 'wip_working_id');
    }

    // ความสัมพันธ์ระหว่าง EmpInOut กับ WorkProcessQC
    public function workProcessQC()
    {
        return $this->belongsTo(WorkProcessQC::class, 'eio_working_id', 'id');
    }
}
