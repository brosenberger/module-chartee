<?php

namespace BroCode\Chartee\Model\DataBuilder\Demo;

use BroCode\Chartee\Model\DataBuilder\GeoChoroplethChartDataBuilder;

class DemoGeoChoroplethChartDataBuilder extends GeoChoroplethChartDataBuilder
{
    public function build()
    {
        $this->addOption('showOutline', true)
            ->addOption('showGraticule', true)
            ->addOption('plugins', ['legend' => ['display' => false]])
            ->addOption('scales', ['projection' => ['axis' => 'x', 'projection' => 'equalEarth']])
            ->setTopology('countries-50m', 'countries');

        $countries = $this->topojson['topology']['objects']['countries']['geometries'];

        $this->createDataSet()
            ->setLabel(__('Countries'))
            ->setDataValues(...array_map(function ($country) {
                return ['feature' => $country['properties']['name'], 'value' => rand(0, 100)];
            }, $countries))
            ->build();

        return parent::build();
    }
}
