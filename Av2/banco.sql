CREATE DATABASE IF NOT EXISTS vivant_beauty
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE vivant_beauty;

CREATE TABLE IF NOT EXISTS servicos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  categoria VARCHAR(50) NOT NULL,
  duracao VARCHAR(20) NOT NULL,
  preco DECIMAL(10,2) NOT NULL
);

CREATE TABLE IF NOT EXISTS agendamentos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  telefone VARCHAR(30) NOT NULL,
  email VARCHAR(120) NOT NULL,
  data_agendamento DATE NOT NULL,
  hora_agendamento TIME NOT NULL,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO servicos (nome, categoria, duracao, preco) VALUES
('Corte feminino', 'Cabelo', '40min', 60.00),
('Escova', 'Cabelo', '45min', 70.00),
('Hidratação', 'Cabelo', '50min', 80.00),
('Maquiagem social', 'Maquiagem', '1h', 100.00),
('Maquiagem artística', 'Maquiagem', '1h30min', 180.00),
('Design de sobrancelhas', 'Sobrancelhas', '30min', 40.00),
('Massagem relaxante', 'Massagem', '1h', 120.00),
('Manicure tradicional', 'Manicure', '1h', 30.00);
