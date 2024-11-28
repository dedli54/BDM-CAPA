<?php

namespace Core\Middleware;

class Authenticated
{
    public function handle()
    {
        if (!($_SESSION['email'] ?? false)) {
            header('Location: /inicioSesion');
            exit();
        }
    }
}