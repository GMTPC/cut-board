<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skumaster extends Model
{
    // กำหนดชื่อตาราง
    protected $table = 'SKUMASTER';

    // กำหนด Primary Key
    protected $primaryKey = 'SKU_KEY';

    // ปิด timestamps ถ้าตารางไม่มี created_at และ updated_at
    public $timestamps = false;

    // กำหนด fillable fields
    protected $fillable = [
        'SKU_KEY',
        'SKU_CODE',
        'SKU_NAME',
        'SKU_E_NAME',
        'SKU_BARCODE',
        'SKU_BRN',
        'SKU_ICCAT',
        'SKU_S_UTQ',
        'SKU_T_UTQ',
        'SKU_K_UTQ',
        'SKU_VAT_TY',
        'SKU_VAT',
        'SKU_COST_TY',
        'SKU_STD_COST',
        'SKU_STOCK',
        'SKU_SKUALT',
        'SKU_WH_TY',
        'SKU_WH_RATE',
        'SKU_MSG_1',
        'SKU_MSG_2',
        'SKU_MSG_3',
        'SKU_MIXCOLOR',
        'SKU_ICCOLOR',
        'SKU_ICSIZE',
        'SKU_ICDEPT',
        'SKU_ICGL',
        'SKU_ICPRT',
        'SKU_WL',
        'SKU_ENABLE',
        'SKU_P_ENABLE',
        'SKU_MIN_QTY',
        'SKU_MAX_QTY',
        'SKU_MIN_ORDER',
        'SKU_MAX_ORDER',
        'SKU_LEAD_TIME',
        'SKU_SATISFY',
        'SKU_SAFTY',
        'SKU_FREQUENCY',
        'SKU_ACCESS',
        'SKU_ABC',
        'SKU_EOQ_A',
        'SKU_EOQ_P',
        'SKU_EOQ_C',
        'SKU_EOQ_NO',
        'SKU_SPEC',
        'SKU_USAGE',
        'SKU_REMARK',
        'SKU_LAST_O',
        'SKU_LAST_R',
        'SKU_LAST_RCOST',
        'SKU_LAST_RQTY',
        'SKU_LAST_COMMIT',
        'SKU_SENSITIVITY',
        'SKU_SENS_POS',
        'SKU_LAST_UBCOST',
        'SKU_LAST_UCCOST',
        'SKU_ALERT',
        'SKU_ALERT_MSG',
        'SKU_PRICE',
        'SKU_EQ_FACTOR',
        'SKU_EQ_NAME',
        'SKU_UDF_1',
        'SKU_UDF_2',
        'SKU_UDF_3',
        'SKU_UDF_4',
        'SKU_UDF_5',
        'SKU_UDF_6',
        'SKU_LASTUPD',
    ];

    // ถ้าตารางอยู่ในฐานข้อมูลอื่น ให้กำหนด connection
}
