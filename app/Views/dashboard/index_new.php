<?php ob_start(); ?>

<!-- Modern Dashboard Hero Section -->
<div class="row align-items-center mb-4" data-aos="fade-down">
    <div class="col-md-8">
        <h1 class="display-6 fw-bold mb-2">
            Welcome back, <?= htmlspecialchars($this->getCurrentEmployee()['full_name'] ?? 'User') ?> 👋
        </h1>
        <p class="text-muted mb-0">Here's what's happening with your work today</p>
    </div>
    <div class="col-md-4 text-md-end">
        <div class="text-muted small">
            <i class="bi bi-calendar-event me-1"></i>
            <?= date('l, F j, Y') ?>
        </div>
        <div class="text-muted small">
            <i class="bi bi-clock me-1"></i>
            <?= date('g:i A') ?>
        </div>
    </div>
</div>

<!-- Modern Stats Cards with Gradient -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="0">
        <div class="stat-card" style="--stat-color: var(--nizaam-primary);">
            <div class="stat-icon" style="background: var(--nizaam-primary-light); color: var(--nizaam-primary);">
                <i class="bi bi-kanban-fill"></i>
            </div>
            <div class="stat-value text-primary"><?= count($myWorkItems) ?></div>
            <div class="stat-label">Assigned to Me</div>
            <div class="mt-3">
                <a href="<?= $this->getBaseUrl() ?>/work-items" class="text-decoration-none small text-primary fw-semibold">
                    View all <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
        <div class="stat-card" style="--stat-color: var(--nizaam-success);">
            <div class="stat-icon" style="background: var(--nizaam-success-light); color: var(--nizaam-success);">
                <i class="bi bi-plus-circle-fill"></i>
            </div>
            <div class="stat-value text-success"><?= count($createdByMe) ?></div>
            <div class="stat-label">Created by Me</div>
            <div class="mt-3">
                <a href="<?= $this->getBaseUrl() ?>/work-items" class="text-decoration-none small text-success fw-semibold">
                    View all <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
        <div class="stat-card" style="--stat-color: var(--nizaam-warning);">
            <div class="stat-icon" style="background: var(--nizaam-warning-light); color: var(--nizaam-warning);">
                <i class="bi bi-bell-fill"></i>
            </div>
            <div class="stat-value text-warning"><?= $unreadCount ?></div>
            <div class="stat-label">Unread Notifications</div>
            <div class="mt-3">
                <a href="<?= $this->getBaseUrl() ?>/notifications" class="text-decoration-none small text-warning fw-semibold">
                    View all <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
        <div class="stat-card" style="--stat-color: var(--nizaam-info);">
            <div class="stat-icon" style="background: var(--nizaam-info-light); color: var(--nizaam-info);">
                <i class="bi bi-folder-fill"></i>
            </div>
            <div class="stat-value text-info"><?= count($projects) ?></div>
            <div class="stat-label">Active Projects</div>
            <div class="mt-3">
                <a href="<?= $this->getBaseUrl() ?>/projects" class="text-decoration-none small text-info fw-semibold">
                    View all <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions - Modern Card Grid -->
