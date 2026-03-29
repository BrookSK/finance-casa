-- =============================================
-- Migration 002 - Lançamentos reais das faturas
-- Dados reais de março/abril/maio 2026
-- Data: 2026-03-29
-- =============================================

USE financas_casal;

-- =============================================
-- CARTÃO XP (id=1) - Lucas
-- Fecha ~dia 22, Vence dia 1
-- =============================================

-- --- FATURA FECHADA ABRIL (fecha ~22/03, vence 01/04) = R$ 396,63 ---

INSERT INTO faturas (cartao_id, mes_referencia, ano_referencia, valor_total, data_fechamento, data_vencimento, status)
VALUES (1, 4, 2026, 396.63, '2026-03-22', '2026-04-01', 'fechada');

SET @fatura_xp_abr = LAST_INSERT_ID();

INSERT INTO fatura_lancamentos (fatura_id, descricao, valor, categoria_id, parcela_atual, total_parcelas, data_compra) VALUES
(@fatura_xp_abr, 'Spotify Lucas', 12.90, 10, NULL, NULL, '2026-03-01'),
(@fatura_xp_abr, 'Ubisoft', 6.50, 10, NULL, NULL, '2026-03-01'),
(@fatura_xp_abr, 'Spotify (duplicado/família)', 12.90, 10, NULL, NULL, '2026-03-01'),
(@fatura_xp_abr, 'Pia - parcela', 100.00, 14, 2, 5, '2026-01-15'),
(@fatura_xp_abr, 'Misael Moto Peças - conserto moto', 264.33, 7, 2, 3, '2026-01-20');

-- Registrar as parcelas como despesas do mês de abril
INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, cartao_id, data_vencimento, parcelada, total_parcelas, parcela_atual, mes_referencia, ano_referencia, status, observacao) VALUES
(1, 'Pia (parcela 2/5)', 100.00, 'parcelada', 14, 'compartilhado', 'credito', 1, '2026-04-01', 1, 5, 2, 4, 2026, 'pendente', 'Pia dividida em 5x de R$100. Fatura XP.'),
(1, 'Misael Moto Peças (parcela 2/3)', 264.33, 'parcelada', 7, 'lucas', 'credito', 1, '2026-04-01', 1, 3, 2, 4, 2026, 'pendente', 'Conserto da moto. 3x R$264,33. Fatura XP.'),
(1, 'Ubisoft', 6.50, 'fixa', 10, 'lucas', 'credito', 1, '2026-04-01', 0, NULL, NULL, 4, 2026, 'pendente', 'Assinatura recorrente.');


-- --- FATURA ABERTA MAIO (fecha 22/04, vence 01/05) = R$ 448,27 ---

INSERT INTO faturas (cartao_id, mes_referencia, ano_referencia, valor_total, data_fechamento, data_vencimento, status)
VALUES (1, 5, 2026, 448.27, '2026-04-22', '2026-05-01', 'aberta');

SET @fatura_xp_mai = LAST_INSERT_ID();

INSERT INTO fatura_lancamentos (fatura_id, descricao, valor, categoria_id, parcela_atual, total_parcelas, data_compra) VALUES
(@fatura_xp_mai, 'Compra não identificada', 13.00, 12, NULL, NULL, '2026-03-25'),
(@fatura_xp_mai, 'Disk Pizza Mirassol', 14.00, 17, NULL, NULL, '2026-03-26'),
(@fatura_xp_mai, 'IOF transação exterior', 0.96, 15, NULL, NULL, '2026-03-27'),
(@fatura_xp_mai, 'Contabo VPS empresa', 27.51, 15, NULL, NULL, '2026-03-27'),
(@fatura_xp_mai, 'IOF transação exterior', 0.96, 15, NULL, NULL, '2026-03-27'),
(@fatura_xp_mai, 'Contabo VPS empresa (2)', 27.51, 15, NULL, NULL, '2026-03-27'),
(@fatura_xp_mai, 'Pia - parcela', 100.00, 14, 3, 5, '2026-01-15'),
(@fatura_xp_mai, 'Misael Moto Peças - conserto moto', 264.33, 7, 3, 3, '2026-01-20');

