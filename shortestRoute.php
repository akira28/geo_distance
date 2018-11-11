<?php

const EARTH_RADIUS = 6371.008;

/**
 * @param array $points
 * Solve the travelling salesman problem with a naive brute force, to obtain the shortest path between multiple points,
 * using the haversine formula to calculate distances
 *
 * @return array
 */
function shortestRoute($points)
{
    $pointsKeys       = array_keys($points);
    $shortestDistance = 0;
    $distances        = getAllDistances($points);

    $allRoutes   = getPermutations($pointsKeys);
    $countPoints = count($points);
    foreach ($allRoutes as $key => $route) {
        $i     = 0;
        $total = 0;
        foreach ($route as $point) {
            if ($i < $countPoints - 1) {
                $total += $distances["{$route[$i]}-{$route[$i+1]}"] ?? $distances["{$route[$i+1]}-{$route[$i]}"];
            }
            $i++;
        }
        if ($total < $shortestDistance || $shortestDistance == 0) {
            $shortestDistance = $total;
            $shortestRoute    = $route;
        }
    }

    uksort(
        $points,
        function ($key1, $key2) use ($shortestRoute) {
            return ((array_search($key1, $shortestRoute) > array_search($key2, $shortestRoute)) ? 1 : -1);
        }
    );

    return [
        'shortestRoute'            => $shortestRoute,
        'shortestRouteCoordinates' => $points,
        'shortestDistance'         => $shortestDistance,
        'distances'                => $distances,
    ];

}

/**
 * @param float $latitudeA
 * @param float $latitudeB
 * @param float $longitudeA
 * @param float $longitudeB
 *
 * @return float
 */
function getDistance($latitudeA, $latitudeB, $longitudeA, $longitudeB)
{

    $dLat = deg2rad($latitudeB - $latitudeA);
    $dLon = deg2rad($longitudeB - $longitudeA);

    $sindLat = sin($dLat / 2) * sin($dLat / 2);
    $sindLon = sin($dLon / 2) * sin($dLon / 2);
    $cosLat  = cos(deg2rad($latitudeA)) * cos(deg2rad($latitudeB));
    $a       = $sindLat + $cosLat * $sindLon;
    $c       = 2 * asin(sqrt($a));

    return round(EARTH_RADIUS * $c * 1000, 0);
}

/**
 * @param array $items
 * @param array $perms
 *
 * @return array
 */
function getPermutations($items, $perms = [])
{
    static $permutations;
    if (empty($items)) {
        $permutations[] = $perms;
    } else {
        for ($i = count($items) - 1; $i >= 0; --$i) {
            $newitems = $items;
            $newperms = $perms;
            list($foo) = array_splice($newitems, $i, 1);
            array_unshift($newperms, $foo);
            getPermutations($newitems, $newperms);
        }
    }

    return $permutations;
}

/**
 * @param array $points
 *
 * @return array
 */
function getAllDistances($points)
{
    $distances = [];
    foreach ($points as $key1 => $point1) {
        foreach ($points as $key2 => $point2) {
            if ($key1 != $key2 && ! isset($distances["{$key2}-{$key1}"])) {
                $distances["{$key1}-{$key2}"] = getDistance($point1[0], $point2[0], $point1[1], $point2[1]);
            }
        }
    }

    return $distances;
}

$points        = [
    [41.408285, 2.216812],
    [41.392755, 2.185117],
    [41.406648, 2.183632],
    [41.414227, 2.183725],
];
$shortestRoute = shortestRoute($points);

echo implode('->', $shortestRoute['shortestRoute']);
echo PHP_EOL;
echo implode(
    ' -> ',
    array_map(
        function ($entry) {
            return implode(',', $entry);
        },
        $shortestRoute['shortestRouteCoordinates']
    )
);
echo PHP_EOL;
echo $shortestRoute['shortestDistance'];

echo PHP_EOL;
echo 'Distances in meters:';
echo PHP_EOL;
print_r($shortestRoute['distances']);
echo PHP_EOL;

