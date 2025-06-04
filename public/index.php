<?php

include __DIR__ . '/../src/App/functions.php';

$app = include __DIR__ . '/../src/App/bootstrap.php';

$app->run();

include __DIR__ . '/../cli.php';

// try {
//   $db->connection->beginTransaction();

//   $db->connection->query("INSERT INTO products (name) VALUES('Gloves')");

//   $stmt = $db->connection->prepare('SELECT * FROM products WHERE name=:name');
//   $stmt->bindValue(':name', 'Gloves', PDO::PARAM_STR);
//   $stmt->execute();
//   var_dump($stmt->fetchAll(PDO::FETCH_ASSOC));

//   $db->connection->commit();
// } catch (Exception $e) {
//   if ($db->connection->inTransaction()) {
//     $db->connection->rollBack();
//   }

//   echo $e->getMessage();
// }



/** 
 * Middlewares calling under the hood
 * */

// $middlewares = [
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

// foreach ($middlewares as $mid) {
//   $a = fn() => $mid($a);
// }

// $a();
