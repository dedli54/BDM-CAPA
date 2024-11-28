<?php

namespace Core\Middleware;

class Middleware
{
    public const MAP = [
        'guest' => Guest::class,
        'auth' => Authenticated::class,
        'role' => RoleMiddleware::class, // Nuevo middleware
    ];

    public static function resolve($key, $args = [])
    {
        if (!$key) {
            return;
        }

        $middleware = static::MAP[$key] ?? false;

        if (!$middleware) {
            throw new \Exception("No matching middleware found for key '{$key}'.");
        }

        
        (new $middleware)->handle(...$args);
    }
}