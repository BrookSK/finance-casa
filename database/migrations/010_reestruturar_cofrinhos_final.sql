-- =============================================
-- Migration 010 - Reestruturação final dos cofrinhos
-- Plano aprovado com ChatGPT em 29/03/2026
--
-- Mudanças:
-- LUCAS: Fatura XP + Fatura PicPay Casa → "Cartões" R$1.400
--        Novo slot: "Mercado" R$500
-- BIA:   Fatura PicPay Bia removida
--        Casa/Apoio: R$870 → R$950
--        Novo slot: "Extras Bia" R$50
--
-- Data: 2026-03-29
-- =============================================

USE financas_casal;

SET @mes = 3;
SET @ano = 2026;

-- =============================================
-- LUCAS: Remover cofrinhos antigos de fatura
-- =============================================

-- Guardar valores atuais antes de deletar
SET @val_xp = (SELECT COALESCE(valor_atual, 0) FROM cofrinhos
    WHERE usuario_id = 1 AND nome = 'Fatura XP' AND mes_referencia = @mes AND ano_referencia = @ano LIMIT 1);
SET @val_pp = (SELECT COALESCE(valor_atual, 0) FROM cofrinhos
    WHERE usuario_id = 1 AND nome = 'Fatura PicPay Casa' AND mes_referencia = @mes AND ano_referencia = @ano LIMIT 1);

-- Deletar cofrinhos antigos de fatura do Lucas
DELETE FROM cofrinhos
WHERE usuario_id = 1 AND nome = 'Fatura XP'
  AND mes_referencia = @mes AND ano_referencia = @ano;

DELETE FROM cofrinhos
WHERE usuario_id = 1 AND nome = 'Fatura PicPay Casa'
  AND mes_referencia = @mes AND ano_referencia = @ano;

-- Criar cofrinho "Cartões" com o valor somado dos antigos
INSERT INTO cofrinhos (usuario_id, nome, categoria_id, tipo, meta_mensal, valor_atual, prioridade, cor, mes_referencia, ano_referencia, observacao)
VALUES (1, 'Cartões', 12, 'pessoal', 1400.00, COALESCE(@val_xp, 0) + COALESCE(@val_pp, 0), 'alta', '#7c3aed', @mes, @ano,
    'TODOS os cartões: XP + PicPay Casa + Sicredi + PicPay Bia. Meta R$1.400. Lucas R$700, Bia R$500 (via Casa/Apoio). Folga R$200.');

-- Registrar movimentação
INSERT INTO cofrinho_movimentacoes (cofrinho_id, usuario_id, tipo, valor, descricao)
SELECT id, 1, 'deposito', valor_atual, 'Migração: valores dos cofrinhos Fatura XP + Fatura PicPay Casa'
FROM cofrinhos WHERE usuario_id = 1 AND nome = 'Cartões' AND mes_referencia = @mes AND ano_referencia = @ano
AND valor_atual > 0;

-- Criar cofrinho "Mercado" (slot liberado)
INSERT INTO cofrinhos (usuario_id, nome, categoria_id, tipo, meta_mensal, valor_atual, prioridade, cor, mes_referencia, ano_referencia, observacao)
VALUES (1, 'Mercado', 9, 'pessoal', 500.00, 0, 'media', '#14b8a6', @mes, @ano,
    'Orçamento mensal de mercado/supermercado. Teto R$500. Bia complementa R$180 via Casa/Apoio.');


-- =============================================
-- LUCAS: Atualizar observações dos cofrinhos existentes
-- com a nova distribuição ao receber
-- =============================================

UPDATE cofrinhos SET observacao = 'Prioridade 1. Vence dia 5. Pagar inteiro ao receber.'
WHERE usuario_id = 1 AND nome = 'Aluguel' AND mes_referencia = @mes AND ano_referencia = @ano;

