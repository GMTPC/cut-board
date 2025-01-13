<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Wipbarcode;
class WorkProcessQC extends Model
{
    use HasFactory;

    protected $table = 'workprocess_qc';
    protected $fillable = ['line', 'group', 'date', 'status'];

    // เชื่อมกับ Wipbarcode
    public function wipBarcodes()
    {
        return $this->hasMany(Wipbarcode::class, 'wip_working_id', 'id');
    }
}
