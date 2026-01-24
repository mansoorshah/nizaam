<?php ob_start(); ?>

<div class="d-flex justify-content-end align-items-center mb-4">
    <div class="btn-group">
        <a href="<?= $this->getBaseUrl() ?>/leaves" class="btn btn-primary">
            <i class="bi bi-list"></i> List View
        </a>
        <a href="<?= $this->getBaseUrl() ?>/leaves/calendar" class="btn btn-outline-secondary">
            <i class="bi bi-calendar"></i> Calendar View
        </a>
        <a href="<?= $this->getBaseUrl() ?>/leaves/request" class="btn btn-outline-primary">
            <i class="bi bi-plus-lg"></i> Request Leave
        </a>
    </div>
</div>

<!-- Leave Balances -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Your Leave Balance (<?= date('Y') ?>)</h5>
    </div>
    <div class="card-body">
        <?php if (empty($leaveBalances)): ?>
        <p class="text-muted">No leave balances available</p>
        <?php else: ?>
        <div class="row">
            <?php foreach ($leaveBalances as $balance): ?>
            <div class="col-md-3 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title"><?= htmlspecialchars($balance['leave_type_name']) ?></h6>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <h3 class="mb-0"><?= $balance['remaining'] ?></h3>
                                <small class="text-muted">days remaining</small>
                            </div>
                            <div class="text-end">
                                <div class="text-muted small">Used: <?= $balance['used'] ?></div>
                                <div class="text-muted small">Total: <?= $balance['quota'] ?></div>
                            </div>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" role="progressbar" 
                                 style="width: <?= ($balance['remaining'] / $balance['quota']) * 100 ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Leave Requests -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">My Leave Requests</h5>
    </div>
    <div class="card-body">
        <?php if (empty($leaveRequests)): ?>
        <div class="text-center py-5 text-muted">
            <i class="bi bi-calendar-x fs-1 d-block mb-3"></i>
            <p>No leave requests found</p>
        </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Request ID</th>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Submitted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($leaveRequests as $request): ?>
                    <tr>
                        <td><strong>#<?= $request['id'] ?></strong></td>
                        <td><?= htmlspecialchars($request['title']) ?></td>
                        <td>
                            <span class="status-badge" style="background-color: <?= $request['status_color'] ?>20; color: <?= $request['status_color'] ?>;">
                                <?= htmlspecialchars($request['status_name']) ?>
                            </span>
                        </td>
                        <td><?= date('M d, Y', strtotime($request['created_at'])) ?></td>
                        <td>
                            <a href="<?= $this->getBaseUrl() ?>/work-items/<?= $request['id'] ?>" class="btn btn-sm btn-outline-primary">
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

<?php
$content = ob_get_clean();
$title = 'Leave Management';
require __DIR__ . '/../layout.php';
?>
