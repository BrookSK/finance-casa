-- =============================================
-- FinançasCasal - Schema do Banco de Dados
-- Rodar este arquivo para criar todas as tabelas
-- NÃO EDITAR ESTE ARQUIVO - usar migrations
-- =============================================

CREATE DATABASE IF NOT EXISTS financas_casal
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE financas_casal;

-- Usuários
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    papel ENUM('admin', 'usuario') NOT NULL DEFAULT 'usuario',
    ativo TINYINT(1) NOT NULL DEFAULT 1,
    tema ENUM('light', 'dark') NOT NULL DEFAULT 'light',
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Categorias
CREATE TABLE IF NOT EXISTS categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    tipo ENUM('receita', 'despesa', 'ambos') NOT NULL DEFAULT 'ambos',
    cor VARCHAR(7) DEFAULT '#6366f1',
    icone VARCHAR(50) DEFAULT 'folder',
    ativo TINYINT(1) NOT NULL DEFAULT 1,
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Subcategorias
CREATE TABLE IF NOT EXISTS subcategorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    categoria_id INT NOT NULL,
    nome VARCHAR(100) NOT NULL,
    ativo TINYINT(1) NOT NULL DEFAULT 1,
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Cartões de crédito
CREATE TABLE IF NOT EXISTS cartoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    nome VARCHAR(100) NOT NULL,
    bandeira VARCHAR(50) DEFAULT '',
    limite_total DECIMAL(10,2) NOT NULL DEFAULT 0,
    dia_fechamento INT NOT NULL DEFAULT 1,
    dia_vencimento INT NOT NULL DEFAULT 10,
    cor VARCHAR(7) DEFAULT '#6366f1',
    ativo TINYINT(1) NOT NULL DEFAULT 1,
    observacao TEXT,
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Receitas
CREATE TABLE IF NOT EXISTS receitas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    titulo VARCHAR(200) NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    tipo ENUM('fixa', 'variavel') NOT NULL DEFAULT 'fixa',
    categoria_id INT,
    data_prevista DATE,
    data_recebida DATE,
    recorrente TINYINT(1) NOT NULL DEFAULT 0,
    dia_recebimento_inicio INT DEFAULT NULL,
    dia_recebimento_fim INT DEFAULT NULL,
    entra_no_orcamento TINYINT(1) NOT NULL DEFAULT 1,
    mes_referencia INT NOT NULL,
    ano_referencia INT NOT NULL,
    status ENUM('prevista', 'recebida', 'atrasada') NOT NULL DEFAULT 'prevista',
    observacao TEXT,
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Despesas
CREATE TABLE IF NOT EXISTS despesas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    nome VARCHAR(200) NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    tipo ENUM('fixa', 'variavel', 'parcelada') NOT NULL DEFAULT 'fixa',
    categoria_id INT,
    subcategoria_id INT,
    proprietario ENUM('lucas', 'bia', 'compartilhado', 'empresa') NOT NULL DEFAULT 'compartilhado',
    forma_pagamento ENUM('dinheiro', 'pix', 'debito', 'credito', 'boleto', 'transferencia') NOT NULL DEFAULT 'pix',
    cartao_id INT,
    data_vencimento DATE,
    data_pagamento DATE,
    recorrente TINYINT(1) NOT NULL DEFAULT 0,
    parcelada TINYINT(1) NOT NULL DEFAULT 0,
    total_parcelas INT DEFAULT NULL,
    parcela_atual INT DEFAULT NULL,
    mes_referencia INT NOT NULL,
    ano_referencia INT NOT NULL,
    status ENUM('pendente', 'paga', 'atrasada', 'cancelada') NOT NULL DEFAULT 'pendente',
    observacao TEXT,
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL,
    FOREIGN KEY (subcategoria_id) REFERENCES subcategorias(id) ON DELETE SET NULL,
    FOREIGN KEY (cartao_id) REFERENCES cartoes(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Cofrinhos / Envelopes
CREATE TABLE IF NOT EXISTS cofrinhos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    nome VARCHAR(150) NOT NULL,
    categoria_id INT,
    tipo ENUM('pessoal', 'compartilhado') NOT NULL DEFAULT 'pessoal',
    meta_mensal DECIMAL(10,2) NOT NULL DEFAULT 0,
    valor_atual DECIMAL(10,2) NOT NULL DEFAULT 0,
    prioridade ENUM('alta', 'media', 'baixa') NOT NULL DEFAULT 'media',
    cor VARCHAR(7) DEFAULT '#6366f1',
    ativo TINYINT(1) NOT NULL DEFAULT 1,
    mes_referencia INT NOT NULL,
    ano_referencia INT NOT NULL,
    observacao TEXT,
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Movimentações dos cofrinhos
CREATE TABLE IF NOT EXISTS cofrinho_movimentacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cofrinho_id INT NOT NULL,
    usuario_id INT NOT NULL,
    tipo ENUM('deposito', 'retirada') NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    descricao VARCHAR(200),
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cofrinho_id) REFERENCES cofrinhos(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Faturas dos cartões
CREATE TABLE IF NOT EXISTS faturas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cartao_id INT NOT NULL,
    mes_referencia INT NOT NULL,
    ano_referencia INT NOT NULL,
    valor_total DECIMAL(10,2) NOT NULL DEFAULT 0,
    valor_reservado DECIMAL(10,2) NOT NULL DEFAULT 0,
    data_fechamento DATE,
    data_vencimento DATE,
    status ENUM('aberta', 'fechada', 'paga') NOT NULL DEFAULT 'aberta',
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (cartao_id) REFERENCES cartoes(id) ON DELETE CASCADE,
    UNIQUE KEY uk_fatura (cartao_id, mes_referencia, ano_referencia)
) ENGINE=InnoDB;

