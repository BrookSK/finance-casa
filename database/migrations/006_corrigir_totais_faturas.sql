-- =============================================
-- Migration 006 - Corrigir totais das faturas
-- Valores = soma exata dos lançamentos informados
--
-- XP abril: 12,90+6,50+12,90+100+264,33 = 396,63
-- XP maio: 13+14+0,96+27,51+0,96+27,51+100+264,33 = 448,27
-- PicPay Lucas abril: 6,49+8,69+36,43+36,84+339,33+70,80+29,50+35,50+4,90+14+25,95+93,83+18,87+8+16,44+62,50+30,60 = 838,67
-- PicPay Bia abril: 16+15,34+23,90+12,99+39,99+29,90 = 138,12
-- Sicredi abril: 8,33
--
-- Data: 2026-03-29
-- =============================================

USE financas_casal;

-- XP abril: corrigir para 396,63
UPDATE faturas SET valor_total = 396.63
WHERE cartao_id = 1 AND mes_referencia = 4 AND ano_referencia = 2026;

-- XP maio: confirmar 448,27
UPDATE faturas SET valor_total = 448.27
WHERE cartao_id = 1 AND mes_referencia = 5 AND ano_referencia = 2026;

-- PicPay Lucas abril: corrigir para 838,67
UPDATE faturas SET valor_total = 838.67
WHERE cartao_id = 2 AND mes_referencia = 4 AND ano_referencia = 2026;

-- PicPay Lucas maio: confirmar 16,44
UPDATE faturas SET valor_total = 16.44
WHERE cartao_id = 2 AND mes_referencia = 5 AND ano_referencia = 2026;

-- PicPay Bia abril: confirmar 138,12
UPDATE faturas SET valor_total = 138.12
WHERE cartao_id = 4 AND mes_referencia = 4 AND ano_referencia = 2026;

-- Sicredi abril: confirmar 8,33
UPDATE faturas SET valor_total = 8.33
WHERE cartao_id = 3 AND mes_referencia = 4 AND ano_referencia = 2026;
