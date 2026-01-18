<?php ob_start(); ?>

<div class="mb-4">
    <a href="<?= $this->getBaseUrl() ?>/leaves" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back to Leave Management
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Request Leave</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= $this->getBaseUrl() ?>/leaves/request">
                    <input type="hidden" name="csrf_token" value="<?= Session::csrfToken() ?>">
                    
                    <div class="mb-3">
                        <label for="leave_type_id" class="form-label">Leave Type <span class="text-danger">*</span></label>
                        <select class="form-select" id="leave_type_id" name="leave_type_id" required>
                            <option value="">Select leave type...</option>
                            <?php foreach ($leaveTypes as $type): ?>
                            <option value="<?= $type['id'] ?>"><?= htmlspecialchars($type['name']) ?> (<?= $type['is_paid'] ? 'Paid' : 'Unpaid' ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="start_date" name="start_date" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="end_date" class="form-label">End Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="end_date" name="end_date" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="reason" class="form-label">Reason <span class="text-danger">*</span></label>
                        <textarea id="reason" name="reason" style="display:none;" data-required="true"></textarea>
                        <div class="rich-editor" style="min-height: 150px;" data-placeholder="Explain the reason for your leave request. You can paste images if needed..."></div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send"></i> Submit Request
                        </button>
                        <a href="<?= $this->getBaseUrl() ?>/leaves" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Your Leave Balance</h6>
            </div>
            <div class="card-body">
                <?php if (empty($leaveBalances)): ?>
                <p class="text-muted">No leave balances available</p>
                <?php else: ?>
                <?php foreach ($leaveBalances as $balance): ?>
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <strong><?= htmlspecialchars($balance['leave_type_name']) ?></strong>
                        <span class="badge bg-primary"><?= $balance['remaining'] ?> days</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar" role="progressbar" style="width: <?= ($balance['remaining'] / $balance['quota']) * 100 ?>%"></div>
                    </div>
                    <small class="text-muted"><?= $balance['used'] ?> used of <?= $balance['quota'] ?> days</small>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = 'Request Leave';
require __DIR__ . '/../layout.php';
?>
