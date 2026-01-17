<?php ob_start(); ?>

<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= dirname($_SERVER['SCRIPT_NAME']) === '/' ? '' : dirname($_SERVER['SCRIPT_NAME']) ?>/dashboard">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= dirname($_SERVER['SCRIPT_NAME']) === '/' ? '' : dirname($_SERVER['SCRIPT_NAME']) ?>/projects">Projects</a></li>
        <li class="breadcrumb-item active">Create Project</li>
    </ol>
</nav>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white">
                <h4 class="mb-0">Create New Project</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= dirname($_SERVER['SCRIPT_NAME']) === '/' ? '' : dirname($_SERVER['SCRIPT_NAME']) ?>/projects/store">
                    <input type="hidden" name="csrf_token" value="<?= Session::get('csrf_token') ?>">

                    <div class="mb-3">
                        <label for="name" class="form-label">Project Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" required 
                               value="<?= htmlspecialchars(Session::getFlash('old')['name'] ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="4"><?= htmlspecialchars(Session::getFlash('old')['description'] ?? '') ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="owner_id" class="form-label">Project Owner <span class="text-danger">*</span></label>
                            <select class="form-select" id="owner_id" name="owner_id" required>
                                <option value="">Select owner...</option>
                                <?php if (isset($employees) && is_array($employees)): ?>
                                    <?php foreach ($employees as $employee): ?>
                                    <option value="<?= $employee['id'] ?>" 
                                            <?= (Session::getFlash('old')['owner_id'] ?? '') == $employee['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($employee['full_name']) ?> - <?= htmlspecialchars($employee['designation']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="active" <?= (Session::getFlash('old')['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Active</option>
                                <option value="on_hold" <?= (Session::getFlash('old')['status'] ?? '') === 'on_hold' ? 'selected' : '' ?>>On Hold</option>
                                <option value="completed" <?= (Session::getFlash('old')['status'] ?? '') === 'completed' ? 'selected' : '' ?>>Completed</option>
                                <option value="cancelled" <?= (Session::getFlash('old')['status'] ?? '') === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" 
                                   value="<?= htmlspecialchars(Session::getFlash('old')['start_date'] ?? '') ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" 
                                   value="<?= htmlspecialchars(Session::getFlash('old')['end_date'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="<?= dirname($_SERVER['SCRIPT_NAME']) === '/' ? '' : dirname($_SERVER['SCRIPT_NAME']) ?>/projects" class="btn btn-secondary">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Create Project
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-light">
            <div class="card-body">
                <h6 class="card-title">📝 Project Guidelines</h6>
                <ul class="small mb-0">
                    <li>Choose a clear, descriptive name</li>
                    <li>Assign an appropriate project owner</li>
                    <li>Set realistic start and end dates</li>
                    <li>Add team members after creation</li>
                    <li>Create work items to track progress</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = 'Create Project';
require __DIR__ . '/../layout.php';
?>

