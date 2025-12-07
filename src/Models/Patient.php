<?php
declare(strict_types=1);
namespace App\Models;

require_once __DIR__ . '/../../config/database.php';
use Database;
use PDO;

class Patient {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    // CREATE (C) - Cadastrar um novo paciente
    public function create(string $name, string $cpf, string $birthDate, string $history): bool {
        // Prevenção de SQL Injection com Prepared Statements
        $stmt = $this->pdo->prepare("INSERT INTO patients (full_name, cpf, birth_date, medical_history) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$name, $cpf, $birthDate, $history]);
    }

    // READ (R) - Buscar paciente por ID
    public function find(int $id): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM patients WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ?: null;
    }

    // READ (R) - Listar todos os pacientes
    public function findAll(): array {
        $stmt = $this->pdo->query("SELECT * FROM patients ORDER BY full_name");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // UPDATE (U) - Atualizar dados do paciente
    public function update(int $id, string $name, string $cpf, string $birthDate, string $history): bool {
        // Uso de Prepared Statements para atualizar múltiplos campos
        $sql = "UPDATE patients 
                SET full_name = ?, cpf = ?, birth_date = ?, medical_history = ? 
                WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        
        // A ordem dos parâmetros no array DEVE ser a mesma dos "?" na query SQL
        return $stmt->execute([$name, $cpf, $birthDate, $history, $id]);
    }

    // DELETE (D) - Excluir paciente por ID
    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM patients WHERE id = ?");
        return $stmt->execute([$id]);
    }
}