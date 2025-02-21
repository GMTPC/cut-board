<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class WipWorking extends Model
{
    use HasFactory;

    protected $table = 'wip_working'; // ชื่อตาราง

    protected $primaryKey = 'ww_id'; // Primary Key

    public $timestamps = false; // ปิด timestamps

    protected $fillable = [
        'ww_id',
        'ww_line',
        'ww_end_date',
    ];

    /**
     * ความสัมพันธ์กับ `Brand`
     */
    public function brands()
    {
        return $this->hasMany(Brand::class, 'brd_working_id', 'ww_id');
    }

    /**
     * Global Scope เพื่อเรียงลำดับตาม `ww_id`
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('orderById', function (Builder $builder) {
            $builder->orderBy('ww_id', 'asc');
        });
    }

    /**
     * Mutator: ตั้งค่า `ww_end_date` เป็นเวลาปัจจุบัน (โซนเวลาไทย)
     */
    public function setWwEndDateAttribute($value)
    {
        $this->attributes['ww_end_date'] = Carbon::now('Asia/Bangkok')->format('Y-m-d H:i:s');
    }
}
