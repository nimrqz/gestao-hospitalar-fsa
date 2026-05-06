<?php
/**
 * Script de Instalação Automática
 * 
 * Acesse via navegador: http://localhost/instalar.php
 * 
 * Este script irá:
 * 1. Criar o banco de dados e tabelas
 * 2. Inserir dados iniciais com hashes bcrypt corretos
 * 3. Testar o login
 */

declare(strict_types=1);

// Configurações - ajuste se necessário
$host = 'localhost';
$user = 'root';
$pass = '';
$dbName = 'gestao_hospitalar';

$erros = [];
$sucessos = [];

// Conecta sem banco
try {
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sucessos[] = "Conexão com MySQL estabelecida.";
} catch (PDOException $e) {
    die("<h1 style='color:red'>Erro ao conectar no MySQL</h1><p>" . $e->getMessage() . "</p><p>Verifique usuário e senha no arquivo config/db.php e neste script.</p>");
}

// Cria banco
$pdo->exec("CREATE DATABASE IF NOT EXISTS $dbName CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$pdo->exec("USE $dbName");
$sucessos[] = "Banco de dados '$dbName' criado/selecionado.";

// SQL das tabelas
$sqlTabelas = <<<SQL
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    senha_hash VARCHAR(255) NOT NULL,
    perfil ENUM('admin','medico','recepcao') NOT NULL DEFAULT 'recepcao',
    ativo TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS pacientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    cpf VARCHAR(14) NOT NULL UNIQUE,
    data_nascimento DATE NOT NULL,
    telefone VARCHAR(20),
    email VARCHAR(255),
    endereco TEXT,
    convenio VARCHAR(100),
    numero_carteirinha VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS medicos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NULL,
    nome VARCHAR(255) NOT NULL,
    crm VARCHAR(20) NOT NULL UNIQUE,
    especialidade VARCHAR(100) NOT NULL,
    telefone VARCHAR(20),
    email VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS consultas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    paciente_id INT NOT NULL,
    medico_id INT NOT NULL,
    data_consulta DATE NOT NULL,
    hora_inicio TIME NOT NULL,
    hora_fim TIME NOT NULL,
    status ENUM('agendada','confirmada','cancelada','realizada') DEFAULT 'agendada',
    observacoes TEXT,
    exames_solicitados TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (paciente_id) REFERENCES pacientes(id) ON DELETE CASCADE,
    FOREIGN KEY (medico_id) REFERENCES medicos(id) ON DELETE CASCADE,
    UNIQUE KEY uk_medico_horario (medico_id, data_consulta, hora_inicio)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS prontuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    consulta_id INT NOT NULL UNIQUE,
    paciente_id INT NOT NULL,
    medico_id INT NOT NULL,
    anamnese TEXT,
    diagnostico TEXT,
    prescricao TEXT,
    exames_realizados TEXT,
    observacoes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (consulta_id) REFERENCES consultas(id) ON DELETE CASCADE,
    FOREIGN KEY (paciente_id) REFERENCES pacientes(id) ON DELETE CASCADE,
    FOREIGN KEY (medico_id) REFERENCES medicos(id) ON DELETE CASCADE
) ENGINE=InnoDB;
SQL;

$pdo->exec($sqlTabelas);
$sucessos[] = "Tabelas criadas com sucesso.";

// Limpa dados antigos e insere novos com hash correto
$hash123456 = password_hash('123456', PASSWORD_BCRYPT);

$pdo->exec("DELETE FROM prontuarios");
$pdo->exec("DELETE FROM consultas");
$pdo->exec("DELETE FROM medicos");
$pdo->exec("DELETE FROM pacientes");
$pdo->exec("DELETE FROM usuarios");

$stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha_hash, perfil) VALUES (?, ?, ?, ?)");
$stmt->execute(['Administrador', 'admin@hospital.com', $hash123456, 'admin']);
$stmt->execute(['Dr. Carlos Silva', 'medico@hospital.com', $hash123456, 'medico']);
$stmt->execute(['Recepcionista Ana', 'recepcao@hospital.com', $hash123456, 'recepcao']);
$sucessos[] = "Usuários de teste inseridos (senha: 123456).";

// Insere médico
$pdo->exec("INSERT INTO medicos (usuario_id, nome, crm, especialidade, telefone, email) VALUES (2, 'Dr. Carlos Silva', 'CRM-SP 123456', 'Cardiologia', '(11) 91234-5678', 'medico@hospital.com')");
$sucessos[] = "Médico de exemplo inserido.";

// Insere pacientes
$pdo->exec("INSERT INTO pacientes (nome, cpf, data_nascimento, telefone, email, endereco, convenio) VALUES 
    ('João Pereira', '123.456.789-00', '1985-03-15', '(11) 98765-4321', 'joao@email.com', 'Rua A, 123 - São Paulo/SP', 'Unimed'),
    ('Maria Oliveira', '987.654.321-00', '1992-07-22', '(11) 91234-5678', 'maria@email.com', 'Av. B, 456 - São Paulo/SP', 'Bradesco Saúde')");
$sucessos[] = "Pacientes de exemplo inseridos.";

// Testa login
$stmt = $pdo->prepare("SELECT senha_hash FROM usuarios WHERE email = ?");
$stmt->execute(['admin@hospital.com']);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$loginOk = $row && password_verify('123456', $row['senha_hash']);

if ($loginOk) {
    $sucessos[] = "Teste de login: SUCESSO (hash válido para '123456').";
} else {
    $erros[] = "Teste de login: FALHOU (hash não corresponde à senha '123456').";
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Instalação - Gestão Hospitalar</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow p-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Instalação Concluída!</h1>
        
        <?php if (!empty($sucessos)): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                <ul class="list-disc list-inside">
                    <?php foreach ($sucessos as $s): ?>
                        <li><?= htmlspecialchars($s) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (!empty($erros)): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
                <ul class="list-disc list-inside">
                    <?php foreach ($erros as $e): ?>
                        <li><?= htmlspecialchars($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="mt-6 p-4 bg-blue-50 rounded-lg">
            <h2 class="font-bold text-blue-800 mb-2">Próximos passos:</h2>
            <ol class="list-decimal list-inside text-blue-900 space-y-1">
                <li>Acesse <a href="/login" class="underline font-bold">http://localhost/login</a></li>
                <li>Use um dos logins abaixo (senha: <strong>123456</strong>)</li>
            </ol>
            <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white p-3 rounded shadow text-center">
                    <div class="font-bold">Admin</div>
                    <div class="text-sm text-gray-600">admin@hospital.com</div>
                </div>
                <div class="bg-white p-3 rounded shadow text-center">
                    <div class="font-bold">Médico</div>
                    <div class="text-sm text-gray-600">medico@hospital.com</div>
                </div>
                <div class="bg-white p-3 rounded shadow text-center">
                    <div class="font-bold">Recepção</div>
                    <div class="text-sm text-gray-600">recepcao@hospital.com</div>
                </div>
            </div>
        </div>

        <div class="mt-6 text-center">
            <a href="/login" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition">
                Ir para o Login
            </a>
        </div>
    </div>
</body>
</html>
