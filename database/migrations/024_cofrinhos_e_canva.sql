-- =============================================
-- Migration 024 - Cofrinhos reais + despesa Canva Bia
--
-- Lucas: 9 cofrinhos (mercado juntou com cartão)
-- Bia: 7 cofrinhos (sem assinaturas, sem Canva no cofrinho)
-- Canva: despesa recorrente no Mercado Pago da Bia
--
-- Mês referência: abril 2026 (guardando agora pra pagar em abril)
-- Data: 2026-03-29
-- =============================================

USE financas_casal;

-- =============================================
-- COFRINHOS LUCAS - Abril 2026
-- =============================================

INSERT INTO cofrinhos (usuario_id, nome, categoria_id, tipo, meta_mensal, valor_atual, prioridade, cor, mes_referencia, ano_referencia, observacao) VALUES
(1, 'Aluguel', 4, 'pessoal', 1000.00, 1000.00, 'alta', '#ef4444', 4, 2026,
 'Prioridade 1. Vence dia 5. Pagar inteiro ao receber.'),

(1, 'Água + Energia', 4, 'pessoal', 380.00, 480.00, 'alta', '#f97316', 4, 2026,
 'Prioridade 2. Água dia 4, Energia dia 5. Meta R$380, tem R$100 a mais de sobra.'),

(1, 'Faculdade + MEI', 6, 'pessoal', 172.86, 86.93, 'media', '#3b82f6', 4, 2026,
 'Prioridade 3. Faculdade dia 6, MEI dia 20. Falta R$85,93.'),

(1, 'Unimed', 5, 'pessoal', 300.00, 300.00, 'alta', '#f43f5e', 4, 2026,
 'Prioridade 4. Vence dia 10. Completo.'),

(1, 'Consórcio + Gasolina', 7, 'pessoal', 613.32, 33.46, 'alta', '#f59e0b', 4, 2026,
 'Prioridade 5. Consórcio dia 15, gasolina ao longo do mês.'),

(1, 'Cartões + Mercado', 12, 'pessoal', 1900.00, 450.00, 'alta', '#7c3aed', 4, 2026,
 'Prioridade 6. Cobre TODOS os cartões (XP + PicPay Casa + Sicredi + PicPay Bia) + mercado. Meta: R$1.400 cartões + R$500 mercado = R$1.900. Lucas R$700, Bia complementa via Casa/Apoio.'),

(1, 'Internet + Celular + TV', 16, 'pessoal', 190.00, 190.00, 'media', '#0ea5e9', 4, 2026,
 'Prioridade 7. Celular dia 10, Internet dia 20, TV dia 25. Completo.'),

(1, 'Reserva / Ajustes', 13, 'pessoal', 150.00, 135.93, 'baixa', '#6b7280', 4, 2026,
 'Prioridade 8. Diferença de contas, farmácia, imprevistos. Compartilhado com Bia.');

-- Registrar movimentações iniciais Lucas
INSERT INTO cofrinho_movimentacoes (cofrinho_id, usuario_id, tipo, valor, descricao)
SELECT id, 1, 'deposito', valor_atual, 'Saldo inicial - redistribuição cofrinhos antigos'
FROM cofrinhos WHERE usuario_id = 1 AND mes_referencia = 4 AND ano_referencia = 2026 AND valor_atual > 0;


-- =============================================
-- COFRINHOS BIA - Abril 2026
-- =============================================

INSERT INTO cofrinhos (usuario_id, nome, categoria_id, tipo, meta_mensal, valor_atual, prioridade, cor, mes_referencia, ano_referencia, observacao) VALUES
(2, 'Faculdade', 6, 'pessoal', 237.19, 0, 'alta', '#3b82f6', 4, 2026,
 'Prioridade 1. Vence dia 20.'),

(2, 'MEI + Celular', 16, 'pessoal', 125.95, 0, 'media', '#0891b2', 4, 2026,
 'Prioridade 2. MEI dia 20 (R$86,05) + Celular dia 10 (R$39,90). Canva sai direto do Mercado Pago.'),

(2, 'Centro + Ônibus', 7, 'pessoal', 110.00, 30.00, 'media', '#f59e0b', 4, 2026,
 'Prioridade 3. Assim que receber.'),

(2, 'Unha', 11, 'pessoal', 125.00, 0, 'media', '#ec4899', 4, 2026,
 'Prioridade 4. Mensal.'),

(2, 'Progressiva', 11, 'pessoal', 125.00, 0, 'media', '#a855f7', 4, 2026,
 'Prioridade 5. R$125/mês fixo. Uso a cada 2 meses (R$250 acumulado).'),

(2, 'Hidratação + Sobrancelha', 11, 'pessoal', 110.00, 60.00, 'media', '#f472b6', 4, 2026,
 'Prioridade 6. Hidratação R$60 + Sobrancelha R$50.');

-- Registrar movimentações iniciais Bia
INSERT INTO cofrinho_movimentacoes (cofrinho_id, usuario_id, tipo, valor, descricao)
SELECT id, 2, 'deposito', valor_atual, 'Saldo inicial - redistribuição cofrinhos antigos'
FROM cofrinhos WHERE usuario_id = 2 AND mes_referencia = 4 AND ano_referencia = 2026 AND valor_atual > 0;


-- =============================================
-- DESPESA RECORRENTE: Canva Bia (Mercado Pago)
-- R$ 34,90 dia 6, débito direto no Mercado Pago
-- =============================================

-- Março (já paga)
INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, data_vencimento, data_pagamento, recorrente, mes_referencia, ano_referencia, status, observacao) VALUES
(2, 'Canva', 34.90, 'fixa', 10, 'bia', 'debito', '2026-03-06', '2026-03-06', 1, 3, 2026, 'paga', 'Débito direto no Mercado Pago. Bia transfere assim que recebe.');

-- Abril (pendente)
INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, data_vencimento, recorrente, mes_referencia, ano_referencia, status, observacao) VALUES
(2, 'Canva', 34.90, 'fixa', 10, 'bia', 'debito', '2026-04-06', 1, 4, 2026, 'pendente', 'Débito direto no Mercado Pago. Bia transfere assim que recebe.');
