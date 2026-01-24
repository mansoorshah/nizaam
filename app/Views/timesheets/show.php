<?php ob_start(); ?>

<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= $this->getBaseUrl() ?>/timesheets">Timesheet Management</a></li>
        <li class="breadcrumb-item active">View Timesheet</li>
    </ol>
</nav>

<div class="card shadow-sm">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0"><?= htmlspecialchars($timesheet['title']) ?></h4>
            <span class="badge" style="background-color: <?= $timesheet['status_color'] ?>; font-size: 0.9rem; padding: 0.5rem 1rem;">
                <?= htmlspecialchars($timesheet['status_name']) ?>
            </span>
        </div>

        <?php
        $metadata = json_decode($timesheet['metadata'], true);
        $entries = $metadata['entries'] ?? [];
        $weekPeriod = $metadata['week_period'] ?? '';
        $weekStart = $metadata['week_start'] ?? '';
        $weekEnd = $metadata['week_end'] ?? '';
        ?>

        <!-- Week Period Info -->
        <div class="mb-4 p-3 bg-light rounded">
            <div class="row">
                <div class="col-md-4">
                    <strong>Week Period:</strong> <?= htmlspecialchars($weekPeriod) ?>
                </div>
                <div class="col-md-4">
                    <strong>Week Start:</strong> <?= date('M d, Y', strtotime($weekStart)) ?>
                </div>
                <div class="col-md-4">
                    <strong>Week End:</strong> <?= date('M d, Y', strtotime($weekEnd)) ?>
                </div>
            </div>
        </div>

        <!-- Timesheet Table -->
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 25%;">PROJECT INFO</th>
                        <th style="width: 15%;">ROLE</th>
                        <th class="text-center" style="width: 8%;">SUN</th>
                        <th class="text-center" style="width: 8%;">MON</th>
                        <th class="text-center" style="width: 8%;">TUE</th>
                        <th class="text-center" style="width: 8%;">WED</th>
                        <th class="text-center" style="width: 8%;">THU</th>
                        <th class="text-center" style="width: 8%;">FRI</th>
                        <th class="text-center" style="width: 8%;">SAT</th>
                        <th class="text-center" style="width: 10%;">TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $dayTotals = [
                        'sunday' => 0,
                        'monday' => 0,
                        'tuesday' => 0,
                        'wednesday' => 0,
                        'thursday' => 0,
                        'friday' => 0,
                        'saturday' => 0
                    ];
                    
                    foreach ($entries as $entry): 
                        $hours = $entry['hours'] ?? [];
                        $rowTotal = array_sum($hours);
                        
                        // Add to day totals
                        foreach ($hours as $day => $value) {
                            if (isset($dayTotals[$day])) {
                                $dayTotals[$day] += $value;
                            }
                        }
                        
                        // Get project name
                        $projectName = '';
                        if (!empty($entry['project_id'])) {
                            $project = $this->db->fetchOne("SELECT name FROM projects WHERE id = ?", [$entry['project_id']]);
                            $projectName = $project ? $project['name'] : 'Unknown Project';
                        } else {
                            $projectName = $entry['project_name'] ?? 'General/Overhead Work';
                        }
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($projectName) ?></td>
                        <td><?= htmlspecialchars($entry['role'] ?? '') ?></td>
                        <td class="text-center"><?= isset($hours['sunday']) ? number_format($hours['sunday'], 1) : '0' ?></td>
                        <td class="text-center"><?= isset($hours['monday']) ? number_format($hours['monday'], 1) : '0' ?></td>
                        <td class="text-center"><?= isset($hours['tuesday']) ? number_format($hours['tuesday'], 1) : '0' ?></td>
                        <td class="text-center"><?= isset($hours['wednesday']) ? number_format($hours['wednesday'], 1) : '0' ?></td>
                        <td class="text-center"><?= isset($hours['thursday']) ? number_format($hours['thursday'], 1) : '0' ?></td>
                        <td class="text-center"><?= isset($hours['friday']) ? number_format($hours['friday'], 1) : '0' ?></td>
                        <td class="text-center"><?= isset($hours['saturday']) ? number_format($hours['saturday'], 1) : '0' ?></td>
                        <td class="text-center"><strong style="color: #3b82f6;"><?= number_format($rowTotal, 1) ?></strong></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="table-light fw-bold">
                        <td colspan="2" class="text-end">TOTAL</td>
                        <td class="text-center"><?= number_format($dayTotals['sunday'], 1) ?></td>
                        <td class="text-center"><?= number_format($dayTotals['monday'], 1) ?></td>
                        <td class="text-center"><?= number_format($dayTotals['tuesday'], 1) ?></td>
                        <td class="text-center"><?= number_format($dayTotals['wednesday'], 1) ?></td>
                        <td class="text-center"><?= number_format($dayTotals['thursday'], 1) ?></td>
                        <td class="text-center"><?= number_format($dayTotals['friday'], 1) ?></td>
                        <td class="text-center"><?= number_format($dayTotals['saturday'], 1) ?></td>
                        <td class="text-center">
                            <strong style="color: #3b82f6; font-size: 1.25rem;">
                                <?= number_format(array_sum($dayTotals), 1) ?>
                            </strong>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex gap-2 mt-4">
            <a href="<?= $this->getBaseUrl() ?>/timesheets" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back to Timesheets
            </a>
        </div>
    </div>
</div>

<style>
.table th {
    background-color: #f8f9fa;
    font-weight: 600;
    font-size: 0.75rem;
    color: #6c757d;
    padding: 0.75rem 0.5rem;
}

.table td {
    padding: 0.5rem;
    vertical-align: middle;
}
</style>

<?php
$content = ob_get_clean();
$title = 'View Timesheet';
require __DIR__ . '/../layout.php';
?>
