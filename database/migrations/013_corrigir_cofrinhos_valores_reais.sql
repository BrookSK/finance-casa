-- =============================================
-- Migration 013 - Corrigir cofrinhos para valores reais
-- Valores conferidos com o PicPay real em 29/03/2026
--
-- Lucas - valores reais nos cofrinhos:
--   Aluguel: R$ 1.000,00 (guardado pra pagar abril)
--   Água + Energia: R$ 480,00
--   Cartões: R$ 450,00
--   Consórcio + Gasolina: R$ 33,46
--   Unimed: R$ 300,00
--   Faculdade + MEI: R$ 86,93 (NÃO 172,86)
--   Internet + Celular + TV: R$ 190,00
--   Investimento: R$ 0
--   Mercado: R$ 0
--   Reserva / Ajustes: R$ 135,93 (NÃO 150)
--
-- Total real: R$ 2.676,32
--
-- Data: 2026-03-29
-- =============================================

USE financas_casal;

SET @mes = 3;
SET @ano = 2026;

-- =============================================
-- LUCAS - Corrigir valores para o real
-- =============================================

-- Aluguel: 0 → 1.000 (guardado pra pagar em abril)
UPDATE cofrinhos SET valor_atual = 1000.00
WHERE usuario_id = 1 AND nome = 'Aluguel'
  AND mes_referencia = @mes AND ano_referencia = @ano;

-- Água + Energia: 380 → 480 (você tem R$100 a mais)
UPDATE cofrinhos SET valor_atual = 480.00
WHERE usuario_id = 1 AND nome = 'Água + Energia'
  AND mes_referencia = @mes AND ano_referencia = @ano;

-- Faculdade + MEI: 172,86 → 86,93 (valor real)
UPDATE cofrinhos SET valor_atual = 86.93
WHERE usuario_id = 1 AND nome = 'Faculdade + MEI'
  AND mes_referencia = @mes AND ano_referencia = @ano;

-- Reserva / Ajustes: 150 → 135,93 (valor real)
UPDATE cofrinhos SET valor_atual = 135.93
WHERE usuario_id = 1 AND nome = 'Reserva / Ajustes'
  AND mes_referencia = @mes AND ano_referencia = @ano;

-- Os demais já estão corretos:
-- Cartões: 450,00 ✅
-- Consórcio + Gasolina: 33,46 ✅
-- Unimed: 300,00 ✅
-- Internet + Celular + TV: 190,00 ✅
-- Investimento: 0 ✅
-- Mercado: 0 ✅


-- =============================================
-- Corrigir movimentações para refletir valores reais
-- (limpar as movimentações erradas da 007 e registrar corretas)
-- =============================================

-- Remover movimentações antigas do Lucas em março
DELETE cm FROM cofrinho_movimentacoes cm
INNER JOIN cofrinhos c ON cm.cofrinho_id = c.id
WHERE c.usuario_id = 1 AND c.mes_referencia = @mes AND c.ano_referencia = @ano;

-- Registrar movimentações corretas
INSERT INTO cofrinho_movimentacoes (cofrinho_id, usuario_id, tipo, valor, descricao)
SELECT id, 1, 'deposito', 1000.00, 'Guardado para aluguel de abril'
FROM cofrinhos WHERE usuario_id = 1 AND nome = 'Aluguel' AND mes_referencia = @mes AND ano_referencia = @ano;

INSERT INTO cofrinho_movimentacoes (cofrinho_id, usuario_id, tipo, valor, descricao)
SELECT id, 1, 'deposito', 480.00, 'Redistribuição: Energia R$283,67 + Água R$200,24 = R$483,91 → arredondado R$480'
FROM cofrinhos WHERE usuario_id = 1 AND nome = 'Água + Energia' AND mes_referencia = @mes AND ano_referencia = @ano;

INSERT INTO cofrinho_movimentacoes (cofrinho_id, usuario_id, tipo, valor, descricao)
SELECT id, 1, 'deposito', 450.00, 'Redistribuição: antigo cofrinho Cartão de Crédito R$440,14 + ajuste R$9,86'
FROM cofrinhos WHERE usuario_id = 1 AND nome = 'Cartões' AND mes_referencia = @mes AND ano_referencia = @ano;

INSERT INTO cofrinho_movimentacoes (cofrinho_id, usuario_id, tipo, valor, descricao)
SELECT id, 1, 'deposito', 33.46, 'Redistribuição: antigo Ônibus/Gasolina R$30,24 + sobra R$3,22'
FROM cofrinhos WHERE usuario_id = 1 AND nome = 'Consórcio + Gasolina' AND mes_referencia = @mes AND ano_referencia = @ano;

INSERT INTO cofrinho_movimentacoes (cofrinho_id, usuario_id, tipo, valor, descricao)
SELECT id, 1, 'deposito', 300.00, 'Redistribuição: antigo Unimed R$300,55 → R$300'
FROM cofrinhos WHERE usuario_id = 1 AND nome = 'Unimed' AND mes_referencia = @mes AND ano_referencia = @ano;

INSERT INTO cofrinho_movimentacoes (cofrinho_id, usuario_id, tipo, valor, descricao)
SELECT id, 1, 'deposito', 86.93, 'Redistribuição: antigo UNIP R$86,93'
FROM cofrinhos WHERE usuario_id = 1 AND nome = 'Faculdade + MEI' AND mes_referencia = @mes AND ano_referencia = @ano;

INSERT INTO cofrinho_movimentacoes (cofrinho_id, usuario_id, tipo, valor, descricao)
SELECT id, 1, 'deposito', 190.00, 'Redistribuição: Internet R$221,90 + TV R$51,12 + Celular R$40,07 → R$190'
FROM cofrinhos WHERE usuario_id = 1 AND nome = 'Internet + Celular + TV' AND mes_referencia = @mes AND ano_referencia = @ano;

INSERT INTO cofrinho_movimentacoes (cofrinho_id, usuario_id, tipo, valor, descricao)
SELECT id, 1, 'deposito', 135.93, 'Redistribuição: sobras + saldo em conta R$21,46'
FROM cofrinhos WHERE usuario_id = 1 AND nome = 'Reserva / Ajustes' AND mes_referencia = @mes AND ano_referencia = @ano;
