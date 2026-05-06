<?php

declare(strict_types=1);

namespace App\Models;

use Config\Database;
use PDO;

class Medico
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function listarTodos(): array
    {
        $stmt = $this->db->query('SELECT * FROM medicos ORDER BY nome');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM medicos WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $medico = $stmt->fetch(PDO::FETCH_ASSOC);
        return $medico ?: null;
    }

    public function criar(array $dados): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO medicos (usuario_id, nome, crm, especialidade, telefone, email) 
             VALUES (:usuario_id, :nome, :crm, :especialidade, :telefone, :email)'
        );
        $stmt->execute([
            ':usuario_id' => $dados['usuario_id'] ?? null,
            ':nome' => $dados['nome'],
            ':crm' => $dados['crm'],
            ':especialidade' => $dados['especialidade'],
            ':telefone' => $dados['telefone'] ?? null,
            ':email' => $dados['email'] ?? null,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function atualizar(int $id, array $dados): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE medicos SET 
                usuario_id = :usuario_id,
                nome = :nome,
                crm = :crm,
                especialidade = :especialidade,
                telefone = :telefone,
                email = :email
             WHERE id = :id'
        );
        return $stmt->execute([
            ':id' => $id,
            ':usuario_id' => $dados['usuario_id'] ?? null,
            ':nome' => $dados['nome'],
            ':crm' => $dados['crm'],
            ':especialidade' => $dados['especialidade'],
            ':telefone' => $dados['telefone'] ?? null,
            ':email' => $dados['email'] ?? null,
        ]);
    }

    public function deletar(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM medicos WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }
}
