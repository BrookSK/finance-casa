<?php
$mesAnterior = $mes - 1; $anoAnterior = $ano;
if ($mesAnterior < 1) { $mesAnterior = 12; $anoAnterior--; }
$mesProximo = $mes + 1; $anoProximo = $ano;
if ($mesProximo > 12) { $mesProximo = 1; $anoProximo++; }
$saldo = $totalReceitas - $totalDespesas;
?>

<div class="page-header">
    <span class="page-header-title">Relatórios</span>
</div>

<div class="month-selector">
    <a href="/relatorios?mes=<?= $mesAnterior ?>&ano=<?= $anoAnterior ?>"><i class="fas fa-chevron-left"></i></a>
    <span class="current-month"><?= monthName($mes) ?> <?= $ano ?></span>
    <a href="/relatorios?mes=<?= $mesProximo ?>&ano=<?= $anoProximo ?>"><i class="fas fa-chevron-right"></i></a>
</div>

<!-- Resumo -->
<div class="stats-grid" style="grid-template-columns: 1fr 1fr 1fr;">
    <div class="stat-card income">
        <div class="stat-label">Receitas</div>
        <div class="stat-value positive"><?= formatMoney($totalReceitas) ?></div>
    </div>
    <div class="stat-card expense">
        <div class="stat-label">Despesas</div>
        <div class="stat-value negative"><?= formatMoney($totalDespesas) ?></div>
    </div>
    <div class="stat-card balance">
        <div class="stat-label">Saldo</div>
        <div class="stat-value <?= $saldo >= 0 ? 'positive' : 'negative' ?>"><?= formatMoney($saldo) ?></div>
    </div>
</div>

<!-- Gráfico: Receita x Despesa (últimos 6 meses) -->
<div class="card">
    <div class="card-title mb-1">Receita x Despesa (6 meses)</div>
    <div class="chart-container">
        <canvas id="chartComparativo"></canvas>
    </div>
</div>

<!-- Gráfico: Gastos por Categoria -->
<div class="card">
    <div class="card-title mb-1">Gastos por Categoria</div>
    <div class="chart-container">
        <canvas id="chartCategorias"></canvas>
    </div>
</div>

<!-- Gastos por Usuário -->
<div class="card">
    <div class="card-title mb-1">Gastos por Pessoa</div>
    <div class="chart-container">
        <canvas id="chartUsuarios"></canvas>
    </div>
</div>

<!-- Fixas vs Variáveis -->
<div class="card">
    <div class="card-title mb-1">Fixas vs Variáveis</div>
    <div class="chart-container">
        <canvas id="chartTipos"></canvas>
    </div>
</div>

<!-- Cofrinhos -->
<div class="card">
    <div class="card-title mb-1">Cofrinhos - Preenchimento</div>
    <div class="chart-container">
        <canvas id="chartCofrinhos"></canvas>
    </div>
</div>

<!-- Tabela detalhada por categoria -->
<div class="card">
    <div class="card-title mb-2">Detalhamento por Categoria</div>
    <?php if (empty($gastosPorCategoria)): ?>
        <div class="empty-state"><p>Sem dados</p></div>
    <?php else: ?>
        <?php foreach ($gastosPorCategoria as $g): ?>
        <div class="list-item">
            <div class="list-item-icon" style="background:<?= e($g['cor'] ?? '#6366f1') ?>22;color:<?= e($g['cor'] ?? '#6366f1') ?>;">
                <i class="fas fa-circle" style="font-size:10px;"></i>
            </div>
            <div class="list-item-content">
                <div class="list-item-title"><?= e($g['nome'] ?? 'Sem categoria') ?></div>
                <div class="list-item-subtitle"><?= $totalDespesas > 0 ? round(($g['total'] / $totalDespesas) * 100, 1) : 0 ?>% do total</div>
            </div>
            <div class="list-item-value">
                <div class="list-item-amount"><?= formatMoney($g['total']) ?></div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const colors = ['#6366f1','#10b981','#f59e0b','#ef4444','#3b82f6','#ec4899','#8b5cf6','#14b8a6','#f97316','#06b6d4','#d946ef','#84cc16'];

    // Comparativo 6 meses
    const compData = <?= json_encode($comparativo) ?>;
    new Chart(document.getElementById('chartComparativo'), {
        type: 'bar',
        data: {
            labels: compData.map(d => d.mes),
            datasets: [
                { label: 'Receitas', data: compData.map(d => d.receitas), backgroundColor: '#10b981' },
                { label: 'Despesas', data: compData.map(d => d.despesas), backgroundColor: '#ef4444' }
            ]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } },
            scales: { y: { beginAtZero: true } }
        }
    });

    // Categorias (doughnut)
    const catData = <?= json_encode($gastosPorCategoria) ?>;
    if (catData.length > 0) {
        new Chart(document.getElementById('chartCategorias'), {
            type: 'doughnut',
            data: {
                labels: catData.map(d => d.nome || 'Sem categoria'),
                datasets: [{
                    data: catData.map(d => d.total),
                    backgroundColor: catData.map((d, i) => d.cor || colors[i % colors.length])
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } } }
            }
        });
    }

    // Usuários
    const userData = <?= json_encode($gastosPorUsuario) ?>;
    if (userData.length > 0) {
        new Chart(document.getElementById('chartUsuarios'), {
            type: 'bar',
            data: {
                labels: userData.map(d => d.nome),
                datasets: [{ label: 'Gastos', data: userData.map(d => d.total), backgroundColor: ['#6366f1', '#ec4899', '#f59e0b'] }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, indexAxis: 'y',
                plugins: { legend: { display: false } },
                scales: { x: { beginAtZero: true } }
            }
        });
    }

    // Fixas vs Variáveis
    const tipoData = <?= json_encode($fixasVsVariaveis) ?>;
    if (tipoData.length > 0) {
        new Chart(document.getElementById('chartTipos'), {
            type: 'pie',
            data: {
                labels: tipoData.map(d => d.tipo.charAt(0).toUpperCase() + d.tipo.slice(1)),
                datasets: [{ data: tipoData.map(d => d.total), backgroundColor: ['#3b82f6', '#f59e0b', '#ef4444'] }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    }

    // Cofrinhos
    const cofData = <?= json_encode($cofrinhos) ?>;
    if (cofData.length > 0) {
        new Chart(document.getElementById('chartCofrinhos'), {
            type: 'bar',
            data: {
                labels: cofData.map(d => d.nome),
                datasets: [
                    { label: 'Guardado', data: cofData.map(d => parseFloat(d.valor_atual)), backgroundColor: '#10b981' },
                    { label: 'Meta', data: cofData.map(d => parseFloat(d.meta_mensal)), backgroundColor: '#e2e8f0' }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false, indexAxis: 'y',
                plugins: { legend: { position: 'bottom' } },
                scales: { x: { beginAtZero: true, stacked: false } }
            }
        });
    }
});
</script>