<div class="card mb-4" data-aos="fade-up">
    <div class="card-header">
        <div>
            <i class="bi bi-lightning-charge-fill me-2"></i>
            Quick Actions
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-lg-3 col-md-6">
                <a href="<?= $this->getBaseUrl() ?>/work-items/create" class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2 py-3">
                    <i class="bi bi-plus-circle fs-5"></i>
                    <span>Create Task</span>
                </a>
            </div>
            <div class="col-lg-3 col-md-6">
                <a href="<?= $this->getBaseUrl() ?>/leaves/request" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-2 py-3">
                    <i class="bi bi-calendar-event fs-5"></i>
                    <span>Request Leave</span>
                </a>
            </div>
            <div class="col-lg-3 col-md-6">
                <a href="<?= $this->getBaseUrl() ?>/expenses/create" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-2 py-3">
                    <i class="bi bi-receipt-cutoff fs-5"></i>
                    <span>Submit Expense</span>
                </a>
            </div>
            <div class="col-lg-3 col-md-6">
                <a href="<?= $this->getBaseUrl() ?>/timesheets/create" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-2 py-3">
                    <i class="bi bi-clock-history fs-5"></i>
                    <span>Log Time</span>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- My Work Items - Enhanced Table -->
    <div class="col-lg-8" data-aos="fade-right">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-kanban me-2"></i>
                My Work Items
                <a href="<?= $this->getBaseUrl() ?>/work-items" class="btn btn-sm btn-outline-primary ms-auto">
                    View All <i class="bi bi-arrow-right-short"></i>
                </a>
            </div>
            <div class="card-body">
                <?php if (empty($myWorkItems)): ?>
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                        <h5 class="text-muted">No Active Work Items</h5>
                        <p class="text-muted small mb-4">You're all caught up! No tasks assigned to you right now.</p>
                        <a href="<?= $this->getBaseUrl() ?>/work-items/create" class="btn btn-primary">
                            <i class="bi bi-plus-lg"></i> Create New Task
                        </a>
                    </div>
                </div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th><i class="bi bi-card-text me-1"></i> Title</th>
                                <th class="text-center"><i class="bi bi-tag me-1"></i> Type</th>
                                <th class="text-center"><i class="bi bi-flag me-1"></i> Priority</th>
                                <th><i class="bi bi-activity me-1"></i> Status</th>
                                <th class="text-end"><i class="bi bi-calendar me-1"></i> Due Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_slice($myWorkItems, 0, 5) as $item): ?>
                            <tr>
                                <td>
                                    <a href="<?= $this->getBaseUrl() ?>/work-items/<?= $item['id'] ?>" class="text-decoration-none fw-semibold">
                                        <?= htmlspecialchars($item['title']) ?>
                                    </a>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-secondary"><?= ucfirst($item['type']) ?></span>
                                </td>
                                <td class="text-center">
                                    <?php
                                    $priorityColors = ['low' => 'success', 'medium' => 'warning', 'high' => 'danger', 'urgent' => 'danger'];
                                    $priorityColor = $priorityColors[$item['priority']] ?? 'secondary';
                                    ?>
                                    <span class="badge bg-<?= $priorityColor ?>">
                                        <?= ucfirst($item['priority']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge" style="background-color: <?= $item['status_color'] ?>20; color: <?= $item['status_color'] ?>;">
                                        <?= htmlspecialchars($item['status_name']) ?>
                                    </span>
                                </td>
                                <td class="text-end text-muted small">
                                    <?= $item['due_date'] ? date('M d, Y', strtotime($item['due_date'])) : '-' ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php if (count($myWorkItems) > 5): ?>
                <div class="text-center mt-3">
                    <a href="<?= $this->getBaseUrl() ?>/work-items" class="btn btn-sm btn-link">
                        Show all <?= count($myWorkItems) ?> items
                    </a>
                </div>
                <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Recent Notifications & Activity -->
    <div class="col-lg-4" data-aos="fade-left">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center">
                <i class="bi bi-bell me-2"></i>
                <span class="flex-grow-1">Recent Notifications</span>
                <?php if ($unreadCount > 0): ?>
                <span class="badge bg-danger"><?= $unreadCount ?></span>
                <?php endif; ?>
            </div>
            <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                <?php if (empty($notifications)): ?>
                <div class="text-center py-4">
                    <i class="bi bi-bell-slash fs-1 text-muted d-block mb-3"></i>
                    <p class="text-muted small">No notifications yet</p>
                </div>
                <?php else: ?>
                <div class="list-group list-group-flush">
                    <?php foreach ($notifications as $notification): ?>
                    <div class="list-group-item px-0 <?= $notification['is_read'] ? '' : 'bg-light' ?>">
                        <div class="d-flex w-100 align-items-start">
                            <div class="flex-shrink-0 me-3">
                                <?php
                                $iconMap = [
                                    'info' => 'info-circle-fill text-info',
                                    'assignment' => 'person-check-fill text-primary',
                                    'approval' => 'check-circle-fill text-success',
                                    'status_change' => 'arrow-repeat text-warning',
                                    'comment' => 'chat-fill text-secondary'
                                ];
                                $iconClass = $iconMap[$notification['type']] ?? 'info-circle-fill text-info';
                                ?>
                                <i class="bi bi-<?= $iconClass ?> fs-5"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1 small fw-bold"><?= htmlspecialchars($notification['title']) ?></h6>
                                <p class="mb-1 small text-muted"><?= htmlspecialchars($notification['message']) ?></p>
                                <small class="text-muted">
                                    <i class="bi bi-clock me-1"></i>
                                    <?php
                                    $time = strtotime($notification['created_at']);
                                    $diff = time() - $time;
                                    if ($diff < 60) echo 'Just now';
                                    elseif ($diff < 3600) echo floor($diff / 60) . ' min ago';
                                    elseif ($diff < 86400) echo floor($diff / 3600) . ' hours ago';
                                    else echo date('M d', $time);
                                    ?>
                                </small>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="text-center mt-3">
                    <a href="<?= $this->getBaseUrl() ?>/notifications" class="btn btn-sm btn-link">
                        View All Notifications
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Active Projects Section (Admin Only) -->
<?php if ($this->isAdmin() && !empty($projects)): ?>
<div class="row mt-4">
    <div class="col-12" data-aos="fade-up">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-folder-fill me-2"></i>
                Active Projects
                <a href="<?= $this->getBaseUrl() ?>/projects" class="btn btn-sm btn-outline-primary ms-auto">
                    View All <i class="bi bi-arrow-right-short"></i>
                </a>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <?php foreach (array_slice($projects, 0, 3) as $project): ?>
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h6 class="card-title fw-bold">
                                    <a href="<?= $this->getBaseUrl() ?>/projects/<?= $project['id'] ?>" class="text-decoration-none">
                                        <?= htmlspecialchars($project['name']) ?>
                                    </a>
                                </h6>
                                <p class="card-text small text-muted">
                                    <?php 
                                    // Strip HTML tags and get plain text
                                    $plainText = strip_tags($project['description'] ?? '');
                                    echo htmlspecialchars(substr($plainText, 0, 100)) . (strlen($plainText) > 100 ? '...' : '');
                                    ?>
                                </p>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <span class="badge bg-success"><?= ucfirst($project['status']) ?></span>
                                    <small class="text-muted">
                                        <i class="bi bi-people me-1"></i> <?= $project['member_count'] ?? 0 ?> members
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php
$content = ob_get_clean();
$title = 'Dashboard';
require __DIR__ . '/../layout.php';
?>
