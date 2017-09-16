<?php
namespace App\Http\Controllers\ChartData;

use App\Http\Models\Select\SelectChartData;

class InsertChartDataWeekController extends InsertChartDataTemplateController
{
    protected function getOriginalChartData($db_name, $file_name)
    {
        $scd = new SelectChartData($db_name);
        return $scd->selectAllData('day');
    }

    protected function summarizeOriginalChartData($large_day_all)
    {
        return parent::summarizeOriginalChartData_Common($large_day_all);
    }

    protected function createOHLC($summarized_datas)
    {
        return parent::createOHLC_Common($summarized_datas);
    }
}
