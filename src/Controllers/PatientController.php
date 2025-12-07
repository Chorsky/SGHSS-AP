<?php
declare(strict_types=1);
namespace App\Controllers;

require_once __DIR__ . '/../Models/Patient.php';
use App\Models\Patient;

class PatientController {
    private Patient $patientModel;

    public function __construct() {
        $this->patientModel = new Patient();
    }

    // GET /api/patients
    public function index(): void {
        $headers = getallheaders();
        $token = $headers['Authorization'] ?? '';

        // Lógica simplificada de checagem (em produção, validaria o token no banco/cache)
        if (empty($token) || strpos($token, 'sghss_token_') === false) { 
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Acesso negado. Token de autorização é necessário.']);
            return; 
        }
        
        $patients = $this->patientModel->findAll();
        http_response_code(200); // OK
        echo json_encode(['success' => true, 'data' => $patients]);
    }

    // GET /api/patients/{id}
    public function show(int $id): void {
        $headers = getallheaders();
        $token = $headers['Authorization'] ?? '';

        // Lógica simplificada de checagem (em produção, validaria o token no banco/cache)
        if (empty($token) || strpos($token, 'sghss_token_') === false) { 
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Acesso negado. Token de autorização é necessário.']);
            return; 
        }
        
        $patient = $this->patientModel->find($id);
        
        if ($patient) {
            http_response_code(200);
            echo json_encode(['success' => true, 'data' => $patient]);
        } else {
            http_response_code(404); // Not Found
            echo json_encode(['success' => false, 'message' => 'Paciente não encontrado.']);
        }
    }

    // POST /api/patients
    public function store(): void {
        $headers = getallheaders();
        $token = $headers['Authorization'] ?? '';

        // Lógica simplificada de checagem (em produção, validaria o token no banco/cache)
        if (empty($token) || strpos($token, 'sghss_token_') === false) { 
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Acesso negado. Token de autorização é necessário.']);
            return; 
        }
        
        // Verifica se a requisição é JSON
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || !isset($data['full_name'], $data['cpf'])) {
            http_response_code(400); // Bad Request
            echo json_encode(['success' => false, 'message' => 'Dados incompletos.']);
            return;
        }

        if ($this->patientModel->create($data['full_name'], $data['cpf'], $data['birth_date'] ?? '', $data['medical_history'] ?? '')) {
            http_response_code(201); // Created
            echo json_encode(['success' => true, 'message' => 'Paciente criado com sucesso!']);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['success' => false, 'message' => 'Erro ao salvar o paciente.']);
        }
    }

    // PUT /api/patients/{id}
    public function update(int $id): void {
        // TODO: Implementar checagem de Autorização/Token aqui!
        
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || !isset($data['full_name'], $data['cpf'])) {
            http_response_code(400); // Bad Request
            echo json_encode(['success' => false, 'message' => 'Dados incompletos para atualização.']);
            return;
        }

        if ($this->patientModel->update($id, $data['full_name'], $data['cpf'], $data['birth_date'] ?? '', $data['medical_history'] ?? '')) {
            http_response_code(200); // OK
            echo json_encode(['success' => true, 'message' => "Paciente ID $id atualizado com sucesso!"]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro ao atualizar o paciente.']);
        }
    }

    // DELETE /api/patients/{id}
    public function destroy(int $id): void {
        // TODO: Implementar checagem de Autorização/Token aqui!
        
        if ($this->patientModel->delete($id)) {
            http_response_code(204); // No Content (Padrão para DELETE)
            // Não retorna corpo em 204
        } else {
            http_response_code(404); // Not Found ou 500
            echo json_encode(['success' => false, 'message' => 'Erro ao deletar ou paciente não encontrado.']);
        }
    }
}