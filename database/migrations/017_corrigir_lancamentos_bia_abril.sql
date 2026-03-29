-- =============================================
-- Migration 017 - Corrigir lançamentos PicPay Bia abril
--
-- Correções:
-- 1. Sabor Caseiro: Restaurante → Mercado (era marmita), data 25/03
-- 2. Mirassol Comércio: Restaurante → Mercado (era marmita), data 24/03
-- 3. ChatGPT: data compra → 14/03
-- 4. Spotify: data compra → 16/03
-- 5. Udemy: data compra → 12/03
-- 6. Pagamento fatura março (R$52,89): registrar como pago em abril
--
-- Data: 2026-03-29
-- =============================================

USE financas_casal;

-- =============================================
-- Corrigir lançamentos na fatura (fatura_lancamentos)
-- =============================================

-- Sabor Caseiro: categoria 17 (Restaurante) → 9 (Mercado), data → 25/03
UPDATE fatura_lancamentos
SET categoria_id = 9, descricao = 'Sabor Caseiro (marmita)', data_compra = '2026-03-25'
WHERE descricao = 'Restaurante Sabor Caseiro'
  AND fatura_id = (SELECT id FROM faturas WHERE cartao_id = 4 AND mes_referencia = 4 AND ano_referencia = 2026);

-- Mirassol Comércio: categoria 17 → 9 (Mercado), data → 24/03
UPDATE fatura_lancamentos
SET categoria_id = 9, descricao = 'Mirassol Comércio (marmita)', data_compra = '2026-03-24'
WHERE descricao LIKE 'Mirassol Comércio%'
  AND fatura_id = (SELECT id FROM faturas WHERE cartao_id = 4 AND mes_referencia = 4 AND ano_referencia = 2026);

-- ChatGPT: data → 14/03
UPDATE fatura_lancamentos
SET data_compra = '2026-03-14'
WHERE descricao = 'ChatGPT Bia'
  AND fatura_id = (SELECT id FROM faturas WHERE cartao_id = 4 AND mes_referencia = 4 AND ano_referencia = 2026);

-- Spotify: data → 16/03
UPDATE fatura_lancamentos
SET data_compra = '2026-03-16'
WHERE descricao = 'Spotify Bia'
  AND fatura_id = (SELECT id FROM faturas WHERE cartao_id = 4 AND mes_referencia = 4 AND ano_referencia = 2026);

-- Udemy: data → 12/03
UPDATE fatura_lancamentos
SET data_compra = '2026-03-12'
WHERE descricao = 'Curso Udemy'
  AND fatura_id = (SELECT id FROM faturas WHERE cartao_id = 4 AND mes_referencia = 4 AND ano_referencia = 2026);


-- =============================================
-- Corrigir despesas correspondentes
-- =============================================

-- Sabor Caseiro: Restaurante → Mercado, nome e data
UPDATE despesas
SET categoria_id = 9, nome = 'Sabor Caseiro (marmita)', observacao = 'Marmita. Entra no orçamento de mercado.'
WHERE nome LIKE 'Restaurante Sabor Caseiro%'
  AND mes_referencia = 4 AND ano_referencia = 2026 AND usuario_id = 2;

-- Mirassol: Restaurante → Mercado
UPDATE despesas
SET categoria_id = 9, nome = 'Mirassol Comércio (marmita)', observacao = 'Marmita. Entra no orçamento de mercado.'
WHERE nome LIKE 'Mirassol Comércio%'
  AND mes_referencia = 4 AND ano_referencia = 2026 AND usuario_id = 2;


-- =============================================
-- Registrar pagamento da fatura de março como lançamento em abril
-- (o PicPay mostra isso na fatura de abril)
-- Não é um gasto novo, é só o registro do pagamento.
-- Vamos adicionar como lançamento informativo com valor 0
-- para não duplicar o gasto.
-- =============================================

-- Na verdade, o pagamento da fatura anterior NÃO deve entrar como
-- lançamento na fatura nova. O PicPay mostra por transparência,
-- mas o valor da fatura de abril (R$138,12) já é só os gastos novos.
-- Então não precisa adicionar nada.
-- A fatura de março já está marcada como 'paga' com data 09/03. ✅