UPDATE cofrinhos SET observacao = 'Prioridade 2. Água dia 4, Energia dia 5. Lucas R$250, Bia complementa R$130 via Casa/Apoio.'
WHERE usuario_id = 1 AND nome = 'Água + Energia' AND mes_referencia = @mes AND ano_referencia = @ano;

UPDATE cofrinhos SET observacao = 'Prioridade 3. Faculdade dia 6, MEI dia 20.'
WHERE usuario_id = 1 AND nome = 'Faculdade + MEI' AND mes_referencia = @mes AND ano_referencia = @ano;

UPDATE cofrinhos SET observacao = 'Prioridade 4. Vence dia 10.'
WHERE usuario_id = 1 AND nome = 'Unimed' AND mes_referencia = @mes AND ano_referencia = @ano;

UPDATE cofrinhos SET observacao = 'Prioridade 5. Consórcio dia 15, gasolina ao longo do mês.'
WHERE usuario_id = 1 AND nome = 'Consórcio + Gasolina' AND mes_referencia = @mes AND ano_referencia = @ano;

UPDATE cofrinhos SET observacao = 'Prioridade 6. Separar assim que receber. Não reduzir.'
WHERE usuario_id = 1 AND nome = 'Investimento' AND mes_referencia = @mes AND ano_referencia = @ano;

UPDATE cofrinhos SET observacao = 'Prioridade 8. Celular dia 10, Internet dia 20, TV dia 25. Lucas R$100, Bia R$90 via Casa/Apoio.'
WHERE usuario_id = 1 AND nome = 'Internet + Celular + TV' AND mes_referencia = @mes AND ano_referencia = @ano;

UPDATE cofrinhos SET observacao = 'Prioridade 9. Diferença de contas, farmácia, imprevistos. Lucas R$50, Bia R$50 via Casa/Apoio.'
WHERE usuario_id = 1 AND nome = 'Reserva / Ajustes' AND mes_referencia = @mes AND ano_referencia = @ano;


-- =============================================
-- BIA: Remover cofrinho de fatura e reorganizar
-- =============================================

-- Guardar valor atual
SET @val_bia_fatura = (SELECT COALESCE(valor_atual, 0) FROM cofrinhos
    WHERE usuario_id = 2 AND nome = 'Fatura PicPay Bia' AND mes_referencia = @mes AND ano_referencia = @ano LIMIT 1);

-- Deletar cofrinho de fatura da Bia
DELETE FROM cofrinhos
WHERE usuario_id = 2 AND nome = 'Fatura PicPay Bia'
  AND mes_referencia = @mes AND ano_referencia = @ano;

-- Atualizar Casa/Apoio: R$870 → R$950
-- (agora inclui: Água+Energia R$130 + Internet+TV R$90 + Cartões R$500 + Mercado R$180 + Reserva R$50)
UPDATE cofrinhos
SET meta_mensal = 950.00,
    valor_atual = valor_atual + COALESCE(@val_bia_fatura, 0),
    observacao = 'Prioridade 8. Parte da Bia nos compartilhados:\nÁgua+Energia R$130\nInternet+TV R$90\nCartões R$500\nMercado R$180\nReserva/Ajuste R$50\nTotal: R$950'
WHERE usuario_id = 2 AND nome = 'Casa / Apoio'
  AND mes_referencia = @mes AND ano_referencia = @ano;

-- Criar cofrinho "Extras Bia" (slot liberado)
INSERT INTO cofrinhos (usuario_id, nome, categoria_id, tipo, meta_mensal, valor_atual, prioridade, cor, mes_referencia, ano_referencia, observacao)
VALUES (2, 'Extras Bia', 12, 'pessoal', 50.00, 0, 'baixa', '#f472b6', @mes, @ano,
    'Prioridade 10. Pessoal/extras/lazer. Liberdade sem bagunçar orçamento.');

-- Atualizar observações da Bia
UPDATE cofrinhos SET observacao = 'Prioridade 1. Vence dia 20.'
WHERE usuario_id = 2 AND nome = 'Faculdade' AND mes_referencia = @mes AND ano_referencia = @ano;

