<?php

namespace BroCode\Chartee\Model\DataBuilder\Demo;

use BroCode\Chartee\Model\DataBuilder\GaugeChartDataBuilder;

class DemoGaugeChartDataBuilder extends GaugeChartDataBuilder
{
    public function build()
    {
        // gauge sample from https://www.youtube.com/watch?v=VF6jd2Fv4bs&list=RDCMUCojXvfr41NqDxaPb9amu8-A&index=1
        $this->setDataLabels(['75%', '100%', '125%'])
            ->setMaxValue(30)
            ->setNeedleValue(rand(0, 30));

        return parent::build();
    }
}