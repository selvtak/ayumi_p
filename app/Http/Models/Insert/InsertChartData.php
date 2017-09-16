<?php
namespace app\Http\Models\Insert;

use app\Http\Models\Common\DBUtilities;

class InsertChartData
{
    /** 日足データ挿入メイン **/
    function insertLargeDayFromCSV()
    {
        $pdo = new PDO('mysql:host=localhost;dbname=nf_large;charset=utf8',
                        'root', '');

        $file = fopen("./csv/large_day.csv", "r");
        $line_count = 1;
        $today = new DateTime();
        while($line = fgetcsv($file)){
            $query  = 'INSERT INTO largedayday VALUES(';
            $query .=     $line_count.","; //id
            $query .= "'".$line[0]."',"; //date
            $query .=     $line[1].","; //o
            $query .=     $line[2].","; //h
            $query .=     $line[3].","; //l
            $query .=     $line[4].","; //c
            $query .=     $line[5].","; //v
            $query .= "'".$today->format('Y-m-d H:i:s')."',"; //created_at
            $query .= "'".$today->format('Y-m-d H:i:s')."')"; //updated_at
            $pdo->query($query);
            $line_count++;

        }
        fclose($file);
    }

    /* DBにデータを挿入(週足or月足) */
    function insertLargeDBForMonthOrWeek(array $ohlcs, string $table_name)
    {
        $pdo = new PDO('mysql:host=localhost;dbname=nf_large;charset=utf8',
                        'root', '');
        $count = 1;
        $today = new DateTime();
        foreach($ohlcs as $ohlc){
            $query  = "INSERT INTO ".$table_name." VALUES(";
            $query .= $count.",";
            $query .= "'".$ohlc['date']."',";
            $query .= $ohlc['o_price'].",";
            $query .= $ohlc['h_price'].",";
            $query .= $ohlc['l_price'].",";
            $query .= $ohlc['c_price'].",";
            $query .= $ohlc['volume'].",";
            $query .= "'".$today->format('Y-m-d H:i:s')."',"; //created_at
            $query .= "'".$today->format('Y-m-d H:i:s')."')"; //updated_at

            $pdo->query($query);
            $count++;
        }
    }

}
