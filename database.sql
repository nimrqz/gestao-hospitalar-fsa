-- ============================================================
-- Sistema de Gestão Hospitalar - Script de Criação do Banco
-- MySQL 8.0+
-- ============================================================

CREATE DATABASE IF NOT EXISTS gestao_hospitalar 
    CHARACTER SET utf8mb4 
    COLLATE utf8mb4_unicode_ci;

USE gestao_hospitalar;

-- ============================================================
-- 1. Tabela: usuarios
-- Armazena credenciais e perfis de acesso (RBAC)
-- ============================================================
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

-- ============================================================
-- 2. Tabela: pacientes
-- Cadastro completo de pacientes
-- ============================================================
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

-- ============================================================
-- 3. Tabela: medicos
-- Cadastro de médicos com especialidade
-- ============================================================
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

-- ============================================================
-- 4. Tabela: consultas
-- Motor de agendamento com prevenção de conflitos de horário
-- ============================================================
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
    -- Garante que um médico não tenha duas consultas no mesmo horário
    UNIQUE KEY uk_medico_horario (medico_id, data_consulta, hora_inicio)
) ENGINE=InnoDB;

-- ============================================================
-- 5. Tabela: prontuarios
-- Histórico clínico vinculado a uma consulta
-- ============================================================
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

-- ============================================================
-- Dados Iniciais (Seed)
-- ============================================================

-- Usuários: senha padrão '123456' (bcrypt)
-- Hash gerado para '123456': $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi
INSERT INTO usuarios (nome, email, senha_hash, perfil) VALUES
('Administrador', 'admin@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Dr. Carlos Silva', 'medico@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'medico'),
('Recepcionista Ana', 'recepcao@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'recepcao');

-- Médicos
INSERT INTO medicos (usuario_id, nome, crm, especialidade, telefone, email) VALUES
(2, 'Dr. Carlos Silva', 'CRM-SP 123456', 'Cardiologia', '(11) 91234-5678', 'medico@hospital.com');

-- Pacientes de exemplo
INSERT INTO pacientes (nome, cpf, data_nascimento, telefone, email, endereco, convenio) VALUES
('João Pereira', '123.456.789-00', '1985-03-15', '(11) 98765-4321', 'joao@email.com', 'Rua A, 123 - São Paulo/SP', 'Unimed'),
('Maria Oliveira', '987.654.321-00', '1992-07-22', '(11) 91234-5678', 'maria@email.com', 'Av. B, 456 - São Paulo/SP', 'Bradesco Saúde');
