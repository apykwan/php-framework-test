<?php

include __DIR__ . '/../src/App/functions.php';
$app = include __DIR__ . '/../src/App/bootstrap.php';

$app->run();

// middleware calling under the hood
// $functions = [
//   function ($next) {
//     echo "A <br>";
//     $next();
//   },
//   function ($next) {
//     echo "B <br>";
//     $next();
//   },
//   function ($next) {
//     echo "C <br>";
//     $next();
//   }
// ];

// $a = function () {
//   echo "Main Content <br>";
// };

// foreach ($functions as $function) {
//   $a = fn() => $function($a);
// }

// $a();
