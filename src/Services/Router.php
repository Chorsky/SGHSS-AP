<?php
declare(strict_types=1);
namespace App\Services;

class Router {
    public static function handle(string $uri, string $method): void {
        
        // Remove barras extras e fragmenta a URI. O trim('/') garante que o primeiro item não seja vazio.
        $uri = trim($uri, '/');
        $segments = explode('/', $uri);
        
        // --- Endpoints de Autenticação ---
        if (isset($segments[1]) && $segments[1] === 'auth') {
            require_once __DIR__ . '/../Controllers/AuthController.php';
            $controller = new \App\Controllers\AuthController();

            if ($method === 'POST' && $segments[2] === 'login' && count($segments) === 3) {
                $controller->login(); // POST /api/auth/login
                return;
            }
        }
        
        // --- Endpoints de Pacientes ---
        if (isset($segments[1]) && $segments[1] === 'patients') {
            require_once __DIR__ . '/../Controllers/PatientController.php';
            $controller = new \App\Controllers\PatientController();

            $id = count($segments) === 3 ? (int)$segments[2] : null;

            switch ($method) {
                case 'GET':
                    if ($id) {
                        $controller->show($id); // GET /api/patients/{id}
                    } else {
                        $controller->index(); // GET /api/patients
                    }
                    return;
                
                case 'POST':
                    if (count($segments) === 2) {
                        $controller->store(); // POST /api/patients
                    }
                    return;
                
                case 'PUT':
                    if ($id) {
                        $controller->update($id); // PUT /api/patients/{id}
                    }
                    return;
                
                case 'DELETE':
                    if ($id) {
                        $controller->destroy($id); // DELETE /api/patients/{id}
                    }
                    return;

                default:
                    http_response_code(405); // Method Not Allowed
                    echo json_encode(['success' => false, 'message' => 'Método não permitido.']);
                    return;
            }
        }
        
        // Fallback para rotas não reconhecidas
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Endpoint não encontrado.']);
    }
}