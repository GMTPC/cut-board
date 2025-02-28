<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTypeEmp extends Model
{
    use HasFactory;

    protected $table = 'product_type_emps';

    protected $fillable = [
        'pe_working_id',
        'pe_type_code',
        'pe_type_name',
        'pe_index',
        'created_at',
        'updated_at',
    ];

    public function wipWorking()
    {
        return $this->belongsTo(WipWorking::class, 'pe_working_id', 'ww_id');
    }
}
