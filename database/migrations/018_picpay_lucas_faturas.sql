-- =============================================
-- Migration 018 - PicPay Lucas: faturas março, abril e parcelas TV
-- Cartão PicPay Lucas (id=2): fecha dia 14, vence dia 20
--
-- Março: R$ 1.100,85 (paga 16/03)
-- Abril: R$ 837,86 (aberta, vence 20/04)
-- Maio a Fev/2027: TV OnExpress parcelas 3/12 a 12/12
--
-- Data: 2026-03-29
-- =============================================

USE financas_casal;

-- =============================================
-- FATURA MARÇO - PicPay Lucas (PAGA)
-- Fechou 14/03, venceu 20/03, paga 16/03
-- Total: R$ 1.100,85
-- =============================================

INSERT INTO faturas (cartao_id, mes_referencia, ano_referencia, valor_total, data_fechamento, data_vencimento, status)
VALUES (2, 3, 2026, 1100.85, '2026-03-14', '2026-03-20', 'paga');
SET @f_mar = LAST_INSERT_ID();

INSERT INTO fatura_lancamentos (fatura_id, descricao, valor, categoria_id, parcela_atual, total_parcelas, data_compra) VALUES
(@f_mar, 'Windsurf IA (empresa)', 81.30, 15, NULL, NULL, '2026-03-10'),
(@f_mar, 'IOF Windsurf', 2.84, 15, NULL, NULL, '2026-03-10'),
(@f_mar, 'Casa e Campo Agropecuária (tomadas)', 30.90, 14, NULL, NULL, '2026-03-07'),
(@f_mar, 'Windsurf IA (empresa)', 54.71, 15, NULL, NULL, '2026-03-05'),
(@f_mar, 'OnExpress TV Sala - parcela 1/12', 16.44, 10, 1, 12, '2026-03-04'),
(@f_mar, 'Megaforte Materiais (tomadas)', 9.79, 14, NULL, NULL, '2026-02-26'),
(@f_mar, 'Servsol Supermercado', 40.68, 9, NULL, NULL, '2026-02-26'),
(@f_mar, 'Megaforte Materiais (tomadas)', 7.00, 14, NULL, NULL, '2026-02-26'),
(@f_mar, 'MP Niles Designer', 6.00, 12, NULL, NULL, '2026-02-23'),
(@f_mar, 'MiraWatts - parcela 1/2', 62.50, 14, 1, 2, '2026-02-23'),
(@f_mar, 'MiraWatts - parcela 1/2', 30.60, 14, 1, 2, '2026-02-23'),
(@f_mar, 'Mercado Max Potirendaba', 584.98, 9, NULL, NULL, '2026-02-22'),
(@f_mar, 'Mercado Proença', 8.59, 9, NULL, NULL, '2026-02-20'),
(@f_mar, 'ServiFesta', 28.50, 9, NULL, NULL, '2026-02-16'),
(@f_mar, 'Megaforte Materiais', 55.00, 14, NULL, NULL, '2026-02-16'),
(@f_mar, 'Mercado Sol Nascente', 17.49, 9, NULL, NULL, '2026-02-16'),
(@f_mar, 'Shopee (casa)', 32.97, 14, NULL, NULL, '2026-02-15'),
(@f_mar, 'Fladmir Medeiros', 30.56, 12, NULL, NULL, '2026-02-14');

-- Despesa da fatura de março (paga)
INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, cartao_id, data_vencimento, data_pagamento, parcelada, total_parcelas, parcela_atual, mes_referencia, ano_referencia, status, observacao) VALUES
(1, 'Fatura PicPay Casa - Março', 1100.85, 'variavel', 12, 'compartilhado', 'credito', 2, '2026-03-20', '2026-03-16', 0, NULL, NULL, 3, 2026, 'paga', 'Mercado R$720,92 + Casa/Material R$236,76 + Empresa R$138,85 + Outros R$4,32');


-- =============================================
-- FATURA ABRIL - PicPay Lucas (ABERTA)
-- Fecha 14/04, vence 20/04
-- Total real: R$ 837,86
-- =============================================

INSERT INTO faturas (cartao_id, mes_referencia, ano_referencia, valor_total, data_fechamento, data_vencimento, status)
VALUES (2, 4, 2026, 837.86, '2026-04-14', '2026-04-20', 'aberta');
SET @f_abr = LAST_INSERT_ID();

