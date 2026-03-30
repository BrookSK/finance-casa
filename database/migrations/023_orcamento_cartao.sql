-- =============================================
-- Migration 023 - Campo "entra no orçamento do cartão"
-- e orçamento de R$ 500 para gastos livres do cartão
--
-- Lógica:
-- - Mercado tem orçamento próprio de R$ 500
-- - Cartão tem orçamento de R$ 500 para gastos livres
-- - Assinaturas, parcelas, leite, empresa = NÃO entra
-- - Compras do dia a dia, shopping, restaurante = ENTRA
--
-- Data: 2026-03-29
-- =============================================

USE financas_casal;

-- Adicionar campo na tabela de despesas
ALTER TABLE despesas
ADD COLUMN entra_orcamento_cartao TINYINT(1) NOT NULL DEFAULT 0
AFTER observacao;
