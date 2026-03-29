-- =============================================
-- Migration 012 - Despesas fixas de abril 2026
-- Copia as despesas recorrentes de março para abril
-- Estas são as contas que serão pagas com o dinheiro
-- guardado nos cofrinhos de março
-- Data: 2026-03-29
-- =============================================

USE financas_casal;

-- =============================================
-- DESPESAS FIXAS LUCAS - Abril 2026
-- =============================================

INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, cartao_id, data_vencimento, recorrente, mes_referencia, ano_referencia, status, observacao) VALUES
(1, 'Aluguel', 1000.00, 'fixa', 4, 'compartilhado', 'pix', NULL, '2026-04-05', 1, 4, 2026, 'pendente', 'Compartilhado: Lucas R$636 + Bia R$364'),
(1, 'Água', 100.00, 'fixa', 4, 'compartilhado', 'boleto', NULL, '2026-04-04', 1, 4, 2026, 'pendente', 'Lucas paga, Bia complementa R$130 no cofrinho Casa/Apoio'),
(1, 'Energia', 280.00, 'fixa', 4, 'compartilhado', 'boleto', NULL, '2026-04-05', 1, 4, 2026, 'pendente', 'Lucas paga, Bia complementa R$130 no cofrinho Casa/Apoio'),
(1, 'Faculdade Lucas', 86.81, 'fixa', 6, 'lucas', 'boleto', NULL, '2026-04-06', 1, 4, 2026, 'pendente', NULL),
(1, 'Celular Lucas', 40.00, 'fixa', 16, 'lucas', 'credito', 1, '2026-04-10', 1, 4, 2026, 'pendente', NULL),
(1, 'Unimed', 300.00, 'fixa', 5, 'lucas', 'boleto', NULL, '2026-04-10', 1, 4, 2026, 'pendente', NULL),
(1, 'Consórcio Carro', 533.32, 'fixa', 7, 'lucas', 'boleto', NULL, '2026-04-15', 1, 4, 2026, 'pendente', NULL),
(1, 'Investimento', 300.00, 'fixa', 13, 'lucas', 'transferencia', NULL, '2026-04-15', 1, 4, 2026, 'pendente', 'Separar assim que receber. Não reduzir.'),
(1, 'Gasolina', 80.00, 'fixa', 7, 'lucas', 'debito', NULL, '2026-04-15', 1, 4, 2026, 'pendente', NULL),
(1, 'Reserva', 150.00, 'fixa', 13, 'lucas', 'transferencia', NULL, '2026-04-15', 1, 4, 2026, 'pendente', NULL),
(1, 'MEI Lucas', 86.05, 'fixa', 15, 'lucas', 'boleto', NULL, '2026-04-20', 1, 4, 2026, 'pendente', NULL),
(1, 'Internet Casa', 100.00, 'fixa', 16, 'compartilhado', 'boleto', NULL, '2026-04-20', 1, 4, 2026, 'pendente', 'Lucas paga, Bia complementa R$90 no cofrinho Casa/Apoio'),
(1, 'TV / Celulares', 50.00, 'fixa', 16, 'compartilhado', 'credito', 1, '2026-04-25', 1, 4, 2026, 'pendente', NULL);

-- Assinaturas Lucas (cartão XP) - abril
INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, cartao_id, recorrente, mes_referencia, ano_referencia, status) VALUES
(1, 'Spotify Lucas', 12.90, 'fixa', 10, 'lucas', 'credito', 1, 1, 4, 2026, 'pendente'),
(1, 'TV da Sala', 16.44, 'fixa', 10, 'compartilhado', 'credito', 2, 1, 4, 2026, 'pendente'),
(1, 'Leite', 12.00, 'fixa', 8, 'compartilhado', 'credito', 2, 1, 4, 2026, 'pendente');

-- =============================================
-- DESPESAS FIXAS BIA - Abril 2026
-- =============================================

INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, cartao_id, data_vencimento, recorrente, mes_referencia, ano_referencia, status) VALUES
(2, 'Celular Bia', 39.90, 'fixa', 16, 'bia', 'credito', 4, '2026-04-10', 1, 4, 2026, 'pendente'),
(2, 'Unha', 125.00, 'fixa', 11, 'bia', 'pix', NULL, '2026-04-15', 1, 4, 2026, 'pendente'),
(2, 'Hidratação', 60.00, 'fixa', 11, 'bia', 'pix', NULL, '2026-04-15', 1, 4, 2026, 'pendente'),
(2, 'Sobrancelha', 50.00, 'fixa', 11, 'bia', 'pix', NULL, '2026-04-15', 1, 4, 2026, 'pendente'),
(2, 'Progressiva', 125.00, 'fixa', 11, 'bia', 'pix', NULL, '2026-04-15', 1, 4, 2026, 'pendente'),
(2, 'MEI Bia', 86.05, 'fixa', 15, 'bia', 'boleto', NULL, '2026-04-20', 1, 4, 2026, 'pendente'),
(2, 'Faculdade Bia', 237.19, 'fixa', 6, 'bia', 'boleto', NULL, '2026-04-20', 1, 4, 2026, 'pendente'),
(2, 'Canva', 34.90, 'fixa', 10, 'bia', 'credito', 4, '2026-04-20', 1, 4, 2026, 'pendente'),
(2, 'Centro', 80.00, 'fixa', 7, 'bia', 'pix', NULL, '2026-04-22', 1, 4, 2026, 'pendente'),
(2, 'Ônibus', 30.00, 'fixa', 7, 'bia', 'pix', NULL, '2026-04-22', 1, 4, 2026, 'pendente');

-- Assinaturas Bia (cartão PicPay Bia) - abril
INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, cartao_id, recorrente, mes_referencia, ano_referencia, status) VALUES
(2, 'Spotify Bia', 12.90, 'fixa', 10, 'bia', 'credito', 4, 1, 4, 2026, 'pendente'),
(2, 'Google Fotos Bia', 9.99, 'fixa', 10, 'bia', 'credito', 4, 1, 4, 2026, 'pendente'),
(2, 'ChatGPT Bia', 39.90, 'fixa', 10, 'bia', 'credito', 4, 1, 4, 2026, 'pendente');

-- =============================================
-- RECEITAS Abril 2026
-- =============================================

INSERT INTO receitas (usuario_id, titulo, valor, tipo, categoria_id, dia_recebimento_inicio, dia_recebimento_fim, recorrente, entra_no_orcamento, mes_referencia, ano_referencia, status) VALUES
(1, 'Salário Lucas', 3500.00, 'fixa', 1, 13, 15, 1, 1, 4, 2026, 'prevista'),
(2, 'Salário Bia', 2000.00, 'fixa', 1, 20, 25, 1, 1, 4, 2026, 'prevista');

INSERT INTO receitas (usuario_id, titulo, valor, tipo, categoria_id, recorrente, entra_no_orcamento, mes_referencia, ano_referencia, status, observacao) VALUES
(1, 'Punta Cana para Brasileiros', 500.00, 'variavel', 3, 0, 0, 4, 2026, 'prevista', 'Cliente LRV Web.'),
(1, 'DM Games', 90.00, 'variavel', 3, 0, 0, 4, 2026, 'prevista', 'Cliente LRV Web.'),
(1, 'H2 Games', 90.00, 'variavel', 3, 0, 0, 4, 2026, 'prevista', 'Cliente LRV Web.');

-- =============================================
-- COFRINHOS Abril 2026 (copiar estrutura de março)
-- =============================================

-- Lucas
INSERT INTO cofrinhos (usuario_id, nome, categoria_id, tipo, meta_mensal, valor_atual, prioridade, cor, mes_referencia, ano_referencia, observacao)
SELECT usuario_id, nome, categoria_id, tipo, meta_mensal, 0, prioridade, cor, 4, 2026, observacao
FROM cofrinhos WHERE usuario_id = 1 AND mes_referencia = 3 AND ano_referencia = 2026;

-- Bia
INSERT INTO cofrinhos (usuario_id, nome, categoria_id, tipo, meta_mensal, valor_atual, prioridade, cor, mes_referencia, ano_referencia, observacao)
SELECT usuario_id, nome, categoria_id, tipo, meta_mensal, 0, prioridade, cor, 4, 2026, observacao
FROM cofrinhos WHERE usuario_id = 2 AND mes_referencia = 3 AND ano_referencia = 2026;
