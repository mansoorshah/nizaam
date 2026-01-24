<?php ob_start(); ?>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="<?= $this->getBaseUrl() ?>/audit">
            <div class="row g-3">
                <div class="col-md-3">
                    <select class="form-select" name="entity_type">
                        <option value="">All Entity Types</option>
                        <option value="user" <?= ($filters['entity_type'] ?? '') === 'user' ? 'selected' : '' ?>>User</option>
                        <option value="employee" <?= ($filters['entity_type'] ?? '') === 'employee' ? 'selected' : '' ?>>Employee</option>
                        <option value="work_item" <?= ($filters['entity_type'] ?? '') === 'work_item' ? 'selected' : '' ?>>Work Item</option>
                        <option value="project" <?= ($filters['entity_type'] ?? '') === 'project' ? 'selected' : '' ?>>Project</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="action">
                        <option value="">All Actions</option>
                        <option value="create" <?= ($filters['action'] ?? '') === 'create' ? 'selected' : '' ?>>Create</option>
                        <option value="update" <?= ($filters['action'] ?? '') === 'update' ? 'selected' : '' ?>>Update</option>
                        <option value="delete" <?= ($filters['action'] ?? '') === 'delete' ? 'selected' : '' ?>>Delete</option>
                        <option value="login" <?= ($filters['action'] ?? '') === 'login' ? 'selected' : '' ?>>Login</option>
                        <option value="logout" <?= ($filters['action'] ?? '') === 'logout' ? 'selected' : '' ?>>Logout</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control" name="date_from" placeholder="From Date" value="<?= htmlspecialchars($filters['date_from'] ?? '') ?>">
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control" name="date_to" placeholder="To Date" value="<?= htmlspecialchars($filters['date_to'] ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-outline-primary w-100">
                        <i class="bi bi-search"></i> Filter
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Audit Logs -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Timestamp</th>
                        <th>Actor</th>
                        <th>Action</th>
                        <th>Entity</th>
                        <th>IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($logs)): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted">No audit logs found</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($logs as $log): ?>
                    <tr>
                        <td><?= date('M d, Y H:i:s', strtotime($log['created_at'])) ?></td>
                        <td><?= htmlspecialchars($log['actor_email'] ?? 'System') ?></td>
                        <td>
                            <span class="badge bg-info"><?= htmlspecialchars($log['action']) ?></span>
                        </td>
                        <td>
                            <?= htmlspecialchars($log['entity_type']) ?> 
                            <span class="text-muted">#<?= $log['entity_id'] ?></span>
                        </td>
                        <td><code class="small"><?= htmlspecialchars($log['ip_address'] ?? '-') ?></code></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = 'Audit Log';
require __DIR__ . '/../layout.php';
?>
