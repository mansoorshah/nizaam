<?php ob_start(); ?>

<!-- Page Header with Back Button -->
<div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-down">
    <div>
        <a href="<?= $this->getBaseUrl() ?>/projects" class="btn btn-outline-secondary mb-2">
            <i class="bi bi-arrow-left"></i> Back to Projects
        </a>
        <h1 class="h3 mb-1">
            <i class="bi bi-folder-fill text-primary"></i>
            <?= htmlspecialchars($project['name']) ?>
        </h1>
        <p class="text-muted mb-0">Project Details and Management</p>
    </div>
    <div>
        <?php
        $statusConfig = [
            'planning' => ['color' => 'info', 'icon' => 'bi-clipboard-check'],
            'active' => ['color' => 'success', 'icon' => 'bi-play-circle-fill'],
            'on_hold' => ['color' => 'warning', 'icon' => 'bi-pause-circle-fill'],
            'completed' => ['color' => 'primary', 'icon' => 'bi-check-circle-fill'],
            'cancelled' => ['color' => 'danger', 'icon' => 'bi-x-circle-fill']
        ];
        $config = $statusConfig[$project['status']] ?? ['color' => 'secondary', 'icon' => 'bi-circle'];
        ?>
        <span class="badge bg-<?= $config['color'] ?> fs-6 px-3 py-2">
            <i class="bi <?= $config['icon'] ?>"></i>
            <?= ucfirst(str_replace('_', ' ', $project['status'])) ?>
        </span>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Project Details Card -->
        <div class="card shadow-sm mb-4" data-aos="fade-up">
            <div class="card-header bg-white border-bottom position-relative">
                <h6 class="mb-0 text-dark fw-semibold">
                    <i class="bi bi-info-circle text-primary me-2"></i>
                    Project Information
                </h6>
                <?php if ($this->isAdmin()): ?>
                <a href="<?= $this->getBaseUrl() ?>/projects/<?= $project['id'] ?>/edit" class="btn btn-sm btn-primary position-absolute top-50 end-0 translate-middle-y me-3">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <?php if ($project['description']): ?>
                <div class="mb-4">
                    <label class="text-muted small fw-semibold mb-2">DESCRIPTION</label>
                    <div class="comment-content"><?= $project['description'] ?></div>
                </div>
                <hr>
                <?php endif; ?>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <div class="me-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 40px; height: 40px; background: var(--nizaam-primary-light); color: var(--nizaam-primary);">
                                    <i class="bi bi-person-circle"></i>
                                </div>
                            </div>
                            <div>
                                <label class="text-muted small fw-semibold mb-1">PROJECT OWNER</label>
                                <div class="fw-medium"><?= htmlspecialchars($project['owner_name'] ?? 'Unknown') ?></div>
                            </div>
                        </div>
                    </div>
                    
                    <?php if (isset($project['start_date']) && $project['start_date']): ?>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <div class="me-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 40px; height: 40px; background: var(--nizaam-success-light); color: var(--nizaam-success);">
                                    <i class="bi bi-calendar-check"></i>
                                </div>
                            </div>
                            <div>
                                <label class="text-muted small fw-semibold mb-1">START DATE</label>
                                <div class="fw-medium"><?= date('F d, Y', strtotime($project['start_date'])) ?></div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (isset($project['end_date']) && $project['end_date']): ?>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <div class="me-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 40px; height: 40px; background: var(--nizaam-warning-light); color: var(--nizaam-warning);">
                                    <i class="bi bi-calendar-x"></i>
                                </div>
                            </div>
                            <div>
                                <label class="text-muted small fw-semibold mb-1">END DATE</label>
                                <div class="fw-medium"><?= date('F d, Y', strtotime($project['end_date'])) ?></div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <div class="me-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 40px; height: 40px; background: var(--nizaam-info-light); color: var(--nizaam-info);">
                                    <i class="bi <?= $config['icon'] ?>"></i>
                                </div>
                            </div>
                            <div>
                                <label class="text-muted small fw-semibold mb-1">STATUS</label>
                                <div class="fw-medium"><?= ucfirst(str_replace('_', ' ', $project['status'])) ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Work Items Card -->
        <div class="card shadow-sm" data-aos="fade-up" data-aos-delay="100">
            <div class="card-header bg-white border-bottom position-relative">
                <h6 class="mb-0 text-dark fw-semibold">
                    <i class="bi bi-list-task text-primary me-2"></i>
                    Work Items
                </h6>
                <a href="<?= $this->getBaseUrl() ?>/work-items/create?project_id=<?= $project['id'] ?>" class="btn btn-sm btn-primary position-absolute top-50 end-0 translate-middle-y me-3">
                    <i class="bi bi-plus-lg"></i> Add Work Item
                </a>
            </div>
            <div class="card-body p-0">
                <?php if (empty($workItems)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-list-task display-1 text-muted opacity-25"></i>
                    <p class="text-muted mt-3 mb-2">No work items yet</p>
                    <a href="<?= $this->getBaseUrl() ?>/work-items/create?project_id=<?= $project['id'] ?>" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus"></i> Create First Work Item
                    </a>
                </div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover data-table mb-0">
                        <thead>
                            <tr>
                                <th><i class="bi bi-card-text"></i> Title</th>
                                <th><i class="bi bi-tag"></i> Type</th>
                                <th><i class="bi bi-flag"></i> Status</th>
                                <th><i class="bi bi-person"></i> Assigned To</th>
                                <th><i class="bi bi-exclamation-triangle"></i> Priority</th>
                                <th><i class="bi bi-calendar"></i> Due Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($workItems as $item): ?>
                            <tr onclick="window.location='<?= $this->getBaseUrl() ?>/work-items/<?= $item['id'] ?>'" style="cursor: pointer;">
                                <td>
                                    <div class="fw-semibold text-dark"><?= htmlspecialchars($item['title']) ?></div>
                                </td>
                                <td>
                                    <?php
                                    $typeConfig = [
                                        'task' => ['color' => 'primary', 'icon' => 'bi-list-task'],
                                        'bug' => ['color' => 'danger', 'icon' => 'bi-bug'],
                                        'feature' => ['color' => 'success', 'icon' => 'bi-star'],
                                        'improvement' => ['color' => 'info', 'icon' => 'bi-arrow-up-circle']
                                    ];
                                    $typeConf = $typeConfig[$item['type'] ?? 'task'] ?? ['color' => 'secondary', 'icon' => 'bi-circle'];
                                    ?>
                                    <span class="badge bg-<?= $typeConf['color'] ?>">
                                        <i class="bi <?= $typeConf['icon'] ?>"></i> <?= ucfirst($item['type'] ?? 'Task') ?>
                                    </span>
                                </td>
                                <td>
                                    <?php
                                    $statusColors = [
                                        'backlog' => 'secondary',
                                        'todo' => 'info',
                                        'in_progress' => 'primary',
                                        'in_review' => 'warning',
                                        'done' => 'success',
                                        'cancelled' => 'danger'
                                    ];
                                    $statusColor = $statusColors[$item['current_status'] ?? $item['status'] ?? 'todo'] ?? 'secondary';
                                    ?>
                                    <span class="badge bg-<?= $statusColor ?>"><?= ucfirst(str_replace('_', ' ', $item['current_status'] ?? $item['status'] ?? 'To Do')) ?></span>
                                </td>
                                <td>
                                    <?php if (isset($item['assigned_to_name']) && $item['assigned_to_name']): ?>
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar-small me-2">
                                            <?= strtoupper(substr($item['assigned_to_name'], 0, 1)) ?>
                                        </div>
                                        <span><?= htmlspecialchars($item['assigned_to_name']) ?></span>
                                    </div>
                                    <?php else: ?>
                                    <span class="text-muted">Unassigned</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                    $priorityConfig = [
                                        'low' => ['color' => 'success', 'icon' => 'bi-arrow-down'],
                                        'medium' => ['color' => 'warning', 'icon' => 'bi-dash'],
                                        'high' => ['color' => 'danger', 'icon' => 'bi-arrow-up'],
                                        'critical' => ['color' => 'danger', 'icon' => 'bi-exclamation-triangle-fill']
                                    ];
                                    $priorityConf = $priorityConfig[$item['priority'] ?? 'medium'] ?? ['color' => 'secondary', 'icon' => 'bi-circle'];
                                    ?>
                                    <span class="badge bg-<?= $priorityConf['color'] ?>">
                                        <i class="bi <?= $priorityConf['icon'] ?>"></i> <?= ucfirst($item['priority'] ?? 'Medium') ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if (isset($item['due_date']) && $item['due_date']): ?>
                                    <span class="text-muted">
                                        <i class="bi bi-calendar-event"></i>
                                        <?= date('M d, Y', strtotime($item['due_date'])) ?>
                                    </span>
                                    <?php else: ?>
                                    <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Team Members Card -->
        <div class="card shadow-sm" data-aos="fade-up" data-aos-delay="200">
            <div class="card-header bg-white border-bottom position-relative">
                <h6 class="mb-0 text-dark fw-semibold">
                    <i class="bi bi-people-fill text-primary me-2"></i>
                    Team Members
                </h6>
                <?php if ($this->isAdmin()): ?>
                <button class="btn btn-sm btn-outline-primary position-absolute top-50 end-0 translate-middle-y me-3" data-bs-toggle="modal" data-bs-target="#addMemberModal">
                    <i class="bi bi-plus-lg"></i>
                </button>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <?php if (empty($members)): ?>
                <div class="text-center py-4">
                    <i class="bi bi-people display-4 text-muted opacity-25"></i>
                    <p class="text-muted mt-3 mb-0">No team members yet</p>
                    <?php if ($this->isAdmin()): ?>
                    <button class="btn btn-sm btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#addMemberModal">
                        <i class="bi bi-plus"></i> Add Member
                    </button>
                    <?php endif; ?>
                </div>
                <?php else: ?>
                <div class="list-group list-group-flush">
                    <?php foreach ($members as $member): ?>
                    <div class="list-group-item px-0 border-0 border-bottom">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center flex-grow-1">
                                <div class="user-avatar me-3" style="background: linear-gradient(135deg, var(--nizaam-primary), var(--nizaam-secondary));">
                                    <?= strtoupper(substr($member['full_name'], 0, 1)) ?>
                                </div>
                                <div>
                                    <div class="fw-semibold"><?= htmlspecialchars($member['full_name']) ?></div>
                                    <div class="small text-muted">
                                        <i class="bi bi-briefcase"></i> <?= htmlspecialchars($member['designation'] ?? 'Team Member') ?>
                                    </div>
                                </div>
                            </div>
                            <?php if ($this->isAdmin()): ?>
                            <form method="POST" action="<?= $this->getBaseUrl() ?>/projects/<?= $project['id'] ?>/remove-member" style="display: inline;">
                                <input type="hidden" name="employee_id" value="<?= $member['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-link text-danger p-0" onclick="return confirm('Remove this member from the project?')" title="Remove member">
                                    <i class="bi bi-x-circle"></i>
                                </button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Add Member Modal -->
<?php if ($this->isAdmin()): ?>
<div class="modal fade" id="addMemberModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="<?= $this->getBaseUrl() ?>/projects/<?= $project['id'] ?>/members">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-person-plus text-primary"></i>
                        Add Team Member
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Select Employee</label>
                        <select name="employee_id" class="form-select" required>
                            <option value="">Choose an employee...</option>
                            <?php
                            $employeeModel = new Employee();
                            $allEmployees = $employeeModel->findAll(['employment_status' => 'active'], 'full_name');
                            foreach ($allEmployees as $emp):
                                $isMember = false;
                                foreach ($members as $member) {
                                    if ($member['id'] === $emp['id']) {
                                        $isMember = true;
                                        break;
                                    }
                                }
                                if (!$isMember):
                            ?>
                            <option value="<?= $emp['id'] ?>">
                                <?= htmlspecialchars($emp['full_name']) ?> <?= isset($emp['designation']) ? '- ' . htmlspecialchars($emp['designation']) : '' ?>
                            </option>
                            <?php endif; endforeach; ?>
                        </select>
                        <div class="form-text">
                            <i class="bi bi-info-circle"></i> Only employees not currently on this project are shown
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> Add Member
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<?php
$content = ob_get_clean();
$title = htmlspecialchars($project['name']);
require __DIR__ . '/../layout.php';
?>
