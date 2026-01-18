<?php ob_start(); ?>

<div class="mb-4">
    <a href="<?= $this->getBaseUrl() ?>/work-items" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back to Work Items
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Create New Work Item</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= $this->getBaseUrl() ?>/work-items/create">
                    <input type="hidden" name="csrf_token" value="<?= Session::csrfToken() ?>">
                    
                    <div class="mb-3">
                        <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="">Select type...</option>
                            <option value="task">Task</option>
                            <option value="leave_request">Leave Request</option>
                            <option value="expense">Expense</option>
                            <option value="timesheet">Timesheet</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea id="description" name="description" style="display:none;" data-required="true"></textarea>
                        <div class="rich-editor" style="min-height: 200px;" data-placeholder="Describe the work item in detail. You can paste images and format text..."></div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="priority" class="form-label">Priority <span class="text-danger">*</span></label>
                            <select class="form-select" id="priority" name="priority" required>
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="due_date" class="form-label">Due Date</label>
                            <input type="date" class="form-control" id="due_date" name="due_date">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="assigned_to" class="form-label">Assign To</label>
                        <select class="form-select" id="assigned_to" name="assigned_to">
                            <option value="">Unassigned</option>
                            <?php foreach ($employees as $emp): ?>
                            <option value="<?= $emp['id'] ?>"><?= htmlspecialchars($emp['full_name']) ?> (<?= htmlspecialchars($emp['designation']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="project_id" class="form-label">Project</label>
                        <select class="form-select" id="project_id" name="project_id">
                            <option value="">No project</option>
                            <?php foreach ($projects as $project): ?>
                            <option value="<?= $project['id'] ?>"><?= htmlspecialchars($project['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Create Work Item
                        </button>
                        <a href="<?= $this->getBaseUrl() ?>/work-items" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Work Item Types</h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><strong>Task:</strong> General work assignments</li>
                    <li class="mb-2"><strong>Leave Request:</strong> Use Leave Management instead</li>
                    <li class="mb-2"><strong>Expense:</strong> Use Expense form instead</li>
                    <li><strong>Timesheet:</strong> Use Timesheet form instead</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = 'Create Work Item';
require __DIR__ . '/../layout.php';
?>
