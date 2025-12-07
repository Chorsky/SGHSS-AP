<?php
declare(strict_types=1);
namespace App\Controllers;

require_once __DIR__ . '/../../config/database.php';
use Database;
use PDO;

class AuthController {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    // POST /api/auth/login
    public function login(): void {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['email'], $data['password'])) {
            http_response_code(400); // Bad Request
            echo json_encode(['success' => false, 'message' => 'Email e senha são obrigatórios.']);
            return;
        }

        $stmt = $this->pdo->prepare("SELECT id, password_hash, role FROM users WHERE email = ?");
        $stmt->execute([$data['email']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($data['password'], $user['password_hash'])) {
            // Autenticação bem-sucedida
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => 'Autenticação realizada com sucesso.',
                'data' => [
                    'user_id' => $user['id'],
                    'role' => $user['role'],
                    'token' => 'sghss_token_' . bin2hex(random_bytes(16)) // Geração de token simples
                ]
            ]);
        } else {
            http_response_code(401); // Unauthorized
            echo json_encode(['success' => false, 'message' => 'Credenciais inválidas ou usuário não encontrado.']);
        }
    }
}