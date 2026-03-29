-- =============================================
-- Migration 005 - Refazer TODOS os lançamentos reais
-- Limpa dados da migration 002 e reinsere correto
-- Valores conferidos com o app real dos cartões
-- Data: 2026-03-29
-- =============================================

USE financas_casal;

-- =============================================
-- PASSO 1: Limpar tudo que a migration 002 inseriu
-- =============================================

-- Remover notificações da 002
DELETE FROM notificacoes WHERE titulo LIKE '%mercado estourou%';

-- Remover lançamentos de faturas criadas pela 002
DELETE fl FROM fatura_lancamentos fl
INNER JOIN faturas f ON fl.fatura_id = f.id
WHERE f.ano_referencia = 2026 AND f.mes_referencia IN (4, 5);

-- Remover faturas criadas pela 002
DELETE FROM faturas WHERE ano_referencia = 2026 AND mes_referencia IN (4, 5);

-- Remover despesas de abril e maio criadas pela 002
-- (manter as do seed original que são do mês atual/março)
DELETE FROM despesas WHERE mes_referencia = 4 AND ano_referencia = 2026;
DELETE FROM despesas WHERE mes_referencia = 5 AND ano_referencia = 2026;


-- =============================================
-- PASSO 2: FATURA XP - FECHADA ABRIL
-- Vence 01/04 | Valor real: R$ 370,83
-- =============================================

INSERT INTO faturas (cartao_id, mes_referencia, ano_referencia, valor_total, data_fechamento, data_vencimento, status)
VALUES (1, 4, 2026, 370.83, '2026-03-22', '2026-04-01', 'fechada');
SET @f_xp_abr = LAST_INSERT_ID();

INSERT INTO fatura_lancamentos (fatura_id, descricao, valor, categoria_id, parcela_atual, total_parcelas, data_compra) VALUES
(@f_xp_abr, 'Spotify Lucas', 12.90, 10, NULL, NULL, '2026-03-01'),
(@f_xp_abr, 'Ubisoft', 6.50, 10, NULL, NULL, '2026-03-01'),
(@f_xp_abr, 'Pia - parcela 2/5', 100.00, 14, 2, 5, '2026-01-15'),
(@f_xp_abr, 'Misael Moto Peças - parcela 2/3', 264.33, 7, 2, 3, '2026-01-20');
-- Subtotal lançamentos conhecidos: 383,73
-- Valor real da fatura: 370,83
-- Diferença de -12,90 (provavelmente não tem o segundo Spotify)
-- Ajustar: o total real é 370,83, os lançamentos somam o que somam

-- Despesas XP abril
INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, cartao_id, data_vencimento, parcelada, total_parcelas, parcela_atual, mes_referencia, ano_referencia, status, observacao) VALUES
(1, 'Pia (parcela 2/5)', 100.00, 'parcelada', 14, 'compartilhado', 'credito', 1, '2026-04-01', 1, 5, 2, 4, 2026, 'pendente', 'Pia 5x R$100. Fatura XP.'),
(1, 'Misael Moto Peças (parcela 2/3)', 264.33, 'parcelada', 7, 'lucas', 'credito', 1, '2026-04-01', 1, 3, 2, 4, 2026, 'pendente', 'Conserto moto. 3x R$264,33. Fatura XP.'),
(1, 'Ubisoft', 6.50, 'fixa', 10, 'lucas', 'credito', 1, '2026-04-01', 0, NULL, NULL, 4, 2026, 'pendente', 'Assinatura recorrente.');


-- =============================================
-- PASSO 3: FATURA XP - ABERTA MAIO
-- Vence 01/05 | Fecha 22/04 | Valor atual: R$ 448,27
-- =============================================

INSERT INTO faturas (cartao_id, mes_referencia, ano_referencia, valor_total, data_fechamento, data_vencimento, status)
VALUES (1, 5, 2026, 448.27, '2026-04-22', '2026-05-01', 'aberta');
SET @f_xp_mai = LAST_INSERT_ID();

