<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Consulta;
use App\Models\Paciente;

/**
 * Controller do Dashboard
 * 
 * Exibe a tela principal com resumo de dados.
 */
class DashboardController extends BaseController
{
    private Consulta $consultaModel;
    private Paciente $pacienteModel;

    public function __construct()
    {
        $this->consultaModel = new Consulta();
        $this->pacienteModel = new Paciente();
    }

    public function index(): void
    {
        $this->verificarAutenticacao();

        $consultasHoje = $this->consultaModel->listarTodas();
        $pacientes = $this->pacienteModel->listarTodos();

        // Contadores simples
        $totalPacientes = count($pacientes);
        $totalConsultas = count($consultasHoje);

        $this->render('dashboard', [
            'usuario' => $_SESSION,
            'totalPacientes' => $totalPacientes,
            'totalConsultas' => $totalConsultas,
        ]);
    }
}
