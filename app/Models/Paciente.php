<?php

declare(strict_types=1);

namespace App\Models;

use Config\Database;
use PDO;

class Paciente
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function listarTodos(): array
    {
        $stmt = $this->db->query('SELECT * FROM pacientes ORDER BY nome');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM pacientes WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $paciente = $stmt->fetch(PDO::FETCH_ASSOC);
        return $paciente ?: null;
    }

    public function buscarPorNomeOuCpf(string $termo): array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM pacientes WHERE nome LIKE :termo OR cpf LIKE :termo ORDER BY nome'
        );
        $stmt->execute([':termo' => '%' . $termo . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function criar(array $dados): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO pacientes (nome, cpf, data_nascimento, telefone, email, endereco, convenio, numero_carteirinha) 
             VALUES (:nome, :cpf, :data_nascimento, :telefone, :email, :endereco, :convenio, :numero_carteirinha)'
        );
        $stmt->execute([
            ':nome' => $dados['nome'],
            ':cpf' => $dados['cpf'],
            ':data_nascimento' => $dados['data_nascimento'],
            ':telefone' => $dados['telefone'] ?? null,
            ':email' => $dados['email'] ?? null,
            ':endereco' => $dados['endereco'] ?? null,
            ':convenio' => $dados['convenio'] ?? null,
            ':numero_carteirinha' => $dados['numero_carteirinha'] ?? null,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function atualizar(int $id, array $dados): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE pacientes SET 
                nome = :nome,
                cpf = :cpf,
                data_nascimento = :data_nascimento,
                telefone = :telefone,
                email = :email,
                endereco = :endereco,
                convenio = :convenio,
                numero_carteirinha = :numero_carteirinha
             WHERE id = :id'
        );
        return $stmt->execute([
            ':id' => $id,
            ':nome' => $dados['nome'],
            ':cpf' => $dados['cpf'],
            ':data_nascimento' => $dados['data_nascimento'],
            ':telefone' => $dados['telefone'] ?? null,
            ':email' => $dados['email'] ?? null,
            ':endereco' => $dados['endereco'] ?? null,
            ':convenio' => $dados['convenio'] ?? null,
            ':numero_carteirinha' => $dados['numero_carteirinha'] ?? null,
        ]);
    }

    public function deletar(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM pacientes WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }
}
