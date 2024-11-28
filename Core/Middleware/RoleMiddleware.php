<?php

namespace Core\Middleware;

class RoleMiddleware
{
    public function handle($role)
    {
        $userRole = $_SESSION['user_type'] ?? null;

        switch ($role) {
            case 'admin':
                if ($userRole != '3') {
                    $this->denyAccess();
                }
                break;

            case 'teacher':
                if ($userRole != '2') {
                    $this->denyAccess();
                }
                break;

            case 'student':
                if ($userRole != '1') {
                    $this->denyAccess();
                }
                break;

            default:
                throw new \Exception("No se reconoce el rol '{$role}'.");
        }
    }

    protected function denyAccess()
    {
        header('Location: /BDM-CAPA/Views/unauthorized.php'); // Página de acceso denegado
        exit();
    }
}