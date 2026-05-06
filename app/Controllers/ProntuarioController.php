<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Prontuario;
use App\Models\Consulta;
use App\Models\Paciente;
use App\Models\Medico;

/**
 * Controller de Prontuário
 * 
 * Gerencia o histórico clínico e exames vinculados às consultas.
 */
class ProntuarioController extends BaseController
{
    private Prontuario $prontuarioModel;
    private Consulta $consultaModel;
    private Paciente $pacienteModel;
    private Medico $medicoModel;

    public function __construct()
    {
        $this->prontuarioModel = new Prontuario();
        $this->consultaModel = new Consulta();
        $this->pacienteModel = new Paciente();
        $this->medicoModel = new Medico();
    }

    /**
     * Lista todos os prontuários
     */
    public function index(): void
    {
        $this->verificarPermissao(['admin', 'medico', 'recepcao']);

        $prontuarios = $this->prontuarioModel->listarTodos();
        $this->render('prontuario/index', ['prontuarios' => $prontuarios]);
    }

    /**
     * Exibe formulário e processa criação de prontuário
     */
    public function criar(): void
    {
        $this->verificarPermissao(['admin', 'medico']);

        $consultaId = (int) ($_GET['consulta_id'] ?? 0);
        $consulta = $this->consultaModel->buscarPorId($consultaId);

        if (!$consulta) {
            http_response_code(404);
            echo 'Consulta não encontrada.';
            return;
        }

        if ($this->isPost()) {
            $dados = $this->getPostData([
                'anamnese', 'diagnostico', 'prescricao',
                'exames_realizados', 'observacoes'
            ]);

            $dados['consulta_id'] = $consultaId;
            $dados['paciente_id'] = $consulta['paciente_id'];
            $dados['medico_id'] = $consulta['medico_id'];

            try {
                $this->prontuarioModel->criar($dados);
                $this->redirecionar('/prontuarios');
            } catch (\Exception $e) {
                $this->render('prontuario/form', [
                    'erro' => $e->getMessage(),
                    'prontuario' => $dados,
                    'consulta' => $consulta,
                ]);
                return;
            }
        }

        $this->render('prontuario/form', [
            'prontuario' => null,
            'consulta' => $consulta,
        ]);
    }

    /**
     * Exibe formulário e processa edição de prontuário
     */
    public function editar(): void
    {
        $this->verificarPermissao(['admin', 'medico']);

        $id = (int) ($_GET['id'] ?? 0);
        $prontuario = $this->prontuarioModel->buscarPorId($id);

        if (!$prontuario) {
            http_response_code(404);
            echo 'Prontuário não encontrado.';
            return;
        }

        if ($this->isPost()) {
            $dados = $this->getPostData([
                'anamnese', 'diagnostico', 'prescricao',
                'exames_realizados', 'observacoes'
            ]);

            try {
                $this->prontuarioModel->atualizar($id, $dados);
                $this->redirecionar('/prontuarios');
            } catch (\Exception $e) {
                $this->render('prontuario/form', [
                    'erro' => $e->getMessage(),
                    'prontuario' => array_merge($prontuario, $dados),
                    'consulta' => null,
                ]);
                return;
            }
        }

        $this->render('prontuario/form', [
            'prontuario' => $prontuario,
            'consulta' => null,
        ]);
    }

    /**
     * Exibe detalhes de um prontuário
     */
    public function visualizar(): void
    {
        $this->verificarPermissao(['admin', 'medico', 'recepcao']);

        $id = (int) ($_GET['id'] ?? 0);
        $prontuario = $this->prontuarioModel->buscarPorId($id);

        if (!$prontuario) {
            http_response_code(404);
            echo 'Prontuário não encontrado.';
            return;
        }

        $this->render('prontuario/visualizar', ['prontuario' => $prontuario]);
    }

    /**
     * Remove um prontuário
     */
    public function deletar(): void
    {
        $this->verificarPermissao(['admin']);

        $id = (int) ($_GET['id'] ?? 0);
        $this->prontuarioModel->deletar($id);
        $this->redirecionar('/prontuarios');
    }
}
