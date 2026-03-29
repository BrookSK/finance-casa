-- =============================================
-- Migration 008 - Dados complementares
-- Nomes reais dos clientes, empresa e contas bancárias
-- Data: 2026-03-29
-- =============================================

USE financas_casal;

-- =============================================
-- 1) Corrigir nomes dos clientes da empresa (LRV Web)
-- =============================================

UPDATE receitas SET titulo = 'Punta Cana para Brasileiros', observacao = 'Cliente LRV Web. Mensal.'
WHERE titulo = 'Cliente 1' AND mes_referencia = 3 AND ano_referencia = 2026;

UPDATE receitas SET titulo = 'DM Games', valor = 90.00, observacao = 'Cliente LRV Web. Mensal.'
WHERE titulo = 'Cliente 2' AND mes_referencia = 3 AND ano_referencia = 2026;

-- Adicionar H2 Games (faltava)
INSERT INTO receitas (usuario_id, titulo, valor, tipo, categoria_id, recorrente, entra_no_orcamento, mes_referencia, ano_referencia, status, observacao)
VALUES (1, 'H2 Games', 90.00, 'variavel', 3, 0, 0, 3, 2026, 'prevista', 'Cliente LRV Web. Mensal.');


-- =============================================
-- 2) Tabela de contas bancárias
-- =============================================

CREATE TABLE IF NOT EXISTS contas_bancarias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    nome VARCHAR(100) NOT NULL,
    banco VARCHAR(100) NOT NULL,
    tipo ENUM('pessoal', 'empresa') NOT NULL DEFAULT 'pessoal',
    proprietario VARCHAR(50) NOT NULL,
    observacao TEXT,
    ativo TINYINT(1) NOT NULL DEFAULT 1,
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- =============================================
-- Contas do Lucas (pessoais)
-- =============================================

INSERT INTO contas_bancarias (usuario_id, nome, banco, tipo, proprietario, observacao) VALUES
(1, 'PicPay Lucas', 'PicPay', 'pessoal', 'Lucas', 'Conta principal. Cofrinhos e cartão da casa.'),
(1, 'XP Investimentos', 'XP', 'pessoal', 'Lucas', 'Cartão XP e investimentos.'),
(1, 'Inter Lucas', 'Inter', 'pessoal', 'Lucas', NULL),
(1, 'Mercado Pago Lucas', 'Mercado Pago', 'pessoal', 'Lucas', NULL),
(1, 'Modalmais', 'Modalmais', 'pessoal', 'Lucas', NULL),
(1, 'Clear', 'Clear', 'pessoal', 'Lucas', NULL),
(1, 'Sicredi', 'Sicredi', 'pessoal', 'Lucas', 'Único banco físico. Saque do aluguel.');

-- =============================================
-- Contas da empresa (LRV Web)
-- =============================================

INSERT INTO contas_bancarias (usuario_id, nome, banco, tipo, proprietario, observacao) VALUES
(1, 'Inter Empresas', 'Inter', 'empresa', 'LRV Web', NULL),
(1, 'EFI Bank', 'EFI Bank', 'empresa', 'LRV Web', NULL),
(1, 'PicPay Negócios', 'PicPay', 'empresa', 'LRV Web', NULL),
(1, 'Asaas', 'Asaas', 'empresa', 'LRV Web', NULL),
(1, 'Stripe', 'Stripe', 'empresa', 'LRV Web', NULL),
(1, 'App Max', 'App Max', 'empresa', 'LRV Web', NULL);

-- =============================================
-- Contas da Bia
-- =============================================

INSERT INTO contas_bancarias (usuario_id, nome, banco, tipo, proprietario, observacao) VALUES
(2, 'PicPay Bia', 'PicPay', 'pessoal', 'Bia', 'Conta principal. Cofrinhos e cartão.'),
(2, 'Mercado Pago Bia', 'Mercado Pago', 'pessoal', 'Bia', NULL),
(2, 'Santander Bia', 'Santander', 'pessoal', 'Bia', NULL),
(2, 'Inter Bia', 'Inter', 'pessoal', 'Bia', NULL);


-- =============================================
-- 3) Salvar nome da empresa nas configurações
-- =============================================

INSERT INTO configuracoes (chave, valor) VALUES
('empresa_nome', 'LRV Web'),
('empresa_clientes', 'Punta Cana para Brasileiros: R$500/mês\nDM Games: R$90/mês\nH2 Games: R$90/mês\nTotal: R$680/mês (não entra no orçamento principal)')
ON DUPLICATE KEY UPDATE valor = VALUES(valor);