INSERT INTO fatura_lancamentos (fatura_id, descricao, valor, categoria_id, parcela_atual, total_parcelas, data_compra) VALUES
(@f_abr, 'Servsol Supermercado', 6.49, 9, NULL, NULL, '2026-03-24'),
(@f_abr, 'Supermercado Antunes', 8.69, 9, NULL, NULL, '2026-03-23'),
(@f_abr, 'Quiro Pro IA (empresa)', 33.43, 15, NULL, NULL, '2026-03-21'),
(@f_abr, 'Servsol Supermercado', 36.84, 9, NULL, NULL, '2026-03-21'),
(@f_abr, 'Empório Alfredo Antunes', 339.33, 9, NULL, NULL, '2026-03-18'),
(@f_abr, 'Shopping Center 3 (alimentação viagem SP)', 70.80, 9, NULL, NULL, '2026-03-17'),
(@f_abr, 'Restaurante Cartago (alimentação viagem SP)', 29.50, 9, NULL, NULL, '2026-03-17'),
(@f_abr, 'Restaurante Pau Seco (alimentação viagem)', 35.50, 9, NULL, NULL, '2026-03-17'),
(@f_abr, 'Pão de Açúcar', 4.09, 9, NULL, NULL, '2026-03-17'),
(@f_abr, 'Bar e Café (alimentação viagem)', 14.00, 9, NULL, NULL, '2026-03-17'),
(@f_abr, 'Supermercado Antunes', 25.95, 9, NULL, NULL, '2026-03-16'),
(@f_abr, 'Servsol Supermercado', 93.83, 9, NULL, NULL, '2026-03-14'),
(@f_abr, 'Servsol Supermercado', 18.87, 9, NULL, NULL, '2026-03-13'),
(@f_abr, 'Gilce Edlaine (alface/verduras)', 8.00, 9, NULL, NULL, '2026-03-13'),
(@f_abr, 'OnExpress TV Sala - parcela 2/12', 16.44, 10, 2, 12, '2026-03-04'),
(@f_abr, 'MiraWatts - parcela 2/2', 62.50, 14, 2, 2, '2026-02-23'),
(@f_abr, 'MiraWatts - parcela 2/2', 30.60, 14, 2, 2, '2026-02-23');
-- Soma lançamentos: 834,86. Diferença de R$3,00 do total real.
-- Pode haver IOF ou lançamento menor não mencionado.

-- Despesas de abril vinculadas
INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, cartao_id, data_vencimento, parcelada, total_parcelas, parcela_atual, mes_referencia, ano_referencia, status, observacao) VALUES
-- Mercado (tudo que é alimentação/supermercado)
(1, 'Mercado (Servsol, Antunes, Alfredo, feirinha)', 587.39, 'variavel', 9, 'compartilhado', 'credito', 2, '2026-04-20', 0, NULL, NULL, 4, 2026, 'pendente', 'Servsol R$6,49+R$36,84+R$93,83+R$18,87 | Antunes R$8,69+R$25,95 | Alfredo R$339,33 | Gilce R$8 | Pão Açúcar R$4,09 | Feirinha alface R$8 → ajuste: R$4,09+R$8=R$12,09 incluído'),
-- Alimentação viagem SP
(1, 'Alimentação viagem SP (Shopping, Cartago, Pau Seco, Café)', 149.80, 'variavel', 9, 'compartilhado', 'credito', 2, '2026-04-20', 0, NULL, NULL, 4, 2026, 'pendente', 'Shopping R$70,80 + Cartago R$29,50 + Pau Seco R$35,50 + Café R$14'),
-- Empresa
(1, 'Quiro Pro IA (empresa)', 33.43, 'fixa', 15, 'empresa', 'credito', 2, '2026-04-20', 0, NULL, NULL, 4, 2026, 'pendente', 'IA para trabalho LRV Web.'),
-- TV parcela
(1, 'OnExpress TV Sala (parcela 2/12)', 16.44, 'parcelada', 10, 'compartilhado', 'credito', 2, '2026-04-20', 1, 12, 2, 4, 2026, 'pendente', NULL),
-- MiraWatts últimas parcelas
(1, 'MiraWatts (parcela 2/2)', 62.50, 'parcelada', 14, 'compartilhado', 'credito', 2, '2026-04-20', 1, 2, 2, 4, 2026, 'pendente', 'Última parcela. Material elétrico.'),
(1, 'MiraWatts (parcela 2/2)', 30.60, 'parcelada', 14, 'compartilhado', 'credito', 2, '2026-04-20', 1, 2, 2, 4, 2026, 'pendente', 'Última parcela. Material elétrico.');


-- =============================================
-- PARCELAS TV OnExpress - Maio 2026 a Fevereiro 2027
-- Parcelas 3/12 a 12/12, R$ 16,44 cada
-- =============================================

