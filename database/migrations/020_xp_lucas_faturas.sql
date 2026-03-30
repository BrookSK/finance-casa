-- =============================================
-- Migration 020 - XP Lucas: faturas março, abril e maio
-- Cartão XP (id=1): fecha dia 22, vence dia 1
--
-- Março: R$ 477,55 (paga)
-- Abril: R$ 396,63 (fechada, vence 01/04)
-- Maio: R$ 448,27 (aberta, fecha 22/04, vence 01/05)
-- Parcelas futuras: Pia 4/5 e 5/5
--
-- Data: 2026-03-29
-- =============================================

USE financas_casal;

-- =============================================
-- FATURA MARÇO - XP (PAGA)
-- Fechou ~22/02, venceu 01/03
-- Total: R$ 477,55
-- =============================================

INSERT INTO faturas (cartao_id, mes_referencia, ano_referencia, valor_total, data_fechamento, data_vencimento, status)
VALUES (1, 3, 2026, 477.55, '2026-02-22', '2026-03-01', 'paga');
SET @f_mar = LAST_INSERT_ID();

INSERT INTO fatura_lancamentos (fatura_id, descricao, valor, categoria_id, parcela_atual, total_parcelas, data_compra) VALUES
(@f_mar, 'Paracatu Materiais - Pia (parcela 1/5)', 100.00, 14, 1, 5, '2026-02-13'),
(@f_mar, 'Misael Moto Peças (parcela 1/3)', 264.34, 7, 1, 3, '2026-02-06'),
(@f_mar, 'Uber (ida Rio Preto)', 10.59, 7, NULL, NULL, '2026-02-02'),
(@f_mar, 'Uber (volta Rio Preto)', 9.77, 7, NULL, NULL, '2026-02-02'),
(@f_mar, 'Boticário perfume mãe (parcela 2/?)', 79.95, 12, 2, NULL, '2026-02-24'),
(@f_mar, 'Spotify Lucas', 12.90, 10, NULL, NULL, '2026-01-22');

-- Despesa março XP (paga)
INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, cartao_id, data_vencimento, data_pagamento, parcelada, total_parcelas, parcela_atual, mes_referencia, ano_referencia, status, observacao) VALUES
(1, 'Fatura XP - Março', 477.55, 'variavel', 12, 'lucas', 'credito', 1, '2026-03-01', '2026-03-01', 0, NULL, NULL, 3, 2026, 'paga', 'Pia 1/5 R$100 + Moto 1/3 R$264,34 + Uber R$20,36 + Boticário R$79,95 + Spotify R$12,90');


-- =============================================
-- FATURA ABRIL - XP (FECHADA)
-- Fechou ~22/03, vence 01/04
-- Total: R$ 396,63
-- =============================================

INSERT INTO faturas (cartao_id, mes_referencia, ano_referencia, valor_total, data_fechamento, data_vencimento, status)
VALUES (1, 4, 2026, 396.63, '2026-03-22', '2026-04-01', 'fechada');
SET @f_abr = LAST_INSERT_ID();

INSERT INTO fatura_lancamentos (fatura_id, descricao, valor, categoria_id, parcela_atual, total_parcelas, data_compra) VALUES
(@f_abr, 'Spotify Lucas', 12.90, 10, NULL, NULL, '2026-03-22'),
(@f_abr, 'Ubisoft (jogo)', 6.50, 10, NULL, NULL, '2026-03-01'),
(@f_abr, 'Spotify Lucas (duplicado? verificar)', 12.90, 10, NULL, NULL, '2026-02-22'),
(@f_abr, 'Paracatu Materiais - Pia (parcela 2/5)', 100.00, 14, 2, 5, '2026-02-13'),
(@f_abr, 'Misael Moto Peças (parcela 2/3)', 264.33, 7, 2, 3, '2026-02-06');

-- Despesas abril XP (pendente, vence 01/04)
INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, cartao_id, data_vencimento, parcelada, total_parcelas, parcela_atual, mes_referencia, ano_referencia, status, observacao) VALUES
(1, 'Paracatu Pia (parcela 2/5)', 100.00, 'parcelada', 14, 'compartilhado', 'credito', 1, '2026-04-01', 1, 5, 2, 4, 2026, 'pendente', NULL),
(1, 'Misael Moto Peças (parcela 2/3)', 264.33, 'parcelada', 7, 'lucas', 'credito', 1, '2026-04-01', 1, 3, 2, 4, 2026, 'pendente', NULL),
(1, 'Spotify Lucas', 12.90, 'fixa', 10, 'lucas', 'credito', 1, '2026-04-01', 0, NULL, NULL, 4, 2026, 'pendente', NULL),
(1, 'Ubisoft (jogo)', 6.50, 'fixa', 10, 'lucas', 'credito', 1, '2026-04-01', 0, NULL, NULL, 4, 2026, 'pendente', NULL),
(1, 'Spotify (duplicado? verificar)', 12.90, 'fixa', 10, 'lucas', 'credito', 1, '2026-04-01', 0, NULL, NULL, 4, 2026, 'pendente', 'Apareceu duplicado na fatura. Verificar com a XP.');


-- =============================================
-- FATURA MAIO - XP (ABERTA)
-- Fecha 22/04, vence 01/05
-- Total: R$ 448,27
-- =============================================

