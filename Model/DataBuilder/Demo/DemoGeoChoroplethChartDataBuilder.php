<?php

namespace BroCode\Chartee\Model\DataBuilder\Demo;

use BroCode\Chartee\Model\DataBuilder\GeoChoroplethChartDataBuilder;

class DemoGeoChoroplethChartDataBuilder extends GeoChoroplethChartDataBuilder
{
    public function build()
    {
        $this->addOption('showOutline', true)
            ->addOption('showGraticule', true)
            ->addOption('fill', false)
            ->addOption('plugin', ['legend' => ['display' => false]])
            ->addOption('scales', ['projection' => ['axis' => 'x', 'projection' => 'equalEarth']]);

        $jsonData = $this->geoCodeJsonService->getGeoCodeJson('countries-50m');

        $countries = $jsonData['objects']['countries']['geometries'];

        // TODO implement correct feature method
        // $this->geoCodeJsonService->feature($jsonData, $jsonData['objects']['countries']);

        $countryNames = array_map(function ($country) { return $country['properties']['name'];}, $countries);
        $this->setDataLabels($countryNames)
        ->createDataSet()
            ->setLabel(__('Countries'))
            ->setFill(false)
            ->setData(array_map(function ($country) {
                return ['feature' => $country, 'value' => rand(0, 100)] ;
            }, $countries))->build();

        return parent::build();
    }
}