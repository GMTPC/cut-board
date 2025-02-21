<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandList extends Model
{
    use HasFactory;

    protected $table = 'brandlist'; // กำหนดชื่อตาราง

    protected $primaryKey = 'bl_id'; // กำหนด Primary Key

    public $timestamps = true; // ใช้ timestamps

    protected $fillable = [
        'bl_name',
        'bl_code',
        'bl_status',
        'created_at',
        'updated_at'
    ];

    /**
     * ความสัมพันธ์กับ `Brand`
     */
    public function brands()
    {
        return $this->hasMany(Brand::class, 'brd_brandlist_id', 'bl_id');
    }
}
