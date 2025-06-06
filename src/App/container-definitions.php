<?php

declare(strict_types=1);

use Framework\{TemplateEngine, Database, Container};
use App\Config\Paths;
use App\Services\{ValidatorService, UserService, TransactionService};

$driver = $_ENV['DB_DRIVER'];
$username = $_ENV['DB_USER'];
$password = $_ENV['DB_PASS'];
$config = [
  'host' => $_ENV['DB_HOST'],
  'port' => $_ENV['DB_PORT'],
  'dbname' => $_ENV['DB_NAME'],
  'charset' => "utf8mb4"
];

return [
  TemplateEngine::class => fn() => new TemplateEngine(Paths::VIEW),
  ValidatorService::class => fn() => new ValidatorService,
  Database::class => fn() => new Database($driver, $config, $username, $password),
  userService::class => function (Container $container) {
    $db = $container->get(Database::class);

    return new UserService($db);
  },
  TransactionService::class => function (Container $container) {
    $db = $container->get(Database::class);

    return new TransactionService($db);
  }
];
