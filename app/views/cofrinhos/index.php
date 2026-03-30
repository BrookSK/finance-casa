<?php
$mesAnterior = $mes - 1; $anoAnterior = $ano;
if ($mesAnterior < 1) { $mesAnterior = 12; $anoAnterior--; }
$mesProximo = $mes + 1; $anoProximo = $ano;
if ($mesProximo > 12) { $mesProximo = 1; $anoProximo++; }
?>

<div class="page-header">
    <span class="page-header-title">Cofrinhos</span>
    <div class="btn-group">
        <a href="/exportar/cofrinhos?mes=<?= $mes ?>&ano=<?= $ano ?>" class="btn btn-outline btn-sm" title="Exportar CSV"><i class="fas fa-download"></i></a>
        <?php if (($currentUser['papel'] ?? '') === 'admin'): ?>
        <a href="/cofrinhos/criar" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Novo</a>
        <?php endif; ?>
    </div>
</div>

<div class="month-selector">
    <a href="/cofrinhos?mes=<?= $mesAnterior ?>&ano=<?= $anoAnterior ?>"><i class="fas fa-chevron-left"></i></a>
    <span class="current-month"><?= monthName($mes) ?> <?= $ano ?></span>
    <a href="/cofrinhos?mes=<?= $mesProximo ?>&ano=<?= $anoProximo ?>"><i class="fas fa-chevron-right"></i></a>
</div>

<!-- Resumo -->
<div class="stats-grid" style="grid-template-columns: 1fr 1fr;">
    <div class="stat-card savings">
        <div class="stat-label">Total guardado</div>
        <div class="stat-value"><?= formatMoney($totalGuardado) ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Meta total</div>
        <div class="stat-value"><?= formatMoney($totalMeta) ?></div>
    </div>
</div>

<!-- Filtros -->
<div class="filter-bar">
    <a href="/cofrinhos?mes=<?= $mes ?>&ano=<?= $ano ?>" class="filter-chip <?= !$filtroUser && !$filtroTipo ? 'active' : '' ?>">Todos</a>
    <?php foreach ($usuarios as $u): ?>
    <a href="/cofrinhos?mes=<?= $mes ?>&ano=<?= $ano ?>&usuario=<?= $u['id'] ?>" class="filter-chip <?= $filtroUser == $u['id'] ? 'active' : '' ?>"><?= e($u['nome']) ?></a>
    <?php endforeach; ?>
    <a href="/cofrinhos?mes=<?= $mes ?>&ano=<?= $ano ?>&tipo=compartilhado" class="filter-chip <?= $filtroTipo === 'compartilhado' ? 'active' : '' ?>">Compartilhados</a>
</div>

<!-- Grid de cofrinhos -->
<div class="cofrinho-grid">
    <?php if (empty($cofrinhos)): ?>
        <div class="empty-state">
            <i class="fas fa-piggy-bank"></i>
            <p>Nenhum cofrinho encontrado</p>
        </div>
    <?php else: ?>
        <?php foreach ($cofrinhos as $c): ?>
        <?php
            $pct = percentual($c['valor_atual'], $c['meta_mensal']);
            $faltante = max($c['meta_mensal'] - $c['valor_atual'], 0);
            $completo = $c['valor_atual'] >= $c['meta_mensal'] && $c['meta_mensal'] > 0;
        ?>
        <div class="cofrinho-card" style="border-left-color: <?= e($c['cor']) ?>;">
            <div class="cofrinho-header">
                <span class="cofrinho-name"><?= e($c['nome']) ?></span>
                <span class="badge <?= $completo ? 'badge-success' : 'badge-warning' ?>">
                    <?= $completo ? 'Completo' : ucfirst($c['prioridade']) ?>
                </span>
            </div>
            <div style="font-size:12px;color:var(--text-secondary);">
                <?= e($c['usuario_nome'] ?? '') ?> · <?= ucfirst($c['tipo']) ?>
            </div>
            <div class="cofrinho-values">
                <span class="cofrinho-current"><?= formatMoney($c['valor_atual']) ?></span>
                <span class="cofrinho-meta">Meta: <?= formatMoney($c['meta_mensal']) ?></span>
            </div>
            <div class="cofrinho-progress">
                <div class="progress">
                    <div class="progress-bar <?= $completo ? 'success' : statusColor($pct) ?>" style="width:<?= $pct ?>%"></div>
                </div>
                <div class="cofrinho-percent"><?= $pct ?>%</div>
            </div>
            <?php if (!$completo && $faltante > 0): ?>
            <div style="font-size:12px;color:var(--danger);margin-bottom:8px;">
                Falta: <?= formatMoney($faltante) ?>
            </div>
            <?php endif; ?>

            <!-- Sub-itens do cofrinho -->
            <?php if (!empty($c['itens'])): ?>
            <div style="border-top:1px solid var(--border);padding-top:6px;margin:6px 0;">
                <?php foreach ($c['itens'] as $item): ?>
                <div style="display:flex;justify-content:space-between;font-size:12px;padding:2px 0;color:var(--text-secondary);">
                    <span><?= e($item['nome']) ?></span>
                    <span style="font-weight:600;"><?= formatMoney($item['valor']) ?></span>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            <div class="cofrinho-footer">
                <!-- Depositar -->
                <form method="POST" action="/cofrinhos/depositar/<?= $c['id'] ?>" style="display:flex;gap:4px;flex:1;">
                    <?= csrfField() ?>
                    <input type="text" name="valor" placeholder="Valor" class="form-input" style="padding:8px;font-size:12px;" data-money>
                    <input type="hidden" name="descricao" value="Depósito rápido">
                    <button type="submit" class="btn btn-success btn-sm" style="padding:8px 12px;font-size:11px;">
                        <i class="fas fa-plus"></i>
                    </button>
                </form>
                <a href="/cofrinhos/historico/<?= $c['id'] ?>" class="btn btn-outline btn-sm" style="padding:8px;" title="Histórico">
                    <i class="fas fa-history"></i>
                </a>
                <?php if (($currentUser['papel'] ?? '') === 'admin'): ?>
                <a href="/cofrinhos/editar/<?= $c['id'] ?>" class="btn btn-outline btn-sm" style="padding:8px;" title="Editar">
                    <i class="fas fa-edit"></i>
                </a>
                <form method="POST" action="/cofrinhos/excluir/<?= $c['id'] ?>" style="display:inline;" onsubmit="return confirm('Excluir o cofrinho &quot;<?= e($c['nome']) ?>&quot;? Todo o histórico será perdido.')">
                    <?= csrfField() ?>
                    <button type="submit" class="btn btn-sm" style="padding:8px;color:var(--danger);background:none;border:1px solid var(--border);" title="Excluir">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
