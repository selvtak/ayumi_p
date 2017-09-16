<?php

class SelectCharData
{
    /* 日足データをすべてDBから取得 */
    public function selectAll($table_name)
    {
        $dbutil = new DBUtilities();
        $pdo = $dbutil->connectDB();
        $query = "select * from ".$table_name;
        $statement = $pdo->prepare($query);
        $statement->execute();
        $chartdata_all = $statement->fetchAll();
        return $chartdata_all;
    }

    //TODO:個別期間のデータを取得する。
    //ただし、日足(date)、5分足(date and time)で必要な情報が異なる。

}
