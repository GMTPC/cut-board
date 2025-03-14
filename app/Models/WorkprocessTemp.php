<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\WipWorking;

class WorkprocessTemp extends Model
{
    use HasFactory;

    protected $table = 'workprocess_temps';

    protected $fillable = ['workprocess_id', 'line', 'wwt_id'];

    public function wipWorking()
    {
        return $this->belongsTo(WipWorking::class, 'workprocess_id', 'ww_id');
    }
}