-- Lançamentos das faturas
CREATE TABLE IF NOT EXISTS fatura_lancamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fatura_id INT NOT NULL,
    despesa_id INT,
    descricao VARCHAR(200) NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    categoria_id INT,
    parcela_atual INT DEFAULT NULL,
    total_parcelas INT DEFAULT NULL,
    data_compra DATE,
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (fatura_id) REFERENCES faturas(id) ON DELETE CASCADE,
    FOREIGN KEY (despesa_id) REFERENCES despesas(id) ON DELETE SET NULL,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Orçamentos por categoria
CREATE TABLE IF NOT EXISTS orcamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    categoria_id INT NOT NULL,
    valor_limite DECIMAL(10,2) NOT NULL,
    mes_referencia INT NOT NULL,
    ano_referencia INT NOT NULL,
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE CASCADE,
    UNIQUE KEY uk_orcamento (categoria_id, mes_referencia, ano_referencia)
) ENGINE=InnoDB;

-- Listas de compras
CREATE TABLE IF NOT EXISTS listas_compras (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    nome VARCHAR(150) NOT NULL,
    status ENUM('ativa', 'concluida', 'cancelada') NOT NULL DEFAULT 'ativa',
    orcamento_limite DECIMAL(10,2) DEFAULT NULL,
    total_estimado DECIMAL(10,2) NOT NULL DEFAULT 0,
    total_real DECIMAL(10,2) NOT NULL DEFAULT 0,
    local_compra VARCHAR(150),
    data_compra DATE,
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Itens da lista de compras
CREATE TABLE IF NOT EXISTS lista_itens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lista_id INT NOT NULL,
    nome VARCHAR(150) NOT NULL,
    quantidade DECIMAL(8,3) NOT NULL DEFAULT 1,
    unidade VARCHAR(20) NOT NULL DEFAULT 'un',
    categoria VARCHAR(80),
    prioridade ENUM('alta', 'media', 'baixa') NOT NULL DEFAULT 'media',
    preco_estimado DECIMAL(10,2) DEFAULT NULL,
    preco_real DECIMAL(10,2) DEFAULT NULL,
    comprado TINYINT(1) NOT NULL DEFAULT 0,
    observacao TEXT,
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (lista_id) REFERENCES listas_compras(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Notificações
CREATE TABLE IF NOT EXISTS notificacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    titulo VARCHAR(200) NOT NULL,
    mensagem TEXT NOT NULL,
    tipo ENUM('info', 'alerta', 'urgente', 'sucesso') NOT NULL DEFAULT 'info',
    lida TINYINT(1) NOT NULL DEFAULT 0,
    link VARCHAR(255),
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Configurações do sistema
CREATE TABLE IF NOT EXISTS configuracoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    chave VARCHAR(100) NOT NULL UNIQUE,
    valor TEXT,
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;
