<?php

namespace Akira28\GeoDistance;

class Point
{
    private $latitude;

    private $longitude;

    public function __construct($latitude, $longitude)
    {
        $this->latitude  = $latitude;
        $this->longitude = $longitude;
    }

    public function getLatitude()
    {
        return $this->latitude;
    }

    public function getLongitude()
    {
        return $this->longitude;

    }
}