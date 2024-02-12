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
        // TODO migrate to PHP from js lib

        $id = $o['id'];
        $bbox = $o['bbox'];
        $properties = $o['properties'] == null ? [] : $o['properties'];
        //$geometry = $this->object($topology, $o);
        return [];
    }
/*
    function feature$1(topology, o) {
        var id = o.id,
            bbox = o.bbox,
            properties = o.properties == null ? {} : o.properties,
            geometry = object(topology, o);
        return id == null && bbox == null ? {type: "Feature", properties: properties, geometry: geometry}
            : bbox == null ? {type: "Feature", id: id, properties: properties, geometry: geometry}
                : {type: "Feature", id: id, bbox: bbox, properties: properties, geometry: geometry};
    }

    function object(topology, o) {
        var transformPoint = transform(topology.transform),
            arcs = topology.arcs;

        function arc(i, points) {
            if (points.length) points.pop();
            for (var a = arcs[i < 0 ? ~i : i], k = 0, n = a.length; k < n; ++k) {
                points.push(transformPoint(a[k], k));
            }
            if (i < 0) reverse(points, n);
        }

        function point(p) {
            return transformPoint(p);
        }

        function line(arcs) {
            var points = [];
            for (var i = 0, n = arcs.length; i < n; ++i) arc(arcs[i], points);
            if (points.length < 2) points.push(points[0]); // This should never happen per the specification.
            return points;
        }

        function ring(arcs) {
            var points = line(arcs);
            while (points.length < 4) points.push(points[0]); // This may happen if an arc has only two points.
            return points;
        }

        function polygon(arcs) {
            return arcs.map(ring);
        }

        function geometry(o) {
            var type = o.type, coordinates;
            switch (type) {
                case "GeometryCollection": return {type: type, geometries: o.geometries.map(geometry)};
                case "Point": coordinates = point(o.coordinates); break;
                case "MultiPoint": coordinates = o.coordinates.map(point); break;
                case "LineString": coordinates = line(o.arcs); break;
                case "MultiLineString": coordinates = o.arcs.map(line); break;
                case "Polygon": coordinates = polygon(o.arcs); break;
                case "MultiPolygon": coordinates = o.arcs.map(polygon); break;
                default: return null;
            }
            return {type: type, coordinates: coordinates};
        }

        return geometry(o);
    }
*/
}