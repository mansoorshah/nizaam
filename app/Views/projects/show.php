<?php ob_start(); ?>

<div class="mb-4">
    <a href="<?= $this->getBaseUrl() ?>/projects" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back to Projects
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <!-- Project Details Card -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><?= htmlspecialchars($project['name']) ?></h5>
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
            <div class="card-body">
                <?php if ($project['description']): ?>
                <div class="mb-4">
                    <h6>Description</h6>
                    <p><?= nl2br(htmlspecialchars($project['description'])) ?></p>
                </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>Project Owner:</strong><br>
                            <i class="bi bi-person-circle"></i> <?= htmlspecialchars($project['owner_name'] ?? 'Unknown') ?>
                        </div>
                        <?php if (isset($project['start_date']) && $project['start_date']): ?>
                        <div class="mb-3">
                            <strong>Start Date:</strong><br>
                            <?= date('F d, Y', strtotime($project['start_date'])) ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>Status:</strong><br>
                            <?= ucfirst(str_replace('_', ' ', $project['status'])) ?>
                        </div>
                        <?php if (isset($project['end_date']) && $project['end_date']): ?>
                        <div class="mb-3">
                            <strong>End Date:</strong><br>
                            <?= date('F d, Y', strtotime($project['end_date'])) ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if ($this->isAdmin()): ?>
                <div class="mt-3">
                    <a href="<?= $this->getBaseUrl() ?>/projects/<?= $project['id'] ?>/edit" class="btn btn-primary">
                        <i class="bi bi-pencil"></i> Edit Project
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Work Items Card -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Work Items</h5>
                <a href="<?= $this->getBaseUrl() ?>/work-items/create?project_id=<?= $project['id'] ?>" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus"></i> Add Work Item
                </a>
            </div>
            <div class="card-body">
                <?php if (empty($workItems)): ?>
                <p class="text-muted text-center py-3">No work items yet</p>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Assigned To</th>
                                <th>Priority</th>
                                <th>Due Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($workItems as $item): ?>
                            <tr>
                                <td>
                                    <a href="<?= $this->getBaseUrl() ?>/work-items/<?= $item['id'] ?>">
                                        <?= htmlspecialchars($item['title']) ?>
                                    </a>
                                </td>
                                <td><span class="badge bg-secondary"><?= ucfirst($item['type']) ?></span></td>
                                <td><span class="badge bg-info"><?= htmlspecialchars($item['current_status']) ?></span></td>
                                <td><?= htmlspecialchars($item['assigned_to_name'] ?? 'Unassigned') ?></td>
                                <td>
                                    <?php
                                    $priorityColors = ['low' => 'success', 'medium' => 'warning', 'high' => 'danger'];
                                    $priorityColor = $priorityColors[$item['priority']] ?? 'secondary';
                                    ?>
                                    <span class="badge bg-<?= $priorityColor ?>"><?= ucfirst($item['priority']) ?></span>
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

    <div class="col-md-4">
        <!-- Team Members Card -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Team Members</h5>
                <?php if ($this->isAdmin()): ?>
                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addMemberModal">
                    <i class="bi bi-plus"></i>
                </button>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <?php if (empty($members)): ?>
                <p class="text-muted text-center">No team members yet</p>
                <?php else: ?>
                <div class="list-group list-group-flush">
                    <?php foreach ($members as $member): ?>
                    <div class="list-group-item px-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-person-circle"></i>
                                <?= htmlspecialchars($member['full_name']) ?>
                                <div class="small text-muted"><?= htmlspecialchars($member['designation']) ?></div>
                            </div>
                            <?php if ($this->isAdmin()): ?>
                            <form method="POST" action="<?= $this->getBaseUrl() ?>/projects/<?= $project['id'] ?>/remove-member" style="display: inline;">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                                <input type="hidden" name="employee_id" value="<?= $member['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-link text-danger" onclick="return confirm('Remove this member?')">
                                    <i class="bi bi-x"></i>
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
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="<?= $this->getBaseUrl() ?>/projects/<?= $project['id'] ?>/add-member">
                <div class="modal-header">
                    <h5 class="modal-title">Add Team Member</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                    <div class="mb-3">
                        <label class="form-label">Employee</label>
                        <select name="employee_id" class="form-select" required>
                            <option value="">Select employee...</option>
                            <?php
                            require_once __DIR__ . '/../../Models/Employee.php';
                            $allEmployees = (new Employee())->getAll(['employment_status' => 'active']);
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
                            <option value="<?= $emp['id'] ?>"><?= htmlspecialchars($emp['full_name']) ?> - <?= htmlspecialchars($emp['designation']) ?></option>
                            <?php endif; endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Member</button>
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
