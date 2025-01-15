<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Wipbarcode;
use App\Models\Listngall;

class AmountNg extends Model
{
    use HasFactory;

    // กำหนดชื่อตาราง
    protected $table = 'amount_ngs';

    // กำหนด Primary Key
    protected $primaryKey = 'amg_id';

    // กำหนดฟิลด์ที่สามารถเพิ่มข้อมูลได้
    protected $fillable = [
        'amg_wip_id',
        'amg_ng_id',
        'amg_amount',
    ];

    // ความสัมพันธ์กับ Model Wipbarcode
    public function wip()
    {
        return $this->belongsTo(Wipbarcode::class, 'amg_wip_id', 'wip_id');
    }

    // ความสัมพันธ์กับ Model Listngall
    public function listng()
    {
        return $this->belongsTo(Listngall::class, 'amg_ng_id', 'lng_id');
    }
}
