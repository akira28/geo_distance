<?php

namespace Akira28\GeoDistance;

use GuzzleHttp\Client;

class GoogleDirection implements DistanceInterface
{
    private $client;

    public function __construct()
    {
        $this->client = new Client(
            ['base_uri' => 'https://maps.googleapis.com']
        );
    }

    /**
     * Get distance in meters
     *
     * @param Point $pointA
     * @param Point $pointB
     *
     * @return int
     */
    private function getDistance(Point $pointA, Point $pointB)
    {
        $response =
            $this->client->request(
                'GET',
                '/maps/api/directions/json',
                [
                    'query' =>
                        [
                            'origin'      => "{$pointA->getLatitude()},{$pointA->getLongitude()}",
                            'destination' => "{$pointB->getLatitude()},{$pointB->getLongitude()}",
                            'key'         => getenv('GOOGLEKEY'),
                        ],
                ]
            )->getBody()->getContents();

        return json_decode($response, true)['routes'][0]['legs'][0]['distance']['value'];
    }

    /**
     * @param array $points
     *
     * @return array
     */
    public function getAllDistances(array $points)
    {
        $distances = [];
        foreach ($points as $key1 => $point1) {
            foreach ($points as $key2 => $point2) {
                // differently from CrowFlies strategy that's based on haversine, here we can deal with different distances in inverse routes
                // Eg. 0->1 = 4732 and 1->0 = 4976
                // So we have to calculate all possible combinations
                if ($key1 != $key2) {
                    $p1                           = new Point($point1[0], $point1[1]);
                    $p2                           = new Point($point2[0], $point2[1]);
                    $distances["{$key1}-{$key2}"] = $this->getDistance($p1, $p2);
                }
            }
        }

        return $distances;
    }
}