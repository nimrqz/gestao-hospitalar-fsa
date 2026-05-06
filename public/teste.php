<?php
/**
 * Script de Diagnóstico do Sistema
 * Acesse via navegador: http://localhost/teste.php
 */

echo '<h1>Diagnóstico - Gestão Hospitalar</h1>';
echo '<hr>';

// 1. Teste de conexão com MySQL
echo '<h2>1. Conexão com Banco de Dados</h2>';
try {
    $pdo = new PDO('mysql:host=localhost;dbname=gestao_hospitalar;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo '<p style="color:green;">✅ Conexão com MySQL OK!</p>';
} catch (Exception $e) {
    echo '<p style="color:red;">❌ Erro na conexão: ' . $e->getMessage() . '</p>';
    echo '<p>Dica: Verifique se o MySQL está rodando e se o banco <strong>gestao_hospitalar</strong> existe.</p>';
    exit;
}

// 2. Verifica tabelas
echo '<h2>2. Tabelas no Banco</h2>';
$stmt = $pdo->query("SHOW TABLES");
$tabelas = $stmt->fetchAll(PDO::FETCH_COLUMN);
if (empty($tabelas)) {
    echo '<p style="color:red;">❌ Nenhuma tabela encontrada. Execute o script database.sql!</p>';
} else {
    echo '<p style="color:green;">✅ Tabelas encontradas: ' . implode(', ', $tabelas) . '</p>';
}

// 3. Verifica usuários
echo '<h2>3. Usuários Cadastrados</h2>';
if (in_array('usuarios', $tabelas)) {
    $stmt = $pdo->query("SELECT id, nome, email, perfil, ativo, senha_hash FROM usuarios");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (empty($usuarios)) {
        echo '<p style="color:red;">❌ Nenhum usuário cadastrado.</p>';
    } else {
        echo '<table border="1" cellpadding="5"><tr><th>ID</th><th>Nome</th><th>Email</th><th>Perfil</th><th>Ativo</th><th>Hash Válido p/ 123456?</th></tr>';
        foreach ($usuarios as $u) {
            $valido = password_verify('123456', $u['senha_hash']) ? '✅ SIM' : '❌ NÃO';
            echo '<tr>';
            echo '<td>' . $u['id'] . '</td>';
            echo '<td>' . htmlspecialchars($u['nome']) . '</td>';
            echo '<td>' . htmlspecialchars($u['email']) . '</td>';
            echo '<td>' . $u['perfil'] . '</td>';
            echo '<td>' . $u['ativo'] . '</td>';
            echo '<td><strong>' . $valido . '</strong></td>';
            echo '</tr>';
        }
        echo '</table>';
    }
} else {
    echo '<p style="color:red;">❌ Tabela usuários não existe.</p>';
}

// 4. Gera hash correto para 123456
echo '<h2>4. Hash Correto para "123456"</h2>';
$hashCorreto = password_hash('123456', PASSWORD_BCRYPT);
echo '<p>Hash gerado: <code>' . $hashCorreto . '</code></p>';
echo '<p>Se os hashes acima estiverem como NÃO, copie este hash e atualize no banco:</p>';
echo '<pre>UPDATE usuarios SET senha_hash = \'' . $hashCorreto . '\' WHERE email = \'admin@hospital.com\';</pre>';

// 5. Informações do servidor
echo '<h2>5. Informações do Servidor</h2>';
echo '<p>PHP Version: ' . phpversion() . '</p>';
echo '<p>Document Root: ' . $_SERVER['DOCUMENT_ROOT'] . '</p>';
echo '<p>Request URI: ' . $_SERVER['REQUEST_URI'] . '</p>';
echo '<p>Script Name: ' . $_SERVER['SCRIPT_NAME'] . '</p>';
