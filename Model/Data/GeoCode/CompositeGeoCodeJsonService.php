<?php

namespace BroCode\Chartee\Model\Data\GeoCode;

use BroCode\Chartee\Api\Data\GeoCodeDataServiceInterface;
use BroCode\Chartee\Api\Exception\CharteeDataException;
use BroCode\Chartee\Api\GeoCodeJsonServiceInterface;

class CompositeGeoCodeJsonService implements GeoCodeJsonServiceInterface
{

    /** @var GeoCodeDataServiceInterface[]  */
    private array $geoCodeJsonServices;

    public function __construct(
        $geoCodeJsonServices = []
    ) {
        $this->geoCodeJsonServices = $geoCodeJsonServices;
    }

    public function getGeoCodeJson($name)
    {
        if (isset($this->geoCodeJsonServices[$name])) {
            $data = $this->geoCodeJsonServices[$name]->getGeoCodeJson($name);
            array_walk_recursive($data, function(&$d) {
                if (is_string($d)) {
                    $d = htmlspecialchars($d);
                }
            });
            return $data;
        }
        throw new CharteeDataException(__('GeoCodeJsonService not found for %1', $name));
    }
}
