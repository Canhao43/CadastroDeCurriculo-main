-- database/create_tables.sql
-- Script para criar tabela users e curriculos, e adicionar a chave estrangeira

CREATE DATABASE IF NOT EXISTS cadastro_curriculo;
USE cadastro_curriculo;

-- Cria a tabela de usuários
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Cria a tabela de currículos (se não existir)
-- Garanta que esta tabela seja criada ou já exista ANTES de tentar adicionar a FK
CREATE TABLE IF NOT EXISTS curriculos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    cpf VARCHAR(14) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL,
    ddi VARCHAR(5),
    ddd VARCHAR(3),
    telefone VARCHAR(15),
    nascimento DATE,
    genero VARCHAR(20),
    estado_civil VARCHAR(20),
    nacionalidade VARCHAR(100),
    cidade VARCHAR(100),
    estado VARCHAR(100),
    formacoes TEXT,
    experiencias TEXT,
    habilidades TEXT,
    idiomas TEXT,
    linkedin VARCHAR(255),
    github VARCHAR(255),
    site VARCHAR(255),
    foto VARCHAR(255),
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    -- A coluna id_usuario será adicionada abaixo se não existir
);

-- Adiciona a coluna id_usuario à tabela curriculos, se ela ainda não existir
ALTER TABLE curriculos
ADD COLUMN IF NOT EXISTS id_usuario INT;

-- Adiciona a Foreign Key
-- Dê um nome único para a constraint para evitar conflitos
ALTER TABLE curriculos
ADD CONSTRAINT fk_curriculos_users_reference -- Nome da constraint
FOREIGN KEY (id_usuario) REFERENCES users(id) ON DELETE CASCADE;