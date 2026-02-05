DROP DATABASE IF EXISTS sparten_main;
CREATE DATABASE sparten_main
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

USE sparten_main;
/* =====================================================
   BANCO DE DADOS - ACADEMIA SPARTEN SPINNING
   ===================================================== */

CREATE TABLE IF NOT EXISTS usuarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(150) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    telefone VARCHAR(20),
    senha VARCHAR(255) NOT NULL,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('ativo', 'inativo') DEFAULT 'ativo'
);

CREATE TABLE IF NOT EXISTS aulas_spinning (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    instrutor VARCHAR(100) NOT NULL,
    horario VARCHAR(20) NOT NULL,
    nivel ENUM('Iniciante', 'Intermediário', 'Avançado') NOT NULL,
    capacidade INT NOT NULL,
    descricao TEXT,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ativo ENUM('sim', 'não') DEFAULT 'sim'
);

CREATE TABLE IF NOT EXISTS inscricoes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT NOT NULL,
    aula_id INT NOT NULL,
    data_inscricao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('confirmado', 'cancelado') DEFAULT 'confirmado',
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (aula_id) REFERENCES aulas_spinning(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS agendamentos_teste (
    id INT PRIMARY KEY AUTO_INCREMENT,
    codigo_unico VARCHAR(20) UNIQUE NOT NULL,
    usuario_id INT,
    nome VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    data_agendamento DATE NOT NULL,
    horario VARCHAR(20) NOT NULL,
    nivel ENUM('Iniciante', 'Intermediário', 'Avançado') NOT NULL,
    status ENUM('confirmado', 'cancelado', 'realizado') DEFAULT 'confirmado',
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL
);

INSERT INTO aulas_spinning (nome, instrutor, horario, nivel, capacidade, descricao) VALUES
('Power Spin', 'Carlos Silva', '06:00 - 07:00', 'Intermediário', 20, 'Treino de alta intensidade com foco em resistência e velocidade'),
('Spin & Burn', 'Marina Costa', '07:30 - 08:30', 'Avançado', 18, 'Queime calorias com intervalos intensos e música energética'),
('Spin Flow', 'Lucas Ferreira', '18:00 - 19:00', 'Iniciante', 25, 'Aula relaxante com foco em técnica e adaptação ao equipamento'),
('Night Ride', 'Ana Paula', '19:30 - 20:30', 'Avançado', 20, 'Simulação de trilhas com mudanças de ritmo e resistência');




USE sparten_main;

-- Adicionar coluna de modalidade na tabela de aulas para unificar
ALTER TABLE aulas_spinning RENAME TO aulas;
ALTER TABLE aulas ADD COLUMN modalidade ENUM('spinning', 'aerobicos', 'funcional') DEFAULT 'spinning' AFTER id;

-- Inserir aulas de Aeróbicos
INSERT INTO aulas (modalidade, nome, instrutor, horario, nivel, capacidade, descricao) VALUES
('aerobicos', 'Hiit Cardio', 'Juliana Lima', '08:00 - 09:00', 'Intermediário', 15, 'Treino aeróbico de alta intensidade para queima rápida de gordura'),
('aerobicos', 'Dance Fit', 'Ricardo Alves', '17:00 - 18:00', 'Iniciante', 30, 'Mistura de ritmos e exercícios aeróbicos de forma divertida'),
('aerobicos', 'Running Club', 'Marcos Souza', '19:00 - 20:00', 'Avançado', 12, 'Treino focado em performance de corrida e resistência');

-- Inserir aulas de Treino Funcional
INSERT INTO aulas (modalidade, nome, instrutor, horario, nivel, capacidade, descricao) VALUES
('funcional', 'Sparten WOD', 'Marlon', '07:00 - 08:00', 'Avançado', 15, 'Treino do dia focado em força funcional e condicionamento extremo'),
('funcional', 'Core & Stability', 'Eduardo', '10:00 - 11:00', 'Iniciante', 20, 'Foco no fortalecimento do core e equilíbrio corporal'),
('funcional', 'Functional Circuit', 'Lucas', '18:30 - 19:30', 'Intermediário', 18, 'Circuito dinâmico trabalhando todos os grupos musculares');
