-- =============================================
-- Migration 019 - Adicionar IOF faltante na fatura PicPay Lucas abril
-- Diferença de R$ 3,00 entre soma dos lançamentos e total real
-- Data: 2026-03-29
-- =============================================

USE financas_casal;

INSERT INTO fatura_lancamentos (fatura_id, descricao, valor, categoria_id, data_compra)
SELECT id, 'IOF / ajuste (verificar)', 3.00, 15, '2026-03-21'
FROM faturas WHERE cartao_id = 2 AND mes_referencia = 4 AND ano_referencia = 2026;
