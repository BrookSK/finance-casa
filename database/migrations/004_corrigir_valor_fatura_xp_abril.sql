-- =============================================
-- Migration 004 - Corrigir valor fatura XP abril
-- Valor correto: R$ 370,83 (era R$ 396,63)
-- Data: 2026-03-29
-- =============================================

USE financas_casal;

UPDATE faturas
SET valor_total = 370.83
WHERE cartao_id = 1
  AND mes_referencia = 4
  AND ano_referencia = 2026;
