-- Cria banco (se quiser usar outro nome, atualiza conexao.php)
CREATE DATABASE IF NOT EXISTS chacara CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE chacara;

-- Tabela de agendamentos (um dia por registro)
CREATE TABLE IF NOT EXISTS agendamentos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  data DATE NOT NULL UNIQUE,
  nome VARCHAR(150) NOT NULL,
  cpf VARCHAR(14) NOT NULL,
  email VARCHAR(150) NOT NULL,
  telefone VARCHAR(30),
  qtd_pessoas INT NOT NULL,
  observacao TEXT,
  valor DECIMAL(10,2) NOT NULL,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
