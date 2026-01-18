<?php ob_start(); ?>

<div class="mb-4">
    <a href="<?= $this->getBaseUrl() ?>/projects/<?= $project['id'] ?>" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back to Project
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white border-bottom">
                <h6 class="mb-0 text-dark fw-semibold">
                    <i class="bi bi-pencil-square text-primary me-2"></i>
                    Edit Project
                </h6>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= $this->getBaseUrl() ?>/projects/<?= $project['id'] ?>/edit">
                    <input type="hidden" name="csrf_token" value="<?= Session::csrfToken() ?>">

                    <div class="mb-3">
                        <label for="name" class="form-label">Project Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($project['name']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" style="display:none;"><?= $project['description'] ?? '' ?></textarea>
                        <div class="rich-editor" style="min-height: 200px;" data-placeholder="Describe the project goals, scope, and deliverables..."></div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="owner_id" class="form-label">Project Owner <span class="text-danger">*</span></label>
                            <select class="form-select" id="owner_id" name="owner_id" required>
                                <option value="">Select owner...</option>
                                <?php foreach ($employees as $employee): ?>
                                <option value="<?= $employee['id'] ?>" <?= $employee['id'] == $project['owner_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($employee['full_name']) ?> (<?= htmlspecialchars($employee['designation']) ?>)
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="active" <?= ($project['status'] ?? '') == 'active' ? 'selected' : '' ?>>Active</option>
                                <option value="on_hold" <?= ($project['status'] ?? '') == 'on_hold' ? 'selected' : '' ?>>On Hold</option>
                                <option value="completed" <?= ($project['status'] ?? '') == 'completed' ? 'selected' : '' ?>>Completed</option>
                                <option value="archived" <?= ($project['status'] ?? '') == 'archived' ? 'selected' : '' ?>>Archived</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Update Project
                        </button>
                        <a href="<?= $this->getBaseUrl() ?>/projects/<?= $project['id'] ?>" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="mb-3 text-dark fw-semibold">
                    <i class="bi bi-info-circle text-primary me-2"></i>
                    Project Information
                </h6>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="bi bi-calendar-event text-muted me-2"></i>
                        <small class="text-muted">Created: <?= date('M d, Y', strtotime($project['created_at'])) ?></small>
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-clock-history text-muted me-2"></i>
                        <small class="text-muted">Updated: <?= date('M d, Y', strtotime($project['updated_at'])) ?></small>
                    </li>
                </ul>
            </div>
        </div>

        <div class="card shadow-sm mt-3">
            <div class="card-body">
                <h6 class="mb-3 text-dark fw-semibold">
                    <i class="bi bi-lightbulb text-warning me-2"></i>
                    Tips
                </h6>
                <ul class="list-unstyled small text-muted mb-0">
                    <li class="mb-2">• Choose a clear, descriptive name</li>
                    <li class="mb-2">• Keep the description concise</li>
                    <li class="mb-2">• Update status as project progresses</li>
                    <li class="mb-2">• Set realistic target dates</li>
                    <li>• Reassign owner if needed</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = 'Edit Project - ' . htmlspecialchars($project['name']);
require __DIR__ . '/../layout.php';
