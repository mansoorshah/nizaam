<?php ob_start(); ?>

<div class="d-flex justify-content-end align-items-center mb-4">
    <a href="<?= $this->getBaseUrl() ?>/timesheets/create" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Log Timesheet
    </a>
</div>

<!-- Timesheets List -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">My Timesheets</h5>
    </div>
    <div class="card-body">
        <?php if (empty($timesheets)): ?>
        <div class="text-center py-5 text-muted">
            <i class="bi bi-calendar-check fs-1 d-block mb-3"></i>
            <p>No timesheets found</p>
            <a href="<?= $this->getBaseUrl() ?>/timesheets/create" class="btn btn-primary mt-3">
                <i class="bi bi-plus-lg"></i> Log Your First Timesheet
            </a>
        </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Timesheet ID</th>
                        <th>Week Ending</th>
                        <th>Total Hours</th>
                        <th>Status</th>
                        <th>Submitted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($timesheets as $timesheet): ?>
                    <?php 
                        $metadata = json_decode($timesheet['metadata'], true);
                        $totalHours = $metadata['total_hours'] ?? 0;
                        $weekPeriod = $metadata['week_period'] ?? '';
                        $weekEnd = $metadata['week_end'] ?? '';
                        
                        // Calculate total hours from entries if not stored
                        if ($totalHours == 0 && isset($metadata['entries'])) {
                            foreach ($metadata['entries'] as $entry) {
                                if (isset($entry['hours']) && is_array($entry['hours'])) {
                                    $totalHours += array_sum($entry['hours']);
                                }
                            }
                        }
                    ?>
                    <tr>
                        <td><strong>#<?= $timesheet['id'] ?></strong></td>
                        <td>
                            <?php if ($weekEnd): ?>
                                <?= date('M d, Y', strtotime($weekEnd)) ?>
                            <?php elseif ($weekPeriod): ?>
                                <?= htmlspecialchars($weekPeriod) ?>
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong><?= number_format($totalHours, 1) ?></strong> hours
                            <?php if ($totalHours < 40): ?>
                                <i class="bi bi-exclamation-triangle text-warning" title="Under 40 hours"></i>
                            <?php elseif ($totalHours > 40): ?>
                                <i class="bi bi-clock-fill text-info" title="Overtime"></i>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="status-badge" style="background-color: <?= $timesheet['status_color'] ?>20; color: <?= $timesheet['status_color'] ?>;">
                                <?= htmlspecialchars($timesheet['status_name']) ?>
                            </span>
                        </td>
                        <td><?= date('M d, Y', strtotime($timesheet['created_at'])) ?></td>
                        <td>
                            <a href="<?= $this->getBaseUrl() ?>/timesheets/<?= $timesheet['id'] ?>" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i> View
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Statistics Card -->
<?php if (!empty($timesheets)): ?>
<div class="row mt-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h6 class="text-muted mb-2">This Month</h6>
                <h3 class="mb-0"><?= $stats['current_month_hours'] ?? 0 ?> hrs</h3>
                <small class="text-muted"><?= $stats['current_month_count'] ?? 0 ?> timesheets</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h6 class="text-muted mb-2">Pending Approval</h6>
                <h3 class="mb-0"><?= $stats['pending_count'] ?? 0 ?></h3>
                <small class="text-muted">awaiting manager review</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h6 class="text-muted mb-2">Average Weekly</h6>
                <h3 class="mb-0"><?= number_format($stats['avg_weekly_hours'] ?? 0, 1) ?> hrs</h3>
                <small class="text-muted">across all weeks</small>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php
$content = ob_get_clean();
$title = 'Timesheets';
require __DIR__ . '/../layout.php';
?>
