CREATE DATABASE IF NOT EXISTS vivant_beauty
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE vivant_beauty;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS agendamento_servicos;
DROP TABLE IF EXISTS agendamentos;
DROP TABLE IF EXISTS profissionais;
DROP TABLE IF EXISTS usuarios;
DROP TABLE IF EXISTS servicos;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE servicos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  categoria VARCHAR(50) NOT NULL,
  duracao VARCHAR(20) NOT NULL,
  preco DECIMAL(10,2) NOT NULL,
  ativo TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB;

CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  telefone VARCHAR(30) NOT NULL,
  email VARCHAR(120) NOT NULL UNIQUE,
  senha_hash VARCHAR(255) NOT NULL,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE profissionais (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  especialidade VARCHAR(100) NOT NULL,
  ativo TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB;

CREATE TABLE agendamentos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  servico_id INT NOT NULL,
  nome VARCHAR(100) NOT NULL,
  telefone VARCHAR(30) NOT NULL,
  email VARCHAR(120) NOT NULL,
  data_agendamento DATE NOT NULL,
  hora_agendamento TIME NOT NULL,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  usuario_id INT NULL,
  profissional_id INT NULL,
  forma_pagamento ENUM('credito', 'debito', 'pix', 'dinheiro') NULL,
  valor_total DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  status ENUM('Confirmado', 'Concluído', 'Cancelado') NOT NULL DEFAULT 'Confirmado',
  CONSTRAINT fk_agendamento_servico_original
    FOREIGN KEY (servico_id) REFERENCES servicos(id),
  CONSTRAINT fk_agendamento_usuario
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL,
  CONSTRAINT fk_agendamento_profissional
    FOREIGN KEY (profissional_id) REFERENCES profissionais(id) ON DELETE SET NULL,
  INDEX idx_agendamento_usuario (usuario_id),
  INDEX idx_agendamento_horario (profissional_id, data_agendamento, hora_agendamento)
) ENGINE=InnoDB;

CREATE TABLE agendamento_servicos (
  agendamento_id INT NOT NULL,
  servico_id INT NOT NULL,
  preco_unitario DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (agendamento_id, servico_id),
  CONSTRAINT fk_item_agendamento
    FOREIGN KEY (agendamento_id) REFERENCES agendamentos(id) ON DELETE CASCADE,
  CONSTRAINT fk_item_servico
    FOREIGN KEY (servico_id) REFERENCES servicos(id)
) ENGINE=InnoDB;

INSERT INTO servicos (nome, categoria, duracao, preco) VALUES
('Corte feminino', 'Cabelo', '40min', 60.00),
('Escova', 'Cabelo', '45min', 70.00),
('Hidratação', 'Cabelo', '50min', 80.00),
('Maquiagem social', 'Maquiagem', '1h', 100.00),
('Maquiagem artística', 'Maquiagem', '1h30min', 180.00),
('Maquiagem para noivas', 'Maquiagem', '1h30min', 250.00),
('Design de sobrancelhas', 'Sobrancelhas', '30min', 40.00),
('Alongamento de cílios', 'Sobrancelhas', '1h30min', 120.00),
('Massagem relaxante', 'Massagem', '1h', 120.00),
('Drenagem linfática', 'Massagem', '1h', 130.00),
('Manicure tradicional', 'Manicure', '1h', 30.00),
('Esmaltação em gel', 'Manicure', '1h', 60.00);

INSERT INTO profissionais (nome, especialidade) VALUES
('Ana Martins', 'Cabelo e maquiagem'),
('Beatriz Souza', 'Manicure e sobrancelhas'),
('Carla Oliveira', 'Massagem e bem-estar'),
('Daniela Lima', 'Cabelo e noivas');
