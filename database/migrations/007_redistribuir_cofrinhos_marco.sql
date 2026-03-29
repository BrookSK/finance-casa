-- =============================================
-- Migration 007 - Redistribuir dinheiro real nos cofrinhos
-- Lucas já recebeu e guardou. Bia ainda não recebeu.
-- Data: 2026-03-29
-- =============================================

USE financas_casal;

SET @mes = 3;
SET @ano = 2026;

-- =============================================
-- LUCAS - Redistribuição dos cofrinhos antigos → novos
--
-- Dinheiro disponível:
--   Cartão crédito: R$440,14
--   Unimed: R$300,55
--   Energia: R$283,67
--   Água: R$200,24
--   Internet: R$221,90
--   UNIP: R$86,93
--   TV: R$51,12
--   Celular: R$40,07
--   Ônibus/Gasolina: R$30,24
--   Saldo conta: R$21,46
--   TOTAL: R$1.676,32
--
-- Distribuição nos cofrinhos novos:
--   Água + Energia: R$380,00 (de Energia R$283,67 + Água R$96,33)
--   Internet + Celular + TV: R$190,00 (de Internet R$190)
--   Unimed: R$300,00 (de Unimed R$300)
--   Fatura XP: R$450,00 (de Cartão R$440,14 + sobra R$9,86)
--   Faculdade + MEI: R$172,86 (de UNIP R$86,93 + sobra R$85,93)
--   Reserva / Ajustes: R$150,00 (de sobras)
--   Consórcio + Gasolina: R$33,46 (de Ônibus/Gas R$30,24 + sobra R$3,22)
--   Aluguel: R$0 (já pago este mês)
--   Investimento: R$0 (já investido este mês)
--   Fatura PicPay Casa: R$0 (vence dia 20, Bia complementa)
--   TOTAL ALOCADO: R$1.676,32
-- =============================================

-- Água + Energia = R$380,00 (COMPLETO)
UPDATE cofrinhos SET valor_atual = 380.00
WHERE usuario_id = 1 AND nome = 'Água + Energia'
  AND mes_referencia = @mes AND ano_referencia = @ano;

-- Internet + Celular + TV = R$190,00 (COMPLETO)
UPDATE cofrinhos SET valor_atual = 190.00
WHERE usuario_id = 1 AND nome = 'Internet + Celular + TV'
  AND mes_referencia = @mes AND ano_referencia = @ano;

-- Unimed = R$300,00 (COMPLETO)
UPDATE cofrinhos SET valor_atual = 300.00
WHERE usuario_id = 1 AND nome = 'Unimed'
  AND mes_referencia = @mes AND ano_referencia = @ano;

-- Fatura XP = R$450,00 (COMPLETO)
UPDATE cofrinhos SET valor_atual = 450.00
WHERE usuario_id = 1 AND nome = 'Fatura XP'
  AND mes_referencia = @mes AND ano_referencia = @ano;

-- Faculdade + MEI = R$172,86 (COMPLETO)
UPDATE cofrinhos SET valor_atual = 172.86
WHERE usuario_id = 1 AND nome = 'Faculdade + MEI'
  AND mes_referencia = @mes AND ano_referencia = @ano;

-- Reserva / Ajustes = R$150,00 (COMPLETO)
UPDATE cofrinhos SET valor_atual = 150.00
WHERE usuario_id = 1 AND nome = 'Reserva / Ajustes'
  AND mes_referencia = @mes AND ano_referencia = @ano;

-- Consórcio + Gasolina = R$33,46 (parcial - consórcio já pago este mês)
UPDATE cofrinhos SET valor_atual = 33.46
WHERE usuario_id = 1 AND nome = 'Consórcio + Gasolina'
  AND mes_referencia = @mes AND ano_referencia = @ano;

-- Aluguel = R$0 (já pago este mês)
-- Investimento = R$0 (já investido este mês)
-- Fatura PicPay Casa = R$0 (vence dia 20 abril)

