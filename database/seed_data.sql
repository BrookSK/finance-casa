-- =============================================
-- FinançasCasal - Seed de receitas, despesas e cofrinhos
-- Rodar APÓS seed.sql
-- Usa mês/ano atual automaticamente
-- =============================================

USE financas_casal;

SET @mes = MONTH(CURDATE());
SET @ano = YEAR(CURDATE());

-- ========== RECEITAS ==========

-- Lucas - Salário
INSERT INTO receitas (usuario_id, titulo, valor, tipo, categoria_id, dia_recebimento_inicio, dia_recebimento_fim, recorrente, entra_no_orcamento, mes_referencia, ano_referencia, status)
VALUES (1, 'Salário Lucas', 3500.00, 'fixa', 1, 13, 15, 1, 1, @mes, @ano, 'prevista');

-- Bia - Salário
INSERT INTO receitas (usuario_id, titulo, valor, tipo, categoria_id, dia_recebimento_inicio, dia_recebimento_fim, recorrente, entra_no_orcamento, mes_referencia, ano_referencia, status)
VALUES (2, 'Salário Bia', 2000.00, 'fixa', 1, 20, 25, 1, 1, @mes, @ano, 'prevista');

-- Empresa
INSERT INTO receitas (usuario_id, titulo, valor, tipo, categoria_id, recorrente, entra_no_orcamento, mes_referencia, ano_referencia, status)
VALUES (1, 'Cliente 1', 500.00, 'variavel', 3, 0, 0, @mes, @ano, 'prevista');

INSERT INTO receitas (usuario_id, titulo, valor, tipo, categoria_id, recorrente, entra_no_orcamento, mes_referencia, ano_referencia, status)
VALUES (1, 'Cliente 2', 180.00, 'variavel', 3, 0, 0, @mes, @ano, 'prevista');

-- ========== DESPESAS FIXAS LUCAS ==========

INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, data_vencimento, recorrente, mes_referencia, ano_referencia) VALUES
(1, 'MEI Lucas', 86.05, 'fixa', 15, 'lucas', 'boleto', DATE(CONCAT(@ano,'-',LPAD(@mes,2,'0'),'-20')), 1, @mes, @ano),
(1, 'Internet Casa', 100.00, 'fixa', 16, 'compartilhado', 'boleto', DATE(CONCAT(@ano,'-',LPAD(@mes,2,'0'),'-20')), 1, @mes, @ano),
(1, 'Celular Lucas', 40.00, 'fixa', 16, 'lucas', 'credito', DATE(CONCAT(@ano,'-',LPAD(@mes,2,'0'),'-10')), 1, @mes, @ano),
(1, 'Aluguel', 1000.00, 'fixa', 4, 'compartilhado', 'pix', DATE(CONCAT(@ano,'-',LPAD(@mes,2,'0'),'-05')), 1, @mes, @ano),
(1, 'Água', 100.00, 'fixa', 4, 'compartilhado', 'boleto', DATE(CONCAT(@ano,'-',LPAD(@mes,2,'0'),'-04')), 1, @mes, @ano),
(1, 'Energia', 280.00, 'fixa', 4, 'compartilhado', 'boleto', DATE(CONCAT(@ano,'-',LPAD(@mes,2,'0'),'-05')), 1, @mes, @ano),
(1, 'Faculdade Lucas', 86.81, 'fixa', 6, 'lucas', 'boleto', DATE(CONCAT(@ano,'-',LPAD(@mes,2,'0'),'-06')), 1, @mes, @ano),
(1, 'Unimed', 300.00, 'fixa', 5, 'lucas', 'boleto', DATE(CONCAT(@ano,'-',LPAD(@mes,2,'0'),'-10')), 1, @mes, @ano),
(1, 'Consórcio Carro', 533.32, 'fixa', 7, 'lucas', 'boleto', DATE(CONCAT(@ano,'-',LPAD(@mes,2,'0'),'-15')), 1, @mes, @ano),
(1, 'Investimento', 300.00, 'fixa', 13, 'lucas', 'transferencia', DATE(CONCAT(@ano,'-',LPAD(@mes,2,'0'),'-15')), 1, @mes, @ano),
(1, 'TV / Celulares', 50.00, 'fixa', 16, 'compartilhado', 'credito', DATE(CONCAT(@ano,'-',LPAD(@mes,2,'0'),'-25')), 1, @mes, @ano),
(1, 'Gasolina', 80.00, 'fixa', 7, 'lucas', 'debito', DATE(CONCAT(@ano,'-',LPAD(@mes,2,'0'),'-15')), 1, @mes, @ano),
(1, 'Reserva', 150.00, 'fixa', 13, 'lucas', 'transferencia', DATE(CONCAT(@ano,'-',LPAD(@mes,2,'0'),'-15')), 1, @mes, @ano);

-- ========== DESPESAS FIXAS BIA ==========

INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, data_vencimento, recorrente, mes_referencia, ano_referencia) VALUES
(2, 'MEI Bia', 86.05, 'fixa', 15, 'bia', 'boleto', DATE(CONCAT(@ano,'-',LPAD(@mes,2,'0'),'-20')), 1, @mes, @ano),
(2, 'Centro', 80.00, 'fixa', 7, 'bia', 'pix', DATE(CONCAT(@ano,'-',LPAD(@mes,2,'0'),'-22')), 1, @mes, @ano),
(2, 'Unha', 125.00, 'fixa', 11, 'bia', 'pix', DATE(CONCAT(@ano,'-',LPAD(@mes,2,'0'),'-15')), 1, @mes, @ano),
(2, 'Hidratação', 60.00, 'fixa', 11, 'bia', 'pix', DATE(CONCAT(@ano,'-',LPAD(@mes,2,'0'),'-15')), 1, @mes, @ano),
(2, 'Sobrancelha', 50.00, 'fixa', 11, 'bia', 'pix', DATE(CONCAT(@ano,'-',LPAD(@mes,2,'0'),'-15')), 1, @mes, @ano),
(2, 'Faculdade Bia', 237.19, 'fixa', 6, 'bia', 'boleto', DATE(CONCAT(@ano,'-',LPAD(@mes,2,'0'),'-20')), 1, @mes, @ano),
(2, 'Celular Bia', 39.90, 'fixa', 16, 'bia', 'credito', DATE(CONCAT(@ano,'-',LPAD(@mes,2,'0'),'-10')), 1, @mes, @ano),
(2, 'Ônibus', 30.00, 'fixa', 7, 'bia', 'pix', DATE(CONCAT(@ano,'-',LPAD(@mes,2,'0'),'-22')), 1, @mes, @ano),
(2, 'Canva', 34.90, 'fixa', 10, 'bia', 'credito', DATE(CONCAT(@ano,'-',LPAD(@mes,2,'0'),'-20')), 1, @mes, @ano),
(2, 'Progressiva', 125.00, 'fixa', 11, 'bia', 'pix', DATE(CONCAT(@ano,'-',LPAD(@mes,2,'0'),'-15')), 1, @mes, @ano);

-- ========== ASSINATURAS / CARTÃO ==========

INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, recorrente, mes_referencia, ano_referencia) VALUES
(1, 'Spotify Lucas', 12.90, 'fixa', 10, 'lucas', 'credito', 1, @mes, @ano),
(2, 'Spotify Bia', 12.90, 'fixa', 10, 'bia', 'credito', 1, @mes, @ano),
(2, 'Google Fotos Bia', 9.99, 'fixa', 10, 'bia', 'credito', 1, @mes, @ano),
(2, 'ChatGPT Bia', 39.90, 'fixa', 10, 'bia', 'credito', 1, @mes, @ano),
(1, 'TV da Sala', 16.44, 'fixa', 10, 'compartilhado', 'credito', 1, @mes, @ano),
(1, 'Leite', 12.00, 'fixa', 8, 'compartilhado', 'credito', 1, @mes, @ano);

-- ========== ORÇAMENTOS MENSAIS ==========

INSERT INTO orcamentos (categoria_id, valor_limite, mes_referencia, ano_referencia) VALUES
(9, 500.00, @mes, @ano),
(12, 500.00, @mes, @ano),
(17, 200.00, @mes, @ano);

-- ========== COFRINHOS LUCAS ==========

INSERT INTO cofrinhos (usuario_id, nome, categoria_id, tipo, meta_mensal, valor_atual, prioridade, cor, mes_referencia, ano_referencia) VALUES
(1, 'Aluguel', 4, 'pessoal', 1000.00, 0, 'alta', '#ef4444', @mes, @ano),
(1, 'Água + Energia', 4, 'pessoal', 380.00, 0, 'alta', '#f97316', @mes, @ano),
(1, 'Internet + Celular + TV', 16, 'pessoal', 190.00, 0, 'media', '#0ea5e9', @mes, @ano),
(1, 'Faculdade + MEI', 6, 'pessoal', 172.86, 0, 'media', '#3b82f6', @mes, @ano),
(1, 'Unimed', 5, 'pessoal', 300.00, 0, 'alta', '#f43f5e', @mes, @ano),
(1, 'Consórcio + Gasolina', 7, 'pessoal', 613.32, 0, 'alta', '#f59e0b', @mes, @ano),
(1, 'Investimento', 13, 'pessoal', 300.00, 0, 'media', '#10b981', @mes, @ano),
(1, 'Fatura XP', 12, 'pessoal', 450.00, 0, 'alta', '#00c853', @mes, @ano),
(1, 'Fatura PicPay Lucas', 12, 'pessoal', 850.00, 0, 'alta', '#00e676', @mes, @ano),
(1, 'Reserva / Ajustes', 13, 'pessoal', 150.00, 0, 'baixa', '#6b7280', @mes, @ano);

-- ========== COFRINHOS BIA ==========

INSERT INTO cofrinhos (usuario_id, nome, categoria_id, tipo, meta_mensal, valor_atual, prioridade, cor, mes_referencia, ano_referencia) VALUES
(2, 'Faculdade', 6, 'pessoal', 237.19, 0, 'alta', '#3b82f6', @mes, @ano),
(2, 'MEI + Celular + Canva', 16, 'pessoal', 160.85, 0, 'media', '#0891b2', @mes, @ano),
(2, 'Centro + Ônibus', 7, 'pessoal', 110.00, 0, 'media', '#f59e0b', @mes, @ano),
(2, 'Unha', 11, 'pessoal', 125.00, 0, 'media', '#ec4899', @mes, @ano),
(2, 'Hidratação + Sobrancelha', 11, 'pessoal', 110.00, 0, 'media', '#f472b6', @mes, @ano),
(2, 'Progressiva', 11, 'pessoal', 125.00, 0, 'media', '#a855f7', @mes, @ano),
(2, 'Fatura PicPay Bia', 12, 'pessoal', 150.00, 0, 'alta', '#e040fb', @mes, @ano),
(2, 'Assinaturas', 10, 'pessoal', 65.00, 0, 'baixa', '#8b5cf6', @mes, @ano),
(2, 'Casa / Apoio', 14, 'compartilhado', 0, 0, 'baixa', '#d97706', @mes, @ano),
(2, 'Reserva / Ajustes', 13, 'pessoal', 0, 0, 'baixa', '#6b7280', @mes, @ano);