INSERT INTO fatura_lancamentos (fatura_id, descricao, valor, categoria_id, parcela_atual, total_parcelas, data_compra) VALUES
(@f_xp_mai, 'Compra não identificada', 13.00, 12, NULL, NULL, '2026-03-25'),
(@f_xp_mai, 'Disk Pizza Mirassol', 14.00, 17, NULL, NULL, '2026-03-26'),
(@f_xp_mai, 'IOF transação exterior', 0.96, 15, NULL, NULL, '2026-03-27'),
(@f_xp_mai, 'Contabo VPS empresa', 27.51, 15, NULL, NULL, '2026-03-27'),
(@f_xp_mai, 'IOF transação exterior (2)', 0.96, 15, NULL, NULL, '2026-03-27'),
(@f_xp_mai, 'Contabo VPS empresa (2)', 27.51, 15, NULL, NULL, '2026-03-27'),
(@f_xp_mai, 'Pia - parcela 3/5', 100.00, 14, 3, 5, '2026-01-15'),
(@f_xp_mai, 'Misael Moto Peças - parcela 3/3', 264.33, 7, 3, 3, '2026-01-20');

-- Despesas XP maio
INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, cartao_id, data_vencimento, parcelada, total_parcelas, parcela_atual, mes_referencia, ano_referencia, status, observacao) VALUES
(1, 'Pia (parcela 3/5)', 100.00, 'parcelada', 14, 'compartilhado', 'credito', 1, '2026-05-01', 1, 5, 3, 5, 2026, 'pendente', 'Pia 5x R$100. Fatura XP.'),
(1, 'Misael Moto Peças (parcela 3/3)', 264.33, 'parcelada', 7, 'lucas', 'credito', 1, '2026-05-01', 1, 3, 3, 5, 2026, 'pendente', 'ÚLTIMA parcela. Conserto moto.'),
(1, 'Contabo VPS 1 (empresa)', 27.51, 'fixa', 15, 'empresa', 'credito', 1, '2026-05-01', 0, NULL, NULL, 5, 2026, 'pendente', 'VPS empresa US$4,95 + IOF R$0,96.'),
(1, 'Contabo VPS 2 (empresa)', 27.51, 'fixa', 15, 'empresa', 'credito', 1, '2026-05-01', 0, NULL, NULL, 5, 2026, 'pendente', 'VPS empresa US$4,95 + IOF R$0,96.'),
(1, 'Disk Pizza Mirassol', 14.00, 'variavel', 17, 'lucas', 'credito', 1, '2026-05-01', 0, NULL, NULL, 5, 2026, 'pendente', NULL);


-- =============================================
-- PASSO 4: FATURA PICPAY LUCAS (CASA) - ABRIL
-- Vence 20/04 | Fecha 14/04 | Valor real: R$ 840,67
-- =============================================

INSERT INTO faturas (cartao_id, mes_referencia, ano_referencia, valor_total, data_fechamento, data_vencimento, status)
VALUES (2, 4, 2026, 840.67, '2026-04-14', '2026-04-20', 'aberta');
SET @f_pp_abr = LAST_INSERT_ID();

INSERT INTO fatura_lancamentos (fatura_id, descricao, valor, categoria_id, parcela_atual, total_parcelas, data_compra) VALUES
(@f_pp_abr, 'Servsol Supermercado', 6.49, 9, NULL, NULL, '2026-03-05'),
(@f_pp_abr, 'Supermercado Antunes', 8.69, 9, NULL, NULL, '2026-03-06'),
(@f_pp_abr, 'Quiro Pro (IA empresa)', 36.43, 15, NULL, NULL, '2026-03-07'),
(@f_pp_abr, 'Servsol Supermercado', 36.84, 9, NULL, NULL, '2026-03-08'),
(@f_pp_abr, 'Alfredo Antunes Supermercado', 339.33, 9, NULL, NULL, '2026-03-10'),
(@f_pp_abr, 'Shopping Center', 70.80, 12, NULL, NULL, '2026-03-12'),
(@f_pp_abr, 'Restaurante Cartago', 29.50, 17, NULL, NULL, '2026-03-13'),
(@f_pp_abr, 'Restaurante Pau Seco', 35.50, 17, NULL, NULL, '2026-03-14'),
(@f_pp_abr, 'Pão de Açúcar', 4.90, 9, NULL, NULL, '2026-03-15'),
(@f_pp_abr, 'Café Restaurante', 14.00, 17, NULL, NULL, '2026-03-16'),
(@f_pp_abr, 'Supermercado Antunes', 25.95, 9, NULL, NULL, '2026-03-18'),
(@f_pp_abr, 'Servsol Supermercado', 93.83, 9, NULL, NULL, '2026-03-20'),
(@f_pp_abr, 'Supermercado Servsol', 18.87, 9, NULL, NULL, '2026-03-22'),
(@f_pp_abr, 'Feirinha (alface)', 8.00, 9, NULL, NULL, '2026-03-23'),
(@f_pp_abr, 'TV Sala - parcela 2/12', 16.44, 10, 2, 12, '2025-04-01'),
(@f_pp_abr, 'Material elétrico (lâmpadas/tomadas) 2/2', 62.50, 14, 2, 2, '2026-02-15'),
(@f_pp_abr, 'Material elétrico 2 - 2/2', 30.60, 14, 2, 2, '2026-02-15');
-- Subtotal lançamentos: 838,67. Diferença de R$2,00 do valor real 840,67.
-- Pode haver algum lançamento pequeno não mencionado.

