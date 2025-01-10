<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\WipProductDate;

class Wipbarcode extends Model
{
    use HasFactory;
    public function wipProductDate()
{
    return $this->hasOne(WipProductDate::class, 'wp_wip_id', 'wip_id');
}
protected $fillable = [
    'wip_barcode',
    'wip_amount',
    'wip_working_id',
    'wip_empgroup_id',
    'wip_sku_name',
    'wip_index',
]; 
public function groupEmp()
{
    return $this->belongsTo(GroupEmp::class, 'wip_empgroup_id', 'id');
}
}

