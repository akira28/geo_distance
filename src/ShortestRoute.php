<?php

namespace Akira28\GeoDistance;

class ShortestRoute
{
    private $strategy = null;

    /**
     * ShortestRoute constructor.
     *
     * @param string $strategyCode
     */
    public function __construct($strategyCode)
    {
        switch ($strategyCode) {
            case "googledirections":
                $this->strategy = new GoogleDirection();
                break;
            case "crowflies":
            default:
                $this->strategy = new CrowFlies();
                break;
        }
    }

    /**
     * @param array $points
     *
     * @return array
     */
    public function getShortestRoute(array $points)
    {

        $pointsKeys       = array_keys($points);
        $shortestDistance = 0;
        $distances        = $this->strategy->getAllDistances($points);

        $allRoutes   = $this->getPermutations($pointsKeys);
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
     * @param array $items
     * @param array $perms
     *
     * @return array
     */
    private function getPermutations($items, $perms = [])
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
                $this->getPermutations($newitems, $newperms);
            }
        }

        return $permutations;
    }

}