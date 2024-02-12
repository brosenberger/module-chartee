<?php

namespace BroCode\Chartee\Model\Data\GeoCode;

use BroCode\Chartee\Api\Data\GeoCodeDataServiceInterface;
use BroCode\Chartee\Api\Exception\CharteeDataException;

class GeoCodeCountries50mService implements GeoCodeDataServiceInterface
{

    public function getGeoCodeJson($name)
    {
        try {
            $jsonData = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . '../../../etc/geodata/countries-50m.json');
            return json_decode($jsonData, true);
        } catch (\Throwable $e) {
            throw new CharteeDataException(__('Countries-50m GeoCode Exception'), 400, $e);
        }
    }
}