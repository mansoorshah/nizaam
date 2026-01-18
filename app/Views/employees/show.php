<?php ob_start(); ?>

<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= dirname($_SERVER['SCRIPT_NAME']) === '/' ? '' : dirname($_SERVER['SCRIPT_NAME']) ?>/dashboard">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= dirname($_SERVER['SCRIPT_NAME']) === '/' ? '' : dirname($_SERVER['SCRIPT_NAME']) ?>/employees">Employees</a></li>
        <li class="breadcrumb-item active"><?= htmlspecialchars($employee['full_name']) ?></li>
    </ol>
</nav>

<div class="row">
    <div class="col-md-4">
        <!-- Employee Profile Card -->
        <div class="card mb-4">
            <div class="card-body text-center">
                <div class="mb-3">
                    <div class="avatar-circle bg-primary text-white d-inline-flex align-items-center justify-content-center" 
                         style="width: 100px; height: 100px; border-radius: 50%; font-size: 2.5rem;">
                        <?= strtoupper(substr($employee['full_name'], 0, 1)) ?>
                    </div>
                </div>
                <h4 class="mb-1"><?= htmlspecialchars($employee['full_name']) ?></h4>
                <p class="text-muted mb-2"><?= htmlspecialchars($employee['designation']) ?></p>
                <p class="text-muted small mb-3">
                    <i class="bi bi-building"></i> <?= htmlspecialchars($employee['department']) ?>
                </p>
                <?php
                $statusColors = [
                    'active' => 'success',
                    'on_leave' => 'warning',
                    'terminated' => 'danger',
                    'resigned' => 'secondary'
                ];
                $badgeColor = $statusColors[$employee['employment_status']] ?? 'secondary';
                ?>
                <span class="badge bg-<?= $badgeColor ?>"><?= ucfirst(str_replace('_', ' ', $employee['employment_status'])) ?></span>
            </div>
        </div>

        <!-- Quick Info Card -->
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h6 class="mb-0 text-dark fw-semibold">Quick Information</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted d-block">Employee ID</small>
                    <strong><?= htmlspecialchars($employee['employee_id']) ?></strong>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">Email</small>
                    <strong><?= htmlspecialchars($employee['email']) ?></strong>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">Phone</small>
                    <strong><?= htmlspecialchars($employee['phone_number'] ?? 'N/A') ?></strong>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">Join Date</small>
                    <strong><?= date('M d, Y', strtotime($employee['join_date'])) ?></strong>
                </div>
                <?php if ($manager): ?>
                <div class="mb-3">
                    <small class="text-muted d-block">Reports To</small>
                    <a href="<?= dirname($_SERVER['SCRIPT_NAME']) === '/' ? '' : dirname($_SERVER['SCRIPT_NAME']) ?>/employees/<?= $manager['id'] ?>">
                        <?= htmlspecialchars($manager['full_name']) ?>
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Leave Balances Card -->
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0 text-dark fw-semibold">Leave Balances</h6>
            </div>
            <div class="card-body">
                <?php if (empty($leaveBalances)): ?>
                <p class="text-muted small mb-0">No leave balances available</p>
                <?php else: ?>
                <?php foreach ($leaveBalances as $balance): ?>
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <small class="text-muted"><?= htmlspecialchars($balance['leave_type_name']) ?></small>
                        <small class="fw-bold"><?= $balance['available'] ?> / <?= $balance['allocated'] ?> days</small>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <?php
                        $percentage = $balance['allocated'] > 0 ? ($balance['available'] / $balance['allocated'] * 100) : 0;
                        $progressColor = $percentage > 50 ? 'success' : ($percentage > 25 ? 'warning' : 'danger');
                        ?>
                        <div class="progress-bar bg-<?= $progressColor ?>" style="width: <?= $percentage ?>%"></div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <!-- Edit Button (Admin or Own Profile) -->
        <?php if ($this->isAdmin() || $this->getCurrentEmployee()['id'] == $employee['id']): ?>
        <div class="mb-4">
            <a href="<?= dirname($_SERVER['SCRIPT_NAME']) === '/' ? '' : dirname($_SERVER['SCRIPT_NAME']) ?>/employees/<?= $employee['id'] ?>/edit" class="btn btn-primary">
                <i class="bi bi-pencil"></i> Edit Profile
            </a>
        </div>
        <?php endif; ?>

        <!-- Personal Information -->
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h6 class="mb-0 text-dark fw-semibold">Personal Information</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Full Name</label>
                        <p class="mb-0"><?= htmlspecialchars($employee['full_name']) ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Employee ID</label>
                        <p class="mb-0"><?= htmlspecialchars($employee['employee_id']) ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Date of Birth</label>
                        <p class="mb-0"><?= isset($employee['date_of_birth']) && $employee['date_of_birth'] ? date('M d, Y', strtotime($employee['date_of_birth'])) : 'N/A' ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Gender</label>
                        <p class="mb-0"><?= isset($employee['gender']) && $employee['gender'] ? ucfirst($employee['gender']) : 'N/A' ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Phone Number</label>
                        <p class="mb-0"><?= htmlspecialchars($employee['phone_number'] ?? 'N/A') ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Email</label>
                        <p class="mb-0"><?= htmlspecialchars($employee['email']) ?></p>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="text-muted small">Address</label>
                        <p class="mb-0"><?= isset($employee['address']) && $employee['address'] ? nl2br(htmlspecialchars($employee['address'])) : 'N/A' ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Employment Information -->
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h6 class="mb-0 text-dark fw-semibold">Employment Information</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Designation</label>
                        <p class="mb-0"><?= htmlspecialchars($employee['designation']) ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Department</label>
                        <p class="mb-0"><?= htmlspecialchars($employee['department']) ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Join Date</label>
                        <p class="mb-0"><?= date('F d, Y', strtotime($employee['join_date'])) ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Employment Status</label>
                        <p class="mb-0">
                            <span class="badge bg-<?= $badgeColor ?>">
                                <?= ucfirst(str_replace('_', ' ', $employee['employment_status'])) ?>
                            </span>
                        </p>
                    </div>
                    <?php if ($manager): ?>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Reports To</label>
                        <p class="mb-0">
                            <a href="<?= dirname($_SERVER['SCRIPT_NAME']) === '/' ? '' : dirname($_SERVER['SCRIPT_NAME']) ?>/employees/<?= $manager['id'] ?>">
                                <?= htmlspecialchars($manager['full_name']) ?>
                            </a>
                        </p>
                    </div>
                    <?php endif; ?>
                    <?php if ($employee['exit_date']): ?>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Exit Date</label>
                        <p class="mb-0"><?= date('F d, Y', strtotime($employee['exit_date'])) ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Direct Reports -->
        <?php if (!empty($directReports)): ?>
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0 text-dark fw-semibold">Direct Reports (<?= count($directReports) ?>)</h6>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <?php foreach ($directReports as $report): ?>
                    <a href="<?= dirname($_SERVER['SCRIPT_NAME']) === '/' ? '' : dirname($_SERVER['SCRIPT_NAME']) ?>/employees/<?= $report['id'] ?>" 
                       class="list-group-item list-group-item-action">
                        <div class="d-flex align-items-center">
                            <div class="avatar-circle bg-secondary text-white d-inline-flex align-items-center justify-content-center me-3" 
                                 style="width: 40px; height: 40px; border-radius: 50%;">
                                <?= strtoupper(substr($report['full_name'], 0, 1)) ?>
                            </div>
                            <div>
                                <div class="fw-bold"><?= htmlspecialchars($report['full_name']) ?></div>
                                <small class="text-muted"><?= htmlspecialchars($report['designation']) ?></small>
                            </div>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = htmlspecialchars($employee['full_name']);
require __DIR__ . '/../layout.php';
?>
