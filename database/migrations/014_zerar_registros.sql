-- =============================================
-- Migration 014 - Zerar todos os registros
-- Mantém: usuários, categorias, cartões (cadastro),
--         contas bancárias, estrutura das tabelas
-- Zera: receitas, despesas, cofrinhos, faturas,
--       lançamentos, orçamentos, listas, notificações,
--       configurações, movimentações
-- Data: 2026-03-29
-- =============================================

USE financas_casal;

-- Desabilitar verificação de FK temporariamente
SET FOREIGN_KEY_CHECKS = 0;

-- Zerar tudo
TRUNCATE TABLE fatura_lancamentos;
TRUNCATE TABLE faturas;
TRUNCATE TABLE cofrinho_movimentacoes;
TRUNCATE TABLE cofrinhos;
TRUNCATE TABLE lista_itens;
TRUNCATE TABLE listas_compras;
TRUNCATE TABLE despesas;
TRUNCATE TABLE receitas;
TRUNCATE TABLE orcamentos;
TRUNCATE TABLE notificacoes;
TRUNCATE TABLE configuracoes;

-- Reabilitar FK
SET FOREIGN_KEY_CHECKS = 1;

-- Confirmar o que ficou
-- SELECT 'usuarios' as tabela, COUNT(*) as registros FROM usuarios
-- UNION SELECT 'categorias', COUNT(*) FROM categorias
-- UNION SELECT 'cartoes', COUNT(*) FROM cartoes
-- UNION SELECT 'contas_bancarias', COUNT(*) FROM contas_bancarias
-- UNION SELECT 'receitas', COUNT(*) FROM receitas
-- UNION SELECT 'despesas', COUNT(*) FROM despesas
-- UNION SELECT 'cofrinhos', COUNT(*) FROM cofrinhos
-- UNION SELECT 'faturas', COUNT(*) FROM faturas;
