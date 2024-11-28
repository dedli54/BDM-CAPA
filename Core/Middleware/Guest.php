<?php

namespace Core\Middleware;

class Guest
{
    public function handle()
    {
        if ($_SESSION['email'] ?? false) {
            header('Location: /');
            exit();
        }
    }
}