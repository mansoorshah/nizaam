<?php ob_start(); ?>

<div class="d-flex justify-content-end align-items-center mb-4">
    <a href="<?= $this->getBaseUrl() ?>/work-items/create" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Create Work Item
    </a>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="<?= $this->getBaseUrl() ?>/work-items">
            <div class="row g-3">
                <div class="col-md-3">
                    <select class="form-select" name="type">
                        <option value="">All Types</option>
                        <option value="task" <?= ($filters['type'] ?? '') === 'task' ? 'selected' : '' ?>>Task</option>
                        <option value="leave_request" <?= ($filters['type'] ?? '') === 'leave_request' ? 'selected' : '' ?>>Leave Request</option>
                        <option value="expense" <?= ($filters['type'] ?? '') === 'expense' ? 'selected' : '' ?>>Expense</option>
                        <option value="timesheet" <?= ($filters['type'] ?? '') === 'timesheet' ? 'selected' : '' ?>>Timesheet</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="priority">
                        <option value="">All Priorities</option>
                        <option value="low" <?= ($filters['priority'] ?? '') === 'low' ? 'selected' : '' ?>>Low</option>
                        <option value="medium" <?= ($filters['priority'] ?? '') === 'medium' ? 'selected' : '' ?>>Medium</option>
                        <option value="high" <?= ($filters['priority'] ?? '') === 'high' ? 'selected' : '' ?>>High</option>
                        <option value="urgent" <?= ($filters['priority'] ?? '') === 'urgent' ? 'selected' : '' ?>>Urgent</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <select class="form-select" name="project_id">
                        <option value="">All Projects</option>
                        <?php foreach ($projects as $project): ?>
                        <option value="<?= $project['id'] ?>" <?= ($filters['project_id'] ?? '') == $project['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($project['name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-primary w-100">
                        <i class="bi bi-search"></i> Filter
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Work Items -->
<div class="card">
    <div class="card-body">
        <?php if (empty($workItems)): ?>
        <div class="text-center py-5 text-muted">
            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
            <p>No work items found</p>
        </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Creator</th>
                        <th>Assignee</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Due Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($workItems as $item): ?>
                    <tr onclick="window.location='<?= $this->getBaseUrl() ?>/work-items/<?= $item['id'] ?>'" style="cursor: pointer;">
                        <td><strong>#<?= $item['id'] ?></strong></td>
                        <td><?= htmlspecialchars($item['title']) ?></td>
                        <td><span class="badge bg-secondary"><?= ucfirst(str_replace('_', ' ', $item['type'])) ?></span></td>
                        <td><?= htmlspecialchars($item['creator_name']) ?></td>
                        <td><?= htmlspecialchars($item['assignee_name'] ?? 'Unassigned') ?></td>
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

<?php
$content = ob_get_clean();
$title = 'Work Items';
require __DIR__ . '/../layout.php';
?>
