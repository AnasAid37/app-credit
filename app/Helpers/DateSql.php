<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class DateSql
{
    /**
     * استخراج الشهر (يعيد string جاهز للاستخدام مع as)
     */
    public static function month($column, $as = null)
    {
        $sql = DB::getDriverName() === 'pgsql'
            ? "EXTRACT(MONTH FROM {$column})::integer"
            : "MONTH({$column})";
        
        return $as ? DB::raw("{$sql} as {$as}") : DB::raw($sql);
    }

    /**
     * استخراج السنة
     */
    public static function year($column, $as = null)
    {
        $sql = DB::getDriverName() === 'pgsql'
            ? "EXTRACT(YEAR FROM {$column})::integer"
            : "YEAR({$column})";
        
        return $as ? DB::raw("{$sql} as {$as}") : DB::raw($sql);
    }

    /**
     * فلترة حسب السنة
     */
    public static function whereYear($query, $column, $year)
    {
        if (DB::getDriverName() === 'pgsql') {
            return $query->whereRaw("EXTRACT(YEAR FROM {$column}) = ?", [$year]);
        }
        return $query->whereYear($column, $year);
    }

    /**
     * فلترة حسب الشهر
     */
    public static function whereMonth($query, $column, $month)
    {
        if (DB::getDriverName() === 'pgsql') {
            return $query->whereRaw("EXTRACT(MONTH FROM {$column}) = ?", [$month]);
        }
        return $query->whereMonth($column, $month);
    }

    /**
     * Group by الشهر (يعيد string للاستخدام مع groupBy)
     */
    public static function monthGroupBy($column)
    {
        return DB::getDriverName() === 'pgsql'
            ? DB::raw("EXTRACT(MONTH FROM {$column})::integer")
            : DB::raw("MONTH({$column})");
    }

    /**
     * Group by السنة
     */
    public static function yearGroupBy($column)
    {
        return DB::getDriverName() === 'pgsql'
            ? DB::raw("EXTRACT(YEAR FROM {$column})::integer")
            : DB::raw("YEAR({$column})");
    }
}