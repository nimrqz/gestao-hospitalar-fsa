<?php

declare(strict_types=1);

namespace App\Models;

use Config\Database;
use PDO;

/**
 * Model de Prontuário
 * 
 * Gerencia o histórico clínico dos pacientes, vinculado a consultas.
 */
class Prontuario
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Lista todos os prontuários com dados relacionados
     */
    public function listarTodos(): array
    {
        $sql = 'SELECT 
                    pr.*,
                    p.nome AS paciente_nome,
                    m.nome AS medico_nome,
                    c.data_consulta,
                    c.hora_inicio,
                    c.exames_solicitados
                FROM prontuarios pr
                INNER JOIN pacientes p ON pr.paciente_id = p.id
                INNER JOIN medicos m ON pr.medico_id = m.id
                INNER JOIN consultas c ON pr.consulta_id = c.id
                ORDER BY c.data_consulta DESC, c.hora_inicio DESC';
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lista prontuários por paciente
     */
    public function listarPorPaciente(int $pacienteId): array
    {
        $sql = 'SELECT 
                    pr.*,
                    p.nome AS paciente_nome,
                    m.nome AS medico_nome,
                    m.especialidade,
                    c.data_consulta,
                    c.hora_inicio,
                    c.exames_solicitados
                FROM prontuarios pr
                INNER JOIN pacientes p ON pr.paciente_id = p.id
                INNER JOIN medicos m ON pr.medico_id = m.id
                INNER JOIN consultas c ON pr.consulta_id = c.id
                WHERE pr.paciente_id = :paciente_id
                ORDER BY c.data_consulta DESC, c.hora_inicio DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':paciente_id' => $pacienteId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId(int $id): ?array
    {
        $sql = 'SELECT 
                    pr.*,
                    p.nome AS paciente_nome,
                    m.nome AS medico_nome,
                    m.especialidade,
                    c.data_consulta,
                    c.hora_inicio,
                    c.hora_fim,
                    c.exames_solicitados
                FROM prontuarios pr
                INNER JOIN pacientes p ON pr.paciente_id = p.id
                INNER JOIN medicos m ON pr.medico_id = m.id
                INNER JOIN consultas c ON pr.consulta_id = c.id
                WHERE pr.id = :id
                LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $prontuario = $stmt->fetch(PDO::FETCH_ASSOC);
        return $prontuario ?: null;
    }

    public function buscarPorConsulta(int $consultaId): ?array
    {
        $sql = 'SELECT 
                    pr.*,
                    p.nome AS paciente_nome,
                    m.nome AS medico_nome,
                    m.especialidade,
                    c.data_consulta,
                    c.hora_inicio,
                    c.hora_fim,
                    c.exames_solicitados
                FROM prontuarios pr
                INNER JOIN pacientes p ON pr.paciente_id = p.id
                INNER JOIN medicos m ON pr.medico_id = m.id
                INNER JOIN consultas c ON pr.consulta_id = c.id
                WHERE pr.consulta_id = :consulta_id
                LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':consulta_id' => $consultaId]);
        $prontuario = $stmt->fetch(PDO::FETCH_ASSOC);
        return $prontuario ?: null;
    }

    public function criar(array $dados): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO prontuarios 
                (consulta_id, paciente_id, medico_id, anamnese, diagnostico, prescricao, exames_realizados, observacoes) 
             VALUES 
                (:consulta_id, :paciente_id, :medico_id, :anamnese, :diagnostico, :prescricao, :exames_realizados, :observacoes)'
        );
        $stmt->execute([
            ':consulta_id' => $dados['consulta_id'],
            ':paciente_id' => $dados['paciente_id'],
            ':medico_id' => $dados['medico_id'],
            ':anamnese' => $dados['anamnese'] ?? null,
            ':diagnostico' => $dados['diagnostico'] ?? null,
            ':prescricao' => $dados['prescricao'] ?? null,
            ':exames_realizados' => $dados['exames_realizados'] ?? null,
            ':observacoes' => $dados['observacoes'] ?? null,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function atualizar(int $id, array $dados): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE prontuarios SET 
                anamnese = :anamnese,
                diagnostico = :diagnostico,
                prescricao = :prescricao,
                exames_realizados = :exames_realizados,
                observacoes = :observacoes
             WHERE id = :id'
        );
        return $stmt->execute([
            ':id' => $id,
            ':anamnese' => $dados['anamnese'] ?? null,
            ':diagnostico' => $dados['diagnostico'] ?? null,
            ':prescricao' => $dados['prescricao'] ?? null,
            ':exames_realizados' => $dados['exames_realizados'] ?? null,
            ':observacoes' => $dados['observacoes'] ?? null,
        ]);
    }

    public function deletar(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM prontuarios WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }
}
