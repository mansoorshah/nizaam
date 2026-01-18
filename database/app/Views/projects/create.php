<?php ob_start(); ?>

<div class="mb-4">
    <a href="<?= $this->getBaseUrl() ?>/projects" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back to Projects
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Create New Project</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= $this->getBaseUrl() ?>/projects/store">
                    <input type="hidden" name="csrf_token" value="<?= Session::csrfToken() ?>">

                    <div class="mb-3">
                        <label for="name" class="form-label">Project Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" style="display:none;"></textarea>
                        <div class="rich-editor" style="min-height: 200px;" data-placeholder="Describe the project goals, scope, and deliverables. You can paste images and format text..."></div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="owner_id" class="form-label">Project Owner <span class="text-danger">*</span></label>
                            <select class="form-select" id="owner_id" name="owner_id" required>
                                <option value="">Select owner...</option>
                                <?php foreach ($employees as $employee): ?>
                                <option value="<?= $employee['id'] ?>">
                                    <?= htmlspecialchars($employee['full_name']) ?> (<?= htmlspecialchars($employee['designation']) ?>)
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="active" selected>Active</option>
                                <option value="on_hold">On Hold</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date">
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Create Project
                        </button>
                        <a href="<?= $this->getBaseUrl() ?>/projects" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">📝 Project Guidelines</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">Choose a clear, descriptive name</li>
                    <li class="mb-2">Assign an appropriate project owner</li>
                    <li class="mb-2">Set realistic start and end dates</li>
                    <li class="mb-2">Add team members after creation</li>
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
