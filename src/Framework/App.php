<?php

declare(strict_types=1);

namespace Framework;

class App
{
  private Router $router;
  private Container $container;

  public function __construct(string | null $containerDefinitionsPath = null)
  {
    $this->router = new Router;
    $this->container = new Container;

    if ($containerDefinitionsPath) {
      $containerDefinitions = include $containerDefinitionsPath;
      $this->container->addDefinitions($containerDefinitions);
    }
  }

  public function run()
  {
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $method = $_SERVER['REQUEST_METHOD'];
    $this->router->dispatch($path, $method, $this->container);
  }

  // $app->get('/', [HomeController::class, 'home']);
  public function get(string $path, array $controller)
  {
    // public function add(string $method, string $path, array $controller)
    $this->router->add('GET', $path, $controller);
  }

  public function post(string $path, array $controller)
  {
    $this->router->add('POST', $path, $controller);
  }

  public function addMiddleware(string $middleware)
  {
    $this->router->addMiddleware($middleware);
  }
}
