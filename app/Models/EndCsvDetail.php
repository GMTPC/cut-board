<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EndCsvDetail extends Model
{
    use HasFactory;

    // ฟังก์ชันเพื่อดึงข้อมูล WIP และ NG
    public static function endcsv($work_id)
    {
        return Wipbarcode::select(DB::raw('wip_barcode, wip_amount, wip_id, wip_amount, SUM(amg_amount) as ng'))
            ->leftJoin('amount_ngs', 'amg_wip_id', '=', 'wip_id')
            ->leftJoin('wip_working', 'ww_id', '=', 'wip_working_id')
            ->where('wip_working_id', $work_id)
            ->groupBy('wip_barcode', 'wip_amount', 'wip_id', 'wip_amount')
            ->get();
    }

    // ฟังก์ชันเพื่อดึงข้อมูล Finished Goods (FG)
    public static function csvfg($work_id)
    {
        return Brand::leftJoin('brandlist', 'bl_id', '=', 'brd_brandlist_id')
            ->leftJoin('wip_working', 'ww_id', '=', 'brd_working_id')
            ->leftJoin('product_type_emps', 'pe_working_id', '=', 'ww_id')
            ->where('ww_id', $work_id)
            ->get();
    }

    // ฟังก์ชันเพื่อดึงข้อมูล Hold Down (HD)
    public static function csvhd($work_id)
    {
        return WipHolding::leftJoin('wip_working', 'ww_id', '=', 'wh_working_id')
            ->leftJoin('wip_summary', 'ws_working_id', '=', 'wh_working_id')
            ->where('wh_working_id', $work_id)
            ->get();
    }

    // ฟังก์ชันเพื่อดึงข้อมูล NG
    public static function csvng($work_id)
    {
        return WipSummary::leftJoin('wip_working', 'ww_id', '=', 'ws_working_id')
            ->leftJoin('product_type_emps', 'pe_working_id', '=', 'ww_id')
            ->where('ws_working_id', $work_id)
            ->get();
    }

    // ฟังก์ชันเพื่อดึงข้อมูล WIP ตาม Line และ Index
    public static function endtimecsv($line, $index)
    {
        return Wipbarcode::select(DB::raw('wip_barcode, wip_amount, wip_id, wip_amount, SUM(amg_amount) as ng'))
            ->leftJoin('amount_ngs', 'amg_wip_id', '=', 'wip_id')
            ->leftJoin('wip_working', 'ww_id', '=', 'wip_working_id')
            ->where('ww_line', $line)
            ->where('ww_wwt_index', $index)
            ->groupBy('wip_barcode', 'wip_amount', 'wip_id', 'wip_amount')
            ->get();
    }

    // ฟังก์ชันเพื่อดึงข้อมูล Finished Goods (FG) ตาม Line และ Index
    public static function csvtimefg($line, $index)
    {
        return Brand::leftJoin('brandlist', 'bl_id', '=', 'brd_brandlist_id')
            ->leftJoin('wip_working', 'ww_id', '=', 'brd_working_id')
            ->leftJoin('product_type_emps', 'pe_working_id', '=', 'ww_id')
            ->where('ww_line', $line)
            ->where('ww_wwt_index', $index)
            ->get();
    }

    // ฟังก์ชันเพื่อดึงข้อมูล Hold Down (HD) ตาม Line และ Index
    public static function csvtimehd($line, $index)
    {
        return WipHolding::leftJoin('wip_working', 'ww_id', '=', 'wh_working_id')
            ->leftJoin('wip_summary', 'ws_working_id', '=', 'wh_working_id')
            ->where('ww_line', $line)
            ->where('ww_wwt_index', $index)
            ->get();
    }

    // ฟังก์ชันเพื่อดึงข้อมูล NG ตาม Line และ Index
    public static function csvtimeng($line, $index)
    {
        return WipSummary::leftJoin('wip_working', 'ww_id', '=', 'ws_working_id')
            ->leftJoin('product_type_emps', 'pe_working_id', '=', 'ww_id')
            ->leftJoin('wip_worktimes', 'wwt_index', '=', 'ww_wwt_index')
            ->where('ww_line', $line)
            ->where('wwt_line', $line)
            ->where('ww_wwt_index', $index)
            ->get();
    }

    // ฟังก์ชันเพื่อดึงข้อมูล Ziptape ตาม Line และ Index
    public static function csvtimeziptape($line, $index)
    {
        return WipZiptape::leftJoin('wip_worktimes', 'wz_worktime_id', 'wwt_id')
            ->where('wz_line', $line)
            ->where('wwt_index', $index)
            ->get();
    }
}
