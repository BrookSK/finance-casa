-- =============================================
-- Migration 001 - Ajuste do planejamento financeiro
-- Baseado no plano de cofrinhos e divisão do casal
-- Data: 2026-03-29
-- =============================================

USE financas_casal;

SET @mes = MONTH(CURDATE());
SET @ano = YEAR(CURDATE());

-- =============================================
-- 1) COFRINHOS BIA — Atualizar metas que estavam zeradas
-- =============================================

-- Casa / Apoio: parte da Bia nas despesas compartilhadas = R$ 870
-- (Água+Energia R$130 + Internet+TV R$90 + Fatura PicPay Casa R$650)
UPDATE cofrinhos
SET meta_mensal = 870.00,
    prioridade = 'alta',
    observacao = 'Parte da Bia: Água+Energia R$130 + Internet+TV R$90 + Fatura PicPay Casa R$650'
WHERE usuario_id = 2
  AND nome = 'Casa / Apoio'
  AND mes_referencia = @mes
  AND ano_referencia = @ano;

-- Reserva / Ajustes Bia: R$ 47 (ajustado para fechar no salário)
UPDATE cofrinhos
SET meta_mensal = 47.00,
    observacao = 'Ajustado para caber no salário de R$2.000'
WHERE usuario_id = 2
  AND nome = 'Reserva / Ajustes'
  AND mes_referencia = @mes
  AND ano_referencia = @ano;

-- =============================================
-- 2) COFRINHO LUCAS — Renomear PicPay Lucas → PicPay Casa
-- =============================================

UPDATE cofrinhos
SET nome = 'Fatura PicPay Casa',
    observacao = 'Cartão da casa/rotina. Meta total R$850 (Lucas R$200 + Bia R$650)'
WHERE usuario_id = 1
  AND nome = 'Fatura PicPay Lucas'
  AND mes_referencia = @mes
  AND ano_referencia = @ano;

-- =============================================
-- 3) VINCULAR DESPESAS AOS CARTÕES CORRETOS
-- =============================================

-- Assinaturas do Lucas → Cartão XP (id=1)
UPDATE despesas SET cartao_id = 1
WHERE nome = 'Spotify Lucas'
  AND mes_referencia = @mes AND ano_referencia = @ano;

UPDATE despesas SET cartao_id = 1
WHERE nome = 'TV da Sala'
  AND mes_referencia = @mes AND ano_referencia = @ano;

UPDATE despesas SET cartao_id = 1
WHERE nome = 'Leite'
  AND mes_referencia = @mes AND ano_referencia = @ano;

-- Celular Lucas → Cartão XP (id=1)
UPDATE despesas SET cartao_id = 1
WHERE nome = 'Celular Lucas'
  AND mes_referencia = @mes AND ano_referencia = @ano;

-- TV / Celulares → Cartão XP (id=1)
UPDATE despesas SET cartao_id = 1
WHERE nome = 'TV / Celulares'
  AND mes_referencia = @mes AND ano_referencia = @ano;

-- Assinaturas da Bia → Cartão PicPay Bia (id=4)
UPDATE despesas SET cartao_id = 4
WHERE nome = 'Spotify Bia'
  AND mes_referencia = @mes AND ano_referencia = @ano;

UPDATE despesas SET cartao_id = 4
WHERE nome = 'Google Fotos Bia'
  AND mes_referencia = @mes AND ano_referencia = @ano;

UPDATE despesas SET cartao_id = 4
WHERE nome = 'ChatGPT Bia'
  AND mes_referencia = @mes AND ano_referencia = @ano;

-- Celular Bia → Cartão PicPay Bia (id=4)
UPDATE despesas SET cartao_id = 4
WHERE nome = 'Celular Bia'
  AND mes_referencia = @mes AND ano_referencia = @ano;

-- Canva Bia → Cartão PicPay Bia (id=4)
UPDATE despesas SET cartao_id = 4
WHERE nome = 'Canva'
  AND mes_referencia = @mes AND ano_referencia = @ano;

-- =============================================
-- 4) ORÇAMENTOS — Ajustar conforme plano
-- =============================================

-- Gastos Livres: reduzir de R$500 para R$250 (compras livres/aleatórias)
UPDATE orcamentos
SET valor_limite = 250.00
WHERE categoria_id = 12
  AND mes_referencia = @mes AND ano_referencia = @ano;

-- Restaurante / Lanche: aumentar de R$200 para R$250
UPDATE orcamentos
SET valor_limite = 250.00
WHERE categoria_id = 17
  AND mes_referencia = @mes AND ano_referencia = @ano;

