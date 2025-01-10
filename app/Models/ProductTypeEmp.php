<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Wipbarcode;

class ProductTypeEmp extends Model
{
    use HasFactory;
    public function wipBarcode()
{
    return $this->belongsTo(Wipbarcode::class, 'pe_working_id', 'wip_working_id');
}
}