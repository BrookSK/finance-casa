-- =============================================
-- Migration 026 - Despesas fixas e receitas abril 2026
-- Todas as contas recorrentes + receitas
-- Data: 2026-03-29
-- =============================================

USE financas_casal;

-- =============================================
-- DESPESAS FIXAS LUCAS - Abril 2026
-- =============================================

INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, data_vencimento, recorrente, mes_referencia, ano_referencia, status, observacao) VALUES
-- Dia 4
(1, 'Água', 80.00, 'fixa', 4, 'compartilhado', 'boleto', '2026-04-04', 1, 4, 2026, 'pendente', 'Valor aproximado. Pode variar.'),
-- Dia 5
(1, 'Aluguel', 1000.00, 'fixa', 4, 'compartilhado', 'pix', '2026-04-05', 1, 4, 2026, 'pendente', 'Sacar R$1.000 no Sicredi e entregar em dinheiro.'),
(1, 'Energia', 280.00, 'fixa', 4, 'compartilhado', 'boleto', '2026-04-05', 1, 4, 2026, 'pendente', 'Valor aproximado. Pode variar.'),
-- Dia 6
(1, 'Faculdade Lucas', 86.81, 'fixa', 6, 'lucas', 'boleto', '2026-04-06', 1, 4, 2026, 'pendente', NULL),
-- Dia 10
(1, 'Celular Lucas', 40.00, 'fixa', 16, 'lucas', 'debito', '2026-04-10', 1, 4, 2026, 'pendente', NULL),
(1, 'Unimed', 300.00, 'fixa', 5, 'lucas', 'boleto', '2026-04-10', 1, 4, 2026, 'pendente', NULL),
-- Dia 15
(1, 'Consórcio Carro', 533.32, 'fixa', 7, 'lucas', 'boleto', '2026-04-15', 1, 4, 2026, 'pendente', NULL),
(1, 'Investimento', 300.00, 'fixa', 13, 'lucas', 'transferencia', '2026-04-15', 1, 4, 2026, 'pendente', 'Transferir para conta de investimentos XP assim que receber.'),
-- Dia 20
(1, 'MEI Lucas', 86.05, 'fixa', 15, 'lucas', 'boleto', '2026-04-20', 1, 4, 2026, 'pendente', NULL),
(1, 'Internet Casa', 100.00, 'fixa', 16, 'compartilhado', 'boleto', '2026-04-20', 1, 4, 2026, 'pendente', NULL),
-- Dia 25
(1, 'TV / Celulares', 50.00, 'fixa', 16, 'compartilhado', 'debito', '2026-04-25', 1, 4, 2026, 'pendente', NULL),
-- Sem dia fixo (ao longo do mês)
(1, 'Gasolina', 80.00, 'fixa', 7, 'lucas', 'debito', NULL, 1, 4, 2026, 'pendente', 'Sem dia fixo. Vai abastecendo conforme precisa (~R$30+R$30+R$20).');

-- =============================================
-- DESPESAS FIXAS BIA - Abril 2026
-- =============================================

INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, data_vencimento, recorrente, mes_referencia, ano_referencia, status, observacao) VALUES
-- Dia 10
(2, 'Faculdade Bia', 237.19, 'fixa', 6, 'bia', 'boleto', '2026-04-10', 1, 4, 2026, 'pendente', NULL),
(2, 'Celular Bia', 39.90, 'fixa', 16, 'bia', 'debito', '2026-04-10', 1, 4, 2026, 'pendente', NULL),
-- Dia 20
(2, 'MEI Bia', 86.05, 'fixa', 15, 'bia', 'boleto', '2026-04-20', 1, 4, 2026, 'pendente', NULL),
-- Assim que receber
(2, 'Centro', 80.00, 'fixa', 7, 'bia', 'pix', NULL, 1, 4, 2026, 'pendente', 'Paga assim que recebe o salário.'),
(2, 'Ônibus', 30.00, 'fixa', 7, 'bia', 'dinheiro', NULL, 1, 4, 2026, 'pendente', 'Sacar R$30 para ônibus do mês. R$1 ida + R$1 volta.'),
-- Sem dia fixo
(2, 'Unha', 125.00, 'fixa', 11, 'bia', 'pix', NULL, 1, 4, 2026, 'pendente', 'Sem dia fixo. Marca quando a profissional pode.'),
(2, 'Hidratação', 60.00, 'fixa', 11, 'bia', 'pix', NULL, 1, 4, 2026, 'pendente', 'Sem dia fixo.'),
(2, 'Sobrancelha', 50.00, 'fixa', 11, 'bia', 'pix', NULL, 1, 4, 2026, 'pendente', 'Sem dia fixo.'),
(2, 'Progressiva', 125.00, 'fixa', 11, 'bia', 'pix', NULL, 1, 4, 2026, 'pendente', 'R$125/mês. Uso a cada 2 meses.');

