<?php

namespace Akira28\GeoDistance;

class Distance
{
    const EARTH_RADIUS = 6371.008;

    /**
     * @param Position $positionA
     * @param Position $positionB
     *
     * @return float|int
     */
    public static function betweenPositions(Position $positionA, Position $positionB)
    {
        $latitudeA = $positionA->getLatitude();
        $latitudeB = $positionB->getLatitude();

        $longitudeA = $positionA->getLongitude();
        $longitudeB = $positionB->getLongitude();

        $dLat = deg2rad($latitudeB - $latitudeA);
        $dLon = deg2rad($longitudeB - $longitudeA);

        $sindLat = sin($dLat / 2) * sin($dLat / 2);
        $sindLon = sin($dLon / 2) * sin($dLon / 2);
        $cosLat  = cos(deg2rad($latitudeA)) * cos(deg2rad($latitudeB));
        $a       = $sindLat + $cosLat * $sindLon;
        $c       = 2 * asin(sqrt($a));

        return self::EARTH_RADIUS * $c;
    }
}