<?php

declare(strict_types=1);

namespace Framework;

class Router
{
  private array $routes = [];
  private array $middlewares = [];

  public function add(string $method, string $path, array $controller)
  {
    $path = $this->normalizePath($path);
    $this->routes[] = [
      'path' => $path,
      'method' => strtoupper($method),
      'controller' => $controller,
      'middlewares' => []
    ];
  }

  private function normalizePath(string $path): string
  {
    $path = trim($path, '/');
    $path = "/{$path}/";
    $path = preg_replace('#[/]{2,}#', '/', $path);
    return $path;
  }

  public function dispatch(string $path, string $method, Container | null $container = null)
  {
    $path = $this->normalizePath($path);
    $method = strtoupper($method);

    foreach ($this->routes as $route) {
      if (!preg_match("#^{$route['path']}$#", $path) || $route['method'] !== $method) continue;

      [$class, $function] = $route['controller'];
      $conrtollerInstance = $container ? $container->resolve($class) : new $class;

      // Full middleware execution chain
      // Run the middlewares first before the controller function
      $action = fn() => $conrtollerInstance->$function();

      $allMiddlewares = [...$route['middlewares'], ...$this->middlewares];

      foreach ($allMiddlewares as $middleware) {
        $middlewareInstance = $container ? $container->resolve($middleware) : new $middleware;
        $action = fn() => $middlewareInstance->process($action);
      }

      $action();
      return;
    }
  }

  public function addMiddleware(string $middleware)
  {
    $this->middlewares[] = $middleware;
  }

  public function addRouteMiddleware(string $middleware)
  {
    $lastRouteKey = array_key_last($this->routes);
    $this->routes[$lastRouteKey]['middlewares'][] = $middleware;
  }
}
