# Shortest distance functions

`shortestRoute.php` contains a simple function to determine the distance 'as the crow flies' using the haversine formula to calculate distances, and a naive algorithm to solve the travelling salesman problem.

Executing `run.php` will run 2 different strategy, one using the haversine formula, and the other using Google directions api to determine distances.

To execute `run.php` you'll need to set and env value:

`GOOGLEKEY=123456789 php run.php`
