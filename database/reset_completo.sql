-- =============================================
-- FinançasCasal - RESET COMPLETO DO BANCO
-- Dropa e recria tudo com dados corretos
-- Rodar este arquivo ÚNICO para zerar o banco
-- =============================================

DROP DATABASE IF EXISTS financas_casal;

CREATE DATABASE financas_casal
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE financas_casal;

-- =============================================
-- TABELAS
-- =============================================

CREATE TABLE usuarios (
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

CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    tipo ENUM('receita', 'despesa', 'ambos') NOT NULL DEFAULT 'ambos',
    cor VARCHAR(7) DEFAULT '#6366f1',
    icone VARCHAR(50) DEFAULT 'folder',
    ativo TINYINT(1) NOT NULL DEFAULT 1,
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE subcategorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    categoria_id INT NOT NULL,
    nome VARCHAR(100) NOT NULL,
    ativo TINYINT(1) NOT NULL DEFAULT 1,
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE cartoes (
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

CREATE TABLE receitas (
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

CREATE TABLE despesas (
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

CREATE TABLE cofrinhos (
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

CREATE TABLE cofrinho_movimentacoes (
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

CREATE TABLE faturas (
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

CREATE TABLE fatura_lancamentos (
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

CREATE TABLE orcamentos (
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

CREATE TABLE listas_compras (
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

CREATE TABLE lista_itens (
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

CREATE TABLE notificacoes (
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

CREATE TABLE configuracoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    chave VARCHAR(100) NOT NULL UNIQUE,
    valor TEXT,
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =============================================
-- DADOS BASE: Usuários, Categorias, Cartões
-- =============================================

-- Usuários (senha padrão: password)
INSERT INTO usuarios (nome, email, senha, papel) VALUES
('Lucas', 'lucas@financas.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Bia', 'bia@financas.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'usuario');

-- Categorias (id 1-17)
INSERT INTO categorias (nome, tipo, cor, icone) VALUES
('Salário', 'receita', '#10b981', 'wallet'),
('Freelance', 'receita', '#06b6d4', 'briefcase'),
('Empresa', 'receita', '#8b5cf6', 'building'),
('Moradia', 'despesa', '#ef4444', 'home'),
('Saúde', 'despesa', '#f43f5e', 'heart'),
('Educação', 'despesa', '#3b82f6', 'book'),
('Transporte', 'despesa', '#f59e0b', 'car'),
('Alimentação', 'despesa', '#22c55e', 'utensils'),
('Mercado', 'despesa', '#14b8a6', 'shopping-cart'),
('Assinaturas', 'despesa', '#a855f7', 'tv'),
('Beleza / Autocuidado', 'despesa', '#ec4899', 'sparkles'),
('Gastos Livres', 'despesa', '#6366f1', 'credit-card'),
('Investimento', 'despesa', '#0ea5e9', 'trending-up'),
('Casa', 'despesa', '#d97706', 'home'),
('Empresa Despesa', 'despesa', '#7c3aed', 'building'),
('Telecomunicações', 'despesa', '#0891b2', 'phone'),
('Restaurante / Lanche', 'despesa', '#fb923c', 'coffee');

-- Cartões (id 1=XP, 2=PicPay Lucas, 3=Sicredi, 4=PicPay Bia)
INSERT INTO cartoes (usuario_id, nome, bandeira, limite_total, dia_fechamento, dia_vencimento, cor, observacao) VALUES
(1, 'XP', 'Visa', 5000.00, 22, 1, '#00c853', 'APENAS parcelas, assinaturas e serviços técnicos. NÃO usar para rotina/mercado.'),
(1, 'PicPay Lucas', 'Visa', 3000.00, 14, 20, '#00e676', 'Cartão da CASA. Mercado, restaurante, compras do casal. Teto: R$1.000/mês.'),
(1, 'Sicredi', 'Mastercard', 2000.00, 10, 20, '#4caf50', 'NÃO USAR. Manutenção bancária / saque aluguel apenas.'),
(2, 'PicPay Bia', 'Visa', 1500.00, 1, 10, '#e040fb', 'Gastos pessoais da Bia. Assinaturas dela, cursos, compras pessoais.');

-- =============================================
-- RECEITAS - Mês atual (março 2026)
-- =============================================

SET @mes = 3;
SET @ano = 2026;

INSERT INTO receitas (usuario_id, titulo, valor, tipo, categoria_id, dia_recebimento_inicio, dia_recebimento_fim, recorrente, entra_no_orcamento, mes_referencia, ano_referencia, status) VALUES
(1, 'Salário Lucas', 3500.00, 'fixa', 1, 13, 15, 1, 1, @mes, @ano, 'prevista'),
(2, 'Salário Bia', 2000.00, 'fixa', 1, 20, 25, 1, 1, @mes, @ano, 'prevista');

INSERT INTO receitas (usuario_id, titulo, valor, tipo, categoria_id, recorrente, entra_no_orcamento, mes_referencia, ano_referencia, status) VALUES
(1, 'Cliente 1', 500.00, 'variavel', 3, 0, 0, @mes, @ano, 'prevista'),
(1, 'Cliente 2', 180.00, 'variavel', 3, 0, 0, @mes, @ano, 'prevista');

-- =============================================
-- DESPESAS FIXAS LUCAS - Março 2026
-- =============================================

INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, cartao_id, data_vencimento, recorrente, mes_referencia, ano_referencia, status, observacao) VALUES
(1, 'Aluguel', 1000.00, 'fixa', 4, 'compartilhado', 'pix', NULL, '2026-03-05', 1, @mes, @ano, 'pendente', 'Compartilhado: Lucas R$636 + Bia R$364'),
(1, 'Água', 100.00, 'fixa', 4, 'compartilhado', 'boleto', NULL, '2026-03-04', 1, @mes, @ano, 'pendente', 'Lucas paga, Bia complementa R$130 no cofrinho Casa/Apoio'),
(1, 'Energia', 280.00, 'fixa', 4, 'compartilhado', 'boleto', NULL, '2026-03-05', 1, @mes, @ano, 'pendente', 'Lucas paga, Bia complementa R$130 no cofrinho Casa/Apoio'),
(1, 'Faculdade Lucas', 86.81, 'fixa', 6, 'lucas', 'boleto', NULL, '2026-03-06', 1, @mes, @ano, 'pendente', NULL),
(1, 'Celular Lucas', 40.00, 'fixa', 16, 'lucas', 'credito', 1, '2026-03-10', 1, @mes, @ano, 'pendente', NULL),
(1, 'Unimed', 300.00, 'fixa', 5, 'lucas', 'boleto', NULL, '2026-03-10', 1, @mes, @ano, 'pendente', NULL),
(1, 'Consórcio Carro', 533.32, 'fixa', 7, 'lucas', 'boleto', NULL, '2026-03-15', 1, @mes, @ano, 'pendente', NULL),
(1, 'Investimento', 300.00, 'fixa', 13, 'lucas', 'transferencia', NULL, '2026-03-15', 1, @mes, @ano, 'pendente', 'Separar assim que receber. Não reduzir.'),
(1, 'Gasolina', 80.00, 'fixa', 7, 'lucas', 'debito', NULL, '2026-03-15', 1, @mes, @ano, 'pendente', NULL),
(1, 'Reserva', 150.00, 'fixa', 13, 'lucas', 'transferencia', NULL, '2026-03-15', 1, @mes, @ano, 'pendente', 'Diferença de contas, farmácia, imprevistos'),
(1, 'MEI Lucas', 86.05, 'fixa', 15, 'lucas', 'boleto', NULL, '2026-03-20', 1, @mes, @ano, 'pendente', NULL),
(1, 'Internet Casa', 100.00, 'fixa', 16, 'compartilhado', 'boleto', NULL, '2026-03-20', 1, @mes, @ano, 'pendente', 'Lucas paga, Bia complementa R$90 no cofrinho Casa/Apoio'),
(1, 'TV / Celulares', 50.00, 'fixa', 16, 'compartilhado', 'credito', 1, '2026-03-25', 1, @mes, @ano, 'pendente', NULL);

-- Assinaturas Lucas (cartão XP)
INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, cartao_id, recorrente, mes_referencia, ano_referencia, status) VALUES
(1, 'Spotify Lucas', 12.90, 'fixa', 10, 'lucas', 'credito', 1, 1, @mes, @ano, 'pendente'),
(1, 'TV da Sala', 16.44, 'fixa', 10, 'compartilhado', 'credito', 2, 1, @mes, @ano, 'pendente'),
(1, 'Leite', 12.00, 'fixa', 8, 'compartilhado', 'credito', 2, 1, @mes, @ano, 'pendente');

-- =============================================
-- DESPESAS FIXAS BIA - Março 2026
-- =============================================

INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, cartao_id, data_vencimento, recorrente, mes_referencia, ano_referencia, status) VALUES
(2, 'Celular Bia', 39.90, 'fixa', 16, 'bia', 'credito', 4, '2026-03-10', 1, @mes, @ano, 'pendente'),
(2, 'Unha', 125.00, 'fixa', 11, 'bia', 'pix', NULL, '2026-03-15', 1, @mes, @ano, 'pendente'),
(2, 'Hidratação', 60.00, 'fixa', 11, 'bia', 'pix', NULL, '2026-03-15', 1, @mes, @ano, 'pendente'),
(2, 'Sobrancelha', 50.00, 'fixa', 11, 'bia', 'pix', NULL, '2026-03-15', 1, @mes, @ano, 'pendente'),
(2, 'Progressiva', 125.00, 'fixa', 11, 'bia', 'pix', NULL, '2026-03-15', 1, @mes, @ano, 'pendente'),
(2, 'MEI Bia', 86.05, 'fixa', 15, 'bia', 'boleto', NULL, '2026-03-20', 1, @mes, @ano, 'pendente'),
(2, 'Faculdade Bia', 237.19, 'fixa', 6, 'bia', 'boleto', NULL, '2026-03-20', 1, @mes, @ano, 'pendente'),
(2, 'Canva', 34.90, 'fixa', 10, 'bia', 'credito', 4, '2026-03-20', 1, @mes, @ano, 'pendente'),
(2, 'Centro', 80.00, 'fixa', 7, 'bia', 'pix', NULL, '2026-03-22', 1, @mes, @ano, 'pendente'),
(2, 'Ônibus', 30.00, 'fixa', 7, 'bia', 'pix', NULL, '2026-03-22', 1, @mes, @ano, 'pendente');

-- Assinaturas Bia (cartão PicPay Bia)
INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, cartao_id, recorrente, mes_referencia, ano_referencia, status) VALUES
(2, 'Spotify Bia', 12.90, 'fixa', 10, 'bia', 'credito', 4, 1, @mes, @ano, 'pendente'),
(2, 'Google Fotos Bia', 9.99, 'fixa', 10, 'bia', 'credito', 4, 1, @mes, @ano, 'pendente'),
(2, 'ChatGPT Bia', 39.90, 'fixa', 10, 'bia', 'credito', 4, 1, @mes, @ano, 'pendente');

-- =============================================
-- ORÇAMENTOS MENSAIS - Março 2026
-- =============================================

INSERT INTO orcamentos (categoria_id, valor_limite, mes_referencia, ano_referencia) VALUES
(9, 500.00, @mes, @ano),   -- Mercado
(12, 250.00, @mes, @ano),  -- Gastos Livres
(17, 250.00, @mes, @ano);  -- Restaurante / Lanche

-- =============================================
-- COFRINHOS LUCAS - 10 cofrinhos
-- =============================================

INSERT INTO cofrinhos (usuario_id, nome, categoria_id, tipo, meta_mensal, valor_atual, prioridade, cor, mes_referencia, ano_referencia, observacao) VALUES
(1, 'Aluguel', 4, 'pessoal', 1000.00, 0, 'alta', '#ef4444', @mes, @ano, 'Prioridade 1 ao receber. Vence dia 5.'),
(1, 'Água + Energia', 4, 'pessoal', 380.00, 0, 'alta', '#f97316', @mes, @ano, 'Prioridade 2. Água dia 4, Energia dia 5. Lucas R$250, Bia complementa R$130.'),
(1, 'Faculdade + MEI', 6, 'pessoal', 172.86, 0, 'media', '#3b82f6', @mes, @ano, 'Prioridade 3. Faculdade dia 6, MEI dia 20.'),
(1, 'Unimed', 5, 'pessoal', 300.00, 0, 'alta', '#f43f5e', @mes, @ano, 'Prioridade 4. Vence dia 10.'),
(1, 'Consórcio + Gasolina', 7, 'pessoal', 613.32, 0, 'alta', '#f59e0b', @mes, @ano, 'Prioridade 5. Consórcio dia 15, gasolina ao longo do mês.'),
(1, 'Investimento', 13, 'pessoal', 300.00, 0, 'media', '#10b981', @mes, @ano, 'Prioridade 6. Separar assim que receber. Não reduzir.'),
(1, 'Fatura XP', 12, 'pessoal', 450.00, 0, 'alta', '#00c853', @mes, @ano, 'Prioridade 7. Vence dia 1. Parcelas e assinaturas técnicas.'),
(1, 'Internet + Celular + TV', 16, 'pessoal', 190.00, 0, 'media', '#0ea5e9', @mes, @ano, 'Prioridade 8. Celular dia 10, Internet dia 20, TV dia 25. Lucas R$100, Bia R$90.'),
(1, 'Fatura PicPay Casa', 12, 'pessoal', 850.00, 0, 'alta', '#00e676', @mes, @ano, 'Prioridade 9. Vence dia 20. Cartão da casa. Lucas R$200, Bia R$650.'),
(1, 'Reserva / Ajustes', 13, 'pessoal', 150.00, 0, 'baixa', '#6b7280', @mes, @ano, 'Prioridade 10. Diferença de contas, farmácia, imprevistos.');

-- =============================================
-- COFRINHOS BIA - 10 cofrinhos
-- =============================================

INSERT INTO cofrinhos (usuario_id, nome, categoria_id, tipo, meta_mensal, valor_atual, prioridade, cor, mes_referencia, ano_referencia, observacao) VALUES
(2, 'Faculdade', 6, 'pessoal', 237.19, 0, 'alta', '#3b82f6', @mes, @ano, 'Prioridade 1 ao receber. Vence dia 20.'),
(2, 'MEI + Celular + Canva', 16, 'pessoal', 160.85, 0, 'media', '#0891b2', @mes, @ano, 'Prioridade 2. MEI dia 20, Celular dia 10, Canva dia 20.'),
(2, 'Centro + Ônibus', 7, 'pessoal', 110.00, 0, 'media', '#f59e0b', @mes, @ano, 'Prioridade 3. Assim que receber.'),
(2, 'Unha', 11, 'pessoal', 125.00, 0, 'media', '#ec4899', @mes, @ano, 'Prioridade 4. Mensal.'),
(2, 'Hidratação + Sobrancelha', 11, 'pessoal', 110.00, 0, 'media', '#f472b6', @mes, @ano, 'Prioridade 5. Hidratação R$60 + Sobrancelha R$50.'),
(2, 'Progressiva', 11, 'pessoal', 125.00, 0, 'media', '#a855f7', @mes, @ano, 'Prioridade 6. R$125/mês fixo. Uso a cada 2 meses (R$250 acumulado).'),
(2, 'Fatura PicPay Bia', 12, 'pessoal', 150.00, 0, 'alta', '#e040fb', @mes, @ano, 'Prioridade 7. Vence dia 10. Gastos pessoais da Bia.'),
(2, 'Assinaturas', 10, 'pessoal', 65.00, 0, 'baixa', '#8b5cf6', @mes, @ano, 'Prioridade 8. Spotify R$12,90 + GPT R$39,90 + Google Fotos R$9,99.'),
(2, 'Casa / Apoio', 14, 'compartilhado', 870.00, 0, 'alta', '#d97706', @mes, @ano, 'Prioridade 9. Água+Energia R$130 + Internet+TV R$90 + PicPay Casa R$650.'),
(2, 'Reserva / Ajustes', 13, 'pessoal', 47.00, 0, 'baixa', '#6b7280', @mes, @ano, 'Prioridade 10. Sobras e emergências pequenas.');

-- =============================================
-- FATURAS REAIS - Abril e Maio 2026
-- =============================================

-- ---- XP: Fatura FECHADA Abril (vence 01/04) = R$ 396,63 ----
-- 12,90 + 6,50 + 12,90 + 100,00 + 264,33 = 396,63

INSERT INTO faturas (cartao_id, mes_referencia, ano_referencia, valor_total, data_fechamento, data_vencimento, status)
VALUES (1, 4, 2026, 396.63, '2026-03-22', '2026-04-01', 'fechada');
SET @f1 = LAST_INSERT_ID();

INSERT INTO fatura_lancamentos (fatura_id, descricao, valor, categoria_id, parcela_atual, total_parcelas, data_compra) VALUES
(@f1, 'Spotify Lucas', 12.90, 10, NULL, NULL, '2026-03-01'),
(@f1, 'Ubisoft', 6.50, 10, NULL, NULL, '2026-03-01'),
(@f1, 'Spotify (segundo)', 12.90, 10, NULL, NULL, '2026-03-01'),
(@f1, 'Pia - parcela 2/5', 100.00, 14, 2, 5, '2026-01-15'),
(@f1, 'Misael Moto Peças - parcela 2/3', 264.33, 7, 2, 3, '2026-01-20');

INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, cartao_id, data_vencimento, parcelada, total_parcelas, parcela_atual, mes_referencia, ano_referencia, status, observacao) VALUES
(1, 'Pia (parcela 2/5)', 100.00, 'parcelada', 14, 'compartilhado', 'credito', 1, '2026-04-01', 1, 5, 2, 4, 2026, 'pendente', 'Pia 5x R$100. Fatura XP.'),
(1, 'Misael Moto Peças (parcela 2/3)', 264.33, 'parcelada', 7, 'lucas', 'credito', 1, '2026-04-01', 1, 3, 2, 4, 2026, 'pendente', 'Conserto moto. 3x R$264,33. Fatura XP.'),
(1, 'Ubisoft', 6.50, 'fixa', 10, 'lucas', 'credito', 1, '2026-04-01', 0, NULL, NULL, 4, 2026, 'pendente', 'Assinatura recorrente.');


-- ---- XP: Fatura ABERTA Maio (vence 01/05) = R$ 448,27 ----
-- 13 + 14 + 0,96 + 27,51 + 0,96 + 27,51 + 100 + 264,33 = 448,27

INSERT INTO faturas (cartao_id, mes_referencia, ano_referencia, valor_total, data_fechamento, data_vencimento, status)
VALUES (1, 5, 2026, 448.27, '2026-04-22', '2026-05-01', 'aberta');
SET @f2 = LAST_INSERT_ID();

INSERT INTO fatura_lancamentos (fatura_id, descricao, valor, categoria_id, parcela_atual, total_parcelas, data_compra) VALUES
(@f2, 'Compra não identificada', 13.00, 12, NULL, NULL, '2026-03-25'),
(@f2, 'Disk Pizza Mirassol', 14.00, 17, NULL, NULL, '2026-03-26'),
(@f2, 'IOF transação exterior', 0.96, 15, NULL, NULL, '2026-03-27'),
(@f2, 'Contabo VPS empresa', 27.51, 15, NULL, NULL, '2026-03-27'),
(@f2, 'IOF transação exterior (2)', 0.96, 15, NULL, NULL, '2026-03-27'),
(@f2, 'Contabo VPS empresa (2)', 27.51, 15, NULL, NULL, '2026-03-27'),
(@f2, 'Pia - parcela 3/5', 100.00, 14, 3, 5, '2026-01-15'),
(@f2, 'Misael Moto Peças - parcela 3/3', 264.33, 7, 3, 3, '2026-01-20');

INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, cartao_id, data_vencimento, parcelada, total_parcelas, parcela_atual, mes_referencia, ano_referencia, status, observacao) VALUES
(1, 'Pia (parcela 3/5)', 100.00, 'parcelada', 14, 'compartilhado', 'credito', 1, '2026-05-01', 1, 5, 3, 5, 2026, 'pendente', 'Pia 5x R$100. Fatura XP.'),
(1, 'Misael Moto Peças (parcela 3/3)', 264.33, 'parcelada', 7, 'lucas', 'credito', 1, '2026-05-01', 1, 3, 3, 5, 2026, 'pendente', 'ÚLTIMA parcela. Conserto moto.'),
(1, 'Contabo VPS 1 (empresa)', 27.51, 'fixa', 15, 'empresa', 'credito', 1, '2026-05-01', 0, NULL, NULL, 5, 2026, 'pendente', 'VPS empresa US$4,95 + IOF.'),
(1, 'Contabo VPS 2 (empresa)', 27.51, 'fixa', 15, 'empresa', 'credito', 1, '2026-05-01', 0, NULL, NULL, 5, 2026, 'pendente', 'VPS empresa US$4,95 + IOF.'),
(1, 'Disk Pizza Mirassol', 14.00, 'variavel', 17, 'lucas', 'credito', 1, '2026-05-01', 0, NULL, NULL, 5, 2026, 'pendente', NULL);


-- ---- PicPay Lucas (Casa): Fatura ABERTA Abril (vence 20/04) = R$ 838,67 ----
-- 6,49+8,69+36,43+36,84+339,33+70,80+29,50+35,50+4,90+14+25,95+93,83+18,87+8+16,44+62,50+30,60 = 838,67

INSERT INTO faturas (cartao_id, mes_referencia, ano_referencia, valor_total, data_fechamento, data_vencimento, status)
VALUES (2, 4, 2026, 838.67, '2026-04-14', '2026-04-20', 'aberta');
SET @f3 = LAST_INSERT_ID();

INSERT INTO fatura_lancamentos (fatura_id, descricao, valor, categoria_id, parcela_atual, total_parcelas, data_compra) VALUES
(@f3, 'Servsol Supermercado', 6.49, 9, NULL, NULL, '2026-03-05'),
(@f3, 'Supermercado Antunes', 8.69, 9, NULL, NULL, '2026-03-06'),
(@f3, 'Quiro Pro (IA empresa)', 36.43, 15, NULL, NULL, '2026-03-07'),
(@f3, 'Servsol Supermercado', 36.84, 9, NULL, NULL, '2026-03-08'),
(@f3, 'Alfredo Antunes Supermercado', 339.33, 9, NULL, NULL, '2026-03-10'),
(@f3, 'Shopping Center', 70.80, 12, NULL, NULL, '2026-03-12'),
(@f3, 'Restaurante Cartago', 29.50, 17, NULL, NULL, '2026-03-13'),
(@f3, 'Restaurante Pau Seco', 35.50, 17, NULL, NULL, '2026-03-14'),
(@f3, 'Pão de Açúcar', 4.90, 9, NULL, NULL, '2026-03-15'),
(@f3, 'Café Restaurante', 14.00, 17, NULL, NULL, '2026-03-16'),
(@f3, 'Supermercado Antunes', 25.95, 9, NULL, NULL, '2026-03-18'),
(@f3, 'Servsol Supermercado', 93.83, 9, NULL, NULL, '2026-03-20'),
(@f3, 'Supermercado Servsol', 18.87, 9, NULL, NULL, '2026-03-22'),
(@f3, 'Feirinha (alface)', 8.00, 9, NULL, NULL, '2026-03-23'),
(@f3, 'TV Sala - parcela 2/12', 16.44, 10, 2, 12, '2025-04-01'),
(@f3, 'Material elétrico (lâmpadas/tomadas) 2/2', 62.50, 14, 2, 2, '2026-02-15'),
(@f3, 'Material elétrico 2 - 2/2', 30.60, 14, 2, 2, '2026-02-15');

INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, cartao_id, data_vencimento, parcelada, total_parcelas, parcela_atual, mes_referencia, ano_referencia, status, observacao) VALUES
(1, 'Mercado (vários supermercados)', 544.90, 'variavel', 9, 'compartilhado', 'credito', 2, '2026-04-20', 0, NULL, NULL, 4, 2026, 'pendente', 'Servsol R$6,49+R$36,84+R$93,83+R$18,87 | Antunes R$8,69+R$25,95+R$339,33 | Pão Açúcar R$4,90 | Feirinha R$8. ESTOUROU orçamento em R$44,90.'),
(1, 'Restaurantes/Lanches', 79.00, 'variavel', 17, 'compartilhado', 'credito', 2, '2026-04-20', 0, NULL, NULL, 4, 2026, 'pendente', 'Cartago R$29,50 + Pau Seco R$35,50 + Café R$14.'),
(1, 'Shopping Center', 70.80, 'variavel', 12, 'compartilhado', 'credito', 2, '2026-04-20', 0, NULL, NULL, 4, 2026, 'pendente', NULL),
(1, 'Quiro Pro (IA empresa)', 36.43, 'fixa', 15, 'empresa', 'credito', 2, '2026-04-20', 0, NULL, NULL, 4, 2026, 'pendente', 'Ferramenta IA para empresa.'),
(1, 'TV Sala (parcela 2/12)', 16.44, 'parcelada', 10, 'compartilhado', 'credito', 2, '2026-04-20', 1, 12, 2, 4, 2026, 'pendente', 'Parcela anual da TV.'),
(1, 'Material elétrico (parcela 2/2)', 62.50, 'parcelada', 14, 'compartilhado', 'credito', 2, '2026-04-20', 1, 2, 2, 4, 2026, 'pendente', 'Última parcela.'),
(1, 'Material elétrico 2 (parcela 2/2)', 30.60, 'parcelada', 14, 'compartilhado', 'credito', 2, '2026-04-20', 1, 2, 2, 4, 2026, 'pendente', 'Última parcela.');


-- ---- PicPay Lucas (Casa): Fatura ABERTA Maio (vence 20/05) = R$ 16,44 ----

INSERT INTO faturas (cartao_id, mes_referencia, ano_referencia, valor_total, data_fechamento, data_vencimento, status)
VALUES (2, 5, 2026, 16.44, '2026-05-14', '2026-05-20', 'aberta');
SET @f4 = LAST_INSERT_ID();

INSERT INTO fatura_lancamentos (fatura_id, descricao, valor, categoria_id, parcela_atual, total_parcelas, data_compra) VALUES
(@f4, 'TV Sala - parcela 3/12', 16.44, 10, 3, 12, '2025-04-01');


-- ---- Sicredi: Fatura Abril (vence 20/04) = R$ 8,33 ----

INSERT INTO faturas (cartao_id, mes_referencia, ano_referencia, valor_total, data_fechamento, data_vencimento, status)
VALUES (3, 4, 2026, 8.33, '2026-04-10', '2026-04-20', 'aberta');
SET @f5 = LAST_INSERT_ID();

INSERT INTO fatura_lancamentos (fatura_id, descricao, valor, categoria_id, data_compra) VALUES
(@f5, 'Anuidade cartão Sicredi', 8.33, 16, '2026-04-01');

INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, cartao_id, data_vencimento, parcelada, total_parcelas, parcela_atual, mes_referencia, ano_referencia, status, observacao) VALUES
(1, 'Anuidade Sicredi', 8.33, 'fixa', 16, 'lucas', 'credito', 3, '2026-04-20', 0, NULL, NULL, 4, 2026, 'pendente', 'Anuidade mensal. Cartão só para saque do aluguel.');


-- ---- PicPay Bia: Fatura Abril (vence 10/04) = R$ 138,12 ----
-- 16 + 15,34 + 23,90 + 12,99 + 39,99 + 29,90 = 138,12

INSERT INTO faturas (cartao_id, mes_referencia, ano_referencia, valor_total, data_fechamento, data_vencimento, status)
VALUES (4, 4, 2026, 138.12, '2026-04-01', '2026-04-10', 'aberta');
SET @f6 = LAST_INSERT_ID();

INSERT INTO fatura_lancamentos (fatura_id, descricao, valor, categoria_id, data_compra) VALUES
(@f6, 'Restaurante Sabor Caseiro', 16.00, 17, '2026-03-05'),
(@f6, 'Mirassol Comércio (restaurante)', 15.34, 17, '2026-03-08'),
(@f6, 'Spotify Bia', 23.90, 10, '2026-03-01'),
(@f6, 'Supermercado Antunes', 12.99, 9, '2026-03-10'),
(@f6, 'ChatGPT Bia', 39.99, 10, '2026-03-01'),
(@f6, 'Curso Udemy', 29.90, 6, '2026-03-15');

INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, cartao_id, data_vencimento, parcelada, total_parcelas, parcela_atual, mes_referencia, ano_referencia, status, observacao) VALUES
(2, 'Restaurantes Bia (Sabor Caseiro + Mirassol)', 31.34, 'variavel', 17, 'bia', 'credito', 4, '2026-04-10', 0, NULL, NULL, 4, 2026, 'pendente', 'Sabor Caseiro R$16 + Mirassol R$15,34.'),
(2, 'Spotify Bia', 23.90, 'fixa', 10, 'bia', 'credito', 4, '2026-04-10', 0, NULL, NULL, 4, 2026, 'pendente', 'Valor temporário R$23,90. Volta ao normal com desconto estudante.'),
(2, 'Supermercado Antunes (Bia)', 12.99, 'variavel', 9, 'bia', 'credito', 4, '2026-04-10', 0, NULL, NULL, 4, 2026, 'pendente', NULL),
(2, 'ChatGPT Bia', 39.99, 'fixa', 10, 'bia', 'credito', 4, '2026-04-10', 0, NULL, NULL, 4, 2026, 'pendente', NULL),
(2, 'Curso Udemy', 29.90, 'variavel', 6, 'bia', 'credito', 4, '2026-04-10', 0, NULL, NULL, 4, 2026, 'pendente', 'Compra avulsa.');

-- =============================================
-- CONFIGURAÇÕES DO SISTEMA
-- =============================================

INSERT INTO configuracoes (chave, valor) VALUES
('distribuicao_lucas', 'Ao receber R$3.500:\n1. Aluguel: R$1.000\n2. Água+Energia: R$250\n3. Faculdade+MEI: R$172,86\n4. Unimed: R$300\n5. Consórcio+Gasolina: R$613,32\n6. Investimento: R$300\n7. Fatura XP: R$450\n8. Internet+Celular+TV: R$100\n9. Fatura PicPay Casa: R$200\n10. Reserva: R$150\nSobra: ~R$113,82'),
('distribuicao_bia', 'Ao receber R$2.000:\n1. Faculdade: R$237,19\n2. MEI+Celular+Canva: R$160,85\n3. Centro+Ônibus: R$110\n4. Unha: R$125\n5. Hidratação+Sobrancelha: R$110\n6. Progressiva: R$125\n7. Fatura PicPay Bia: R$150\n8. Assinaturas: R$65\n9. Casa/Apoio: R$870\n10. Reserva: R$47\nSobra: ~R$0'),
('regra_cartao_casa', 'Teto mensal PicPay Casa: R$1.000\nMercado: R$500\nRestaurante/lanche: R$250\nCompras livres: R$250\nSe bater R$1.000, PARA de passar.'),
('divisao_casal', 'Proporção: Lucas 63,6% / Bia 36,4%\nTotal compartilhado: R$2.530\nLucas: R$1.609,08\nBia: R$920,92');

-- =============================================
-- NOTIFICAÇÕES INICIAIS
-- =============================================

INSERT INTO notificacoes (usuario_id, titulo, mensagem, tipo, link) VALUES
(1, 'Orçamento de mercado estourou em abril',
 'Gasto com mercado no PicPay Casa: R$544,90. Orçamento: R$500. Estourou R$44,90. Principal: Alfredo Antunes R$339,33.',
 'urgente', '/orcamentos'),
(2, 'Orçamento de mercado estourou em abril',
 'Gasto com mercado no PicPay Casa: R$544,90. Orçamento: R$500. Estourou R$44,90.',
 'alerta', '/orcamentos'),
(1, 'Bem-vindo ao FinançasCasal!',
 'Sistema configurado com seus dados reais. Cofrinhos, despesas, receitas e faturas prontos.',
 'sucesso', '/dashboard'),
(2, 'Bem-vindo ao FinançasCasal!',
 'Sistema configurado com seus dados reais. Cofrinhos, despesas, receitas e faturas prontos.',
 'sucesso', '/dashboard');
