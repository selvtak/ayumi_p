<?php
namespace App\Http\Controllers\ChartData;

use App\Http\Models\Select\SelectChartData;
use App\Http\Models\Common\DBinstance as DB;

class ReadChartFileDayAppendController extends ReadChartFileStrategyController
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
                array_unshift($ohlcs, $ohlc);
                $line_count++;
            }
            $ohlcs = $this->processOHLCS($db_name, $ohlcs);
            return $ohlcs;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    private function processOHLCS($db_name, $ohlcs)
    {
        $pdo = DB::getDB($db_name);
        $query = "SELECT date FROM day";
        $statement = $pdo->prepare($query);
        $statement->execute();
        $db_dates = $statement->fetchAll(\PDO::FETCH_COLUMN);
        $same_line_count = 0;
        $return_ohlcs = [];

        foreach ($ohlcs as $ohlc){
            $check_date = new \Datetime($ohlc['date']);
            if (in_array($check_date->format('Y-m-d'), $db_dates)){
                $same_line_count++;
            }else{
                array_unshift($return_ohlcs, $ohlc);
            }
            if ($same_line_count == 2){
                return $return_ohlcs;
            }
        }
        return $return_ohlcs;
    }

}