UPDATE cofrinhos SET observacao = 'Prioridade 2. MEI dia 20, Celular dia 10, Canva dia 20.'
WHERE usuario_id = 2 AND nome = 'MEI + Celular + Canva' AND mes_referencia = @mes AND ano_referencia = @ano;

UPDATE cofrinhos SET observacao = 'Prioridade 3. Assim que receber.'
WHERE usuario_id = 2 AND nome = 'Centro + Ônibus' AND mes_referencia = @mes AND ano_referencia = @ano;

UPDATE cofrinhos SET observacao = 'Prioridade 4. Mensal.'
WHERE usuario_id = 2 AND nome = 'Unha' AND mes_referencia = @mes AND ano_referencia = @ano;

UPDATE cofrinhos SET observacao = 'Prioridade 5. Hidratação R$60 + Sobrancelha R$50.'
WHERE usuario_id = 2 AND nome = 'Hidratação + Sobrancelha' AND mes_referencia = @mes AND ano_referencia = @ano;

UPDATE cofrinhos SET observacao = 'Prioridade 6. R$125/mês fixo. Uso a cada 2 meses.'
WHERE usuario_id = 2 AND nome = 'Progressiva' AND mes_referencia = @mes AND ano_referencia = @ano;

UPDATE cofrinhos SET observacao = 'Prioridade 7. Spotify R$12,90 + GPT R$39,90 + Google Fotos R$9,99.'
WHERE usuario_id = 2 AND nome = 'Assinaturas' AND mes_referencia = @mes AND ano_referencia = @ano;

UPDATE cofrinhos SET observacao = 'Prioridade 9. Sobras e emergências pequenas.'
WHERE usuario_id = 2 AND nome = 'Reserva / Ajustes' AND mes_referencia = @mes AND ano_referencia = @ano;


-- =============================================
-- Atualizar configurações com novo plano de distribuição
-- =============================================

UPDATE configuracoes SET valor = 'Ao receber R$3.500:\n1. Aluguel: R$1.000\n2. Água+Energia: R$250 (Bia complementa R$130)\n3. Faculdade+MEI: R$172,86\n4. Unimed: R$300\n5. Consórcio+Gasolina: R$613,32\n6. Investimento: R$300\n7. Cartões: R$700 (Bia complementa R$500)\n8. Internet+Celular+TV: R$100 (Bia complementa R$90)\n9. Reserva: R$50 (Bia complementa R$50)\n10. Mercado: R$0 (Bia cobre R$180)\nTotal Lucas: R$3.486,18\nSobra: ~R$13,82'
WHERE chave = 'distribuicao_lucas';

UPDATE configuracoes SET valor = 'Ao receber R$2.000:\n1. Faculdade: R$237,19\n2. MEI+Celular+Canva: R$160,85\n3. Centro+Ônibus: R$110\n4. Unha: R$125\n5. Hidratação+Sobrancelha: R$110\n6. Progressiva: R$125\n7. Assinaturas: R$65\n8. Casa/Apoio: R$950 (Água R$130 + Internet R$90 + Cartões R$500 + Mercado R$180 + Reserva R$50)\n9. Reserva: R$47\n10. Extras Bia: R$50\nTotal Bia: R$1.980,04\nSobra: ~R$19,96'
WHERE chave = 'distribuicao_bia';

UPDATE configuracoes SET valor = 'Proporção: Lucas 63,6% / Bia 36,4%\nTotal compartilhado: ~R$2.530\nCartões: Lucas R$700 + Bia R$500 = R$1.200 (meta R$1.400, folga R$200 do giro)\nMercado: Bia R$180 via Casa/Apoio\nÁgua+Energia: Lucas R$250 + Bia R$130\nInternet+TV: Lucas R$100 + Bia R$90'
WHERE chave = 'divisao_casal';
