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

    public function feature($topology, $o)
    {
        if (is_string($o)) {
            $o = $topology['objects'][$o];
        }
        return $o['type'] === 'GeometryCollection'
            ? ['type' => 'FeatureCollection', 'features' => array_map(function($o) use ($topology) {
                return $this->_feature($topology, $o);
            }, $o['geometries'])]
            : $this->_feature($topology, $o);
    }

    protected function _feature($topology, $o)
    {
        $id = isset($o['id']) ? $o['id'] : null;
        $bbox = isset($o['bbox']) ? $o['bbox'] : null;
        $properties = isset($o['properties']) && $o['properties'] != null ? $o['properties'] : [];
        $geometry = $this->_object($topology, $o);

        $feature = ['type'=> "Feature", 'properties'=> $properties, 'geometry'=> $geometry];
        if ($id != null) {
            $feature['id'] = $id;
        }
        if ($bbox != null) {
            $feature['bbox'] = $bbox;
        }
        return $feature;
    }

    protected function _object($topology, $o)
    {
        $transformPoint = $this->transform($topology['transform']);
        $arcs = $topology['arcs'];

        return $this->geometry($arcs, $transformPoint, $o);
    }

    function transform($transform) {
        if ($transform == null) {
            $identy = function($x) { return $x; };
            return $identy;
        }
        $x0 = null;
        $y0 = null;
        $kx = $transform['scale'][0];
        $ky = $transform['scale'][1];
        $dx = $transform['translate'][0];
        $dy = $transform['translate'][1];
        $tranformation = function($input, $i = null) use ($dx, $dy, $ky, $kx) {
            if (!$i) $x0 = $y0 = 0;
            $j = 2;
            $n = count($input);
            $output = [];
            $output[0] = ($x0 += $input[0]) * $kx + $dx;
            $output[1] = ($y0 += $input[1]) * $ky + $dy;
            while ($j < $n) {
                $output[$j] = $input[$j];
                ++$j;
            } ;
            return $output;
        };
        return $tranformation;
    }

    private function point($p, callable $transformPoint)
    {
        return $transformPoint($p);
    }

    private function arc($arcs, callable $transformPoint, $i, &$points)
    {
        if (count($points)>0) array_pop($points);
        $a = $arcs[abs($i)];
        $k = 0;
        $n = count($a);
        for (; $k < $n; ++$k) {
            $points[] = $transformPoint($a[$k], $k);
        }
        if ($i < 0) {
            $points = array_reverse($points);
        }
    }

    private function line($arcs, callable $transformPoint)
    {
        $points = [];
        $n = count($arcs);
        for ($i = 0;  $i < $n; ++$i) $this->arc($arcs, $transformPoint, $arcs[$i], $points);
        if (count($points) < 2) $points[] = $points[0]; // This should never happen per the specification.
        return $points;
    }

    private function ring($arcs, callable $transformPoint)
    {
        $points = $this->line($arcs, $transformPoint);
        while (count($points) < 4) $points[] = $points[0]; // This may happen if an arc has only two points.
        return $points;
    }

    private function polygon($arcs, callable $transformPoint)
    {
        return array_map(function ($arc) use ($transformPoint) { return $this->ring($arc, $transformPoint); }, $arcs);
    }

    private function geometry($arcs, callable $transformPoint, $o)
    {
        $type = $o['type'];
        $coordinates = null;
        switch ($type) {
            case "GeometryCollection": return ['type' => $type, 'geometries' => array_map(function($geo) use ($arcs, $transformPoint) { return $this->geometry($arcs, $transformPoint, $geo); }, $o['geometries'])];
            case "Point": $coordinates = $this->point($o['coordinates'], $transformPoint); break;
            case "MultiPoint": $coordinates = array_map(function ($p) use ($transformPoint) { return $this->point($p, $transformPoint); }, $o['coordinates']); break;
            case "LineString": $coordinates = $this->line($o['arcs'], $transformPoint); break;
            case "MultiLineString": $coordinates = array_map(function ($arc) use ($transformPoint) { return $this->line($arc, $transformPoint); }, $o['arcs']); break;
            case "Polygon": $coordinates = $this->polygon($o['arcs'], $transformPoint); break;
            case "MultiPolygon": $coordinates = array_map(function($arc) use ($transformPoint) { return $this->polygon($arc, $transformPoint); }, $o['arcs']); break;
            default: return null;
        }
        return ['type' => $type, 'coordinates' => $coordinates];
    }
}