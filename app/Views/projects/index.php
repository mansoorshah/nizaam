<?php ob_start(); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Projects</h2>
        <p class="text-muted mb-0">Manage and track your projects</p>
    </div>
    <?php if ($this->isAdmin()): ?>
    <a href="<?= dirname($_SERVER['SCRIPT_NAME']) === '/' ? '' : dirname($_SERVER['SCRIPT_NAME']) ?>/projects/create" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> New Project
    </a>
    <?php endif; ?>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="active" <?= ($filters['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="completed" <?= ($filters['status'] ?? '') === 'completed' ? 'selected' : '' ?>>Completed</option>
                    <option value="on_hold" <?= ($filters['status'] ?? '') === 'on_hold' ? 'selected' : '' ?>>On Hold</option>
                    <option value="" <?= empty($filters['status']) ? 'selected' : '' ?>>All</option>
                </select>
            </div>
        </form>
    </div>
</div>

<!-- Projects List -->
<div class="row">
    <?php if (empty($projects)): ?>
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-folder2-open" style="font-size: 3rem; color: #ddd;"></i>
                <p class="text-muted mt-3">No projects found</p>
                <?php if ($this->isAdmin()): ?>
                <a href="<?= dirname($_SERVER['SCRIPT_NAME']) === '/' ? '' : dirname($_SERVER['SCRIPT_NAME']) ?>/projects/create" class="btn btn-primary">
                    Create First Project
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php else: ?>
    <?php foreach ($projects as $project): ?>
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h5 class="card-title mb-0">
                        <a href="<?= dirname($_SERVER['SCRIPT_NAME']) === '/' ? '' : dirname($_SERVER['SCRIPT_NAME']) ?>/projects/<?= $project['id'] ?>" class="text-decoration-none text-dark">
                            <?= htmlspecialchars($project['name']) ?>
                        </a>
                    </h5>
                    <?php
                    $statusColors = [
                        'active' => 'success',
                        'completed' => 'primary',
                        'on_hold' => 'warning',
                        'cancelled' => 'danger'
                    ];
                    $badgeColor = $statusColors[$project['status']] ?? 'secondary';
                    ?>
                    <span class="badge bg-<?= $badgeColor ?>"><?= ucfirst(str_replace('_', ' ', $project['status'])) ?></span>
                </div>

                <?php if ($project['description']): ?>
                <p class="card-text text-muted small mb-3">
                    <?= htmlspecialchars(substr($project['description'], 0, 100)) ?><?= strlen($project['description']) > 100 ? '...' : '' ?>
                </p>
                <?php endif; ?>

                <div class="small text-muted mb-3">
                    <i class="bi bi-person"></i> <?= htmlspecialchars($project['owner_name'] ?? 'Unknown') ?>
                </div>

                <?php if (isset($project['start_date']) || isset($project['end_date'])): ?>
                <div class="small text-muted mb-3">
                    <i class="bi bi-calendar"></i>
                    <?php if (isset($project['start_date']) && $project['start_date']): ?>
                        <?= date('M d, Y', strtotime($project['start_date'])) ?>
                    <?php endif; ?>
                    <?php if (isset($project['end_date']) && $project['end_date']): ?>
                        - <?= date('M d, Y', strtotime($project['end_date'])) ?>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
            <div class="card-footer bg-white border-top-0">
                <a href="<?= dirname($_SERVER['SCRIPT_NAME']) === '/' ? '' : dirname($_SERVER['SCRIPT_NAME']) ?>/projects/<?= $project['id'] ?>" class="btn btn-sm btn-outline-primary w-100">
                    <i class="bi bi-eye"></i> View Details
                </a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>

<style>
.card {
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-5px);
}
</style>

<?php
$content = ob_get_clean();
$title = 'Projects';
require __DIR__ . '/../layout.php';
?>
