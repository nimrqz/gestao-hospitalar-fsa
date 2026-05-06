<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Consulta;
use App\Models\Paciente;
use App\Models\Medico;

/**
 * Controller de Agendamento
 * 
 * Implementa o motor de agendamento com validação de conflitos.
 * Garante que um médico não tenha duas consultas no mesmo horário.
 */
class AgendamentoController extends BaseController
{
    private Consulta $consultaModel;
    private Paciente $pacienteModel;
    private Medico $medicoModel;

    public function __construct()
    {
        $this->consultaModel = new Consulta();
        $this->pacienteModel = new Paciente();
        $this->medicoModel = new Medico();
    }

    /**
     * Lista todos os agendamentos
     */
    public function index(): void
    {
        $this->verificarPermissao(['admin', 'recepcao', 'medico']);

        $consultas = $this->consultaModel->listarTodas();
        $this->render('agenda/index', ['consultas' => $consultas]);
    }

    /**
     * Exibe formulário e processa criação de agendamento
     */
    public function criar(): void
    {
        $this->verificarPermissao(['admin', 'recepcao']);

        $pacientes = $this->pacienteModel->listarTodos();
        $medicos = $this->medicoModel->listarTodos();

        if ($this->isPost()) {
            $dados = $this->getPostData([
                'paciente_id', 'medico_id', 'data_consulta',
                'hora_inicio', 'hora_fim', 'observacoes', 'exames_solicitados'
            ]);

            try {
                $novaConsultaId = $this->consultaModel->criar($dados);
                $this->redirecionar('/agenda');
            } catch (\Exception $e) {
                $this->render('agenda/form', [
                    'erro' => $e->getMessage(),
                    'consulta' => $dados,
                    'pacientes' => $pacientes,
                    'medicos' => $medicos,
                ]);
                return;
            }
        }

        $this->render('agenda/form', [
            'consulta' => null,
            'pacientes' => $pacientes,
            'medicos' => $medicos,
        ]);
    }

    /**
     * Exibe formulário e processa edição de agendamento
     */
    public function editar(): void
    {
        $this->verificarPermissao(['admin', 'recepcao']);

        $id = (int) ($_GET['id'] ?? 0);
        $consulta = $this->consultaModel->buscarPorId($id);

        if (!$consulta) {
            http_response_code(404);
            echo 'Consulta não encontrada.';
            return;
        }

        $pacientes = $this->pacienteModel->listarTodos();
        $medicos = $this->medicoModel->listarTodos();

        if ($this->isPost()) {
            $dados = $this->getPostData([
                'paciente_id', 'medico_id', 'data_consulta',
                'hora_inicio', 'hora_fim', 'status',
                'observacoes', 'exames_solicitados'
            ]);

            try {
                $this->consultaModel->atualizar($id, $dados);
                $this->redirecionar('/agenda');
            } catch (\Exception $e) {
                $this->render('agenda/form', [
                    'erro' => $e->getMessage(),
                    'consulta' => array_merge($consulta, $dados),
                    'pacientes' => $pacientes,
                    'medicos' => $medicos,
                ]);
                return;
            }
        }

        $this->render('agenda/form', [
            'consulta' => $consulta,
            'pacientes' => $pacientes,
            'medicos' => $medicos,
        ]);
    }

    /**
     * Atualiza status da consulta (ex: confirmar, cancelar)
     */
    public function atualizarStatus(): void
    {
        $this->verificarPermissao(['admin', 'recepcao', 'medico']);

        if (!$this->isPost()) {
            $this->redirecionar('/agenda');
        }

        $id = (int) ($_POST['id'] ?? 0);
        $status = $this->sanitizar($_POST['status'] ?? '');

        $statusPermitidos = ['agendada', 'confirmada', 'cancelada', 'realizada'];
        if (!in_array($status, $statusPermitidos, true)) {
            $this->redirecionar('/agenda?erro=status_invalido');
        }

        $this->consultaModel->atualizarStatus($id, $status);
        $this->redirecionar('/agenda');
    }

    /**
     * Remove um agendamento
     */
    public function deletar(): void
    {
        $this->verificarPermissao(['admin', 'recepcao']);

        $id = (int) ($_GET['id'] ?? 0);
        $this->consultaModel->deletar($id);
        $this->redirecionar('/agenda');
    }
}
