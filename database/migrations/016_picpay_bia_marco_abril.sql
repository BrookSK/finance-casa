-- =============================================
-- Migration 016 - PicPay Bia: faturas março e abril
-- Cartão PicPay Bia (id=4): fecha dia 1, vence dia 10
--
-- Março: R$ 52,89 (paga dia 09/03)
--   Spotify R$12,90 + ChatGPT R$39,99
--
-- Abril: R$ 138,12 (aberta, vence 10/04)
--   Sabor Caseiro R$16 + Mirassol R$15,34 + Spotify R$23,90
--   + Antunes R$12,99 + ChatGPT R$39,99 + Udemy R$29,90
--
-- Data: 2026-03-29
-- =============================================

USE financas_casal;

-- =============================================
-- FATURA MARÇO - PicPay Bia (PAGA)
-- Fechou 01/03, venceu 10/03, paga 09/03
-- Total: R$ 52,89
-- =============================================

INSERT INTO faturas (cartao_id, mes_referencia, ano_referencia, valor_total, data_fechamento, data_vencimento, status)
VALUES (4, 3, 2026, 52.89, '2026-03-01', '2026-03-10', 'paga');
SET @f_bia_mar = LAST_INSERT_ID();

INSERT INTO fatura_lancamentos (fatura_id, descricao, valor, categoria_id, data_compra) VALUES
(@f_bia_mar, 'Spotify Bia', 12.90, 10, '2026-02-01'),
(@f_bia_mar, 'ChatGPT Bia', 39.99, 10, '2026-02-01');

-- Despesas de março (pagas)
INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, cartao_id, data_vencimento, data_pagamento, parcelada, total_parcelas, parcela_atual, mes_referencia, ano_referencia, status, observacao) VALUES
(2, 'Spotify Bia', 12.90, 'fixa', 10, 'bia', 'credito', 4, '2026-03-10', '2026-03-09', 0, NULL, NULL, 3, 2026, 'paga', 'Fatura PicPay Bia março.'),
(2, 'ChatGPT Bia', 39.99, 'fixa', 10, 'bia', 'credito', 4, '2026-03-10', '2026-03-09', 0, NULL, NULL, 3, 2026, 'paga', 'Fatura PicPay Bia março.');


-- =============================================
-- FATURA ABRIL - PicPay Bia (ABERTA)
-- Fecha 01/04, vence 10/04
-- Total: R$ 138,12
-- =============================================

INSERT INTO faturas (cartao_id, mes_referencia, ano_referencia, valor_total, data_fechamento, data_vencimento, status)
VALUES (4, 4, 2026, 138.12, '2026-04-01', '2026-04-10', 'aberta');
SET @f_bia_abr = LAST_INSERT_ID();

INSERT INTO fatura_lancamentos (fatura_id, descricao, valor, categoria_id, data_compra) VALUES
(@f_bia_abr, 'Restaurante Sabor Caseiro', 16.00, 17, '2026-03-05'),
(@f_bia_abr, 'Mirassol Comércio (alimentação)', 15.34, 17, '2026-03-08'),
(@f_bia_abr, 'Spotify Bia', 23.90, 10, '2026-03-01'),
(@f_bia_abr, 'Supermercado Antunes', 12.99, 9, '2026-03-10'),
(@f_bia_abr, 'ChatGPT Bia', 39.99, 10, '2026-03-01'),
(@f_bia_abr, 'Curso Udemy', 29.90, 6, '2026-03-15');

-- Despesas de abril (pendentes — vence 10/04)
INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, cartao_id, data_vencimento, parcelada, total_parcelas, parcela_atual, mes_referencia, ano_referencia, status, observacao) VALUES
(2, 'Restaurante Sabor Caseiro', 16.00, 'variavel', 17, 'bia', 'credito', 4, '2026-04-10', 0, NULL, NULL, 4, 2026, 'pendente', NULL),
(2, 'Mirassol Comércio (alimentação)', 15.34, 'variavel', 17, 'bia', 'credito', 4, '2026-04-10', 0, NULL, NULL, 4, 2026, 'pendente', NULL),
(2, 'Spotify Bia', 23.90, 'fixa', 10, 'bia', 'credito', 4, '2026-04-10', 0, NULL, NULL, 4, 2026, 'pendente', 'Valor temporário R$23,90. Volta ao normal com desconto estudante.'),
(2, 'Supermercado Antunes', 12.99, 'variavel', 9, 'bia', 'credito', 4, '2026-04-10', 0, NULL, NULL, 4, 2026, 'pendente', NULL),
(2, 'ChatGPT Bia', 39.99, 'fixa', 10, 'bia', 'credito', 4, '2026-04-10', 0, NULL, NULL, 4, 2026, 'pendente', NULL),
(2, 'Curso Udemy', 29.90, 'variavel', 6, 'bia', 'credito', 4, '2026-04-10', 0, NULL, NULL, 4, 2026, 'pendente', 'Compra avulsa.');
