<?php

namespace Akira28\GeoDistance;

class CrowFlies implements DistanceInterface
{
    const EARTH_RADIUS = 6371.008;

    /**
     * Get distance in meters using the haversine Formula
     *
     * @param Point $pointA
     * @param Point $pointB
     *
     * @return int
     */
    public function getDistance(Point $pointA, Point $pointB)
    {
        $latitudeA = $pointA->getLatitude();
        $latitudeB = $pointB->getLatitude();

        $longitudeA = $pointA->getLongitude();
        $longitudeB = $pointB->getLongitude();

        $dLat = deg2rad($latitudeB - $latitudeA);
        $dLon = deg2rad($longitudeB - $longitudeA);

        $sindLat = sin($dLat / 2) * sin($dLat / 2);
        $sindLon = sin($dLon / 2) * sin($dLon / 2);
        $cosLat  = cos(deg2rad($latitudeA)) * cos(deg2rad($latitudeB));
        $a       = $sindLat + $cosLat * $sindLon;
        $c       = 2 * asin(sqrt($a));

        return round(self::EARTH_RADIUS * $c * 1000, 0);
    }
}