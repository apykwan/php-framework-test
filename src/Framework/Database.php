<?php

declare(strict_types=1);

namespace Framework;

use \PDO, \PDOException;

class Database
{
  private PDO $connection;

  public function __construct(string $driver, array $config, string $username, string $password)
  {
    $query = http_build_query($config, '', ';');
    $dsn = "{$driver}:{$query}";

    $options = [
      PDO::ATTR_PERSISTENT => true,
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ];

    try {
      $this->connection = new PDO($dsn, $username, $password, $options);
    } catch (PDOException $e) {
      echo "Connection failed: " . $e->getMessage();
      die("Unable to connect to database");
    }
  }

  public function query(string $query)
  {
    $this->connection->query($query);
  }
}