-- Despesas de maio referentes a essa fatura
INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, cartao_id, data_vencimento, parcelada, total_parcelas, parcela_atual, mes_referencia, ano_referencia, status, observacao) VALUES
(1, 'Pia (parcela 3/5)', 100.00, 'parcelada', 14, 'compartilhado', 'credito', 1, '2026-05-01', 1, 5, 3, 5, 2026, 'pendente', 'Pia dividida em 5x de R$100. Fatura XP.'),
(1, 'Misael Moto Peças (parcela 3/3)', 264.33, 'parcelada', 7, 'lucas', 'credito', 1, '2026-05-01', 1, 3, 3, 5, 2026, 'pendente', 'Última parcela. Conserto da moto.'),
(1, 'Contabo VPS 1', 27.51, 'fixa', 15, 'empresa', 'credito', 1, '2026-05-01', 0, NULL, NULL, 5, 2026, 'pendente', 'VPS empresa. US$4,95 + IOF.'),
(1, 'Contabo VPS 2', 27.51, 'fixa', 15, 'empresa', 'credito', 1, '2026-05-01', 0, NULL, NULL, 5, 2026, 'pendente', 'VPS empresa. US$4,95 + IOF.'),
(1, 'Disk Pizza Mirassol', 14.00, 'variavel', 17, 'lucas', 'credito', 1, '2026-05-01', 0, NULL, NULL, 5, 2026, 'pendente', NULL);


-- =============================================
-- CARTÃO PICPAY LUCAS (id=2) - Casa
-- Fecha dia 14, Vence dia 20
-- =============================================

-- --- FATURA ATUAL ABRIL (fecha 14/04, vence 20/04) = R$ 837,86 ---

INSERT INTO faturas (cartao_id, mes_referencia, ano_referencia, valor_total, data_fechamento, data_vencimento, status)
VALUES (2, 4, 2026, 837.86, '2026-04-14', '2026-04-20', 'aberta');

SET @fatura_pp_lucas_abr = LAST_INSERT_ID();

INSERT INTO fatura_lancamentos (fatura_id, descricao, valor, categoria_id, parcela_atual, total_parcelas, data_compra) VALUES
(@fatura_pp_lucas_abr, 'Servsol Supermercado', 6.49, 9, NULL, NULL, '2026-03-05'),
(@fatura_pp_lucas_abr, 'Supermercado Antunes', 8.69, 9, NULL, NULL, '2026-03-06'),
(@fatura_pp_lucas_abr, 'Quiro Pro (IA empresa)', 36.43, 15, NULL, NULL, '2026-03-07'),
(@fatura_pp_lucas_abr, 'Servsol Supermercado', 36.84, 9, NULL, NULL, '2026-03-08'),
(@fatura_pp_lucas_abr, 'Alfredo Antunes Supermercado', 339.33, 9, NULL, NULL, '2026-03-10'),
(@fatura_pp_lucas_abr, 'Shopping Center', 70.80, 12, NULL, NULL, '2026-03-12'),
(@fatura_pp_lucas_abr, 'Restaurante Cartago', 29.50, 17, NULL, NULL, '2026-03-13'),
(@fatura_pp_lucas_abr, 'Restaurante Pau Seco', 35.50, 17, NULL, NULL, '2026-03-14'),
(@fatura_pp_lucas_abr, 'Pão de Açúcar', 4.90, 9, NULL, NULL, '2026-03-15'),
(@fatura_pp_lucas_abr, 'Café Restaurante', 14.00, 17, NULL, NULL, '2026-03-16'),
(@fatura_pp_lucas_abr, 'Supermercado Antunes', 25.95, 9, NULL, NULL, '2026-03-18'),
(@fatura_pp_lucas_abr, 'Servsol Supermercado', 93.83, 9, NULL, NULL, '2026-03-20'),
(@fatura_pp_lucas_abr, 'Supermercado Servsol', 18.87, 9, NULL, NULL, '2026-03-22'),
(@fatura_pp_lucas_abr, 'Feirinha (alface)', 8.00, 9, NULL, NULL, '2026-03-23'),
(@fatura_pp_lucas_abr, 'TV Sala - parcela anual', 16.44, 10, 2, 12, '2025-04-01'),
(@fatura_pp_lucas_abr, 'Material elétrico (lâmpadas/tomadas)', 62.50, 14, 2, 2, '2026-02-15'),
(@fatura_pp_lucas_abr, 'Material elétrico (lâmpadas/tomadas 2)', 30.60, 14, 2, 2, '2026-02-15');

