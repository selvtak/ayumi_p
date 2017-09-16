<?php
namespace App\Http\Controllers\ChartData;

class ReadChartFileDayController extends ReadChartFileStrategyController
{
    protected function readChartFile($db_name, $file_name)
    {
        try {
            $spl_file_object = new \SplFileObject($file_name);
            $spl_file_object->setFlags(\SplFileObject::READ_CSV | \SplFileObject::DROP_NEW_LINE);
            $line_count = 0;
            $ohlcs = [];

            foreach ($spl_file_object as $key => $line){
                if ($line_count == 0){
                    $line_count++;
                    continue;
                }
                if (is_null($line[0])) continue;
                $ohlc = [];
                $ohlc['id']         = "";
                $ohlc['date']       = $line[0];
                $ohlc['o_price']    = $line[1];
                $ohlc['h_price']    = $line[2];
                $ohlc['l_price']    = $line[3];
                $ohlc['c_price']    = $line[4];
                $ohlc['volume']     = $line[5];
                $ohlc['created_at'] = "";
                $ohlc['updated_at'] = "";
                array_push($ohlcs, $ohlc);
                $line_count++;
            }
            return $ohlcs;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
