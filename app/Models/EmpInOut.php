<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\WorkProcessQC;
use App\Models\Wipbarcode;

class EmpInOut extends Model
{
    use HasFactory;
    public function wipBarcode()
{
    return $this->belongsTo(Wipbarcode::class, 'eio_working_id', 'wip_working_id');
}
public function workprocess()
{
    return $this->belongsTo(WorkprocessQC::class, 'eio_working_id', 'id');
}
protected $fillable = [
    'eio_emp_group',
    'eio_working_id',
    'eio_input_amount',
    'eio_line',
    'eio_division',
];
}