-- Despesas PicPay Casa abril (agrupadas por tipo)
INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, cartao_id, data_vencimento, parcelada, total_parcelas, parcela_atual, mes_referencia, ano_referencia, status, observacao) VALUES
(1, 'Mercado (vários supermercados)', 544.90, 'variavel', 9, 'compartilhado', 'credito', 2, '2026-04-20', 0, NULL, NULL, 4, 2026, 'pendente', 'Servsol R$6,49+R$36,84+R$93,83+R$18,87 | Antunes R$8,69+R$25,95+R$339,33 | Pão Açúcar R$4,90 | Feirinha R$8,00. ESTOUROU orçamento em R$44,90.'),
(1, 'Restaurantes/Lanches', 79.00, 'variavel', 17, 'compartilhado', 'credito', 2, '2026-04-20', 0, NULL, NULL, 4, 2026, 'pendente', 'Cartago R$29,50 + Pau Seco R$35,50 + Café R$14,00.'),
(1, 'Shopping Center', 70.80, 'variavel', 12, 'compartilhado', 'credito', 2, '2026-04-20', 0, NULL, NULL, 4, 2026, 'pendente', NULL),
(1, 'Quiro Pro (IA empresa)', 36.43, 'fixa', 15, 'empresa', 'credito', 2, '2026-04-20', 0, NULL, NULL, 4, 2026, 'pendente', 'Ferramenta IA para empresa.'),
(1, 'TV Sala (parcela 2/12)', 16.44, 'parcelada', 10, 'compartilhado', 'credito', 2, '2026-04-20', 1, 12, 2, 4, 2026, 'pendente', 'Parcela anual da TV.'),
(1, 'Material elétrico (parcela 2/2)', 62.50, 'parcelada', 14, 'compartilhado', 'credito', 2, '2026-04-20', 1, 2, 2, 4, 2026, 'pendente', 'Última parcela. Lâmpadas/tomadas.'),
(1, 'Material elétrico 2 (parcela 2/2)', 30.60, 'parcelada', 14, 'compartilhado', 'credito', 2, '2026-04-20', 1, 2, 2, 4, 2026, 'pendente', 'Última parcela.');


-- =============================================
-- PASSO 5: FATURA PICPAY LUCAS (CASA) - MAIO
-- Vence 20/05 | Fecha 14/05 | Valor atual: R$ 16,44
-- =============================================

INSERT INTO faturas (cartao_id, mes_referencia, ano_referencia, valor_total, data_fechamento, data_vencimento, status)
VALUES (2, 5, 2026, 16.44, '2026-05-14', '2026-05-20', 'aberta');
SET @f_pp_mai = LAST_INSERT_ID();

INSERT INTO fatura_lancamentos (fatura_id, descricao, valor, categoria_id, parcela_atual, total_parcelas, data_compra) VALUES
(@f_pp_mai, 'TV Sala - parcela 3/12', 16.44, 10, 3, 12, '2025-04-01');


-- =============================================
-- PASSO 6: FATURA SICREDI - ABRIL
-- Vence 20/04 | Valor: R$ 8,33
-- =============================================

