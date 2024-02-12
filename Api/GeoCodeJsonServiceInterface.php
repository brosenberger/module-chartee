<?php

namespace BroCode\Chartee\Api;

use BroCode\Chartee\Api\Data\GeoCodeDataServiceInterface;

interface GeoCodeJsonServiceInterface extends GeoCodeDataServiceInterface
{
    public function feature($topology, $o);
}