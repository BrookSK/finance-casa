-- =============================================
-- Migration 022 - Taxas da conta Sicredi (não é cartão)
-- Integralização Capital Subscrito: R$ 11,00 dia 10
-- Cesta de Relacionamento: R$ 15,90 dia 20
-- São cobranças da conta corrente, não do cartão
-- Data: 2026-03-29
-- =============================================

USE financas_casal;

-- Março (já pagas)
INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, data_vencimento, data_pagamento, recorrente, mes_referencia, ano_referencia, status, observacao) VALUES
(1, 'Sicredi - Capital Subscrito', 11.00, 'fixa', 16, 'lucas', 'debito', '2026-03-10', '2026-03-10', 1, 3, 2026, 'paga', 'Integralização de capital. Cobrado da conta dia 10.'),
(1, 'Sicredi - Cesta de Relacionamento', 15.90, 'fixa', 16, 'lucas', 'debito', '2026-03-20', '2026-03-20', 1, 3, 2026, 'paga', 'Taxa mensal da conta. Cobrada dia 20.');

-- Abril (pendentes)
INSERT INTO despesas (usuario_id, nome, valor, tipo, categoria_id, proprietario, forma_pagamento, data_vencimento, recorrente, mes_referencia, ano_referencia, status, observacao) VALUES
(1, 'Sicredi - Capital Subscrito', 11.00, 'fixa', 16, 'lucas', 'debito', '2026-04-10', 1, 4, 2026, 'pendente', 'Integralização de capital. Cobrado da conta dia 10. Precisa ter saldo.'),
(1, 'Sicredi - Cesta de Relacionamento', 15.90, 'fixa', 16, 'lucas', 'debito', '2026-04-20', 1, 4, 2026, 'pendente', 'Taxa mensal da conta. Cobrada dia 20. Precisa ter saldo.');
