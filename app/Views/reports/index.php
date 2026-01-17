<?php ob_start(); ?>

<h2 class="mb-4">Reports & Analytics</h2>

<!-- Work Items by Status -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Work Items by Status</h5>
    </div>
    <div class="card-body">
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

<div class="row">
    <!-- Work Items by Type -->
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Work Items by Type</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
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
                <div class="table-responsive">
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

<?php
$content = ob_get_clean();
$title = 'Reports';
require __DIR__ . '/../layout.php';
?>
