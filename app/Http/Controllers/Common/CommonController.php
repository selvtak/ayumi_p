<?php
namespace App\Http\Controllers\Common;

class CommonController
{
    /* 年情報を取得する */
    public function getYearNumber($date)
    {
        $year_number = new DateTime($date);
        return $year_number->format('Y');
    }

    /* 年間の週番号(1～53)を取得する */
    public function getWeekNumberInYear($date)
    {
        $week_number = new DateTime($date);
        return $week_number->format('W');
    }

    /* 月情報を取得する */
    public function getMonthNumber($date)
    {
        $month_number = new DateTime($date);
        return $month_number->format('m');
    }
}
