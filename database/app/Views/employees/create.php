<?php ob_start(); ?>

<div class="mb-4">
    <a href="<?= $this->getBaseUrl() ?>/employees" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back to Employees
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white border-bottom">
                <h6 class="mb-0 text-dark fw-semibold">
                    <i class="bi bi-person-plus text-primary me-2"></i>
                    Add New Employee
                </h6>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= $this->getBaseUrl() ?>/employees/create">
                    <input type="hidden" name="csrf_token" value="<?= Session::csrfToken() ?>">

                    <div class="mb-3">
                        <label for="full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="full_name" name="full_name" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="employee_id" class="form-label">Employee ID <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="employee_id" name="employee_id" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone_number" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone_number" name="phone_number">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="date_of_birth" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" id="date_of_birth" name="date_of_birth">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="designation" class="form-label">Designation <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="designation" name="designation" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="department" class="form-label">Department <span class="text-danger">*</span></label>
                            <select class="form-select" id="department" name="department" required>
                                <option value="">Select department...</option>
                                <option value="Engineering">Engineering</option>
                                <option value="Sales">Sales</option>
                                <option value="Marketing">Marketing</option>
                                <option value="Finance">Finance</option>
                                <option value="Human Resources">Human Resources</option>
                                <option value="Operations">Operations</option>
                                <option value="Customer Support">Customer Support</option>
                                <option value="Product">Product</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="join_date" class="form-label">Join Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="join_date" name="join_date" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="employment_status" class="form-label">Employment Status</label>
                            <select class="form-select" id="employment_status" name="employment_status">
                                <option value="active" selected>Active</option>
                                <option value="on_leave">On Leave</option>
                                <option value="terminated">Terminated</option>
                                <option value="resigned">Resigned</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="manager_id" class="form-label">Reports To (Manager)</label>
                        <select class="form-select" id="manager_id" name="manager_id">
                            <option value="">No manager (Top level)</option>
                            <?php if (isset($employees)): ?>
                                <?php foreach ($employees as $emp): ?>
                                <option value="<?= $emp['id'] ?>">
                                    <?= htmlspecialchars($emp['full_name']) ?> (<?= htmlspecialchars($emp['designation']) ?>)
                                </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3"></textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Create Employee
                        </button>
                        <a href="<?= $this->getBaseUrl() ?>/employees" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="mb-3 text-dark fw-semibold">
                    <i class="bi bi-lightbulb text-warning me-2"></i>
                    Tips
                </h6>
                <ul class="list-unstyled small text-muted mb-0">
                    <li class="mb-2">• Employee ID should be unique</li>
                    <li class="mb-2">• Email will be used for login</li>
                    <li class="mb-2">• Default password: employee123</li>
                    <li class="mb-2">• Manager assignment creates reporting hierarchy</li>
                    <li>• All fields marked with * are required</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = 'Add New Employee';
require __DIR__ . '/../layout.php';