-- Maio (3/12)
INSERT INTO faturas (cartao_id, mes_referencia, ano_referencia, valor_total, data_fechamento, data_vencimento, status)
VALUES (2, 5, 2026, 16.44, '2026-05-14', '2026-05-20', 'aberta');
SET @f5 = LAST_INSERT_ID();
INSERT INTO fatura_lancamentos (fatura_id, descricao, valor, categoria_id, parcela_atual, total_parcelas, data_compra) VALUES
(@f5, 'OnExpress TV Sala - parcela 3/12', 16.44, 10, 3, 12, '2026-03-04');
INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, cartao_id, data_vencimento, parcelada, total_parcelas, parcela_atual, mes_referencia, ano_referencia, status) VALUES
(1, 'OnExpress TV Sala (parcela 3/12)', 16.44, 'parcelada', 10, 'compartilhado', 'credito', 2, '2026-05-20', 1, 12, 3, 5, 2026, 'pendente');

-- Junho (4/12)
INSERT INTO faturas (cartao_id, mes_referencia, ano_referencia, valor_total, data_fechamento, data_vencimento, status)
VALUES (2, 6, 2026, 16.44, '2026-06-14', '2026-06-20', 'aberta');
SET @f6 = LAST_INSERT_ID();
INSERT INTO fatura_lancamentos (fatura_id, descricao, valor, categoria_id, parcela_atual, total_parcelas, data_compra) VALUES
(@f6, 'OnExpress TV Sala - parcela 4/12', 16.44, 10, 4, 12, '2026-03-04');
INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, cartao_id, data_vencimento, parcelada, total_parcelas, parcela_atual, mes_referencia, ano_referencia, status) VALUES
(1, 'OnExpress TV Sala (parcela 4/12)', 16.44, 'parcelada', 10, 'compartilhado', 'credito', 2, '2026-06-20', 1, 12, 4, 6, 2026, 'pendente');

-- Julho (5/12)
INSERT INTO faturas (cartao_id, mes_referencia, ano_referencia, valor_total, data_fechamento, data_vencimento, status)
VALUES (2, 7, 2026, 16.44, '2026-07-14', '2026-07-20', 'aberta');
SET @f7 = LAST_INSERT_ID();
INSERT INTO fatura_lancamentos (fatura_id, descricao, valor, categoria_id, parcela_atual, total_parcelas, data_compra) VALUES
(@f7, 'OnExpress TV Sala - parcela 5/12', 16.44, 10, 5, 12, '2026-03-04');
INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, cartao_id, data_vencimento, parcelada, total_parcelas, parcela_atual, mes_referencia, ano_referencia, status) VALUES
(1, 'OnExpress TV Sala (parcela 5/12)', 16.44, 'parcelada', 10, 'compartilhado', 'credito', 2, '2026-07-20', 1, 12, 5, 7, 2026, 'pendente');

-- Agosto (6/12)
INSERT INTO faturas (cartao_id, mes_referencia, ano_referencia, valor_total, data_fechamento, data_vencimento, status)
VALUES (2, 8, 2026, 16.44, '2026-08-14', '2026-08-20', 'aberta');
SET @f8 = LAST_INSERT_ID();
INSERT INTO fatura_lancamentos (fatura_id, descricao, valor, categoria_id, parcela_atual, total_parcelas, data_compra) VALUES
(@f8, 'OnExpress TV Sala - parcela 6/12', 16.44, 10, 6, 12, '2026-03-04');
INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, cartao_id, data_vencimento, parcelada, total_parcelas, parcela_atual, mes_referencia, ano_referencia, status) VALUES
(1, 'OnExpress TV Sala (parcela 6/12)', 16.44, 'parcelada', 10, 'compartilhado', 'credito', 2, '2026-08-20', 1, 12, 6, 8, 2026, 'pendente');

-- Setembro (7/12)
INSERT INTO faturas (cartao_id, mes_referencia, ano_referencia, valor_total, data_fechamento, data_vencimento, status)
VALUES (2, 9, 2026, 16.44, '2026-09-14', '2026-09-20', 'aberta');
SET @f9 = LAST_INSERT_ID();
INSERT INTO fatura_lancamentos (fatura_id, descricao, valor, categoria_id, parcela_atual, total_parcelas, data_compra) VALUES
(@f9, 'OnExpress TV Sala - parcela 7/12', 16.44, 10, 7, 12, '2026-03-04');
INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, cartao_id, data_vencimento, parcelada, total_parcelas, parcela_atual, mes_referencia, ano_referencia, status) VALUES
(1, 'OnExpress TV Sala (parcela 7/12)', 16.44, 'parcelada', 10, 'compartilhado', 'credito', 2, '2026-09-20', 1, 12, 7, 9, 2026, 'pendente');

