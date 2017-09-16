<?php

namespace app\Http\Models\Delete;

use app\Http\Models\Common\DBUtilities;

class DeleteChartData
{
    //TODO:全データ削除と1件削除を作る。(1件削除は必要になったら)

    public function DeleteAllData($table_name)
    {
        //TODO:確認メッセージはviewかcontrollerでやってください。
        //TODO:エラーチェック。テーブルの存在確認
        //TODO:ログ出力(開始時刻終了時刻対象function/テーブル)
        try{
            $dbutil = new DBUtilities();
            $pdo = $dbutil->connectDB();
            $query = "DELETE FROM ".$table_name;
            $pdo->query($query);
        } catch (PDOException $e){
            echo $e->getMessage();
            exit;
        }
    }
}
