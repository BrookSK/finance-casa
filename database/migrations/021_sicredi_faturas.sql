-- =============================================
-- Migration 021 - Sicredi: faturas fevereiro a abril
-- Cartão Sicredi (id=3): fecha dia 10, vence dia 20
-- Só anuidade de R$ 8,33 todo mês, cai dia 8
-- Paga sempre dia 20
-- Data: 2026-03-29
-- =============================================

USE financas_casal;

-- Fevereiro (paga)
INSERT INTO faturas (cartao_id, mes_referencia, ano_referencia, valor_total, data_fechamento, data_vencimento, status)
VALUES (3, 2, 2026, 8.33, '2026-02-10', '2026-02-20', 'paga');
SET @f2 = LAST_INSERT_ID();
INSERT INTO fatura_lancamentos (fatura_id, descricao, valor, categoria_id, data_compra) VALUES
(@f2, 'Anuidade Sicredi', 8.33, 16, '2026-02-08');

-- Março (paga)
INSERT INTO faturas (cartao_id, mes_referencia, ano_referencia, valor_total, data_fechamento, data_vencimento, status)
VALUES (3, 3, 2026, 8.33, '2026-03-10', '2026-03-20', 'paga');
SET @f3 = LAST_INSERT_ID();
INSERT INTO fatura_lancamentos (fatura_id, descricao, valor, categoria_id, data_compra) VALUES
(@f3, 'Anuidade Sicredi', 8.33, 16, '2026-03-08');

INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, cartao_id, data_vencimento, data_pagamento, parcelada, total_parcelas, parcela_atual, mes_referencia, ano_referencia, status, observacao) VALUES
(1, 'Anuidade Sicredi', 8.33, 'fixa', 16, 'lucas', 'credito', 3, '2026-03-20', '2026-03-20', 0, NULL, NULL, 3, 2026, 'paga', 'Cartão mantido só para saque do aluguel.');

-- Abril (aberta, vence 20/04)
INSERT INTO faturas (cartao_id, mes_referencia, ano_referencia, valor_total, data_fechamento, data_vencimento, status)
VALUES (3, 4, 2026, 8.33, '2026-04-10', '2026-04-20', 'aberta');
SET @f4 = LAST_INSERT_ID();
INSERT INTO fatura_lancamentos (fatura_id, descricao, valor, categoria_id, data_compra) VALUES
(@f4, 'Anuidade Sicredi', 8.33, 16, '2026-04-08');

INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, cartao_id, data_vencimento, parcelada, total_parcelas, parcela_atual, mes_referencia, ano_referencia, status, observacao) VALUES
(1, 'Anuidade Sicredi', 8.33, 'fixa', 16, 'lucas', 'credito', 3, '2026-04-20', 0, NULL, NULL, 4, 2026, 'pendente', 'Cartão mantido só para saque do aluguel.');
