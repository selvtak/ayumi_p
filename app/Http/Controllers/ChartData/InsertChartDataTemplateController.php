<?php
namespace App\Http\Controllers\ChartData;

use App\Http\Models\Insert\InsertChartData;

abstract class InsertChartDataTemplateController
{
    private $db_name;
    private $table_name;
    private $file_name;

    protected abstract function getOriginalChartData($db_name, $file_name);
    protected abstract function summarizeOriginalChartData($original_datas);
    protected abstract function insertOHLC($summarized_datas);

    public function __construct($db_name, $table_name, $file_name = null)
    {
        $this->db_name = $db_name;
        $this->table_name = $table_name;
        $this->file_name = $file_name;
    }

    public function insertController()
    {
        $original_datas = $this->getOriginalChartData($this->db_name, $this->file_name);
        $summarized_datas = $this->summarizeOriginalChartData($original_datas);
        $ohlcs = $this->createOHLC($summarized_datas);
        return $this->insertOHLCWrapper($ohlcs);
    }

    public function getDBName()
    {
        return $this->db_name;
    }

    public function getTableName()
    {
        return $this->table_name;
    }

    public function insertOHLCWrapper($ohlcs)
    {
        $icd = new InsertChartData($this-getDBName());
        return $icd->insertOHLC($this->getTableName(), $ohlcs);
    }

    protected function summarizeOriginalChartData_Common($original_datas)
    {
        $intermediate_ohlcs = [];
        $cc = new CommonCotroller();
        foreach ($large_day_all as $large_day){
            $year_number = $cc->getYearNumber($large_day['date']);
            if ($this->getTableName() == 'week'){
                $branch_number = $cc->getWeekNumberInYear($large_day['date']);
            }else{
                $branch_number = $cc->getMonthNumber($large_day['date']);
            }
            $keyname = $year_number.$branch_number;
            if (array_key_exists($keyname, $intermediate_ohlcs)){
                array_push($intermediate_ohlcs[$keyname].$large_day);
            }else{
                $intermediate_ohlcs[$keyname][0] = $large_day;
            }
        }
        return $intermediate_ohlcs;
    }

    protected function createOHLC_Common($summarized_datas)
    {
        $count = 0;
        $ohlcs = [];
        $ohlc = [
            'id'         => '',
            'date'       => '',
            'o_price'    => 0,
            'h_price'    => 0,
            'l_price'    => 999999,
            'c_price'    => 0,
            'volume'     => 0,
            'created_at' => '',
            'updated_at' => ''
        ];

        foreach ($summarized_datas as $summarized_data){
            foreach ($summarized_data as $temp_ohlc){
                $ohlc['date'] = $temp_ohlc['date'];
                if ($count == 0){ $ohlc['o_price'] = $temp_ohlc['o_price'];}
                if ($ohlc['h_price'] < $temp_ohlc['h_price']){$ohlc['h_price'] = $temp_ohlc['h_price'];}
                if ($ohlc['l_price'] > $temp_ohlc['l_price']){$ohlc['l_price'] = $temp_ohlc['l_price'];}
                $ohlc['c_price'] = $temp_ohlc['c_price'];
                $ohlc['volume'] += $temp_ohlc['volume'];
            }
            array_push($ohlcs, $ohlc);
        }
        return $ohlcs;
    }

}
