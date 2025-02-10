<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class WipHolding extends Model
{
    use HasFactory;

    protected $table = 'wip_holding'; // ชื่อตารางในฐานข้อมูล

    protected $primaryKey = 'wh_id'; // Primary Key ของตาราง

    public $timestamps = true; // เปิดใช้งาน created_at และ updated_at

    protected $fillable = [
        'wh_working_id',
        'wh_barcode',
        'wh_lot',
        'wh_index',
    ];

    // ✅ เพิ่มการเรียงลำดับจากน้อยไปมาก
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('wh_id', 'ASC'); // เรียงจากน้อยไปมาก
        });
    }
}