-- Despesas correspondentes no mês de abril
INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, cartao_id, data_vencimento, parcelada, total_parcelas, parcela_atual, mes_referencia, ano_referencia, status, observacao) VALUES
-- Mercado (total: R$544,90 — acima do orçamento de R$500!)
(1, 'Mercado (Servsol, Antunes, Feirinha)', 544.90, 'variavel', 9, 'compartilhado', 'credito', 2, '2026-04-20', 0, NULL, NULL, 4, 2026, 'pendente', 'Servsol R$6,49+R$36,84+R$93,83+R$18,87 / Antunes R$8,69+R$25,95+R$339,33 / Pão Açúcar R$4,90 / Feirinha R$8,00. ESTOUROU orçamento mercado em R$44,90.'),
-- Restaurantes (total: R$79,00)
(1, 'Restaurantes/Lanches (Cartago, Pau Seco, Café)', 79.00, 'variavel', 17, 'compartilhado', 'credito', 2, '2026-04-20', 0, NULL, NULL, 4, 2026, 'pendente', 'Cartago R$29,50 + Pau Seco R$35,50 + Café R$14,00'),
-- Shopping
(1, 'Shopping Center', 70.80, 'variavel', 12, 'compartilhado', 'credito', 2, '2026-04-20', 0, NULL, NULL, 4, 2026, 'pendente', NULL),
-- Quiro Pro (empresa)
(1, 'Quiro Pro (IA empresa)', 36.43, 'fixa', 15, 'empresa', 'credito', 2, '2026-04-20', 0, NULL, NULL, 4, 2026, 'pendente', 'Ferramenta IA para empresa. Recorrente.'),
-- TV parcela
(1, 'TV Sala (parcela 2/12)', 16.44, 'parcelada', 10, 'compartilhado', 'credito', 2, '2026-04-20', 1, 12, 2, 4, 2026, 'pendente', 'Parcela anual da TV.'),
-- Material elétrico (últimas parcelas)
(1, 'Material elétrico - lâmpadas/tomadas (parcela 2/2)', 62.50, 'parcelada', 14, 'compartilhado', 'credito', 2, '2026-04-20', 1, 2, 2, 4, 2026, 'pendente', 'Última parcela.'),
(1, 'Material elétrico 2 (parcela 2/2)', 30.60, 'parcelada', 14, 'compartilhado', 'credito', 2, '2026-04-20', 1, 2, 2, 4, 2026, 'pendente', 'Última parcela.');


-- --- FATURA MAIO PICPAY LUCAS (fecha 14/05, vence 20/05) = R$ 16,44 por enquanto ---

INSERT INTO faturas (cartao_id, mes_referencia, ano_referencia, valor_total, data_fechamento, data_vencimento, status)
VALUES (2, 5, 2026, 16.44, '2026-05-14', '2026-05-20', 'aberta');

SET @fatura_pp_lucas_mai = LAST_INSERT_ID();

INSERT INTO fatura_lancamentos (fatura_id, descricao, valor, categoria_id, parcela_atual, total_parcelas, data_compra) VALUES
(@fatura_pp_lucas_mai, 'TV Sala - parcela anual', 16.44, 10, 3, 12, '2025-04-01');


-- =============================================
-- CARTÃO SICREDI (id=3) - Lucas
-- Fecha ~dia 10, Vence dia 20
-- Não usar. Só anuidade.
-- =============================================

INSERT INTO faturas (cartao_id, mes_referencia, ano_referencia, valor_total, data_fechamento, data_vencimento, status)
VALUES (3, 4, 2026, 8.33, '2026-04-10', '2026-04-20', 'aberta');

SET @fatura_sicredi = LAST_INSERT_ID();

INSERT INTO fatura_lancamentos (fatura_id, descricao, valor, categoria_id, data_compra) VALUES
(@fatura_sicredi, 'Anuidade cartão Sicredi', 8.33, 16, '2026-04-01');

INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, cartao_id, data_vencimento, parcelada, total_parcelas, parcela_atual, mes_referencia, ano_referencia, status, observacao) VALUES
(1, 'Anuidade Sicredi', 8.33, 'fixa', 16, 'lucas', 'credito', 3, '2026-04-20', 0, NULL, NULL, 4, 2026, 'pendente', 'Anuidade mensal. Cartão mantido apenas para saque do aluguel.');


-- =============================================
-- CARTÃO PICPAY BIA (id=4)
-- Fecha dia 1, Vence dia 10
-- =============================================

-- --- FATURA ATUAL ABRIL (fecha 01/04, vence 10/04) = R$ 138,12 ---

INSERT INTO faturas (cartao_id, mes_referencia, ano_referencia, valor_total, data_fechamento, data_vencimento, status)
VALUES (4, 4, 2026, 138.12, '2026-04-01', '2026-04-10', 'aberta');

SET @fatura_pp_bia_abr = LAST_INSERT_ID();

INSERT INTO fatura_lancamentos (fatura_id, descricao, valor, categoria_id, data_compra) VALUES
(@fatura_pp_bia_abr, 'Restaurante Sabor Caseiro', 16.00, 17, '2026-03-05'),
(@fatura_pp_bia_abr, 'Mirassol Comércio (restaurante)', 15.34, 17, '2026-03-08'),
(@fatura_pp_bia_abr, 'Spotify Bia', 23.90, 10, '2026-03-01'),
(@fatura_pp_bia_abr, 'Supermercado Antunes', 12.99, 9, '2026-03-10'),
(@fatura_pp_bia_abr, 'ChatGPT Bia', 39.99, 10, '2026-03-01'),
(@fatura_pp_bia_abr, 'Curso Udemy', 29.90, 6, '2026-03-15');

