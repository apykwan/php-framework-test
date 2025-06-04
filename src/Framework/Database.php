<?php

declare(strict_types=1);

namespace Framework;

use PDO, PDOException, PDOStatement;

class Database
{
  private PDO $connection;
  private PDOStatement $stmt;

  public function __construct(string $driver, array $config, string $username, string $password)
  {
    $query = http_build_query($config, '', ';');
    $dsn = "{$driver}:{$query}";

    $options = [
      PDO::ATTR_PERSISTENT => true,
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ];

    try {
      $this->connection = new PDO($dsn, $username, $password, $options);
    } catch (PDOException $e) {
      echo "Connection failed: " . $e->getMessage();
      die("Unable to connect to database");
    }
  }

  public function query(string $query, array $params = [])
  {
    $this->stmt = $this->connection->prepare($query);

    $this->stmt->execute($params);

    return $this;
  }

  public function count()
  {
    return $this->stmt->fetchColumn();
  }

  public function find(): array|false
  {
    return $this->stmt->fetch();
  }

  public function findAll(): array
  {
    return $this->stmt->fetchAll();
  }

  public function id()
  {
    return $this->connection->lastInsertId();
  }
}