-- =============================================
-- Registrar movimentações dos cofrinhos do Lucas
-- =============================================

INSERT INTO cofrinho_movimentacoes (cofrinho_id, usuario_id, tipo, valor, descricao)
SELECT id, 1, 'deposito', 380.00, 'Redistribuição: Energia R$283,67 + Água R$96,33'
FROM cofrinhos WHERE usuario_id = 1 AND nome = 'Água + Energia' AND mes_referencia = @mes AND ano_referencia = @ano;

INSERT INTO cofrinho_movimentacoes (cofrinho_id, usuario_id, tipo, valor, descricao)
SELECT id, 1, 'deposito', 190.00, 'Redistribuição: Internet R$190 (sobra de R$221,90+R$51,12+R$40,07 redistribuída)'
FROM cofrinhos WHERE usuario_id = 1 AND nome = 'Internet + Celular + TV' AND mes_referencia = @mes AND ano_referencia = @ano;

INSERT INTO cofrinho_movimentacoes (cofrinho_id, usuario_id, tipo, valor, descricao)
SELECT id, 1, 'deposito', 300.00, 'Redistribuição: Unimed R$300 (R$0,55 de sobra redistribuída)'
FROM cofrinhos WHERE usuario_id = 1 AND nome = 'Unimed' AND mes_referencia = @mes AND ano_referencia = @ano;

INSERT INTO cofrinho_movimentacoes (cofrinho_id, usuario_id, tipo, valor, descricao)
SELECT id, 1, 'deposito', 450.00, 'Redistribuição: Cartão crédito R$440,14 + sobra R$9,86'
FROM cofrinhos WHERE usuario_id = 1 AND nome = 'Fatura XP' AND mes_referencia = @mes AND ano_referencia = @ano;

INSERT INTO cofrinho_movimentacoes (cofrinho_id, usuario_id, tipo, valor, descricao)
SELECT id, 1, 'deposito', 172.86, 'Redistribuição: UNIP R$86,93 + sobra R$85,93'
FROM cofrinhos WHERE usuario_id = 1 AND nome = 'Faculdade + MEI' AND mes_referencia = @mes AND ano_referencia = @ano;

INSERT INTO cofrinho_movimentacoes (cofrinho_id, usuario_id, tipo, valor, descricao)
SELECT id, 1, 'deposito', 150.00, 'Redistribuição: sobras dos cofrinhos antigos'
FROM cofrinhos WHERE usuario_id = 1 AND nome = 'Reserva / Ajustes' AND mes_referencia = @mes AND ano_referencia = @ano;

INSERT INTO cofrinho_movimentacoes (cofrinho_id, usuario_id, tipo, valor, descricao)
SELECT id, 1, 'deposito', 33.46, 'Redistribuição: Ônibus/Gasolina R$30,24 + sobra R$3,22'
FROM cofrinhos WHERE usuario_id = 1 AND nome = 'Consórcio + Gasolina' AND mes_referencia = @mes AND ano_referencia = @ano;


-- =============================================
-- BIA - Redistribuição
--
-- Dinheiro disponível:
--   Progressivo/cabelo: R$60,00
--   Ônibus: R$30,00
--   Saldo conta: R$7,47
--   TOTAL: R$97,47
--
-- Distribuição:
--   Progressiva: R$60,00
--   Centro + Ônibus: R$30,00
--   Reserva / Ajustes: R$7,47
-- =============================================

-- Progressiva = R$60,00
UPDATE cofrinhos SET valor_atual = 60.00
WHERE usuario_id = 2 AND nome = 'Progressiva'
  AND mes_referencia = @mes AND ano_referencia = @ano;

-- Centro + Ônibus = R$30,00
UPDATE cofrinhos SET valor_atual = 30.00
WHERE usuario_id = 2 AND nome = 'Centro + Ônibus'
  AND mes_referencia = @mes AND ano_referencia = @ano;

