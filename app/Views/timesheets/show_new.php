<?php ob_start(); ?>

<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= dirname($_SERVER['SCRIPT_NAME']) === '/' ? '' : dirname($_SERVER['SCRIPT_NAME']) ?>/timesheets">Timesheet Management</a></li>
        <li class="breadcrumb-item active">View Timesheet</li>
    </ol>
</nav>

<?php
$metadata = json_decode($timesheet['metadata'], true);
$entries = $metadata['entries'] ?? [];
$weekPeriod = $metadata['week_period'] ?? '';
$totalHours = $metadata['total_hours'] ?? 0;

// Parse week period (format: 2026-W04)
$weekStart = '';
$weekEnd = '';
if (!empty($weekPeriod)) {
    $year = (int)substr($weekPeriod, 0, 4);
    $week = (int)substr($weekPeriod, 6);
    $weekStartDate = new DateTime();
    $weekStartDate->setISODate($year, $week);
    $weekEndDate = clone $weekStartDate;
    $weekEndDate->modify('+6 days');
    $weekStart = $weekStartDate->format('M d, Y');
    $weekEnd = $weekEndDate->format('M d, Y');
} else {
    // Fallback to metadata dates if available
    $weekStart = !empty($metadata['week_start']) ? date('M d, Y', strtotime($metadata['week_start'])) : 'N/A';
    $weekEnd = !empty($metadata['week_end']) ? date('M d, Y', strtotime($metadata['week_end'])) : 'N/A';
}
?>

<div class="card" data-aos="fade-up">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <i class="bi bi-clock-history me-2"></i>
                Timesheet: Week ending <?= $weekEnd ?> (<?= number_format($totalHours, 0) ?> hours)
            </div>
            <span class="badge" style="background-color: <?= $timesheet['status_color'] ?>; font-size: 0.85rem; padding: 0.4rem 0.8rem;">
                <?= htmlspecialchars($timesheet['status_name']) ?>
            </span>
        </div>
    </div>
    <div class="card-body p-4">
        <div class="mb-4 p-3 rounded" style="background-color: rgba(59, 130, 246, 0.15); border: 1px solid rgba(59, 130, 246, 0.3);">
            <div style="color: var(--bs-body-color);">
                <i class="bi bi-calendar-range me-2"></i>
                <strong>Week Period:</strong> <?= $weekStart ?> - <?= $weekEnd ?>
            </div>
        </div>

        <!-- Timesheet Table -->
        <div class="table-responsive mb-4">
            <table class="table table-hover align-middle" id="timesheetTable">
                <thead>
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
                        $rowTotal = 0;
                        
                        // Calculate row total and add to day totals
                        foreach ($hours as $day => $value) {
                            $rowTotal += (float)$value;
                            if (isset($dayTotals[$day])) {
                                $dayTotals[$day] += (float)$value;
                            }
                        }
                        
                        // Get project name
                        $projectName = '';
                        if (!empty($entry['project_id'])) {
                            $projectModel = new Project();
                            $project = $projectModel->db->fetchOne("SELECT name FROM projects WHERE id = ?", [$entry['project_id']]);
                            $projectName = $project ? $project['name'] : 'Unknown Project';
                        } else {
                            $projectName = $entry['project_name'] ?? 'General/Overhead Work';
                        }
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($projectName) ?></td>
                        <td><?= htmlspecialchars($entry['role'] ?? '') ?></td>
                        <td class="text-center"><?= isset($hours['sunday']) ? number_format($hours['sunday'], 1) : '0.0' ?></td>
                        <td class="text-center"><?= isset($hours['monday']) ? number_format($hours['monday'], 1) : '0.0' ?></td>
                        <td class="text-center"><?= isset($hours['tuesday']) ? number_format($hours['tuesday'], 1) : '0.0' ?></td>
                        <td class="text-center"><?= isset($hours['wednesday']) ? number_format($hours['wednesday'], 1) : '0.0' ?></td>
                        <td class="text-center"><?= isset($hours['thursday']) ? number_format($hours['thursday'], 1) : '0.0' ?></td>
                        <td class="text-center"><?= isset($hours['friday']) ? number_format($hours['friday'], 1) : '0.0' ?></td>
                        <td class="text-center"><?= isset($hours['saturday']) ? number_format($hours['saturday'], 1) : '0.0' ?></td>
                        <td class="text-center"><strong style="color: #3b82f6; font-size: 1.1rem;"><?= number_format($rowTotal, 0) ?></strong></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="fw-bold" style="background-color: rgba(59, 130, 246, 0.1);">
                        <td colspan="2" class="text-end">TOTAL</td>
                        <td class="text-center"><?= number_format($dayTotals['sunday'], 0) ?></td>
                        <td class="text-center"><?= number_format($dayTotals['monday'], 0) ?></td>
                        <td class="text-center"><?= number_format($dayTotals['tuesday'], 0) ?></td>
                        <td class="text-center"><?= number_format($dayTotals['wednesday'], 0) ?></td>
                        <td class="text-center"><?= number_format($dayTotals['thursday'], 0) ?></td>
                        <td class="text-center"><?= number_format($dayTotals['friday'], 0) ?></td>
                        <td class="text-center"><?= number_format($dayTotals['saturday'], 0) ?></td>
                        <td class="text-center">
                            <strong style="color: #3b82f6; font-size: 1.25rem;">
                                <?= number_format(array_sum($dayTotals), 0) ?>
                            </strong>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex gap-2">
            <a href="<?= dirname($_SERVER['SCRIPT_NAME']) === '/' ? '' : dirname($_SERVER['SCRIPT_NAME']) ?>/timesheets" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back to Timesheets
            </a>
        </div>
    </div>
</div>

<style>
.table th {
    background-color: rgba(59, 130, 246, 0.15);
    font-weight: 600;
    font-size: 0.75rem;
    color: var(--bs-body-color);
    padding: 0.75rem 0.5rem;
    border-color: rgba(59, 130, 246, 0.2);
}

.table td {
    padding: 0.5rem;
    vertical-align: middle;
    font-size: 0.9rem;
    border-color: rgba(59, 130, 246, 0.15);
}

.table tfoot tr {
    border-top: 2px solid rgba(59, 130, 246, 0.3);
}

.table tbody tr:hover {
    background-color: rgba(59, 130, 246, 0.08);
}

.badge {
    font-size: 0.75rem;
    padding: 0.35rem 0.65rem;
}
</style>

<?php
$content = ob_get_clean();
$title = 'View Timesheet';
require __DIR__ . '/../layout.php';
?>
