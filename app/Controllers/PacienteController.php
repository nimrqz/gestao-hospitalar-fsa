<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Paciente;

/**
 * Controller de Pacientes
 * 
 * Gerencia o CRUD completo de pacientes.
 */
class PacienteController extends BaseController
{
    private Paciente $pacienteModel;

    public function __construct()
    {
        $this->pacienteModel = new Paciente();
    }

    public function index(): void
    {
        $this->verificarPermissao(['admin', 'recepcao', 'medico']);
        $pacientes = $this->pacienteModel->listarTodos();
        $this->render('pacientes/index', ['pacientes' => $pacientes]);
    }

    public function criar(): void
    {
        $this->verificarPermissao(['admin', 'recepcao']);

        if ($this->isPost()) {
            $dados = $this->getPostData([
                'nome', 'cpf', 'data_nascimento', 'telefone',
                'email', 'endereco', 'convenio', 'numero_carteirinha'
            ]);

            try {
                $this->pacienteModel->criar($dados);
                $this->redirecionar('/pacientes');
            } catch (\Exception $e) {
                $this->render('pacientes/form', [
                    'erro' => $e->getMessage(),
                    'paciente' => $dados,
                ]);
                return;
            }
        }

        $this->render('pacientes/form', ['paciente' => null]);
    }

    public function editar(): void
    {
        $this->verificarPermissao(['admin', 'recepcao']);

        $id = (int) ($_GET['id'] ?? 0);
        $paciente = $this->pacienteModel->buscarPorId($id);

        if (!$paciente) {
            http_response_code(404);
            echo 'Paciente não encontrado.';
            return;
        }

        if ($this->isPost()) {
            $dados = $this->getPostData([
                'nome', 'cpf', 'data_nascimento', 'telefone',
                'email', 'endereco', 'convenio', 'numero_carteirinha'
            ]);

            try {
                $this->pacienteModel->atualizar($id, $dados);
                $this->redirecionar('/pacientes');
            } catch (\Exception $e) {
                $this->render('pacientes/form', [
                    'erro' => $e->getMessage(),
                    'paciente' => array_merge($paciente, $dados),
                ]);
                return;
            }
        }

        $this->render('pacientes/form', ['paciente' => $paciente]);
    }

    public function deletar(): void
    {
        $this->verificarPermissao(['admin']);

        $id = (int) ($_GET['id'] ?? 0);
        $this->pacienteModel->deletar($id);
        $this->redirecionar('/pacientes');
    }
}