-- Outubro (8/12)
INSERT INTO faturas (cartao_id, mes_referencia, ano_referencia, valor_total, data_fechamento, data_vencimento, status)
VALUES (2, 10, 2026, 16.44, '2026-10-14', '2026-10-20', 'aberta');
SET @f10 = LAST_INSERT_ID();
INSERT INTO fatura_lancamentos (fatura_id, descricao, valor, categoria_id, parcela_atual, total_parcelas, data_compra) VALUES
(@f10, 'OnExpress TV Sala - parcela 8/12', 16.44, 10, 8, 12, '2026-03-04');
INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, cartao_id, data_vencimento, parcelada, total_parcelas, parcela_atual, mes_referencia, ano_referencia, status) VALUES
(1, 'OnExpress TV Sala (parcela 8/12)', 16.44, 'parcelada', 10, 'compartilhado', 'credito', 2, '2026-10-20', 1, 12, 8, 10, 2026, 'pendente');

-- Novembro (9/12)
INSERT INTO faturas (cartao_id, mes_referencia, ano_referencia, valor_total, data_fechamento, data_vencimento, status)
VALUES (2, 11, 2026, 16.44, '2026-11-14', '2026-11-20', 'aberta');
SET @f11 = LAST_INSERT_ID();
INSERT INTO fatura_lancamentos (fatura_id, descricao, valor, categoria_id, parcela_atual, total_parcelas, data_compra) VALUES
(@f11, 'OnExpress TV Sala - parcela 9/12', 16.44, 10, 9, 12, '2026-03-04');
INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, cartao_id, data_vencimento, parcelada, total_parcelas, parcela_atual, mes_referencia, ano_referencia, status) VALUES
(1, 'OnExpress TV Sala (parcela 9/12)', 16.44, 'parcelada', 10, 'compartilhado', 'credito', 2, '2026-11-20', 1, 12, 9, 11, 2026, 'pendente');

-- Dezembro (10/12)
INSERT INTO faturas (cartao_id, mes_referencia, ano_referencia, valor_total, data_fechamento, data_vencimento, status)
VALUES (2, 12, 2026, 16.44, '2026-12-14', '2026-12-20', 'aberta');
SET @f12 = LAST_INSERT_ID();
INSERT INTO fatura_lancamentos (fatura_id, descricao, valor, categoria_id, parcela_atual, total_parcelas, data_compra) VALUES
(@f12, 'OnExpress TV Sala - parcela 10/12', 16.44, 10, 10, 12, '2026-03-04');
INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, cartao_id, data_vencimento, parcelada, total_parcelas, parcela_atual, mes_referencia, ano_referencia, status) VALUES
(1, 'OnExpress TV Sala (parcela 10/12)', 16.44, 'parcelada', 10, 'compartilhado', 'credito', 2, '2026-12-20', 1, 12, 10, 12, 2026, 'pendente');

-- Janeiro 2027 (11/12)
INSERT INTO faturas (cartao_id, mes_referencia, ano_referencia, valor_total, data_fechamento, data_vencimento, status)
VALUES (2, 1, 2027, 16.44, '2027-01-14', '2027-01-20', 'aberta');
SET @f13 = LAST_INSERT_ID();
INSERT INTO fatura_lancamentos (fatura_id, descricao, valor, categoria_id, parcela_atual, total_parcelas, data_compra) VALUES
(@f13, 'OnExpress TV Sala - parcela 11/12', 16.44, 10, 11, 12, '2026-03-04');
INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, cartao_id, data_vencimento, parcelada, total_parcelas, parcela_atual, mes_referencia, ano_referencia, status) VALUES
(1, 'OnExpress TV Sala (parcela 11/12)', 16.44, 'parcelada', 10, 'compartilhado', 'credito', 2, '2027-01-20', 1, 12, 11, 1, 2027, 'pendente');

-- Fevereiro 2027 (12/12 - ÚLTIMA)
INSERT INTO faturas (cartao_id, mes_referencia, ano_referencia, valor_total, data_fechamento, data_vencimento, status)
VALUES (2, 2, 2027, 16.44, '2027-02-14', '2027-02-20', 'aberta');
SET @f14 = LAST_INSERT_ID();
INSERT INTO fatura_lancamentos (fatura_id, descricao, valor, categoria_id, parcela_atual, total_parcelas, data_compra) VALUES
(@f14, 'OnExpress TV Sala - parcela 12/12', 16.44, 10, 12, 12, '2026-03-04');
INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, cartao_id, data_vencimento, parcelada, total_parcelas, parcela_atual, mes_referencia, ano_referencia, status) VALUES
(1, 'OnExpress TV Sala (parcela 12/12)', 16.44, 'parcelada', 10, 'compartilhado', 'credito', 2, '2027-02-20', 1, 12, 12, 2, 2027, 'pendente');
