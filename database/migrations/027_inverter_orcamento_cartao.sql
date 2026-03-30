-- =============================================
-- Migration 027 - Inverter lógica do orçamento do cartão
-- Renomear campo: entra_orcamento_cartao → excluir_orcamento_cartao
-- Antes: 1 = entra, 0 = não entra (maioria era 0)
-- Agora: 1 = NÃO entra, 0 = entra (maioria fica 0 = entra)
--
-- Assinaturas e parcelas fixas = excluir (1)
-- Compras do dia a dia = entra (0, padrão)
-- Data: 2026-03-29
-- =============================================

USE financas_casal;

ALTER TABLE despesas
ADD COLUMN excluir_orcamento_cartao TINYINT(1) NOT NULL DEFAULT 0
AFTER entra_orcamento_cartao;

-- Migrar dados: o que tinha entra=0 agora fica excluir=1 (e vice-versa)
-- Mas só para despesas de cartão
UPDATE despesas SET excluir_orcamento_cartao = 1
WHERE cartao_id IS NOT NULL AND entra_orcamento_cartao = 0;

UPDATE despesas SET excluir_orcamento_cartao = 0
WHERE cartao_id IS NOT NULL AND entra_orcamento_cartao = 1;

-- Marcar assinaturas como excluídas do orçamento
UPDATE despesas SET excluir_orcamento_cartao = 1
WHERE nome LIKE 'Spotify%' OR nome LIKE 'ChatGPT%' OR nome LIKE 'Google Fotos%'
   OR nome LIKE 'Ubisoft%' OR nome LIKE 'Windsurf%' OR nome LIKE 'Canva%'
   OR nome LIKE 'Contabo%' OR nome LIKE 'Quiro Pro%'
   OR nome LIKE 'Anuidade%' OR nome LIKE 'IOF%'
   OR nome LIKE 'OnExpress%' OR nome LIKE 'MiraWatts%'
   OR nome LIKE 'Paracatu%' OR nome LIKE 'Misael%'
   OR nome LIKE 'Fatura%';

-- Remover coluna antiga
ALTER TABLE despesas DROP COLUMN entra_orcamento_cartao;
