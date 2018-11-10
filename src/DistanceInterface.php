<?php

namespace Akira28\GeoDistance;

interface DistanceInterface
{
    public function getDistance(Point $pointA, Point $pointB);
}