-- Despesas da Bia em abril
INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, cartao_id, data_vencimento, parcelada, total_parcelas, parcela_atual, mes_referencia, ano_referencia, status, observacao) VALUES
(2, 'Restaurantes Bia (Sabor Caseiro + Mirassol)', 31.34, 'variavel', 17, 'bia', 'credito', 4, '2026-04-10', 0, NULL, NULL, 4, 2026, 'pendente', 'Sabor Caseiro R$16 + Mirassol R$15,34'),
(2, 'Spotify Bia', 23.90, 'fixa', 10, 'bia', 'credito', 4, '2026-04-10', 0, NULL, NULL, 4, 2026, 'pendente', 'Valor temporário. Volta ao normal quando renovar desconto estudante.'),
(2, 'Supermercado Antunes (Bia)', 12.99, 'variavel', 9, 'bia', 'credito', 4, '2026-04-10', 0, NULL, NULL, 4, 2026, 'pendente', NULL),
(2, 'ChatGPT Bia', 39.99, 'fixa', 10, 'bia', 'credito', 4, '2026-04-10', 0, NULL, NULL, 4, 2026, 'pendente', NULL),
(2, 'Curso Udemy', 29.90, 'variavel', 6, 'bia', 'credito', 4, '2026-04-10', 0, NULL, NULL, 4, 2026, 'pendente', 'Compra avulsa.');


-- =============================================
-- ATUALIZAR METAS DOS COFRINHOS DE FATURA
-- com base nos valores reais
-- =============================================

-- Fatura XP: meta era R$450, fatura real abril = R$396,63, maio = R$448,27
-- Manter R$450 como meta (cobre bem)

-- Fatura PicPay Casa: meta era R$850, fatura real abril = R$837,86
-- Manter R$850 como meta (quase exato)

-- Fatura PicPay Bia: meta era R$150, fatura real abril = R$138,12
-- Manter R$150 como meta (cobre com folga)

-- Anuidade Sicredi: R$8,33 — não precisa de cofrinho, valor irrelevante


-- =============================================
-- ATUALIZAR OBSERVAÇÃO DO COFRINHO FATURA XP
-- com detalhes das parcelas restantes
-- =============================================

SET @mes = MONTH(CURDATE());
SET @ano = YEAR(CURDATE());

UPDATE cofrinhos
SET observacao = 'Vence dia 1. Fatura abr: R$396,63 (Spotify, Ubisoft, Pia 2/5, Moto 2/3). Fatura mai: R$448,27 (Contabo x2, Pia 3/5, Moto 3/3 ÚLTIMA). Parcelas restantes Pia: 3 de 5.'
WHERE usuario_id = 1 AND nome = 'Fatura XP'
  AND mes_referencia = @mes AND ano_referencia = @ano;

UPDATE cofrinhos
SET observacao = 'Vence dia 20. Fatura abr: R$837,86. Mercado R$544,90 (ESTOUROU R$44,90!), Restaurantes R$79, Shopping R$70,80, TV 2/12, Material elétrico últimas parcelas. Mai: só TV R$16,44 por enquanto.'
WHERE usuario_id = 1 AND nome LIKE 'Fatura PicPay%'
  AND usuario_id = 1
  AND mes_referencia = @mes AND ano_referencia = @ano;

UPDATE cofrinhos
SET observacao = 'Vence dia 10. Fatura abr: R$138,12. Restaurantes R$31,34, Spotify R$23,90 (volta ao normal mês que vem), Mercado R$12,99, ChatGPT R$39,99, Udemy R$29,90.'
WHERE usuario_id = 2 AND nome = 'Fatura PicPay Bia'
  AND mes_referencia = @mes AND ano_referencia = @ano;


-- =============================================
-- ALERTA: Mercado estourou em abril!
-- =============================================

INSERT INTO notificacoes (usuario_id, titulo, mensagem, tipo, link) VALUES
(1, 'Orçamento de mercado estourou em abril',
 'O gasto com mercado no cartão PicPay Casa ficou em R$544,90, ultrapassando o orçamento de R$500 em R$44,90. Principais gastos: Alfredo Antunes R$339,33, Servsol R$93,83.',
 'urgente', '/orcamentos'),
(2, 'Orçamento de mercado estourou em abril',
 'O gasto com mercado no cartão PicPay Casa ficou em R$544,90, ultrapassando o orçamento de R$500 em R$44,90.',
 'alerta', '/orcamentos');
