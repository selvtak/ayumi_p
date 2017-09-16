<?php
namespace App\Http\Controllers\ChartData;

abstract class ReadChartFileStrategyController
{
    private $db_name;
    private $file_name;

    public function __construct($db_name, $file_name)
    {
        $this->db_name = $db_name;
        $this->file_name = $file_name;
    }

    public function getChartFileContext()
    {
        return $this->readChartFile($this->db_name, $this->getChartFileName());
    }

    public function getChartFileName()
    {
        return $this->file_name;
    }

    protected abstract function readChartFile($db_name, $file_name);
}
