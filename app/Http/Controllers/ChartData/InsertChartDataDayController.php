<?php
namespace App\Http\Controllers\ChartData;

class InsertChartDataDayController extends InsertChartDataTemplateController
{
    protected function getOriginalChartData($db_name, $file_name)
    {
        $strategy = new ReadChartFileDayController($db_name, $file_name);
        $context = new ReadChartFileContextController($strategy);
        return $context->getChartFileContext();
    }

    protected function summarizeOriginalChartData($original_datas)
    {
        //日足はデータ加工不要です。
        return $original_datas;
    }

    protected function createOHLC($summarized_datas)
    {
        //日足はデータ加工不要です。
        return $summarized_datas;
    }
}
