<?php ob_start(); ?>

<div class="d-flex justify-content-end align-items-center mb-4">
    <?php if ($this->isAdmin()): ?>
    <a href="<?= $this->getBaseUrl() ?>/employees/create" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Add Employee
    </a>
    <?php endif; ?>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="<?= $this->getBaseUrl() ?>/employees">
            <div class="row g-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="search" placeholder="Search by name, ID, or email" value="<?= htmlspecialchars($filters['search'] ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="department">
                        <option value="">All Departments</option>
                        <?php foreach ($departments as $dept): ?>
                        <option value="<?= htmlspecialchars($dept) ?>" <?= ($filters['department'] ?? '') === $dept ? 'selected' : '' ?>>
                            <?= htmlspecialchars($dept) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="status">
                        <option value="">All Statuses</option>
                        <option value="active" <?= ($filters['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="on_leave" <?= ($filters['status'] ?? '') === 'on_leave' ? 'selected' : '' ?>>On Leave</option>
                        <option value="terminated" <?= ($filters['status'] ?? '') === 'terminated' ? 'selected' : '' ?>>Terminated</option>
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

<!-- Employee List -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Employee ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Designation</th>
                        <th>Department</th>
                        <th>Manager</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($employees as $employee): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($employee['employee_id']) ?></strong></td>
                        <td><?= htmlspecialchars($employee['full_name']) ?></td>
                        <td><?= htmlspecialchars($employee['email']) ?></td>
                        <td><?= htmlspecialchars($employee['designation']) ?></td>
                        <td><?= htmlspecialchars($employee['department']) ?></td>
                        <td><?= htmlspecialchars($employee['manager_name'] ?? '-') ?></td>
                        <td>
                            <?php
                            $statusColors = ['active' => 'success', 'on_leave' => 'warning', 'terminated' => 'danger', 'resigned' => 'secondary'];
                            $color = $statusColors[$employee['employment_status']] ?? 'secondary';
                            ?>
                            <span class="badge bg-<?= $color ?>"><?= ucfirst(str_replace('_', ' ', $employee['employment_status'])) ?></span>
                        </td>
                        <td>
                            <a href="<?= $this->getBaseUrl() ?>/employees/<?= $employee['id'] ?>" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i> View
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = 'Employees';
require __DIR__ . '/../layout.php';
?>
