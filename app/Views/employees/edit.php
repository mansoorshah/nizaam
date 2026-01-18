<?php ob_start(); ?>

<div class="mb-4">
    <a href="<?= $this->getBaseUrl() ?>/employees/<?= $employee['id'] ?>" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back to Profile
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white border-bottom">
                <h6 class="mb-0 text-dark fw-semibold">
                    <i class="bi bi-pencil-square text-primary me-2"></i>
                    Edit Employee Profile
                </h6>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= $this->getBaseUrl() ?>/employees/<?= $employee['id'] ?>/edit">
                    <input type="hidden" name="csrf_token" value="<?= Session::csrfToken() ?>">

                    <div class="mb-3">
                        <label for="full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="full_name" name="full_name" value="<?= htmlspecialchars($employee['full_name']) ?>" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="employee_id" class="form-label">Employee ID <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="employee_id" name="employee_id" value="<?= htmlspecialchars($employee['employee_id']) ?>" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($employee['email']) ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone_number" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone_number" name="phone_number" value="<?= htmlspecialchars($employee['phone_number'] ?? '') ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="date_of_birth" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="<?= $employee['date_of_birth'] ?? '' ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="designation" class="form-label">Designation <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="designation" name="designation" value="<?= htmlspecialchars($employee['designation']) ?>" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="department" class="form-label">Department <span class="text-danger">*</span></label>
                            <select class="form-select" id="department" name="department" required>
                                <option value="">Select department...</option>
                                <option value="Engineering" <?= $employee['department'] == 'Engineering' ? 'selected' : '' ?>>Engineering</option>
                                <option value="Sales" <?= $employee['department'] == 'Sales' ? 'selected' : '' ?>>Sales</option>
                                <option value="Marketing" <?= $employee['department'] == 'Marketing' ? 'selected' : '' ?>>Marketing</option>
                                <option value="Finance" <?= $employee['department'] == 'Finance' ? 'selected' : '' ?>>Finance</option>
                                <option value="Human Resources" <?= $employee['department'] == 'Human Resources' ? 'selected' : '' ?>>Human Resources</option>
                                <option value="Operations" <?= $employee['department'] == 'Operations' ? 'selected' : '' ?>>Operations</option>
                                <option value="Customer Support" <?= $employee['department'] == 'Customer Support' ? 'selected' : '' ?>>Customer Support</option>
                                <option value="Product" <?= $employee['department'] == 'Product' ? 'selected' : '' ?>>Product</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="join_date" class="form-label">Join Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="join_date" name="join_date" value="<?= $employee['join_date'] ?>" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="employment_status" class="form-label">Employment Status</label>
                            <select class="form-select" id="employment_status" name="employment_status">
                                <option value="active" <?= $employee['employment_status'] == 'active' ? 'selected' : '' ?>>Active</option>
                                <option value="on_leave" <?= $employee['employment_status'] == 'on_leave' ? 'selected' : '' ?>>On Leave</option>
                                <option value="terminated" <?= $employee['employment_status'] == 'terminated' ? 'selected' : '' ?>>Terminated</option>
                                <option value="resigned" <?= $employee['employment_status'] == 'resigned' ? 'selected' : '' ?>>Resigned</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="manager_id" class="form-label">Reports To (Manager)</label>
                        <select class="form-select" id="manager_id" name="manager_id">
                            <option value="">No manager (Top level)</option>
                            <?php if (isset($employees)): ?>
                                <?php foreach ($employees as $emp): ?>
                                    <?php if ($emp['id'] != $employee['id']): ?>
                                    <option value="<?= $emp['id'] ?>" <?= ($employee['manager_id'] ?? '') == $emp['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($emp['full_name']) ?> (<?= htmlspecialchars($emp['designation']) ?>)
                                    </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3"><?= htmlspecialchars($employee['address'] ?? '') ?></textarea>
                    </div>

                    <?php if ($employee['employment_status'] !== 'active'): ?>
                    <div class="mb-3">
                        <label for="exit_date" class="form-label">Exit Date</label>
                        <input type="date" class="form-control" id="exit_date" name="exit_date" value="<?= $employee['exit_date'] ?? '' ?>">
                    </div>
                    <?php endif; ?>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Update Employee
                        </button>
                        <a href="<?= $this->getBaseUrl() ?>/employees/<?= $employee['id'] ?>" class="btn btn-outline-secondary">Cancel</a>
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
                    Profile Information
                </h6>
                <ul class="list-unstyled mb-0 small text-muted">
                    <li class="mb-2">
                        <i class="bi bi-calendar-event text-muted me-2"></i>
                        Created: <?= date('M d, Y', strtotime($employee['created_at'])) ?>
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-clock-history text-muted me-2"></i>
                        Updated: <?= date('M d, Y', strtotime($employee['updated_at'])) ?>
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
                    <li class="mb-2">• Employee ID must remain unique</li>
                    <li class="mb-2">• Email changes affect login credentials</li>
                    <li class="mb-2">• Manager changes update org chart</li>
                    <li>• Status changes may affect access</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = 'Edit Employee - ' . htmlspecialchars($employee['full_name']);
require __DIR__ . '/../layout.php';
