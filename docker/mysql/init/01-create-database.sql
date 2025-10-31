-- Script de inicialização do banco de dados
-- Sistema de Biblioteca

-- Criar banco de dados se não existir
CREATE DATABASE IF NOT EXISTS sistema_biblioteca CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Usar o banco de dados
USE sistema_biblioteca;

-- Criar usuário se não existir
CREATE USER IF NOT EXISTS 'biblioteca_user'@'%' IDENTIFIED BY 'biblioteca_pass_123';

-- Conceder privilégios
GRANT ALL PRIVILEGES ON sistema_biblioteca.* TO 'biblioteca_user'@'%';
GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, INDEX, ALTER, CREATE TEMPORARY TABLES, LOCK TABLES ON sistema_biblioteca.* TO 'biblioteca_user'@'%';

-- Aplicar mudanças
FLUSH PRIVILEGES;

-- Configurações de timezone
SET time_zone = '-03:00';