INSERT INTO faturas (cartao_id, mes_referencia, ano_referencia, valor_total, data_fechamento, data_vencimento, status)
VALUES (1, 5, 2026, 448.27, '2026-04-22', '2026-05-01', 'aberta');
SET @f_mai = LAST_INSERT_ID();

INSERT INTO fatura_lancamentos (fatura_id, descricao, valor, categoria_id, parcela_atual, total_parcelas, data_compra) VALUES
(@f_mai, 'Café com Bia', 13.00, 9, NULL, NULL, '2026-03-26'),
(@f_mai, 'Disk Pizza Mirassol', 14.00, 9, NULL, NULL, '2026-03-24'),
(@f_mai, 'Contabo VPS (empresa)', 27.51, 15, NULL, NULL, '2026-03-24'),
(@f_mai, 'IOF Contabo', 0.96, 15, NULL, NULL, '2026-03-24'),
(@f_mai, 'Contabo VPS 2 (empresa)', 27.51, 15, NULL, NULL, '2026-03-24'),
(@f_mai, 'IOF Contabo 2', 0.96, 15, NULL, NULL, '2026-03-24'),
(@f_mai, 'Paracatu Materiais - Pia (parcela 3/5)', 100.00, 14, 3, 5, '2026-02-13'),
(@f_mai, 'Misael Moto Peças (parcela 3/3) ÚLTIMA', 264.33, 7, 3, 3, '2026-02-06');

-- Despesas maio XP (pendente, vence 01/05)
INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, cartao_id, data_vencimento, parcelada, total_parcelas, parcela_atual, mes_referencia, ano_referencia, status, observacao) VALUES
(1, 'Paracatu Pia (parcela 3/5)', 100.00, 'parcelada', 14, 'compartilhado', 'credito', 1, '2026-05-01', 1, 5, 3, 5, 2026, 'pendente', NULL),
(1, 'Misael Moto Peças (parcela 3/3)', 264.33, 'parcelada', 7, 'lucas', 'credito', 1, '2026-05-01', 1, 3, 3, 5, 2026, 'pendente', 'ÚLTIMA parcela.'),
(1, 'Contabo VPS (empresa)', 27.51, 'fixa', 15, 'empresa', 'credito', 1, '2026-05-01', 0, NULL, NULL, 5, 2026, 'pendente', 'US$4,95 + IOF R$0,96'),
(1, 'Contabo VPS 2 (empresa)', 27.51, 'fixa', 15, 'empresa', 'credito', 1, '2026-05-01', 0, NULL, NULL, 5, 2026, 'pendente', 'US$4,95 + IOF R$0,96'),
(1, 'Café com Bia', 13.00, 'variavel', 9, 'compartilhado', 'credito', 1, '2026-05-01', 0, NULL, NULL, 5, 2026, 'pendente', NULL),
(1, 'Disk Pizza Mirassol', 14.00, 'variavel', 9, 'compartilhado', 'credito', 1, '2026-05-01', 0, NULL, NULL, 5, 2026, 'pendente', NULL);


-- =============================================
-- PARCELAS FUTURAS DA PIA (4/5 e 5/5)
-- Misael Moto já termina em maio (3/3)
-- =============================================

-- Junho: Pia 4/5
INSERT INTO faturas (cartao_id, mes_referencia, ano_referencia, valor_total, data_fechamento, data_vencimento, status)
VALUES (1, 6, 2026, 100.00, '2026-05-22', '2026-06-01', 'aberta');
SET @f_jun = LAST_INSERT_ID();
INSERT INTO fatura_lancamentos (fatura_id, descricao, valor, categoria_id, parcela_atual, total_parcelas, data_compra) VALUES
(@f_jun, 'Paracatu Materiais - Pia (parcela 4/5)', 100.00, 14, 4, 5, '2026-02-13');
INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, cartao_id, data_vencimento, parcelada, total_parcelas, parcela_atual, mes_referencia, ano_referencia, status) VALUES
(1, 'Paracatu Pia (parcela 4/5)', 100.00, 'parcelada', 14, 'compartilhado', 'credito', 1, '2026-06-01', 1, 5, 4, 6, 2026, 'pendente');

-- Julho: Pia 5/5 ÚLTIMA
INSERT INTO faturas (cartao_id, mes_referencia, ano_referencia, valor_total, data_fechamento, data_vencimento, status)
VALUES (1, 7, 2026, 100.00, '2026-06-22', '2026-07-01', 'aberta');
SET @f_jul = LAST_INSERT_ID();
INSERT INTO fatura_lancamentos (fatura_id, descricao, valor, categoria_id, parcela_atual, total_parcelas, data_compra) VALUES
(@f_jul, 'Paracatu Materiais - Pia (parcela 5/5) ÚLTIMA', 100.00, 14, 5, 5, '2026-02-13');
INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, cartao_id, data_vencimento, parcelada, total_parcelas, parcela_atual, mes_referencia, ano_referencia, status) VALUES
(1, 'Paracatu Pia (parcela 5/5)', 100.00, 'parcelada', 14, 'compartilhado', 'credito', 1, '2026-07-01', 1, 5, 5, 7, 2026, 'pendente');
