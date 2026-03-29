-- =============================================
-- Migration 011 - Registrar gastos de março nos orçamentos
-- Os gastos de mercado/restaurante/livres já aconteceram
-- dentro dos cartões, mas não estavam como despesas pagas
-- de março. Isso faz o dashboard mostrar corretamente.
--
-- PicPay Casa (fatura abril = gastos de março):
--   Mercado: R$544,90
--   Restaurantes: R$79,00
--   Shopping/Livres: R$70,80
--   Empresa (Quiro Pro): R$36,43
--
-- PicPay Bia (fatura abril = gastos de março):
--   Restaurantes Bia: R$31,34
--   Mercado Bia: R$12,99
--
-- Data: 2026-03-29
-- =============================================

USE financas_casal;

-- =============================================
-- Gastos de MARÇO que já estão nos cartões
-- (entram como despesas pagas de março para os orçamentos)
-- =============================================

-- Mercado - PicPay Casa (R$544,90)
INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, cartao_id, data_pagamento, parcelada, total_parcelas, parcela_atual, mes_referencia, ano_referencia, status, observacao) VALUES
(1, 'Mercado março (PicPay Casa)', 544.90, 'variavel', 9, 'compartilhado', 'credito', 2, '2026-03-23', 0, NULL, NULL, 3, 2026, 'paga',
 'Servsol R$6,49+R$36,84+R$93,83+R$18,87 | Antunes R$8,69+R$25,95+R$339,33 | Pão Açúcar R$4,90 | Feirinha R$8. ESTOUROU orçamento em R$44,90.');

-- Restaurantes/Lanches - PicPay Casa (R$79,00)
INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, cartao_id, data_pagamento, parcelada, total_parcelas, parcela_atual, mes_referencia, ano_referencia, status, observacao) VALUES
(1, 'Restaurantes março (PicPay Casa)', 79.00, 'variavel', 17, 'compartilhado', 'credito', 2, '2026-03-16', 0, NULL, NULL, 3, 2026, 'paga',
 'Cartago R$29,50 + Pau Seco R$35,50 + Café R$14.');

-- Shopping/Gastos Livres - PicPay Casa (R$70,80)
INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, cartao_id, data_pagamento, parcelada, total_parcelas, parcela_atual, mes_referencia, ano_referencia, status, observacao) VALUES
(1, 'Shopping Center março', 70.80, 'variavel', 12, 'compartilhado', 'credito', 2, '2026-03-12', 0, NULL, NULL, 3, 2026, 'paga', NULL);

-- Quiro Pro empresa - PicPay Casa (R$36,43)
INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, cartao_id, data_pagamento, parcelada, total_parcelas, parcela_atual, mes_referencia, ano_referencia, status, observacao) VALUES
(1, 'Quiro Pro IA (empresa)', 36.43, 'variavel', 15, 'empresa', 'credito', 2, '2026-03-07', 0, NULL, NULL, 3, 2026, 'paga', 'Ferramenta IA empresa. Não entra no orçamento pessoal.');

-- Material elétrico últimas parcelas - PicPay Casa
INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, cartao_id, data_pagamento, parcelada, total_parcelas, parcela_atual, mes_referencia, ano_referencia, status, observacao) VALUES
(1, 'Material elétrico (parcela 2/2)', 62.50, 'parcelada', 14, 'compartilhado', 'credito', 2, '2026-03-15', 1, 2, 2, 3, 2026, 'paga', 'Última parcela. Lâmpadas/tomadas.'),
(1, 'Material elétrico 2 (parcela 2/2)', 30.60, 'parcelada', 14, 'compartilhado', 'credito', 2, '2026-03-15', 1, 2, 2, 3, 2026, 'paga', 'Última parcela.');

-- Restaurantes Bia - PicPay Bia (R$31,34)
INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, cartao_id, data_pagamento, parcelada, total_parcelas, parcela_atual, mes_referencia, ano_referencia, status, observacao) VALUES
(2, 'Restaurantes março (Bia)', 31.34, 'variavel', 17, 'bia', 'credito', 4, '2026-03-08', 0, NULL, NULL, 3, 2026, 'paga', 'Sabor Caseiro R$16 + Mirassol R$15,34.');

-- Mercado Bia - PicPay Bia (R$12,99)
INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, cartao_id, data_pagamento, parcelada, total_parcelas, parcela_atual, mes_referencia, ano_referencia, status, observacao) VALUES
(2, 'Mercado março (Bia)', 12.99, 'variavel', 9, 'bia', 'credito', 4, '2026-03-10', 0, NULL, NULL, 3, 2026, 'paga', NULL);

-- Curso Udemy Bia (R$29,90)
INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, cartao_id, data_pagamento, parcelada, total_parcelas, parcela_atual, mes_referencia, ano_referencia, status, observacao) VALUES
(2, 'Curso Udemy (Bia)', 29.90, 'variavel', 6, 'bia', 'credito', 4, '2026-03-15', 0, NULL, NULL, 3, 2026, 'paga', 'Compra avulsa.');

-- Disk Pizza XP (R$14,00) - gasto de março que cai na fatura de maio
INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, cartao_id, data_pagamento, parcelada, total_parcelas, parcela_atual, mes_referencia, ano_referencia, status, observacao) VALUES
(1, 'Disk Pizza Mirassol', 14.00, 'variavel', 17, 'lucas', 'credito', 1, '2026-03-26', 0, NULL, NULL, 3, 2026, 'paga', NULL);


-- =============================================
-- Atualizar orçamentos de março para refletir realidade
-- =============================================

-- Mercado março: já existia com R$500, manter
-- Gastos Livres março: já existia com R$250, manter
-- Restaurante março: já existia com R$250, manter

-- Criar orçamentos de abril também (mesmo valores)
INSERT INTO orcamentos (categoria_id, valor_limite, mes_referencia, ano_referencia) VALUES
(9, 500.00, 4, 2026),   -- Mercado
(12, 250.00, 4, 2026),  -- Gastos Livres
(17, 250.00, 4, 2026)   -- Restaurante / Lanche
ON DUPLICATE KEY UPDATE valor_limite = VALUES(valor_limite);
