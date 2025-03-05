<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Wipbarcode;
use App\Models\Brand;
use App\Models\WipHolding;
use App\Models\WipSummary;
use App\Models\WipZiptape;
use App\Models\ProductTypeEmp;
use App\Models\AmountNg;
use App\Models\WipWorking;
use App\Models\WipWorktimes;
use App\Models\Brandlist;

class EndCsvDetail extends Model
{
    // ✅ ดึงข้อมูล WIP
    public static function endcsv($line, $workprocess)
    {
        $line = self::normalizeLine($line);

        return Wipbarcode::select([
                'wip_barcode',
                'wip_amount',
                'wip_id',
                DB::raw('SUM(amg_amount) as ng'),
                'brands.brd_lot',
                'brands.brd_brandlist_id'
            ])
            ->leftJoin('amount_ngs', 'amg_wip_id', '=', 'wip_id')
            ->leftJoin('wip_working', 'ww_id', '=', 'wip_working_id')
            ->leftJoin('brands', 'brands.brd_working_id', '=', 'wip_working.ww_id')
            ->where('ww_line', $line)
            ->whereIn('wip_working_id', (array) $workprocess)
            ->groupBy('wip_barcode', 'wip_amount', 'wip_id', 'brands.brd_lot', 'brands.brd_brandlist_id')
            ->get();
    }

    // ✅ ดึงข้อมูล FG
    public static function csvfg($line, $workprocess) 
    {
        $line = self::normalizeLine($line);

        return Brand::whereIn('brd_working_id', (array) $workprocess)
                    ->pluck('brd_brandlist_id');
    }

    // ✅ ดึงข้อมูล HD
    public static function csvhd($line, $workprocess)
    {
        $line = self::normalizeLine($line);

        return WipHolding::leftJoin('wip_working', 'ww_id', '=', 'wh_working_id')
            ->leftJoin('wip_summary', 'ws_working_id', '=', 'wh_working_id')
            ->where('ww_line', $line)
            ->whereIn('wh_working_id', (array) $workprocess)
            ->get();
    }

    // ✅ ดึงข้อมูล NG
    public static function csvng($line, $workprocess)
    {
        $line = self::normalizeLine($line);

        return WipSummary::leftJoin('wip_working', 'ww_id', '=', 'ws_working_id')
            ->leftJoin('product_type_emps', 'pe_working_id', '=', 'ww_id')
            ->where('ww_line', $line)
            ->whereIn('ws_working_id', (array) $workprocess)
            ->get();
    }

    // ✅ ดึงข้อมูล WIP ตาม Line และ Work Process
    public static function endtimecsv($line, $workprocess)
    {
        return Wipbarcode::select(DB::raw('wip_barcode, wip_amount, wip_id, SUM(amg_amount) as ng'))
            ->leftJoin('amount_ngs', 'amg_wip_id', '=', 'wip_id')
            ->leftJoin('wip_working', 'ww_id', '=', 'wip_working_id')
            ->where('ww_line', $line)
            ->whereIn('wip_working_id', (array) $workprocess)
            ->groupBy('wip_barcode', 'wip_amount', 'wip_id')
            ->get();
    }

    // ✅ ดึงข้อมูล FG ตาม Line และ Work Process
    public static function csvtimefg($line, $workprocess)
    {
        return Brand::leftJoin('brandlist', 'bl_id', '=', 'brd_brandlist_id')
            ->leftJoin('wip_working', 'ww_id', '=', 'brd_working_id')
            ->leftJoin('product_type_emps', 'pe_working_id', '=', 'ww_id')
            ->where('ww_line', $line)
            ->whereIn('ww_id', (array) $workprocess)
            ->get();
    }

    // ✅ ดึงข้อมูล HD ตาม Line และ Work Process
    public static function csvtimehd($line, $workprocess)
    {
        return WipHolding::leftJoin('wip_working', 'ww_id', '=', 'wh_working_id')
            ->leftJoin('wip_summary', 'ws_working_id', '=', 'wh_working_id')
            ->where('ww_line', $line)
            ->whereIn('wh_working_id', (array) $workprocess)
            ->get();
    }

    // ✅ ดึงข้อมูล NG ตาม Line และ Work Process
    public static function csvtimeng($line, $workprocess)
    {
        return WipSummary::leftJoin('wip_working', 'ww_id', '=', 'ws_working_id')
            ->leftJoin('product_type_emps', 'pe_working_id', '=', 'ww_id')
            ->leftJoin('wip_worktimes', 'wwt_workprocess', '=', 'ww_wwt_index')
            ->where('ww_line', $line)
            ->where('wwt_line', $line)
            ->whereIn('ww_wwt_index', (array) $workprocess)
            ->get();
    }

    // ✅ ดึงข้อมูล Ziptape ตาม Line และ Work Process
    public static function csvtimeziptape($line, $workprocess)
    {
        return WipZiptape::leftJoin('wip_worktimes', 'wz_worktime_id', '=', 'wwt_id')
            ->where('wz_line', $line)
            ->whereIn('wwt_index', (array) $workprocess)
            ->get();
    }

    // ✅ ฟังก์ชันช่วยในการจัดรูปแบบ Line ให้เริ่มต้นด้วย "L"
    private static function normalizeLine($line)
    {
        return str_starts_with($line, 'L') ? $line : 'L' . $line;
    }
}
