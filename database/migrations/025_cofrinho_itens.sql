-- =============================================
-- Migration 025 - Sub-itens dos cofrinhos
-- Permite detalhar a composição de cada cofrinho
-- Ex: "Hidratação + Sobrancelha" → Hidratação R$60 + Sobrancelha R$50
-- Data: 2026-03-29
-- =============================================

USE financas_casal;

CREATE TABLE IF NOT EXISTS cofrinho_itens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cofrinho_id INT NOT NULL,
    nome VARCHAR(100) NOT NULL,
    valor DECIMAL(10,2) NOT NULL DEFAULT 0,
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cofrinho_id) REFERENCES cofrinhos(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =============================================
-- Preencher sub-itens dos cofrinhos compostos
-- =============================================

-- LUCAS: Água + Energia
INSERT INTO cofrinho_itens (cofrinho_id, nome, valor)
SELECT id, 'Água', 100.00 FROM cofrinhos WHERE usuario_id = 1 AND nome = 'Água + Energia' AND mes_referencia = 4 AND ano_referencia = 2026;
INSERT INTO cofrinho_itens (cofrinho_id, nome, valor)
SELECT id, 'Energia', 280.00 FROM cofrinhos WHERE usuario_id = 1 AND nome = 'Água + Energia' AND mes_referencia = 4 AND ano_referencia = 2026;

-- LUCAS: Faculdade + MEI
INSERT INTO cofrinho_itens (cofrinho_id, nome, valor)
SELECT id, 'Faculdade Lucas', 86.81 FROM cofrinhos WHERE usuario_id = 1 AND nome = 'Faculdade + MEI' AND mes_referencia = 4 AND ano_referencia = 2026;
INSERT INTO cofrinho_itens (cofrinho_id, nome, valor)
SELECT id, 'MEI Lucas', 86.05 FROM cofrinhos WHERE usuario_id = 1 AND nome = 'Faculdade + MEI' AND mes_referencia = 4 AND ano_referencia = 2026;

-- LUCAS: Consórcio + Gasolina
INSERT INTO cofrinho_itens (cofrinho_id, nome, valor)
SELECT id, 'Consórcio Carro', 533.32 FROM cofrinhos WHERE usuario_id = 1 AND nome = 'Consórcio + Gasolina' AND mes_referencia = 4 AND ano_referencia = 2026;
INSERT INTO cofrinho_itens (cofrinho_id, nome, valor)
SELECT id, 'Gasolina', 80.00 FROM cofrinhos WHERE usuario_id = 1 AND nome = 'Consórcio + Gasolina' AND mes_referencia = 4 AND ano_referencia = 2026;

-- LUCAS: Cartões + Mercado
INSERT INTO cofrinho_itens (cofrinho_id, nome, valor)
SELECT id, 'Fatura XP', 450.00 FROM cofrinhos WHERE usuario_id = 1 AND nome = 'Cartões + Mercado' AND mes_referencia = 4 AND ano_referencia = 2026;
INSERT INTO cofrinho_itens (cofrinho_id, nome, valor)
SELECT id, 'Fatura PicPay Casa', 850.00 FROM cofrinhos WHERE usuario_id = 1 AND nome = 'Cartões + Mercado' AND mes_referencia = 4 AND ano_referencia = 2026;
INSERT INTO cofrinho_itens (cofrinho_id, nome, valor)
SELECT id, 'Fatura PicPay Bia', 150.00 FROM cofrinhos WHERE usuario_id = 1 AND nome = 'Cartões + Mercado' AND mes_referencia = 4 AND ano_referencia = 2026;
INSERT INTO cofrinho_itens (cofrinho_id, nome, valor)
SELECT id, 'Mercado', 500.00 FROM cofrinhos WHERE usuario_id = 1 AND nome = 'Cartões + Mercado' AND mes_referencia = 4 AND ano_referencia = 2026;

-- LUCAS: Internet + Celular + TV
INSERT INTO cofrinho_itens (cofrinho_id, nome, valor)
SELECT id, 'Internet Casa', 100.00 FROM cofrinhos WHERE usuario_id = 1 AND nome = 'Internet + Celular + TV' AND mes_referencia = 4 AND ano_referencia = 2026;
INSERT INTO cofrinho_itens (cofrinho_id, nome, valor)
SELECT id, 'Celular Lucas', 40.00 FROM cofrinhos WHERE usuario_id = 1 AND nome = 'Internet + Celular + TV' AND mes_referencia = 4 AND ano_referencia = 2026;
INSERT INTO cofrinho_itens (cofrinho_id, nome, valor)
SELECT id, 'TV / Celulares', 50.00 FROM cofrinhos WHERE usuario_id = 1 AND nome = 'Internet + Celular + TV' AND mes_referencia = 4 AND ano_referencia = 2026;

-- BIA: MEI + Celular
INSERT INTO cofrinho_itens (cofrinho_id, nome, valor)
SELECT id, 'MEI Bia', 86.05 FROM cofrinhos WHERE usuario_id = 2 AND nome = 'MEI + Celular' AND mes_referencia = 4 AND ano_referencia = 2026;
INSERT INTO cofrinho_itens (cofrinho_id, nome, valor)
SELECT id, 'Celular Bia', 39.90 FROM cofrinhos WHERE usuario_id = 2 AND nome = 'MEI + Celular' AND mes_referencia = 4 AND ano_referencia = 2026;

-- BIA: Centro + Ônibus
INSERT INTO cofrinho_itens (cofrinho_id, nome, valor)
SELECT id, 'Centro', 80.00 FROM cofrinhos WHERE usuario_id = 2 AND nome = 'Centro + Ônibus' AND mes_referencia = 4 AND ano_referencia = 2026;
INSERT INTO cofrinho_itens (cofrinho_id, nome, valor)
SELECT id, 'Ônibus', 30.00 FROM cofrinhos WHERE usuario_id = 2 AND nome = 'Centro + Ônibus' AND mes_referencia = 4 AND ano_referencia = 2026;

-- BIA: Hidratação + Sobrancelha
INSERT INTO cofrinho_itens (cofrinho_id, nome, valor)
SELECT id, 'Hidratação', 60.00 FROM cofrinhos WHERE usuario_id = 2 AND nome = 'Hidratação + Sobrancelha' AND mes_referencia = 4 AND ano_referencia = 2026;
INSERT INTO cofrinho_itens (cofrinho_id, nome, valor)
SELECT id, 'Sobrancelha', 50.00 FROM cofrinhos WHERE usuario_id = 2 AND nome = 'Hidratação + Sobrancelha' AND mes_referencia = 4 AND ano_referencia = 2026;
