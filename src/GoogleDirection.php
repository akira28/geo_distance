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
     * @return int
     */
    public function getDistance(Point $pointA, Point $pointB)
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
}