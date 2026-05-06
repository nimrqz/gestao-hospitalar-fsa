<?php

declare(strict_types=1);

namespace App\Models;

use Config\Database;
use PDO;
use PDOException;

class Usuario
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function buscarPorEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM usuarios WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        return $usuario ?: null;
    }

    public function buscarPorId(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM usuarios WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        return $usuario ?: null;
    }

    public function listarTodos(): array
    {
        $stmt = $this->db->query('SELECT id, nome, email, perfil, ativo, created_at FROM usuarios ORDER BY nome');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function criar(array $dados): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO usuarios (nome, email, senha_hash, perfil, ativo) 
             VALUES (:nome, :email, :senha_hash, :perfil, :ativo)'
        );
        $stmt->execute([
            ':nome' => $dados['nome'],
            ':email' => $dados['email'],
            ':senha_hash' => password_hash($dados['senha'], PASSWORD_BCRYPT),
            ':perfil' => $dados['perfil'],
            ':ativo' => $dados['ativo'] ?? 1,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function atualizar(int $id, array $dados): bool
    {
        $campos = [];
        $params = [':id' => $id];

        if (isset($dados['nome'])) {
            $campos[] = 'nome = :nome';
            $params[':nome'] = $dados['nome'];
        }
        if (isset($dados['email'])) {
            $campos[] = 'email = :email';
            $params[':email'] = $dados['email'];
        }
        if (isset($dados['perfil'])) {
            $campos[] = 'perfil = :perfil';
            $params[':perfil'] = $dados['perfil'];
        }
        if (isset($dados['ativo'])) {
            $campos[] = 'ativo = :ativo';
            $params[':ativo'] = $dados['ativo'];
        }
        if (isset($dados['senha']) && !empty($dados['senha'])) {
            $campos[] = 'senha_hash = :senha_hash';
            $params[':senha_hash'] = password_hash($dados['senha'], PASSWORD_BCRYPT);
        }

        if (empty($campos)) {
            return false;
        }

        $sql = 'UPDATE usuarios SET ' . implode(', ', $campos) . ' WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function deletar(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM usuarios WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }
}
