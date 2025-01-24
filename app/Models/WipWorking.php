<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class WipWorking extends Model
{
    use HasFactory;

    // ชื่อตารางในฐานข้อมูล
    protected $table = 'wip_working';

    // ชื่อ Primary Key
    protected $primaryKey = 'ww_id';

    // ตารางไม่มี timestamps (created_at, updated_at)
    public $timestamps = false;

    // ฟิลด์ที่สามารถกรอกข้อมูลได้
    protected $fillable = [
        'ww_id',
        'ww_line',
        // เพิ่มฟิลด์อื่น ๆ ตามที่ต้องการ
    ];

    // เพิ่ม Global Scope เพื่อเรียงลำดับตาม `ww_id`
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('orderById', function (Builder $builder) {
            $builder->orderBy('ww_id', 'asc'); // เรียงจากน้อยไปมาก
        });
    }
}
