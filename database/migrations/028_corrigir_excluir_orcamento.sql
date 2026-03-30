-- =============================================
-- Migration 028 - Corrigir excluir_orcamento_cartao
-- A migration 027 marcou tudo como excluído por engano
-- Corrigir: tudo entra (0) exceto assinaturas/parcelas/empresa
-- Data: 2026-03-29
-- =============================================

USE financas_casal;

-- Primeiro: zerar tudo (tudo entra no orçamento)
UPDATE despesas SET excluir_orcamento_cartao = 0 WHERE cartao_id IS NOT NULL;

-- Depois: marcar o que NÃO entra
-- Assinaturas
UPDATE despesas SET excluir_orcamento_cartao = 1
WHERE cartao_id IS NOT NULL AND (
    nome LIKE 'Spotify%'
    OR nome LIKE 'ChatGPT%'
    OR nome LIKE 'Google Fotos%'
    OR nome LIKE 'Ubisoft%'
    OR nome LIKE 'Windsurf%'
    OR nome LIKE 'Canva%'
    OR nome LIKE 'Contabo%'
    OR nome LIKE 'IOF%'
    OR nome LIKE 'Quiro Pro%'
    OR nome LIKE 'Anuidade%'
    OR nome LIKE 'Boticário%'
    OR nome LIKE 'MP Niles%'
);

-- Parcelas fixas (TV, material elétrico, pia, moto)
UPDATE despesas SET excluir_orcamento_cartao = 1
WHERE cartao_id IS NOT NULL AND (
    nome LIKE 'OnExpress%'
    OR nome LIKE 'MiraWatts%'
    OR nome LIKE 'Paracatu%'
    OR nome LIKE 'Misael%'
    OR nome LIKE 'Material elétrico%'
);

-- Faturas (registro de pagamento)
UPDATE despesas SET excluir_orcamento_cartao = 1
WHERE cartao_id IS NOT NULL AND nome LIKE 'Fatura%';

-- Empresa
UPDATE despesas SET excluir_orcamento_cartao = 1
WHERE cartao_id IS NOT NULL AND proprietario = 'empresa';