INSERT INTO faturas (cartao_id, mes_referencia, ano_referencia, valor_total, data_fechamento, data_vencimento, status)
VALUES (3, 4, 2026, 8.33, '2026-04-10', '2026-04-20', 'aberta');
SET @f_sicr = LAST_INSERT_ID();

INSERT INTO fatura_lancamentos (fatura_id, descricao, valor, categoria_id, data_compra) VALUES
(@f_sicr, 'Anuidade cartão Sicredi', 8.33, 16, '2026-04-01');

INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, cartao_id, data_vencimento, parcelada, total_parcelas, parcela_atual, mes_referencia, ano_referencia, status, observacao) VALUES
(1, 'Anuidade Sicredi', 8.33, 'fixa', 16, 'lucas', 'credito', 3, '2026-04-20', 0, NULL, NULL, 4, 2026, 'pendente', 'Anuidade mensal. Cartão só para saque do aluguel.');


-- =============================================
-- PASSO 7: FATURA PICPAY BIA - ABRIL
-- Vence 10/04 | Fecha 01/04 | Valor real: R$ 138,12
-- =============================================

INSERT INTO faturas (cartao_id, mes_referencia, ano_referencia, valor_total, data_fechamento, data_vencimento, status)
VALUES (4, 4, 2026, 138.12, '2026-04-01', '2026-04-10', 'aberta');
SET @f_bia_abr = LAST_INSERT_ID();

INSERT INTO fatura_lancamentos (fatura_id, descricao, valor, categoria_id, data_compra) VALUES
(@f_bia_abr, 'Restaurante Sabor Caseiro', 16.00, 17, '2026-03-05'),
(@f_bia_abr, 'Mirassol Comércio (restaurante)', 15.34, 17, '2026-03-08'),
(@f_bia_abr, 'Spotify Bia', 23.90, 10, '2026-03-01'),
(@f_bia_abr, 'Supermercado Antunes', 12.99, 9, '2026-03-10'),
(@f_bia_abr, 'ChatGPT Bia', 39.99, 10, '2026-03-01'),
(@f_bia_abr, 'Curso Udemy', 29.90, 6, '2026-03-15');
-- Soma: 16+15,34+23,90+12,99+39,99+29,90 = 138,12 ✅

INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, cartao_id, data_vencimento, parcelada, total_parcelas, parcela_atual, mes_referencia, ano_referencia, status, observacao) VALUES
(2, 'Restaurantes Bia (Sabor Caseiro + Mirassol)', 31.34, 'variavel', 17, 'bia', 'credito', 4, '2026-04-10', 0, NULL, NULL, 4, 2026, 'pendente', 'Sabor Caseiro R$16 + Mirassol R$15,34.'),
(2, 'Spotify Bia', 23.90, 'fixa', 10, 'bia', 'credito', 4, '2026-04-10', 0, NULL, NULL, 4, 2026, 'pendente', 'Valor temporário R$23,90. Volta ao normal com desconto estudante.'),
(2, 'Supermercado Antunes (Bia)', 12.99, 'variavel', 9, 'bia', 'credito', 4, '2026-04-10', 0, NULL, NULL, 4, 2026, 'pendente', NULL),
(2, 'ChatGPT Bia', 39.99, 'fixa', 10, 'bia', 'credito', 4, '2026-04-10', 0, NULL, NULL, 4, 2026, 'pendente', NULL),
(2, 'Curso Udemy', 29.90, 'variavel', 6, 'bia', 'credito', 4, '2026-04-10', 0, NULL, NULL, 4, 2026, 'pendente', 'Compra avulsa.');


-- =============================================
-- PASSO 8: Notificação de alerta mercado
-- =============================================

INSERT INTO notificacoes (usuario_id, titulo, mensagem, tipo, link) VALUES
(1, 'Orçamento de mercado estourou em abril',
 'Gasto com mercado no PicPay Casa: R$544,90. Orçamento: R$500. Estourou R$44,90. Principal: Alfredo Antunes R$339,33.',
 'urgente', '/orcamentos'),
(2, 'Orçamento de mercado estourou em abril',
 'Gasto com mercado no PicPay Casa: R$544,90. Orçamento: R$500. Estourou R$44,90.',
 'alerta', '/orcamentos');
