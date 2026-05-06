<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Usuario;

/**
 * Controller de Autenticação
 * 
 * Responsável pelo login, logout e controle de sessão com RBAC.
 */
class AuthController extends BaseController
{
    private Usuario $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new Usuario();
    }

    /**
     * Exibe o formulário de login
     */
    public function login(): void
    {
        $this->iniciarSessao();
        if (!empty($_SESSION['usuario_id'])) {
            $this->redirecionar('/dashboard');
        }
        $this->render('auth/login', ['erro' => $_GET['erro'] ?? null]);
    }

    /**
     * Processa o login
     */
    public function autenticar(): void
    {
        if (!$this->isPost()) {
            $this->redirecionar('/login');
        }

        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $senha = $_POST['senha'] ?? '';

        if (!$email || empty($senha)) {
            $this->redirecionar('/login?erro=campos');
        }

        $usuario = $this->usuarioModel->buscarPorEmail($email);

        if (!$usuario || !password_verify($senha, $usuario['senha_hash'])) {
            $this->redirecionar('/login?erro=invalido');
        }

        if (!(int) $usuario['ativo']) {
            $this->redirecionar('/login?erro=inativo');
        }

        $this->iniciarSessao();
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['usuario_email'] = $usuario['email'];
        $_SESSION['usuario_perfil'] = $usuario['perfil'];

        $this->redirecionar('/dashboard');
    }

    /**
     * Encerra a sessão do usuário
     */
    public function logout(): void
    {
        $this->iniciarSessao();
        session_destroy();
        $this->redirecionar('/login');
    }
}
