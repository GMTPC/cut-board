<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BrandList;
use App\Models\EmpInOut;
use App\Models\WipWorking;

class Brand extends Model
{
    use HasFactory;

    // ชื่อของตารางในฐานข้อมูล
    protected $table = 'brands';

    // คีย์หลักของตาราง
    protected $primaryKey = 'brd_id';

    // เปิดใช้งาน timestamps (created_at, updated_at)
    public $timestamps = true;

    // ระบุคอลัมน์ที่สามารถบันทึกข้อมูลได้
    protected $fillable = [
        'brd_working_id',
        'brd_brandlist_id',
        'brd_backboard_no',
        'brd_eg_id',
        'brd_empdate_index_key',
        'brd_lot',
        'brd_amount',
        'brd_remark',
        'brd_checker',
        'brd_color',
        'brd_outfg_date',
        'brd_status',
        'brd_count',
    ];

    // ระบุคอลัมน์ที่ต้องการให้เป็น Carbon Instance (วันที่)
    protected $dates = [
        'brd_outfg_date',
        'created_at',
        'updated_at',
    ];

    /**
     * ความสัมพันธ์ระหว่าง Brand กับ BrandList
     * Brand มี brandlist_id เชื่อมกับ BrandList
     */
    public function brandList()
    {
        return $this->belongsTo(BrandList::class, 'brd_brandlist_id', 'bl_id');
    }

    /**
     * ความสัมพันธ์ระหว่าง Brand กับ EmpInOut
     * Brand มี eg_id เชื่อมกับ EmpInOut
     */
    public function empInOut()
    {
        return $this->belongsTo(EmpInOut::class, 'brd_eg_id', 'eio_id');
    }

    /**
     * ความสัมพันธ์ระหว่าง Brand กับ WipWorking
     * Brand มี working_id เชื่อมกับ WipWorking
     */
    public function wipWorking()
    {
        return $this->belongsTo(WipWorking::class, 'brd_working_id', 'ww_id');
    }
}