-- Reserva / Ajustes = R$7,47
UPDATE cofrinhos SET valor_atual = 7.47
WHERE usuario_id = 2 AND nome = 'Reserva / Ajustes'
  AND mes_referencia = @mes AND ano_referencia = @ano;

-- Registrar movimentações da Bia
INSERT INTO cofrinho_movimentacoes (cofrinho_id, usuario_id, tipo, valor, descricao)
SELECT id, 2, 'deposito', 60.00, 'Redistribuição: Progressivo/cabelo antigo'
FROM cofrinhos WHERE usuario_id = 2 AND nome = 'Progressiva' AND mes_referencia = @mes AND ano_referencia = @ano;

INSERT INTO cofrinho_movimentacoes (cofrinho_id, usuario_id, tipo, valor, descricao)
SELECT id, 2, 'deposito', 30.00, 'Redistribuição: Ônibus antigo'
FROM cofrinhos WHERE usuario_id = 2 AND nome = 'Centro + Ônibus' AND mes_referencia = @mes AND ano_referencia = @ano;

INSERT INTO cofrinho_movimentacoes (cofrinho_id, usuario_id, tipo, valor, descricao)
SELECT id, 2, 'deposito', 7.47, 'Redistribuição: saldo em conta'
FROM cofrinhos WHERE usuario_id = 2 AND nome = 'Reserva / Ajustes' AND mes_referencia = @mes AND ano_referencia = @ano;


-- =============================================
-- Marcar despesas de março que já foram pagas
-- (Lucas já recebeu e pagou)
-- =============================================

-- Aluguel já pago
UPDATE despesas SET status = 'paga', data_pagamento = '2026-03-05'
WHERE nome = 'Aluguel' AND mes_referencia = @mes AND ano_referencia = @ano;

-- Água já paga
UPDATE despesas SET status = 'paga', data_pagamento = '2026-03-04'
WHERE nome = 'Água' AND mes_referencia = @mes AND ano_referencia = @ano;

-- Energia já paga
UPDATE despesas SET status = 'paga', data_pagamento = '2026-03-05'
WHERE nome = 'Energia' AND mes_referencia = @mes AND ano_referencia = @ano;

-- Faculdade Lucas já paga
UPDATE despesas SET status = 'paga', data_pagamento = '2026-03-06'
WHERE nome = 'Faculdade Lucas' AND mes_referencia = @mes AND ano_referencia = @ano;

-- Unimed já paga
UPDATE despesas SET status = 'paga', data_pagamento = '2026-03-10'
WHERE nome = 'Unimed' AND mes_referencia = @mes AND ano_referencia = @ano;

-- Consórcio já pago
UPDATE despesas SET status = 'paga', data_pagamento = '2026-03-15'
WHERE nome = 'Consórcio Carro' AND mes_referencia = @mes AND ano_referencia = @ano;

-- Investimento já feito
UPDATE despesas SET status = 'paga', data_pagamento = '2026-03-15'
WHERE nome = 'Investimento' AND mes_referencia = @mes AND ano_referencia = @ano;

-- Gasolina já paga
UPDATE despesas SET status = 'paga', data_pagamento = '2026-03-15'
WHERE nome = 'Gasolina' AND mes_referencia = @mes AND ano_referencia = @ano;

-- Reserva já separada
UPDATE despesas SET status = 'paga', data_pagamento = '2026-03-15'
WHERE nome = 'Reserva' AND mes_referencia = @mes AND ano_referencia = @ano;

-- Celular Lucas já pago
UPDATE despesas SET status = 'paga', data_pagamento = '2026-03-10'
WHERE nome = 'Celular Lucas' AND mes_referencia = @mes AND ano_referencia = @ano;

-- Receita do Lucas marcada como recebida
UPDATE receitas SET status = 'recebida', data_recebida = '2026-03-15'
WHERE titulo = 'Salário Lucas' AND mes_referencia = @mes AND ano_referencia = @ano;
