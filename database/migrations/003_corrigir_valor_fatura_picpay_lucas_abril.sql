-- =============================================
-- Migration 003 - Corrigir valor fatura PicPay Lucas abril
-- Valor correto: R$ 840,67 (era R$ 837,86)
-- Data: 2026-03-29
-- =============================================

USE financas_casal;

UPDATE faturas
SET valor_total = 840.67
WHERE cartao_id = 2
  AND mes_referencia = 4
  AND ano_referencia = 2026;
