-- =============================================
-- Migration 029 - Parcelas entram no orçamento do cartão
-- Só não entram: assinaturas e empresa
-- Data: 2026-03-29
-- =============================================

USE financas_casal;

-- Desmarcar parcelas (elas entram no orçamento)
UPDATE despesas SET excluir_orcamento_cartao = 0
WHERE cartao_id IS NOT NULL AND (
    nome LIKE 'OnExpress%'
    OR nome LIKE 'MiraWatts%'
    OR nome LIKE 'Paracatu%'
    OR nome LIKE 'Misael%'
    OR nome LIKE 'Material elétrico%'
);
