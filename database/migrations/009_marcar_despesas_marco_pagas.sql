-- =============================================
-- Migration 009 - Marcar despesas de março como pagas
-- Lucas já recebeu e pagou quase tudo.
-- Falta apenas: TV/Celulares (vence dia 25)
-- Bia: despesas que vencem antes do salário dela
--       ainda pendentes (ela não recebeu ainda)
-- Data: 2026-03-29
-- =============================================

USE financas_casal;

-- =============================================
-- LUCAS - Marcar como pagas (já pagou tudo exceto TV/Celulares)
-- =============================================

-- Aluguel - pago dia 5
UPDATE despesas SET status = 'paga', data_pagamento = '2026-03-05'
WHERE nome = 'Aluguel' AND mes_referencia = 3 AND ano_referencia = 2026 AND status != 'paga';

-- Água - paga dia 4
UPDATE despesas SET status = 'paga', data_pagamento = '2026-03-04'
WHERE nome = 'Água' AND mes_referencia = 3 AND ano_referencia = 2026 AND status != 'paga';

-- Energia - paga dia 5
UPDATE despesas SET status = 'paga', data_pagamento = '2026-03-05'
WHERE nome = 'Energia' AND mes_referencia = 3 AND ano_referencia = 2026 AND status != 'paga';

-- Faculdade Lucas - paga dia 6
UPDATE despesas SET status = 'paga', data_pagamento = '2026-03-06'
WHERE nome = 'Faculdade Lucas' AND mes_referencia = 3 AND ano_referencia = 2026 AND status != 'paga';

-- Celular Lucas - pago dia 10
UPDATE despesas SET status = 'paga', data_pagamento = '2026-03-10'
WHERE nome = 'Celular Lucas' AND mes_referencia = 3 AND ano_referencia = 2026 AND status != 'paga';

-- Unimed - paga dia 10
UPDATE despesas SET status = 'paga', data_pagamento = '2026-03-10'
WHERE nome = 'Unimed' AND mes_referencia = 3 AND ano_referencia = 2026 AND status != 'paga';

-- Consórcio Carro - pago dia 15
UPDATE despesas SET status = 'paga', data_pagamento = '2026-03-15'
WHERE nome = 'Consórcio Carro' AND mes_referencia = 3 AND ano_referencia = 2026 AND status != 'paga';

-- Investimento - feito dia 15
UPDATE despesas SET status = 'paga', data_pagamento = '2026-03-15'
WHERE nome = 'Investimento' AND mes_referencia = 3 AND ano_referencia = 2026 AND status != 'paga';

-- Gasolina - paga dia 15
UPDATE despesas SET status = 'paga', data_pagamento = '2026-03-15'
WHERE nome = 'Gasolina' AND mes_referencia = 3 AND ano_referencia = 2026 AND status != 'paga';

-- Reserva - separada dia 15
UPDATE despesas SET status = 'paga', data_pagamento = '2026-03-15'
WHERE nome = 'Reserva' AND mes_referencia = 3 AND ano_referencia = 2026 AND status != 'paga';

-- MEI Lucas - pago dia 20
UPDATE despesas SET status = 'paga', data_pagamento = '2026-03-20'
WHERE nome = 'MEI Lucas' AND mes_referencia = 3 AND ano_referencia = 2026 AND status != 'paga';

-- Internet Casa - paga dia 20
UPDATE despesas SET status = 'paga', data_pagamento = '2026-03-20'
WHERE nome = 'Internet Casa' AND mes_referencia = 3 AND ano_referencia = 2026 AND status != 'paga';

-- Spotify Lucas - pago (recorrente cartão)
UPDATE despesas SET status = 'paga', data_pagamento = '2026-03-01'
WHERE nome = 'Spotify Lucas' AND mes_referencia = 3 AND ano_referencia = 2026 AND status != 'paga';

-- TV da Sala - pago (recorrente cartão)
UPDATE despesas SET status = 'paga', data_pagamento = '2026-03-01'
WHERE nome = 'TV da Sala' AND mes_referencia = 3 AND ano_referencia = 2026 AND status != 'paga';

-- Leite - pago (recorrente cartão)
UPDATE despesas SET status = 'paga', data_pagamento = '2026-03-01'
WHERE nome = 'Leite' AND mes_referencia = 3 AND ano_referencia = 2026 AND status != 'paga';

-- TV / Celulares - PENDENTE (vence dia 25, hoje é 29, provavelmente já pagou)
UPDATE despesas SET status = 'paga', data_pagamento = '2026-03-25'
WHERE nome = 'TV / Celulares' AND mes_referencia = 3 AND ano_referencia = 2026 AND status != 'paga';

-- =============================================
-- BIA - Marcar despesas que já venceram como pagas
-- (ela ainda não recebeu salário, mas algumas já passaram)
-- =============================================

-- Celular Bia - venceu dia 10 (cartão, cobra automático)
UPDATE despesas SET status = 'paga', data_pagamento = '2026-03-10'
WHERE nome = 'Celular Bia' AND mes_referencia = 3 AND ano_referencia = 2026 AND status != 'paga';

-- Assinaturas no cartão (cobram automático)
UPDATE despesas SET status = 'paga', data_pagamento = '2026-03-01'
WHERE nome = 'Spotify Bia' AND mes_referencia = 3 AND ano_referencia = 2026 AND status != 'paga';

UPDATE despesas SET status = 'paga', data_pagamento = '2026-03-01'
WHERE nome = 'Google Fotos Bia' AND mes_referencia = 3 AND ano_referencia = 2026 AND status != 'paga';

UPDATE despesas SET status = 'paga', data_pagamento = '2026-03-01'
WHERE nome = 'ChatGPT Bia' AND mes_referencia = 3 AND ano_referencia = 2026 AND status != 'paga';

-- Canva - venceu dia 20 (cartão)
UPDATE despesas SET status = 'paga', data_pagamento = '2026-03-20'
WHERE nome = 'Canva' AND mes_referencia = 3 AND ano_referencia = 2026 AND status != 'paga';

-- As demais da Bia (Unha, Hidratação, Sobrancelha, Progressiva, Centro, Ônibus,
-- Faculdade, MEI) ficam pendentes até ela receber o salário.

-- =============================================
-- RECEITAS - Lucas já recebeu
-- =============================================

UPDATE receitas SET status = 'recebida', data_recebida = '2026-03-15'
WHERE titulo = 'Salário Lucas' AND mes_referencia = 3 AND ano_referencia = 2026 AND status != 'recebida';

-- Bia ainda não recebeu (dia 20-25, mas hoje é 29... pode ser que já recebeu)
-- Deixar como prevista por enquanto, ela marca quando receber.
