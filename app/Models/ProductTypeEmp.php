<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Wipbarcode;

class ProductTypeEmp extends Model
{
    use HasFactory;

    protected $table = 'product_type_emps'; // ชื่อจริงของตารางในฐานข้อมูล

    protected $fillable = [
        'pe_working_id',
        'pe_type_code',
        'pe_type_name',
        'pe_index',
        'created_at',
        'updated_at',
    ];

    public function wipBarcode()
    {
        return $this->belongsTo(Wipbarcode::class, 'pe_working_id', 'wip_working_id');
    }
}

