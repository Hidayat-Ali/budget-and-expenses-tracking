<?php

declare(strict_types=1);

namespace App;
// the exception file is required here
use App\Exceptions\RouteNotFoundException;

class Router
{
    // private access modifier
    private array $routes;

    public function register(string $requestMethod, string $route, callable|array $action): self
    {
        $this->routes[$requestMethod][$route] = $action;

        return $this;
    }

    public function get(string $route, callable|array $action): self
    {
        return $this->register('get', $route, $action);
    }

    public function post(string $route, callable|array $action): self
    {
        return $this->register('post', $route, $action);
    }

    public function routes(): array
    {
        return $this->routes;
    }

    public function resolve(string $requestUri, string $requestMethod)
    {
        $route = explode('?', $requestUri)[0];
        $action = $this->routes[$requestMethod][$route] ?? null;

        if (!$action) {
            // if not valid route wil throw an exception
            throw new RouteNotFoundException();
        }
        // it checks whether the varaible is called as function or not
        if (is_callable($action)) {

            return call_user_func($action);
        }
        // checks whether it is array or not
        if (is_array($action)) {
            // destructuring of array
            [$class, $method] = $action;
        //   checks if this class exists and difined ?
            if (class_exists($class)) {
                $class = new $class();
            //    checks if these method exists?
                if (method_exists($class, $method)) {
                    return call_user_func_array([$class, $method], []);
                }
            }
        }

        throw new RouteNotFoundException();
    }
}
