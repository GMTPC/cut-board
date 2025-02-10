<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class WipSummary extends Model
{
    use HasFactory;

    protected $table = 'wip_summary'; // ชื่อตารางในฐานข้อมูล

    protected $primaryKey = 'ws_id'; // Primary Key ของตาราง

    public $timestamps = true; // เปิดใช้งาน created_at และ updated_at

    protected $fillable = [
        'ws_output_amount',
        'ws_input_amount',
        'ws_working_id',
        'ws_holding_amount',
        'ws_ng_amount',
        'ws_index',
    ];

    // ✅ เพิ่มการเรียงลำดับจากน้อยไปมาก
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('ws_id', 'ASC'); // เรียงจากน้อยไปมาก
        });
    }
}
