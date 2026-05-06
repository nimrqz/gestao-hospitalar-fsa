<?php

declare(strict_types=1);

namespace App\Controllers;

/**
 * Controller Base
 * 
 * Fornece métodos utilitários comuns a todos os controllers,
 * incluindo verificação de autenticação, RBAC e helpers de resposta.
 */
abstract class BaseController
{
    /**
     * Inicia sessão se ainda não estiver ativa
     */
    protected function iniciarSessao(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Verifica se o usuário está autenticado
     */
    protected function verificarAutenticacao(): void
    {
        $this->iniciarSessao();
        if (empty($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }
    }

    /**
     * Verifica se o usuário possui um dos perfis permitidos (RBAC)
     *
     * @param array|string $perfis
     */
    protected function verificarPermissao(array|string $perfis): void
    {
        $this->verificarAutenticacao();
        $perfilAtual = $_SESSION['usuario_perfil'] ?? '';
        $perfisPermitidos = is_array($perfis) ? $perfis : [$perfis];

        if (!in_array($perfilAtual, $perfisPermitidos, true)) {
            http_response_code(403);
            echo 'Acesso negado. Você não tem permissão para acessar este recurso.';
            exit;
        }
    }

    /**
     * Verifica se a requisição é do tipo POST
     */
    protected function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * Sanitiza um valor de entrada (string)
     */
    protected function sanitizar(string $valor): string
    {
        return htmlspecialchars(strip_tags(trim($valor)), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Retorna um array de dados sanitizados de POST
     */
    protected function getPostData(array $campos): array
    {
        $dados = [];
        foreach ($campos as $campo) {
            $dados[$campo] = isset($_POST[$campo]) ? $this->sanitizar($_POST[$campo]) : null;
        }
        return $dados;
    }

    /**
     * Redireciona para uma URL
     */
    protected function redirecionar(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }

    /**
     * Renderiza uma view com dados
     */
    protected function render(string $view, array $dados = []): void
    {
        extract($dados);
        require __DIR__ . '/../../views/' . $view . '.php';
    }

    /**
     * Retorna resposta JSON
     */
    protected function json(array $dados, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($dados, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
