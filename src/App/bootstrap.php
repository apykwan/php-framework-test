<?php

declare(strict_types=1);

include __DIR__ . '/../../vendor/autoload.php';

use Framework\App;
use App\Config\paths;

use function App\Config\{registerRoutes, registerMiddleware};

$app = new App(Paths::SOURCE . "app/container-definitions.php");

registerRoutes($app);
registerMiddleware($app);

return $app;
