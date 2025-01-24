<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupEmp extends Model
{
    use HasFactory;

    /**
     * ระบุชื่อของตารางในฐานข้อมูล
     *
     * @var string
     */
    protected $table = 'group_emp';

    /**
     * คอลัมน์ที่สามารถกำหนดค่าได้
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'emp1',
        'emp2',
        'line',
        'date',
        'status', // เพิ่มคอลัมน์ status ที่นี่
    ];
}
