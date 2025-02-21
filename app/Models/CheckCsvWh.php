<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckCsvWh extends Model
{
    use HasFactory;

    protected $table = 'check_csv_wh'; // ชื่อตาราง

    protected $primaryKey = 'ccw_id'; // กำหนด Primary Key

    public $timestamps = true; // ใช้ timestamps (created_at, updated_at)

    protected $fillable = [
        'ccw_index',
        'ccw_barcode',
        'ccw_lot',
        'ccw_amount',
        'created_at',
        'updated_at'
    ];

    /**
     * ความสัมพันธ์กับ `CheckCsvWhIndex`
     * เชื่อมกับ `ccw_index`
     */
    public function index()
    {
        return $this->belongsTo(CheckCsvWhIndex::class, 'ccw_index', 'cswi_index');
    }

    /**
     * ความสัมพันธ์กับ `Brands`
     * ใช้ `ccw_lot` เชื่อมกับ `brd_lot`
     */
    public function brand()
    {
        return $this->hasOne(Brand::class, 'brd_lot', 'ccw_lot');
    }
}
