<?php

declare(strict_types=1);

include __DIR__ . '/../../vendor/autoload.php';


use Framework\App;
use App\Config\paths;
use function App\Config\{registerRoutes, registerMiddleware};
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(Paths::ROOT);
$dotenv->load();

$app = new App(Paths::SOURCE . "app/container-definitions.php");

registerRoutes($app);
registerMiddleware($app);

return $app;
