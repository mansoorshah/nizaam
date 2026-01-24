<?php ob_start(); ?>

<div class="d-flex justify-content-end align-items-center mb-4">
    <div class="btn-group">
        <a href="<?= $this->getBaseUrl() ?>/reports/export/work-items" class="btn btn-outline-primary">
            <i class="bi bi-download"></i> Export Work Items
        </a>
        <a href="<?= $this->getBaseUrl() ?>/reports/export/workload" class="btn btn-outline-primary">
            <i class="bi bi-download"></i> Export Workload
        </a>
        <a href="<?= $this->getBaseUrl() ?>/reports/export/leaves" class="btn btn-outline-primary">
            <i class="bi bi-download"></i> Export Leave Usage
        </a>
    </div>
</div>

<!-- Work Items by Status -->
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Work Items by Status</h5>
        <small class="text-muted">Total: <?= array_sum(array_column($statusReport, 'count')) ?> items</small>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-5">
                <canvas id="statusChart" style="max-height: 300px;"></canvas>
            </div>
            <div class="col-md-7">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Count</th>
                                <th>Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total = array_sum(array_column($statusReport, 'count'));
                            foreach ($statusReport as $row): 
                                $percentage = $total > 0 ? ($row['count'] / $total) * 100 : 0;
                            ?>
                            <tr>
                                <td>
                                    <span class="status-badge" style="background-color: <?= $row['color'] ?>20; color: <?= $row['color'] ?>;">
                                        <?= htmlspecialchars($row['status_name']) ?>
                                    </span>
                                </td>
                                <td><strong><?= $row['count'] ?></strong></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 me-2" style="height: 20px;">
                                            <div class="progress-bar" style="width: <?= $percentage ?>%; background-color: <?= $row['color'] ?>;">
                                                <?= number_format($percentage, 1) ?>%
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Work Items by Type -->
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Work Items by Type</h5>
            </div>
            <div class="card-body">
                <canvas id="typeChart" style="max-height: 250px;"></canvas>
                <div class="table-responsive mt-3">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($typeReport as $row): ?>
                            <tr>
                                <td><?= ucfirst(str_replace('_', ' ', $row['type'])) ?></td>
                                <td><strong><?= $row['count'] ?></strong></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Employee Workload -->
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Top Employee Workload</h5>
            </div>
            <div class="card-body">
                <canvas id="workloadChart" style="max-height: 250px;"></canvas>
                <div class="table-responsive mt-3">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Department</th>
                                <th>Work Items</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($workloadReport as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['full_name']) ?></td>
                                <td><?= htmlspecialchars($row['department']) ?></td>
                                <td><strong><?= $row['work_item_count'] ?></strong></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Status Chart - Doughnut
const statusData = {
    labels: [<?php echo implode(',', array_map(function($r) { return "'" . addslashes($r['status_name']) . "'"; }, $statusReport)); ?>],
    datasets: [{
        data: [<?php echo implode(',', array_column($statusReport, 'count')); ?>],
        backgroundColor: [<?php echo implode(',', array_map(function($r) { return "'" . $r['color'] . "'"; }, $statusReport)); ?>],
        borderWidth: 2,
        borderColor: '#fff'
    }]
};

new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: statusData,
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 15,
                    font: { size: 12 }
                }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = ((context.parsed / total) * 100).toFixed(1);
                        return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                    }
                }
            }
        }
    }
});

// Type Chart - Bar
const typeData = {
    labels: [<?php echo implode(',', array_map(function($r) { return "'" . ucfirst(str_replace('_', ' ', $r['type'])) . "'"; }, $typeReport)); ?>],
    datasets: [{
        label: 'Count',
        data: [<?php echo implode(',', array_column($typeReport, 'count')); ?>],
        backgroundColor: 'rgba(99, 102, 241, 0.8)',
        borderColor: 'rgb(99, 102, 241)',
        borderWidth: 2
    }]
};

new Chart(document.getElementById('typeChart'), {
    type: 'bar',
    data: typeData,
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1 }
            }
        }
    }
});

// Workload Chart - Horizontal Bar
const workloadData = {
    labels: [<?php echo implode(',', array_map(function($r) { return "'" . addslashes($r['full_name']) . "'"; }, array_slice($workloadReport, 0, 10))); ?>],
    datasets: [{
        label: 'Work Items',
        data: [<?php echo implode(',', array_column(array_slice($workloadReport, 0, 10), 'work_item_count')); ?>],
        backgroundColor: 'rgba(16, 185, 129, 0.8)',
        borderColor: 'rgb(16, 185, 129)',
        borderWidth: 2
    }]
};

new Chart(document.getElementById('workloadChart'), {
    type: 'bar',
    data: workloadData,
    options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { display: false }
        },
        scales: {
            x: {
                beginAtZero: true,
                ticks: { stepSize: 1 }
            }
        }
    }
});
</script>

<?php
$content = ob_get_clean();
$title = 'Reports';
require __DIR__ . '/../layout.php';
?>
