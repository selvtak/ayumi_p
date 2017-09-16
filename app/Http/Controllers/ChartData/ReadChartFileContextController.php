<?php
namespace App\Http\Controllers\ChartData;

class ReadChartFileContextController
{
    private $strategy;

    public function __construct(ReadChartFileStrategyController $strategy)
    {
        $this->strategy = $strategy;
    }

    public function getChartFileContext()
    {
        return $this->strategy->getChartFileContext();
    }
}
