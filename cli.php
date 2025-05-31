<?php

// include __DIR__ . '/vendor/autoload.php';

use Framework\Database;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$driver = $_ENV['DB_DRIVER'];
$username = $_ENV['DB_USER'];
$password = $_ENV['DB_PASS'];
$config = [
  'host' => $_ENV['DB_HOST'],
  'port' => $_ENV['DB_PORT'],
  'dbname' => $_ENV['DB_NAME'],
  'charset' => "utf8mb4"
];

$db = new Database($driver, $config, $username, $password);

$sqlFile = file_get_contents(__DIR__ . "/database.sql");
