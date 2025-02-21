<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckCsvWhIndex extends Model
{
    use HasFactory;

    protected $table = 'check_csv_wh_index'; // ชื่อตาราง

    protected $primaryKey = 'cswi_id'; // กำหนด Primary Key

    public $timestamps = true; // ใช้ timestamps (created_at, updated_at)

    protected $fillable = [
        'cswi_index',
        'cswi_ziptape',
        'created_at',
        'updated_at'
    ];

    /**
     * ความสัมพันธ์กับ `CheckCsvWh`
     * เชื่อมกับ `cswi_index`
     */
    public function checkCsvWhs()
    {
        return $this->hasMany(CheckCsvWh::class, 'ccw_index', 'cswi_index');
    }
}