-- =============================================
-- 5) ADICIONAR OBSERVAÇÕES NAS DESPESAS COMPARTILHADAS
--    para indicar a divisão proporcional (Lucas 63.6% / Bia 36.4%)
-- =============================================

UPDATE despesas SET observacao = 'Compartilhado: Lucas R$636 + Bia R$364'
WHERE nome = 'Aluguel'
  AND mes_referencia = @mes AND ano_referencia = @ano;

UPDATE despesas SET observacao = 'Compartilhado: Lucas paga, Bia complementa R$130 no cofrinho Casa/Apoio'
WHERE nome = 'Água'
  AND mes_referencia = @mes AND ano_referencia = @ano;

UPDATE despesas SET observacao = 'Compartilhado: Lucas paga, Bia complementa R$130 no cofrinho Casa/Apoio'
WHERE nome = 'Energia'
  AND mes_referencia = @mes AND ano_referencia = @ano;

UPDATE despesas SET observacao = 'Compartilhado: Lucas paga, Bia complementa R$90 no cofrinho Casa/Apoio'
WHERE nome = 'Internet Casa'
  AND mes_referencia = @mes AND ano_referencia = @ano;

-- =============================================
-- 6) ADICIONAR OBSERVAÇÕES NOS COFRINHOS DO LUCAS
--    com a sequência de distribuição ao receber
-- =============================================

UPDATE cofrinhos SET observacao = 'Prioridade 1 ao receber. Vence dia 5.'
WHERE usuario_id = 1 AND nome = 'Aluguel'
  AND mes_referencia = @mes AND ano_referencia = @ano;

UPDATE cofrinhos SET observacao = 'Prioridade 2 ao receber. Água dia 4, Energia dia 5. Lucas R$250, Bia complementa R$130.'
WHERE usuario_id = 1 AND nome = 'Água + Energia'
  AND mes_referencia = @mes AND ano_referencia = @ano;

UPDATE cofrinhos SET observacao = 'Prioridade 3. Faculdade dia 6, MEI dia 20.'
WHERE usuario_id = 1 AND nome = 'Faculdade + MEI'
  AND mes_referencia = @mes AND ano_referencia = @ano;

UPDATE cofrinhos SET observacao = 'Prioridade 4. Vence dia 10.'
WHERE usuario_id = 1 AND nome = 'Unimed'
  AND mes_referencia = @mes AND ano_referencia = @ano;

UPDATE cofrinhos SET observacao = 'Prioridade 5. Consórcio dia 15, gasolina ao longo do mês.'
WHERE usuario_id = 1 AND nome = 'Consórcio + Gasolina'
  AND mes_referencia = @mes AND ano_referencia = @ano;

UPDATE cofrinhos SET observacao = 'Prioridade 6. Separar assim que receber. Não reduzir.'
WHERE usuario_id = 1 AND nome = 'Investimento'
  AND mes_referencia = @mes AND ano_referencia = @ano;

UPDATE cofrinhos SET observacao = 'Prioridade 7. Vence dia 1. Parcelas e assinaturas técnicas.'
WHERE usuario_id = 1 AND nome = 'Fatura XP'
  AND mes_referencia = @mes AND ano_referencia = @ano;

UPDATE cofrinhos SET observacao = 'Prioridade 8. Celular dia 10, Internet dia 20, TV dia 25. Lucas R$100, Bia complementa R$90.'
WHERE usuario_id = 1 AND nome = 'Internet + Celular + TV'
  AND mes_referencia = @mes AND ano_referencia = @ano;

UPDATE cofrinhos SET observacao = 'Prioridade 9. Vence dia 20. Cartão da casa. Lucas R$200, Bia R$650.'
WHERE usuario_id = 1 AND nome = 'Fatura PicPay Casa'
  AND mes_referencia = @mes AND ano_referencia = @ano;

UPDATE cofrinhos SET observacao = 'Prioridade 10. Diferença de contas, farmácia, imprevistos.'
WHERE usuario_id = 1 AND nome = 'Reserva / Ajustes'
  AND mes_referencia = @mes AND ano_referencia = @ano;

-- =============================================
-- 7) ADICIONAR OBSERVAÇÕES NOS COFRINHOS DA BIA
--    com a sequência de distribuição ao receber
-- =============================================

UPDATE cofrinhos SET observacao = 'Prioridade 1 ao receber. Vence dia 20.'
WHERE usuario_id = 2 AND nome = 'Faculdade'
  AND mes_referencia = @mes AND ano_referencia = @ano;

UPDATE cofrinhos SET observacao = 'Prioridade 2. MEI dia 20, Celular dia 10, Canva dia 20.'
WHERE usuario_id = 2 AND nome = 'MEI + Celular + Canva'
  AND mes_referencia = @mes AND ano_referencia = @ano;

