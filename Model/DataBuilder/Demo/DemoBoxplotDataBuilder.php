<?php

namespace BroCode\Chartee\Model\DataBuilder\Demo;

use BroCode\Chartee\Model\DataBuilder\BoxplotChartDataBuilder;

class DemoBoxplotDataBuilder extends BoxplotChartDataBuilder implements \BroCode\Chartee\Api\ChartDataBuilderInterface
{

    public function build()
    {
        // boxplot sample from https://www.youtube.com/watch?v=2zEbeX0bPS8
        $this->setDataLabels(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'])
            ->createDataSet()
                ->setLabel('Weekly Sales')
                ->setDataValues($this->randomValues(100,0,100),
                    $this->randomValues(100,0,20),
                    $this->randomValues(100,20,70),
                    $this->randomValues(100,60,100),
                    $this->randomValues(40,50,100),
                    $this->randomValues(100,0,100),
                    $this->randomValues(100,60,120),
                    $this->randomValues(100,80,100))
                ->setOutlierColor('#999999')
                ->setBackgroundColor('rgba(255, 26, 104, 0.2)')
                ->setBorderColor('rgba(255, 26, 104, 1)')
                ->build();

        return parent::build();
    }

    protected function randomValues($count, $min, $max)
    {
        $values = [];
        for ($i = 0; $i < $count; $i++) {
            $values[] = rand($min, $max);
        }
        return $values;
    }
}