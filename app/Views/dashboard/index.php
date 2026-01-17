<?php ob_start(); ?>

<div class="row">
    <div class="col-12">
        <h2 class="mb-4">Welcome back, <?= htmlspecialchars($this->getCurrentEmployee()['full_name'] ?? 'User') ?></h2>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">Assigned to Me</div>
                        <h3 class="mb-0"><?= count($myWorkItems) ?></h3>
                    </div>
                    <div class="fs-1 text-primary">
                        <i class="bi bi-list-check"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">Created by Me</div>
                        <h3 class="mb-0"><?= count($createdByMe) ?></h3>
                    </div>
                    <div class="fs-1 text-success">
                        <i class="bi bi-plus-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">Notifications</div>
                        <h3 class="mb-0"><?= $unreadCount ?></h3>
                    </div>
                    <div class="fs-1 text-warning">
                        <i class="bi bi-bell"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">Active Projects</div>
                        <h3 class="mb-0"><?= count($projects) ?></h3>
                    </div>
                    <div class="fs-1 text-info">
                        <i class="bi bi-folder"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Quick Actions</h5>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="<?= $this->getBaseUrl() ?>/work-items/create" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i> Create Task
                    </a>
                    <a href="<?= $this->getBaseUrl() ?>/leaves/request" class="btn btn-outline-primary">
                        <i class="bi bi-calendar-check"></i> Request Leave
                    </a>
                    <a href="<?= $this->getBaseUrl() ?>/expenses/create" class="btn btn-outline-primary">
                        <i class="bi bi-receipt"></i> Submit Expense
                    </a>
                    <a href="<?= $this->getBaseUrl() ?>/timesheets/create" class="btn btn-outline-primary">
                        <i class="bi bi-clock"></i> Log Time
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- My Work Items -->
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>My Work Items</span>
                <a href="<?= $this->getBaseUrl() ?>/work-items" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                <?php if (empty($myWorkItems)): ?>
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                    <p>No work items assigned to you</p>
                </div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Due Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_slice($myWorkItems, 0, 10) as $item): ?>
                            <tr onclick="window.location='<?= $this->getBaseUrl() ?>/work-items/<?= $item['id'] ?>'" style="cursor: pointer;">
                                <td>
                                    <strong><?= htmlspecialchars($item['title']) ?></strong>
                                </td>
                                <td>
                                    <span class="badge bg-secondary"><?= ucfirst(str_replace('_', ' ', $item['type'])) ?></span>
                                </td>
                                <td>
                                    <?php
                                    $priorityColors = ['low' => 'info', 'medium' => 'primary', 'high' => 'warning', 'urgent' => 'danger'];
                                    $color = $priorityColors[$item['priority']] ?? 'secondary';
                                    ?>
                                    <span class="badge bg-<?= $color ?>"><?= ucfirst($item['priority']) ?></span>
                                </td>
                                <td>
                                    <span class="status-badge" style="background-color: <?= $item['status_color'] ?>20; color: <?= $item['status_color'] ?>;">
                                        <?= htmlspecialchars($item['status_name']) ?>
                                    </span>
                                </td>
                                <td><?= $item['due_date'] ? date('M d, Y', strtotime($item['due_date'])) : '-' ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Recent Notifications -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Recent Notifications</span>
                <a href="<?= $this->getBaseUrl() ?>/notifications" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                <?php if (empty($notifications)): ?>
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-bell-slash fs-3 d-block mb-2"></i>
                    <p class="small mb-0">No notifications</p>
                </div>
                <?php else: ?>
                <div class="list-group list-group-flush">
                    <?php foreach ($notifications as $notification): ?>
                    <div class="list-group-item px-0 <?= $notification['is_read'] ? '' : 'bg-light' ?>">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1"><?= htmlspecialchars($notification['title']) ?></h6>
                            <small class="text-muted"><?= date('M d', strtotime($notification['created_at'])) ?></small>
                        </div>
                        <p class="mb-1 small"><?= htmlspecialchars($notification['message']) ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = 'Dashboard';
require __DIR__ . '/../layout.php';
?>
