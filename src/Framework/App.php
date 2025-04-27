<?php

declare(strict_types=1);

namespace Framework;

class App
{
  private Router $router;

  public function __construct()
  {
    $this->router = new Router();
  }

  public function run()
  {
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $method = $_SERVER['REQUEST_METHOD'];
    $this->router->dispatch($path, $method);
  }

  // $app->get('/', [HomeController::class, 'home']);
  public function get(string $path, array $controller)
  {
    // public function add(string $method, string $path, array $controller)
    $this->router->add('GET', $path, $controller);
  }
}
