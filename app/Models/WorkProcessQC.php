<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Wipbarcode;

class WorkProcessQC extends Model
{
    use HasFactory;

    protected $table = 'workprocess_qc';
    protected $primaryKey = 'id';

    protected $fillable = ['line', 'group', 'date', 'status', 'status_qc'];

    // เชื่อมกับ Wipbarcode
    public function wipBarcodes()
    {
        // ลบเงื่อนไขเกี่ยวกับ status
        return $this->hasMany(Wipbarcode::class, 'wip_working_id', 'id');
    }
    
}
