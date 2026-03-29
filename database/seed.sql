-- =============================================
-- FinançasCasal - Seed de dados iniciais
-- Rodar APÓS o schema.sql
-- Senhas: lucas123 e bia123 (hash bcrypt)
-- =============================================

USE financas_casal;

-- Usuários
-- IMPORTANTE: Após rodar este seed, faça login e troque as senhas.
-- Senha padrão para ambos: "password" (hash bcrypt padrão do Laravel)
-- Ou use o script PHP abaixo para gerar novos hashes:
-- php -r "echo password_hash('suasenha', PASSWORD_DEFAULT);"
INSERT INTO usuarios (nome, email, senha, papel) VALUES
('Lucas', 'lucas@financas.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Bia', 'bia@financas.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'usuario');

-- Categorias
INSERT INTO categorias (nome, tipo, cor, icone) VALUES
('Salário', 'receita', '#10b981', 'wallet'),
('Freelance', 'receita', '#06b6d4', 'briefcase'),
('Empresa', 'receita', '#8b5cf6', 'building'),
('Moradia', 'despesa', '#ef4444', 'home'),
('Saúde', 'despesa', '#f43f5e', 'heart'),
('Educação', 'despesa', '#3b82f6', 'book'),
('Transporte', 'despesa', '#f59e0b', 'car'),
('Alimentação', 'despesa', '#22c55e', 'utensils'),
('Mercado', 'despesa', '#14b8a6', 'shopping-cart'),
('Assinaturas', 'despesa', '#a855f7', 'tv'),
('Beleza / Autocuidado', 'despesa', '#ec4899', 'sparkles'),
('Gastos Livres', 'despesa', '#6366f1', 'credit-card'),
('Investimento', 'despesa', '#0ea5e9', 'trending-up'),
('Casa', 'despesa', '#d97706', 'home'),
('Empresa Despesa', 'despesa', '#7c3aed', 'building'),
('Telecomunicações', 'despesa', '#0891b2', 'phone'),
('Restaurante / Lanche', 'despesa', '#fb923c', 'coffee');

-- Cartões de crédito
INSERT INTO cartoes (usuario_id, nome, bandeira, limite_total, dia_fechamento, dia_vencimento, cor, observacao) VALUES
(1, 'XP', 'Visa', 5000.00, 22, 1, '#00c853', 'Assinaturas, parcelas, serviços técnicos'),
(1, 'PicPay Lucas', 'Visa', 3000.00, 14, 20, '#00e676', 'Casa, mercado, rotina'),
(1, 'Sicredi', 'Mastercard', 2000.00, 10, 20, '#4caf50', 'Evitar uso / manutenção bancária'),
(2, 'PicPay Bia', 'Visa', 1500.00, 1, 10, '#e040fb', 'Gastos pessoais Bia');
