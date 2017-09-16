<?php
namespace App\Http\Models\Common;

class DBinstance
{
    const CONFIG_PATH = PATH_TO_CONFIG_FILE;

    private $pdo = [];
    private $conf = [];

    private function __construct()
    {
        $this->conf = require(self::CONFIG_PATH);
    }

    private function connect($name)
    {
        if (empty($this->conf[$name])){
            throw new Exception('Unknown database: ' . $name);
        }
        $conf = $this->conf[$name];
        $dsn = "mysql:host={$conf['host']};dbname={$conf['db']};charset={$conf['charset']};";
        $options = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
        ];
        $this->pdo[$name] = new \PDO($dsn, $conf['user'], $conf['password'], $options);
    }

    final public static function getDB($name)
    {
        static $instance;

        if (empty($instance)){
            $instance = new static;
        }
        if (empty($instance->pdo[$name])){
            $instance->connect($name);
        }
        return $instance->pdo[$name];
    }

    final public function __clone()
    {
        throw new Exception("this instance is the singleton class....");
    }
}
