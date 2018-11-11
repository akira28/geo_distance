#!/usr/bin/env php
<?php
require __DIR__ . '/vendor/autoload.php';

use \Akira28\GeoDistance\ShortestRoute;

if(empty(getenv('GOOGLEKEY'))) {
    echo 'You should provide a google key as an env variable: GOOGLEKEY=123456789 php run.php';
    echo PHP_EOL;
    exit;
}

function printRoute($route)
{
    echo implode('->', $route['shortestRoute']);
    echo PHP_EOL;
    echo implode(
        ' -> ',
        array_map(
            function ($entry) {
                return implode(',', $entry);
            },
            $route['shortestRouteCoordinates']
        )
    );
    echo PHP_EOL;
    echo $route['shortestDistance'];
    echo PHP_EOL;
    echo 'Distances in meters:';
    echo PHP_EOL;
    print_r($route['distances']);
}

$points    = [
    [41.408285, 2.216812],
    [41.392755, 2.185117],
    [41.406648, 2.183632],
    [41.414227, 2.183725],
];

$shortestRoute = new ShortestRoute('crowflies');

$crowflies        = $shortestRoute->getShortestRoute($points);

$shortestRoute = new ShortestRoute('googledirections');
$googleDirections = $shortestRoute->getShortestRoute($points);


echo 'CROWFLIES RESULT';
echo PHP_EOL;
printRoute($crowflies);

echo 'GOOGLE DIRECTIONS RESULT';
echo PHP_EOL;
printRoute($googleDirections);
