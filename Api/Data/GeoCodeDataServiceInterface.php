<?php

namespace BroCode\Chartee\Api\Data;

interface GeoCodeDataServiceInterface
{
    /**
     * @param string $name
     * @return array
     * @throws \BroCode\Chartee\Api\Exception\CharteeDataException
     */
    public function getGeoCodeJson($name);
}