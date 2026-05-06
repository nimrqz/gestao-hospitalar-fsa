<?php

declare(strict_types=1);

namespace App\Models;

use Config\Database;
use PDO;
use PDOException;

/**
 * Model de Consulta - Motor de Agendamento
 * 
 * Responsável pela lógica de agendamento com validação de conflitos de horário.
 * Garante que um médico não tenha duas consultas no mesmo horário.
 */
class Consulta
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Lista todas as consultas com dados relacionados
     */
    public function listarTodas(): array
    {
        $sql = 'SELECT 
                    c.*,
                    p.nome AS paciente_nome,
                    m.nome AS medico_nome,
                    m.especialidade
                FROM consultas c
                INNER JOIN pacientes p ON c.paciente_id = p.id
                INNER JOIN medicos m ON c.medico_id = m.id
                ORDER BY c.data_consulta DESC, c.hora_inicio ASC';
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lista consultas por médico
     */
    public function listarPorMedico(int $medicoId): array
    {
        $sql = 'SELECT 
                    c.*,
                    p.nome AS paciente_nome,
                    m.nome AS medico_nome
                FROM consultas c
                INNER JOIN pacientes p ON c.paciente_id = p.id
                INNER JOIN medicos m ON c.medico_id = m.id
                WHERE c.medico_id = :medico_id
                ORDER BY c.data_consulta DESC, c.hora_inicio ASC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':medico_id' => $medicoId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lista consultas por paciente
     */
    public function listarPorPaciente(int $pacienteId): array
    {
        $sql = 'SELECT 
                    c.*,
                    p.nome AS paciente_nome,
                    m.nome AS medico_nome,
                    m.especialidade
                FROM consultas c
                INNER JOIN pacientes p ON c.paciente_id = p.id
                INNER JOIN medicos m ON c.medico_id = m.id
                WHERE c.paciente_id = :paciente_id
                ORDER BY c.data_consulta DESC, c.hora_inicio ASC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':paciente_id' => $pacienteId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId(int $id): ?array
    {
        $sql = 'SELECT 
                    c.*,
                    p.nome AS paciente_nome,
                    m.nome AS medico_nome,
                    m.especialidade
                FROM consultas c
                INNER JOIN pacientes p ON c.paciente_id = p.id
                INNER JOIN medicos m ON c.medico_id = m.id
                WHERE c.id = :id
                LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $consulta = $stmt->fetch(PDO::FETCH_ASSOC);
        return $consulta ?: null;
    }

    /**
     * Cria um agendamento validando conflitos de horário
     *
     * @throws \Exception Se houver conflito de horário
     */
    public function criar(array $dados): int
    {
        $this->validarConflito(
            (int) $dados['medico_id'],
            $dados['data_consulta'],
            $dados['hora_inicio'],
            $dados['hora_fim']
        );

        $stmt = $this->db->prepare(
            'INSERT INTO consultas 
                (paciente_id, medico_id, data_consulta, hora_inicio, hora_fim, status, observacoes, exames_solicitados) 
             VALUES 
                (:paciente_id, :medico_id, :data_consulta, :hora_inicio, :hora_fim, :status, :observacoes, :exames_solicitados)'
        );
        $stmt->execute([
            ':paciente_id' => $dados['paciente_id'],
            ':medico_id' => $dados['medico_id'],
            ':data_consulta' => $dados['data_consulta'],
            ':hora_inicio' => $dados['hora_inicio'],
            ':hora_fim' => $dados['hora_fim'],
            ':status' => $dados['status'] ?? 'agendada',
            ':observacoes' => $dados['observacoes'] ?? null,
            ':exames_solicitados' => $dados['exames_solicitados'] ?? null,
        ]);
        return (int) $this->db->lastInsertId();
    }

    /**
     * Atualiza um agendamento validando conflitos de horário (exceto a própria consulta)
     *
     * @throws \Exception Se houver conflito de horário
     */
    public function atualizar(int $id, array $dados): bool
    {
        $this->validarConflito(
            (int) $dados['medico_id'],
            $dados['data_consulta'],
            $dados['hora_inicio'],
            $dados['hora_fim'],
            $id
        );

        $stmt = $this->db->prepare(
            'UPDATE consultas SET 
                paciente_id = :paciente_id,
                medico_id = :medico_id,
                data_consulta = :data_consulta,
                hora_inicio = :hora_inicio,
                hora_fim = :hora_fim,
                status = :status,
                observacoes = :observacoes,
                exames_solicitados = :exames_solicitados
             WHERE id = :id'
        );
        return $stmt->execute([
            ':id' => $id,
            ':paciente_id' => $dados['paciente_id'],
            ':medico_id' => $dados['medico_id'],
            ':data_consulta' => $dados['data_consulta'],
            ':hora_inicio' => $dados['hora_inicio'],
            ':hora_fim' => $dados['hora_fim'],
            ':status' => $dados['status'] ?? 'agendada',
            ':observacoes' => $dados['observacoes'] ?? null,
            ':exames_solicitados' => $dados['exames_solicitados'] ?? null,
        ]);
    }

    public function atualizarStatus(int $id, string $status): bool
    {
        $stmt = $this->db->prepare('UPDATE consultas SET status = :status WHERE id = :id');
        return $stmt->execute([':id' => $id, ':status' => $status]);
    }

    public function deletar(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM consultas WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Valida se existe conflito de horário para o médico
     *
     * @throws \Exception
     */
    private function validarConflito(
        int $medicoId,
        string $data,
        string $horaInicio,
        string $horaFim,
        ?int $excluirConsultaId = null
    ): void {
        $sql = 'SELECT COUNT(*) FROM consultas 
                WHERE medico_id = :medico_id 
                  AND data_consulta = :data_consulta 
                  AND status != "cancelada"
                  AND (
                      (hora_inicio < :hora_fim AND hora_fim > :hora_inicio)
                  )';
        $params = [
            ':medico_id' => $medicoId,
            ':data_consulta' => $data,
            ':hora_inicio' => $horaInicio,
            ':hora_fim' => $horaFim,
        ];

        if ($excluirConsultaId !== null) {
            $sql .= ' AND id != :id';
            $params[':id'] = $excluirConsultaId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $conflitos = (int) $stmt->fetchColumn();

        if ($conflitos > 0) {
            throw new \Exception('Conflito de horário: o médico já possui uma consulta neste período.');
        }
    }
}
