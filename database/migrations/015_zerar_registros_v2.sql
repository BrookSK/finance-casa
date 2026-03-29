-- =============================================
-- Migration 015 - Zerar todos os registros (v2)
-- Usa DELETE ao invés de TRUNCATE para evitar erro de FK
-- =============================================

USE financas_casal;

DELETE FROM fatura_lancamentos;
DELETE FROM faturas;
DELETE FROM cofrinho_movimentacoes;
DELETE FROM cofrinhos;
DELETE FROM lista_itens;
DELETE FROM listas_compras;
DELETE FROM despesas;
DELETE FROM receitas;
DELETE FROM orcamentos;
DELETE FROM notificacoes;
DELETE FROM configuracoes;

-- Resetar auto_increment
ALTER TABLE fatura_lancamentos AUTO_INCREMENT = 1;
ALTER TABLE faturas AUTO_INCREMENT = 1;
ALTER TABLE cofrinho_movimentacoes AUTO_INCREMENT = 1;
ALTER TABLE cofrinhos AUTO_INCREMENT = 1;
ALTER TABLE lista_itens AUTO_INCREMENT = 1;
ALTER TABLE listas_compras AUTO_INCREMENT = 1;
ALTER TABLE despesas AUTO_INCREMENT = 1;
ALTER TABLE receitas AUTO_INCREMENT = 1;
ALTER TABLE orcamentos AUTO_INCREMENT = 1;
ALTER TABLE notificacoes AUTO_INCREMENT = 1;
ALTER TABLE configuracoes AUTO_INCREMENT = 1;