-- Taxas Sicredi abril (conta corrente)
INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, data_vencimento, recorrente, mes_referencia, ano_referencia, status, observacao) VALUES
(1, 'Sicredi - Capital Subscrito', 11.00, 'fixa', 16, 'lucas', 'debito', '2026-04-10', 1, 4, 2026, 'pendente', 'Integralização de capital. Débito automático.'),
(1, 'Sicredi - Cesta de Relacionamento', 15.90, 'fixa', 16, 'lucas', 'debito', '2026-04-20', 1, 4, 2026, 'pendente', 'Taxa mensal da conta. Débito automático.');


-- =============================================
-- RECEITAS - Abril 2026
-- =============================================

-- Lucas - Salário
INSERT INTO receitas (usuario_id, titulo, valor, tipo, categoria_id, dia_recebimento_inicio, dia_recebimento_fim, recorrente, entra_no_orcamento, mes_referencia, ano_referencia, status) VALUES
(1, 'Salário Lucas', 3500.00, 'fixa', 1, 13, 15, 1, 1, 4, 2026, 'prevista');

-- Bia - Salário
INSERT INTO receitas (usuario_id, titulo, valor, tipo, categoria_id, dia_recebimento_inicio, dia_recebimento_fim, recorrente, entra_no_orcamento, mes_referencia, ano_referencia, status) VALUES
(2, 'Salário Bia', 2000.00, 'fixa', 1, 20, 30, 1, 1, 4, 2026, 'prevista');

-- Empresa LRV Web (não entra no orçamento)
INSERT INTO receitas (usuario_id, titulo, valor, tipo, categoria_id, dia_recebimento_inicio, dia_recebimento_fim, recorrente, entra_no_orcamento, mes_referencia, ano_referencia, status, observacao) VALUES
(1, 'Punta Cana para Brasileiros', 500.00, 'variavel', 3, 28, 30, 0, 0, 4, 2026, 'prevista', 'Cliente LRV Web. Não entra no orçamento pessoal.'),
(1, 'H2 Games', 90.00, 'variavel', 3, 28, 30, 0, 0, 4, 2026, 'prevista', 'Cliente LRV Web. Não entra no orçamento pessoal.'),
(1, 'DM Games', 90.00, 'variavel', 3, 28, 30, 0, 0, 4, 2026, 'prevista', 'Cliente LRV Web. Não entra no orçamento pessoal.');


-- =============================================
-- RECEITAS - Março 2026 (registrar o que já aconteceu)
-- =============================================

-- Lucas recebeu dia 15/03
INSERT INTO receitas (usuario_id, titulo, valor, tipo, categoria_id, dia_recebimento_inicio, dia_recebimento_fim, recorrente, entra_no_orcamento, mes_referencia, ano_referencia, status, data_recebida) VALUES
(1, 'Salário Lucas', 3500.00, 'fixa', 1, 13, 15, 1, 1, 3, 2026, 'recebida', '2026-03-15');

-- Bia recebeu parcial dia 26/03 (R$500, falta R$1.500)
INSERT INTO receitas (usuario_id, titulo, valor, tipo, categoria_id, dia_recebimento_inicio, dia_recebimento_fim, recorrente, entra_no_orcamento, mes_referencia, ano_referencia, status, data_recebida, observacao) VALUES
(2, 'Salário Bia (parcial)', 500.00, 'fixa', 1, 20, 30, 1, 1, 3, 2026, 'recebida', '2026-03-26', 'Recebeu R$500 dia 26/03. Falta R$1.500 do chefe.'),
(2, 'Salário Bia (restante)', 1500.00, 'fixa', 1, 20, 30, 1, 1, 3, 2026, 'prevista', NULL, 'Falta o chefe mandar R$1.500.');

-- Empresa março
INSERT INTO receitas (usuario_id, titulo, valor, tipo, categoria_id, dia_recebimento_inicio, dia_recebimento_fim, recorrente, entra_no_orcamento, mes_referencia, ano_referencia, status, observacao) VALUES
(1, 'Punta Cana para Brasileiros', 500.00, 'variavel', 3, 28, 30, 0, 0, 3, 2026, 'prevista', 'Cliente LRV Web.'),
(1, 'H2 Games', 90.00, 'variavel', 3, 28, 30, 0, 0, 3, 2026, 'prevista', 'Cliente LRV Web.'),
(1, 'DM Games', 90.00, 'variavel', 3, 28, 30, 0, 0, 3, 2026, 'prevista', 'Cliente LRV Web.');


-- =============================================
-- COFRINHO: Ônibus Bia (novo)
-- R$ 30 para sacar e usar no mês
-- =============================================

INSERT INTO cofrinhos (usuario_id, nome, categoria_id, tipo, meta_mensal, valor_atual, prioridade, cor, mes_referencia, ano_referencia, observacao) VALUES
(2, 'Ônibus', 7, 'pessoal', 30.00, 0, 'media', '#f59e0b', 4, 2026,
 'R$30 para sacar. R$1 ida + R$1 volta. Sacar assim que receber.');


-- =============================================
-- ORÇAMENTOS - Abril 2026
-- =============================================

INSERT INTO orcamentos (categoria_id, valor_limite, mes_referencia, ano_referencia) VALUES
(9, 500.00, 4, 2026),   -- Mercado
(12, 500.00, 4, 2026)   -- Cartão / Gastos Livres
ON DUPLICATE KEY UPDATE valor_limite = VALUES(valor_limite);
