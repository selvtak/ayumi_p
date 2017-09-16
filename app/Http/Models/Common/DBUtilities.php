<?php
namespace app\Http\Models\Common;

use PDO;

class DBUtilities
{
    const DBUSERNAME   = "root";
    const DBPASSWORD   = "";
    const DSN_NF_LARGE = "mysql:host=localhost;dbname=nf_large;charset=utf8";

    /**
     * PDOを使ってDB接続を行う。
     *
     * @param string $table_name
     * @return object PDO
     */
    public function connectDB($dsn = self::DSN_NF_LARGE)
    {
        try{
            return new PDO(self::DSN_NF_LARGE, self::DBUSERNAME, self::DBPASSWORD);
        } catch (PDOException $e){
            echo $e->getMessage();
            exit;
        }
    }

    /**
     * テーブルが存在するかを確認する。
     * 主に作業前のエラーチェックで使う。
     *
     * @param string $table_name
     * @return bool
     */
    public function searchTables($table_name)
    {
        try{
            $pdo = $this->connectDB();
            $statement = $pdo->query("SHOW TABLES LIKE '".$table_name."'");
            $tables = $statement->fetchAll(PDO::FETCH_COLUMN);

            return in_array($table_name, $tables, true);

        } catch (PDOException $e){
            echo $e->getMessage();
            exit;
        }
    }
}
