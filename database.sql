DROP DATABASE IF EXISTS sparten_academia;
CREATE DATABASE sparten_academia
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

USE sparten_academia;

/* =========================
   TABELA: USUARIOS
   ========================= */
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    telefone VARCHAR(20),
    senha VARCHAR(255) NOT NULL,
    tipo ENUM('usuario', 'admin') NOT NULL DEFAULT 'usuario',
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('ativo', 'inativo') DEFAULT 'ativo'
);

/* =========================
   TABELA: AULAS
   ========================= */
CREATE TABLE aulas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    modalidade ENUM('spinning', 'aerobicos', 'funcional') NOT NULL,
    nome VARCHAR(100) NOT NULL,
    instrutor VARCHAR(100) NOT NULL,
    horario VARCHAR(20) NOT NULL,
    nivel ENUM('Iniciante', 'Intermediário', 'Avançado') NOT NULL,
    capacidade INT NOT NULL,
    descricao TEXT,
    ativo ENUM('sim', 'não') DEFAULT 'sim',
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

/* =========================
   TABELA: INSCRICOES
   ========================= */
CREATE TABLE inscricoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    aula_id INT NOT NULL,
    data_inscricao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('confirmado', 'cancelado') DEFAULT 'confirmado',
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (aula_id) REFERENCES aulas(id) ON DELETE CASCADE
);

/* =========================
   TABELA: AGENDAMENTOS TESTE
   ========================= */
CREATE TABLE agendamentos_teste (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo_unico VARCHAR(20) UNIQUE NOT NULL,
    usuario_id INT NULL,
    aula_id INT NULL,
    nome VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    data_agendamento DATE NOT NULL,
    horario VARCHAR(20) NOT NULL,
    nivel ENUM('Iniciante', 'Intermediário', 'Avançado') NOT NULL,
    status ENUM('confirmado', 'cancelado', 'realizado') DEFAULT 'confirmado',
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL,
    FOREIGN KEY (aula_id) REFERENCES aulas(id) ON DELETE CASCADE
);

/* =========================
   INSERTS - AULAS
   ========================= */

/* Spinning */
INSERT INTO aulas (modalidade, nome, instrutor, horario, nivel, capacidade, descricao) VALUES
('spinning', 'Power Spin', 'Carlos Silva', '06:00 - 07:00', 'Intermediário', 20, 'Treino de alta intensidade com foco em resistência'),
('spinning', 'Spin & Burn', 'Marina Costa', '07:30 - 08:30', 'Avançado', 18, 'Queima calórica com música energética'),
('spinning', 'Spin Flow', 'Lucas Ferreira', '18:00 - 19:00', 'Iniciante', 25, 'Aula leve focada em técnica'),
('spinning', 'Night Ride', 'Ana Paula', '19:30 - 20:30', 'Avançado', 20, 'Simulação de trilhas e resistência');

/* Aeróbicos */
INSERT INTO aulas (modalidade, nome, instrutor, horario, nivel, capacidade, descricao) VALUES
('aerobicos', 'Hiit Cardio', 'Juliana Lima', '08:00 - 09:00', 'Intermediário', 15, 'HIIT para alta queima calórica'),
('aerobicos', 'Dance Fit', 'Ricardo Alves', '17:00 - 18:00', 'Iniciante', 30, 'Dança + cardio'),
('aerobicos', 'Running Club', 'Marcos Souza', '19:00 - 20:00', 'Avançado', 12, 'Performance e resistência');

/* Funcional */
INSERT INTO aulas (modalidade, nome, instrutor, horario, nivel, capacidade, descricao) VALUES
('funcional', 'Sparten WOD', 'Marlon', '07:00 - 08:00', 'Avançado', 15, 'Treino funcional intenso'),
('funcional', 'Core & Stability', 'Eduardo', '10:00 - 11:00', 'Iniciante', 20, 'Fortalecimento do core'),
('funcional', 'Functional Circuit', 'Lucas', '18:30 - 19:30', 'Intermediário', 18, 'Circuito completo');