UPDATE cofrinhos SET observacao = 'Prioridade 3. Assim que receber.'
WHERE usuario_id = 2 AND nome = 'Centro + Ônibus'
  AND mes_referencia = @mes AND ano_referencia = @ano;

UPDATE cofrinhos SET observacao = 'Prioridade 4. Mensal.'
WHERE usuario_id = 2 AND nome = 'Unha'
  AND mes_referencia = @mes AND ano_referencia = @ano;

UPDATE cofrinhos SET observacao = 'Prioridade 5. Hidratação R$60 + Sobrancelha R$50.'
WHERE usuario_id = 2 AND nome = 'Hidratação + Sobrancelha'
  AND mes_referencia = @mes AND ano_referencia = @ano;

UPDATE cofrinhos SET observacao = 'Prioridade 6. R$125/mês fixo. Uso a cada 2 meses (R$250 acumulado).'
WHERE usuario_id = 2 AND nome = 'Progressiva'
  AND mes_referencia = @mes AND ano_referencia = @ano;

UPDATE cofrinhos SET observacao = 'Prioridade 7. Vence dia 10. Gastos pessoais da Bia.'
WHERE usuario_id = 2 AND nome = 'Fatura PicPay Bia'
  AND mes_referencia = @mes AND ano_referencia = @ano;

UPDATE cofrinhos SET observacao = 'Prioridade 8. Spotify R$12,90 + GPT R$39,90 + Google Fotos R$9,99.'
WHERE usuario_id = 2 AND nome = 'Assinaturas'
  AND mes_referencia = @mes AND ano_referencia = @ano;

UPDATE cofrinhos SET observacao = 'Prioridade 9. Parte da Bia: Água+Energia R$130 + Internet+TV R$90 + PicPay Casa R$650.'
WHERE usuario_id = 2 AND nome = 'Casa / Apoio'
  AND mes_referencia = @mes AND ano_referencia = @ano;

UPDATE cofrinhos SET observacao = 'Prioridade 10. Sobras e emergências pequenas.'
WHERE usuario_id = 2 AND nome = 'Reserva / Ajustes'
  AND mes_referencia = @mes AND ano_referencia = @ano;

-- =============================================
-- 8) REGRAS DOS CARTÕES — Atualizar observações
-- =============================================

UPDATE cartoes SET observacao = 'APENAS parcelas, assinaturas e serviços técnicos. NÃO usar para rotina/mercado.'
WHERE nome = 'XP';

UPDATE cartoes SET observacao = 'Cartão da CASA. Mercado, restaurante, padaria, compras do casal. Teto: R$1.000/mês.'
WHERE nome = 'PicPay Lucas';

UPDATE cartoes SET observacao = 'NÃO USAR. Manutenção bancária / saque aluguel apenas.'
WHERE nome = 'Sicredi';

UPDATE cartoes SET observacao = 'Gastos pessoais da Bia. Assinaturas dela, cursos, compras pessoais.'
WHERE nome = 'PicPay Bia';

-- =============================================
-- 9) DISTRIBUIÇÃO AO RECEBER — Criar como configuração
-- =============================================

INSERT INTO configuracoes (chave, valor) VALUES
('distribuicao_lucas', 'Ao receber R$3.500:\n1. Aluguel: R$1.000\n2. Água+Energia: R$250\n3. Faculdade+MEI: R$172,86\n4. Unimed: R$300\n5. Consórcio+Gasolina: R$613,32\n6. Investimento: R$300\n7. Fatura XP: R$450\n8. Internet+Celular+TV: R$100\n9. Fatura PicPay Casa: R$200\n10. Reserva: R$150\nSobra: ~R$113,82'),
('distribuicao_bia', 'Ao receber R$2.000:\n1. Faculdade: R$237,19\n2. MEI+Celular+Canva: R$160,85\n3. Centro+Ônibus: R$110\n4. Unha: R$125\n5. Hidratação+Sobrancelha: R$110\n6. Progressiva: R$125\n7. Fatura PicPay Bia: R$150\n8. Assinaturas: R$65\n9. Casa/Apoio: R$870\n10. Reserva: R$47\nSobra: ~R$0'),
('regra_cartao_casa', 'Teto mensal PicPay Casa: R$1.000\nMercado: R$500\nRestaurante/lanche: R$250\nCompras livres: R$250\nSe bater R$1.000, PARA de passar.'),
('divisao_casal', 'Proporção: Lucas 63,6% / Bia 36,4%\nTotal compartilhado: R$2.530\nLucas: R$1.609,08\nBia: R$920,92')
ON DUPLICATE KEY UPDATE valor = VALUES(valor);
