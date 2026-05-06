<?php
/**
 * Front Controller
 * 
 * Ponto de entrada único da aplicação.
 * Responsável por carregar o autoloader, iniciar a sessão
 * e rotear as requisições para os controllers adequados.
 */

declare(strict_types=1);

require_once __DIR__ . '/../app/autoload.php';

use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\PacienteController;
use App\Controllers\AgendamentoController;
use App\Controllers\ProntuarioController;

// Inicia sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Obtém a URI da requisição
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = trim($uri, '/');

// Remove query string da URI para fins de roteamento
$uri = explode('?', $uri)[0];

// Router simples
switch ($uri) {
    // Autenticação
    case 'login':
        $controller = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->autenticar();
        } else {
            $controller->login();
        }
        break;

    case 'logout':
        $controller = new AuthController();
        $controller->logout();
        break;

    // Dashboard
    case 'dashboard':
        $controller = new DashboardController();
        $controller->index();
        break;

    // Pacientes
    case 'pacientes':
        $controller = new PacienteController();
        $controller->index();
        break;

    case 'pacientes/criar':
        $controller = new PacienteController();
        $controller->criar();
        break;

    case 'pacientes/editar':
        $controller = new PacienteController();
        $controller->editar();
        break;

    case 'pacientes/deletar':
        $controller = new PacienteController();
        $controller->deletar();
        break;

    // Agenda / Agendamentos
    case 'agenda':
        $controller = new AgendamentoController();
        $controller->index();
        break;

    case 'agenda/criar':
        $controller = new AgendamentoController();
        $controller->criar();
        break;

    case 'agenda/editar':
        $controller = new AgendamentoController();
        $controller->editar();
        break;

    case 'agenda/deletar':
        $controller = new AgendamentoController();
        $controller->deletar();
        break;

    // Prontuários
    case 'prontuarios':
        $controller = new ProntuarioController();
        $controller->index();
        break;

    case 'prontuarios/criar':
        $controller = new ProntuarioController();
        $controller->criar();
        break;

    case 'prontuarios/editar':
        $controller = new ProntuarioController();
        $controller->editar();
        break;

    case 'prontuarios/visualizar':
        $controller = new ProntuarioController();
        $controller->visualizar();
        break;

    case 'prontuarios/deletar':
        $controller = new ProntuarioController();
        $controller->deletar();
        break;

    // Raiz: redireciona para login ou dashboard
    case '':
    case 'index.php':
        if (!empty($_SESSION['usuario_id'])) {
            header('Location: /dashboard');
        } else {
            header('Location: /login');
        }
        exit;

    // 404
    default:
        http_response_code(404);
        echo '<h1>404 - Página não encontrada</h1>';
        break;
}
