<?php 
ob_start(); 

// Get current employee and their projects
$employeeModel = new Employee();
$projectModel = new Project();
$employee = null;

if (isset($_SESSION['user_id'])) {
    $employee = $employeeModel->findByUserId($_SESSION['user_id']);
}

// Get projects the employee is assigned to
$projects = [];
if ($employee) {
    $sql = "SELECT p.id, p.name, pm.created_at as joined_at
            FROM projects p
            INNER JOIN project_members pm ON p.id = pm.project_id
            WHERE pm.employee_id = ? AND p.status = 'active'
            ORDER BY p.name";
    $projects = $projectModel->db->fetchAll($sql, [$employee['id']]);
}
?>

<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= dirname($_SERVER['SCRIPT_NAME']) === '/' ? '' : dirname($_SERVER['SCRIPT_NAME']) ?>/dashboard">Timesheet Management</a></li>
        <li class="breadcrumb-item active">Add Timesheet</li>
    </ol>
</nav>

<div class="card shadow-sm">
    <div class="card-body p-4">
        <h4 class="mb-4">Add Timesheet</h4>
        
        <form method="POST" action="<?= dirname($_SERVER['SCRIPT_NAME']) === '/' ? '' : dirname($_SERVER['SCRIPT_NAME']) ?>/timesheets/store" id="timesheetForm">
            <input type="hidden" name="csrf_token" value="<?= Session::get('csrf_token') ?>">

            <!-- Week Period Selector -->
            <div class="mb-4">
                <label for="week_period" class="form-label fw-semibold">
                    Week Period<span class="text-danger">*</span>
                </label>
                <input type="week" class="form-control" id="week_period" name="week_period" required 
                       value="<?= date('Y') ?>-W<?= date('W') ?>">
                <div class="form-text">Select the week for this timesheet</div>
            </div>

            <?php if (empty($projects)): ?>
            <div class="alert alert-info mb-4">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Note:</strong> You are not currently assigned to any active projects. 
                You can still log hours for general work, overhead activities, or training. 
                Enter a description in the "PROJECT INFO" field.
            </div>
            <?php endif; ?>

            <!-- Legend -->
            <div class="mb-3 d-flex gap-3 align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary">InProgress Leave</span>
                    <span class="badge bg-warning text-dark">Approved Leave</span>
                    <span class="badge" style="background-color: #e91e63;">Holiday</span>
                </div>
            </div>

            <!-- Timesheet Table -->
            <div class="table-responsive mb-4">
                <table class="table table-bordered align-middle" id="timesheetTable">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 25%;">PROJECT INFO</th>
                            <th style="width: 15%;">ROLE</th>
                            <th class="text-center" style="width: 8%;">SUN</th>
                            <th class="text-center" style="width: 8%;">MON</th>
                            <th class="text-center" style="width: 8%;">TUE</th>
                            <th class="text-center" style="width: 8%;">WED</th>
                            <th class="text-center" style="width: 8%;">THU</th>
                            <th class="text-center" style="width: 8%;">FRI</th>
                            <th class="text-center" style="width: 8%;">SAT</th>
                            <th class="text-center" style="width: 10%;">TOTAL</th>
                            <th style="width: 5%;"></th>
                        </tr>
                    </thead>
                    <tbody id="projectRows">
                        <!-- Always show at least one row, even if no projects -->
                        <tr class="project-row" data-row-index="0">
                            <td>
                                <?php if (!empty($projects)): ?>
                                <select class="form-select form-select-sm project-select" name="entries[0][project_id]">
                                    <option value="">General/Overhead</option>
                                    <?php foreach ($projects as $proj): ?>
                                    <option value="<?= $proj['id'] ?>"><?= htmlspecialchars($proj['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <?php else: ?>
                                <input type="text" class="form-control form-control-sm" 
                                       name="entries[0][project_name]" 
                                       value="General/Overhead Work" 
                                       placeholder="Enter project or work type">
                                <input type="hidden" name="entries[0][project_id]" value="">
                                <?php endif; ?>
                            </td>
                            <td>
                                <input type="text" class="form-control form-control-sm" 
                                       name="entries[0][role]" placeholder="Your role" required>
                            </td>
                            <td><input type="number" class="form-control form-control-sm text-center hours-input" 
                                       name="entries[0][hours][sunday]" min="0" max="24" step="0.5" value="0"></td>
                            <td><input type="number" class="form-control form-control-sm text-center hours-input" 
                                       name="entries[0][hours][monday]" min="0" max="24" step="0.5" value="0"></td>
                            <td><input type="number" class="form-control form-control-sm text-center hours-input" 
                                       name="entries[0][hours][tuesday]" min="0" max="24" step="0.5" value="0"></td>
                            <td><input type="number" class="form-control form-control-sm text-center hours-input" 
                                       name="entries[0][hours][wednesday]" min="0" max="24" step="0.5" value="0"></td>
                            <td><input type="number" class="form-control form-control-sm text-center hours-input" 
                                       name="entries[0][hours][thursday]" min="0" max="24" step="0.5" value="0"></td>
                            <td><input type="number" class="form-control form-control-sm text-center hours-input" 
                                       name="entries[0][hours][friday]" min="0" max="24" step="0.5" value="0"></td>
                            <td><input type="number" class="form-control form-control-sm text-center hours-input" 
                                       name="entries[0][hours][saturday]" min="0" max="24" step="0.5" value="0"></td>
                            <td class="text-center">
                                <strong class="row-total">0</strong>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-danger remove-row" style="display:none;">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr class="table-light fw-bold">
                            <td colspan="2" class="text-end">TOTAL</td>
                            <td class="text-center day-total" data-day="sunday">0</td>
                            <td class="text-center day-total" data-day="monday">0</td>
                            <td class="text-center day-total" data-day="tuesday">0</td>
                            <td class="text-center day-total" data-day="wednesday">0</td>
                            <td class="text-center day-total" data-day="thursday">0</td>
                            <td class="text-center day-total" data-day="friday">0</td>
                            <td class="text-center day-total" data-day="saturday">0</td>
                            <td class="text-center" id="grandTotal">0</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Add New Button -->
            <div class="mb-4">
                <button type="button" class="btn btn-outline-primary" id="addNewRow">
                    <i class="bi bi-plus-circle me-2"></i>ADD NEW
                </button>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex gap-2 justify-content-end">
                <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                    <i class="bi bi-arrow-left me-2"></i>Back
                </button>
                <button type="button" class="btn btn-outline-secondary">
                    <i class="bi bi-three-dots me-2"></i>MORE
                </button>
                <button type="button" class="btn btn-secondary">
                    <i class="bi bi-save me-2"></i>SAVE
                </button>
                <button type="submit" class="btn btn-primary" style="background-color: #3b82f6; border-color: #3b82f6;">
                    <i class="bi bi-check-circle me-2"></i>SUBMIT FOR APPROVAL
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.table th {
    background-color: #f8f9fa;
    font-weight: 600;
    font-size: 0.75rem;
    color: #6c757d;
    padding: 0.75rem 0.5rem;
}

.table td {
    padding: 0.5rem;
    vertical-align: middle;
}

.hours-input {
    max-width: 70px;
    padding: 0.25rem 0.5rem;
}

.project-select {
    font-size: 0.875rem;
}

.row-total {
    color: #3b82f6;
    font-size: 1.1rem;
}

#grandTotal {
    color: #3b82f6;
    font-size: 1.25rem;
    font-weight: 700;
}

.day-total {
    font-weight: 600;
    color: #495057;
}

.remove-row {
    padding: 0.25rem 0.5rem;
}

.badge {
    font-size: 0.75rem;
    padding: 0.35rem 0.65rem;
}
</style>

<script>
let rowIndex = 1;
const projectsList = <?= json_encode($projects ?? []) ?>;

// Calculate row total
function calculateRowTotal(row) {
    let total = 0;
    row.querySelectorAll('.hours-input').forEach(input => {
        total += parseFloat(input.value) || 0;
    });
    row.querySelector('.row-total').textContent = total.toFixed(0);
    calculateDayTotals();
    calculateGrandTotal();
}

// Calculate day totals (column sums)
function calculateDayTotals() {
    const days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
    
    days.forEach((day, index) => {
        let dayTotal = 0;
        document.querySelectorAll(`.project-row input[name$="[hours][${day}]"]`).forEach(input => {
            dayTotal += parseFloat(input.value) || 0;
        });
        
        const dayTotalCell = document.querySelector(`.day-total[data-day="${day}"]`);
        if (dayTotalCell) {
            dayTotalCell.textContent = dayTotal.toFixed(0);
        }
    });
}

// Calculate grand total
function calculateGrandTotal() {
    let grandTotal = 0;
    document.querySelectorAll('.row-total').forEach(cell => {
        grandTotal += parseFloat(cell.textContent) || 0;
    });
    document.getElementById('grandTotal').textContent = grandTotal.toFixed(0);
}

// Add event listeners to existing rows
function attachRowListeners(row) {
    row.querySelectorAll('.hours-input').forEach(input => {
        input.addEventListener('input', () => calculateRowTotal(row));
    });
    
    const removeBtn = row.querySelector('.remove-row');
    if (removeBtn) {
        removeBtn.addEventListener('click', () => {
            row.remove();
            calculateDayTotals();
            calculateGrandTotal();
            updateRemoveButtons();
        });
    }
}

// Update visibility of remove buttons
function updateRemoveButtons() {
    const rows = document.querySelectorAll('.project-row');
    rows.forEach((row, index) => {
        const removeBtn = row.querySelector('.remove-row');
        if (removeBtn) {
            removeBtn.style.display = rows.length > 1 ? 'inline-block' : 'none';
        }
    });
}

// Add new project row
document.getElementById('addNewRow')?.addEventListener('click', function() {
    const tbody = document.getElementById('projectRows');
    const newRow = document.createElement('tr');
    newRow.className = 'project-row';
    newRow.dataset.rowIndex = rowIndex;
    
    // Create project field based on whether projects exist
    let projectField = '';
    if (projectsList && projectsList.length > 0) {
        let projectOptions = '<option value="">General/Overhead</option>';
        projectsList.forEach(proj => {
            projectOptions += `<option value="${proj.id}">${proj.name}</option>`;
        });
        projectField = `
            <select class="form-select form-select-sm project-select" name="entries[${rowIndex}][project_id]">
                ${projectOptions}
            </select>
        `;
    } else {
        projectField = `
            <input type="text" class="form-control form-control-sm" 
                   name="entries[${rowIndex}][project_name]" 
                   placeholder="Enter project or work type">
            <input type="hidden" name="entries[${rowIndex}][project_id]" value="">
        `;
    }
    
    newRow.innerHTML = `
        <td>
            ${projectField}
        </td>
        <td>
            <input type="text" class="form-control form-control-sm" 
                   name="entries[${rowIndex}][role]" placeholder="Your role" required>
        </td>
        <td><input type="number" class="form-control form-control-sm text-center hours-input" 
                   name="entries[${rowIndex}][hours][sunday]" min="0" max="24" step="0.5" value="0"></td>
        <td><input type="number" class="form-control form-control-sm text-center hours-input" 
                   name="entries[${rowIndex}][hours][monday]" min="0" max="24" step="0.5" value="0"></td>
        <td><input type="number" class="form-control form-control-sm text-center hours-input" 
                   name="entries[${rowIndex}][hours][tuesday]" min="0" max="24" step="0.5" value="0"></td>
        <td><input type="number" class="form-control form-control-sm text-center hours-input" 
                   name="entries[${rowIndex}][hours][wednesday]" min="0" max="24" step="0.5" value="0"></td>
        <td><input type="number" class="form-control form-control-sm text-center hours-input" 
                   name="entries[${rowIndex}][hours][thursday]" min="0" max="24" step="0.5" value="0"></td>
        <td><input type="number" class="form-control form-control-sm text-center hours-input" 
                   name="entries[${rowIndex}][hours][friday]" min="0" max="24" step="0.5" value="0"></td>
        <td><input type="number" class="form-control form-control-sm text-center hours-input" 
                   name="entries[${rowIndex}][hours][saturday]" min="0" max="24" step="0.5" value="0"></td>
        <td class="text-center">
            <strong class="row-total">0</strong>
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-sm btn-outline-danger remove-row">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    `;
    
    tbody.appendChild(newRow);
    attachRowListeners(newRow);
    rowIndex++;
    updateRemoveButtons();
});

// Initialize existing rows
document.querySelectorAll('.project-row').forEach(row => {
    attachRowListeners(row);
});

updateRemoveButtons();
</script>

<?php
$content = ob_get_clean();
$title = 'Add Timesheet';
require __DIR__ . '/../layout.php';
?>

<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= dirname($_SERVER['SCRIPT_NAME']) === '/' ? '' : dirname($_SERVER['SCRIPT_NAME']) ?>/dashboard">Timesheet Management</a></li>
        <li class="breadcrumb-item active">Add Timesheet</li>
    </ol>
</nav>

<div class="card shadow-sm">
    <div class="card-body p-4">
        <h4 class="mb-4">Add Timesheet</h4>
        
        <form method="POST" action="<?= dirname($_SERVER['SCRIPT_NAME']) === '/' ? '' : dirname($_SERVER['SCRIPT_NAME']) ?>/timesheets/store" id="timesheetForm">
            <input type="hidden" name="csrf_token" value="<?= Session::get('csrf_token') ?>">

            <!-- Week Period Selector -->
            <div class="mb-4">
                <label for="week_period" class="form-label fw-semibold">
                    Week Period<span class="text-danger">*</span>
                </label>
                <input type="week" class="form-control" id="week_period" name="week_period" required 
                       value="<?= date('Y') ?>-W<?= date('W') ?>">
                <div class="form-text">Select the week for this timesheet</div>
            </div>

            <?php if (empty($projects)): ?>
            <div class="alert alert-info mb-4">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Note:</strong> You are not currently assigned to any active projects. 
                You can still log hours for general work, overhead activities, or training. 
                Enter a description in the "PROJECT INFO" field.
            </div>
            <?php endif; ?>

            <!-- Legend -->
            <div class="mb-3 d-flex gap-3 align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary">InProgress Leave</span>
                    <span class="badge bg-warning text-dark">Approved Leave</span>
                    <span class="badge" style="background-color: #e91e63;">Holiday</span>
                </div>
            </div>

            <!-- Timesheet Table -->
            <div class="table-responsive mb-4">
                <table class="table table-bordered align-middle" id="timesheetTable">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 25%;">PROJECT INFO</th>
                            <th style="width: 15%;">ROLE</th>
                            <th class="text-center" style="width: 8%;">SUN</th>
                            <th class="text-center" style="width: 8%;">MON</th>
                            <th class="text-center" style="width: 8%;">TUE</th>
                            <th class="text-center" style="width: 8%;">WED</th>
                            <th class="text-center" style="width: 8%;">THU</th>
                            <th class="text-center" style="width: 8%;">FRI</th>
                            <th class="text-center" style="width: 8%;">SAT</th>
                            <th class="text-center" style="width: 10%;">TOTAL</th>
                            <th style="width: 5%;"></th>
                        </tr>
                    </thead>
                    <tbody id="projectRows">
                        <!-- Always show at least one row, even if no projects -->
                        <tr class="project-row" data-row-index="0">
                            <td>
                                <?php if (!empty($projects)): ?>
                                <select class="form-select form-select-sm project-select" name="entries[0][project_id]">
                                    <option value="">General/Overhead</option>
                                    <?php foreach ($projects as $proj): ?>
                                    <option value="<?= $proj['id'] ?>"><?= htmlspecialchars($proj['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <?php else: ?>
                                <input type="text" class="form-control form-control-sm" 
                                       name="entries[0][project_name]" 
                                       value="General/Overhead Work" 
                                       placeholder="Enter project or work type">
                                <input type="hidden" name="entries[0][project_id]" value="">
                                <?php endif; ?>
                            </td>
                            <td>
                                <input type="text" class="form-control form-control-sm" 
                                       name="entries[0][role]" placeholder="Your role" required>
                            </td>
                            <td><input type="number" class="form-control form-control-sm text-center hours-input" 
                                       name="entries[0][hours][sunday]" min="0" max="24" step="0.5" value="0"></td>
                            <td><input type="number" class="form-control form-control-sm text-center hours-input" 
                                       name="entries[0][hours][monday]" min="0" max="24" step="0.5" value="0"></td>
                            <td><input type="number" class="form-control form-control-sm text-center hours-input" 
                                       name="entries[0][hours][tuesday]" min="0" max="24" step="0.5" value="0"></td>
                            <td><input type="number" class="form-control form-control-sm text-center hours-input" 
                                       name="entries[0][hours][wednesday]" min="0" max="24" step="0.5" value="0"></td>
                            <td><input type="number" class="form-control form-control-sm text-center hours-input" 
                                       name="entries[0][hours][thursday]" min="0" max="24" step="0.5" value="0"></td>
                            <td><input type="number" class="form-control form-control-sm text-center hours-input" 
                                       name="entries[0][hours][friday]" min="0" max="24" step="0.5" value="0"></td>
                            <td><input type="number" class="form-control form-control-sm text-center hours-input" 
                                       name="entries[0][hours][saturday]" min="0" max="24" step="0.5" value="0"></td>
                            <td class="text-center">
                                <strong class="row-total">0</strong>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-danger remove-row" style="display:none;">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr class="table-light fw-bold">
                            <td colspan="2" class="text-end">TOTAL</td>
                            <td class="text-center day-total" data-day="sunday">0</td>
                            <td class="text-center day-total" data-day="monday">0</td>
                            <td class="text-center day-total" data-day="tuesday">0</td>
                            <td class="text-center day-total" data-day="wednesday">0</td>
                            <td class="text-center day-total" data-day="thursday">0</td>
                            <td class="text-center day-total" data-day="friday">0</td>
                            <td class="text-center day-total" data-day="saturday">0</td>
                            <td class="text-center" id="grandTotal">0</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Add New Button -->
            <div class="mb-4">
                <button type="button" class="btn btn-outline-primary" id="addNewRow">
                    <i class="bi bi-plus-circle me-2"></i>ADD NEW
                </button>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex gap-2 justify-content-end">
                <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                    <i class="bi bi-arrow-left me-2"></i>Back
                </button>
                <button type="button" class="btn btn-outline-secondary">
                    <i class="bi bi-three-dots me-2"></i>MORE
                </button>
                <button type="button" class="btn btn-secondary">
                    <i class="bi bi-save me-2"></i>SAVE
                </button>
                <button type="submit" class="btn btn-primary" style="background-color: #3b82f6; border-color: #3b82f6;">
                    <i class="bi bi-check-circle me-2"></i>SUBMIT FOR APPROVAL
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.table th {
    background-color: #f8f9fa;
    font-weight: 600;
    font-size: 0.75rem;
    color: #6c757d;
    padding: 0.75rem 0.5rem;
}

.table td {
    padding: 0.5rem;
    vertical-align: middle;
}

.hours-input {
    max-width: 70px;
    padding: 0.25rem 0.5rem;
}

.project-select {
    font-size: 0.875rem;
}

.row-total {
    color: #3b82f6;
    font-size: 1.1rem;
}

#grandTotal {
    color: #3b82f6;
    font-size: 1.25rem;
    font-weight: 700;
}

.day-total {
    font-weight: 600;
    color: #495057;
}

.remove-row {
    padding: 0.25rem 0.5rem;
}

.badge {
    font-size: 0.75rem;
    padding: 0.35rem 0.65rem;
}
</style>

<script>
let rowIndex = 1;
const projectsList = <?= json_encode($projects ?? []) ?>;

// Calculate row total
function calculateRowTotal(row) {
    let total = 0;
    row.querySelectorAll('.hours-input').forEach(input => {
        total += parseFloat(input.value) || 0;
    });
    row.querySelector('.row-total').textContent = total.toFixed(0);
    calculateDayTotals();
    calculateGrandTotal();
}

// Calculate day totals (column sums)
function calculateDayTotals() {
    const days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
    
    days.forEach((day, index) => {
        let dayTotal = 0;
        document.querySelectorAll(`.project-row input[name$="[hours][${day}]"]`).forEach(input => {
            dayTotal += parseFloat(input.value) || 0;
        });
        
        const dayTotalCell = document.querySelector(`.day-total[data-day="${day}"]`);
        if (dayTotalCell) {
            dayTotalCell.textContent = dayTotal.toFixed(0);
        }
    });
}

// Calculate grand total
function calculateGrandTotal() {
    let grandTotal = 0;
    document.querySelectorAll('.row-total').forEach(cell => {
        grandTotal += parseFloat(cell.textContent) || 0;
    });
    document.getElementById('grandTotal').textContent = grandTotal.toFixed(0);
}

// Add event listeners to existing rows
function attachRowListeners(row) {
    row.querySelectorAll('.hours-input').forEach(input => {
        input.addEventListener('input', () => calculateRowTotal(row));
    });
    
    const removeBtn = row.querySelector('.remove-row');
    if (removeBtn) {
        removeBtn.addEventListener('click', () => {
            row.remove();
            calculateDayTotals();
            calculateGrandTotal();
            updateRemoveButtons();
        });
    }
}

// Update visibility of remove buttons
function updateRemoveButtons() {
    const rows = document.querySelectorAll('.project-row');
    rows.forEach((row, index) => {
        const removeBtn = row.querySelector('.remove-row');
        if (removeBtn) {
            removeBtn.style.display = rows.length > 1 ? 'inline-block' : 'none';
        }
    });
}

// Add new project row
document.getElementById('addNewRow')?.addEventListener('click', function() {
    const tbody = document.getElementById('projectRows');
    const newRow = document.createElement('tr');
    newRow.className = 'project-row';
    newRow.dataset.rowIndex = rowIndex;
    
    // Create project field based on whether projects exist
    let projectField = '';
    if (projectsList && projectsList.length > 0) {
        let projectOptions = '<option value="">General/Overhead</option>';
        projectsList.forEach(proj => {
            projectOptions += `<option value="${proj.id}">${proj.name}</option>`;
        });
        projectField = `
            <select class="form-select form-select-sm project-select" name="entries[${rowIndex}][project_id]">
                ${projectOptions}
            </select>
        `;
    } else {
        projectField = `
            <input type="text" class="form-control form-control-sm" 
                   name="entries[${rowIndex}][project_name]" 
                   placeholder="Enter project or work type">
            <input type="hidden" name="entries[${rowIndex}][project_id]" value="">
        `;
    }
    
    newRow.innerHTML = `
        <td>
            ${projectField}
        </td>
        <td>
            <input type="text" class="form-control form-control-sm" 
                   name="entries[${rowIndex}][role]" placeholder="Your role" required>
        </td>
        <td><input type="number" class="form-control form-control-sm text-center hours-input" 
                   name="entries[${rowIndex}][hours][sunday]" min="0" max="24" step="0.5" value="0"></td>
        <td><input type="number" class="form-control form-control-sm text-center hours-input" 
                   name="entries[${rowIndex}][hours][monday]" min="0" max="24" step="0.5" value="0"></td>
        <td><input type="number" class="form-control form-control-sm text-center hours-input" 
                   name="entries[${rowIndex}][hours][tuesday]" min="0" max="24" step="0.5" value="0"></td>
        <td><input type="number" class="form-control form-control-sm text-center hours-input" 
                   name="entries[${rowIndex}][hours][wednesday]" min="0" max="24" step="0.5" value="0"></td>
        <td><input type="number" class="form-control form-control-sm text-center hours-input" 
                   name="entries[${rowIndex}][hours][thursday]" min="0" max="24" step="0.5" value="0"></td>
        <td><input type="number" class="form-control form-control-sm text-center hours-input" 
                   name="entries[${rowIndex}][hours][friday]" min="0" max="24" step="0.5" value="0"></td>
        <td><input type="number" class="form-control form-control-sm text-center hours-input" 
                   name="entries[${rowIndex}][hours][saturday]" min="0" max="24" step="0.5" value="0"></td>
        <td class="text-center">
            <strong class="row-total">0</strong>
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-sm btn-outline-danger remove-row">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    `;
    
    tbody.appendChild(newRow);
    attachRowListeners(newRow);
    rowIndex++;
    updateRemoveButtons();
});

// Initialize existing rows
document.querySelectorAll('.project-row').forEach(row => {
    attachRowListeners(row);
});

updateRemoveButtons();
</script>

<?php
$content = ob_get_clean();
$title = 'Add Timesheet';
require __DIR__ . '/../layout.php';
?>
                    </div>

                    <div id="weeksContainer">
                        <!-- Week 1 (Default) -->
                        <div class="week-section card mb-4 border-primary" data-week-index="0">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="bi bi-calendar-week text-primary me-2"></i>
                                    Week <span class="week-number">1</span>
                                    <span class="badge bg-primary ms-2 week-date-range"></span>
                                </h5>
                                <button type="button" class="btn btn-sm btn-outline-danger remove-week" style="display: none;">
                                    <i class="bi bi-trash"></i> Remove
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Week Starting <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control week-start-date" name="weeks[0][week_start]" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Week Ending <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control week-end-date" name="weeks[0][week_end]" required>
                                    </div>
                                </div>

                                <label class="form-label fw-semibold mb-3">Daily Hours <span class="text-danger">*</span></label>
                                <div class="row g-3">
                                    <?php 
                                    $days = [
                                        'monday' => 'Monday',
                                        'tuesday' => 'Tuesday',
                                        'wednesday' => 'Wednesday',
                                        'thursday' => 'Thursday',
                                        'friday' => 'Friday',
                                        'saturday' => 'Saturday',
                                        'sunday' => 'Sunday'
                                    ];
                                    foreach ($days as $key => $label): 
                                    ?>
                                    <div class="col-md-6 col-lg-3">
                                        <div class="card border">
                                            <div class="card-body p-3">
                                                <label class="form-label small text-muted mb-2"><?= $label ?></label>
                                                <input type="number" class="form-control daily-hours" 
                                                       name="weeks[0][hours][<?= $key ?>]" 
                                                       min="0" max="24" step="0.5" value="0" required>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>

                                <div class="mt-3 p-3 bg-light rounded">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-semibold">Week Total:</span>
                                        <span class="badge bg-primary fs-6 week-total">0.0 hours</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Add Week Button -->
                    <div class="text-center mb-4">
                        <button type="button" class="btn btn-outline-primary btn-lg" id="addWeekBtn">
                            <i class="bi bi-plus-circle me-2"></i>Add Another Week
                        </button>
                    </div>

                    <!-- Monthly Total -->
                    <div class="card border-primary mb-4">
                        <div class="card-body bg-primary bg-opacity-10">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="bi bi-calculator me-2"></i>Monthly Total:</h5>
                                <h4 class="mb-0 text-primary" id="monthlyTotal">0.0 hours</h4>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="notes" class="form-label fw-semibold">Additional Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" 
                                  placeholder="Any comments or explanations for this month's timesheet..."></textarea>
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="<?= dirname($_SERVER['SCRIPT_NAME']) === '/' ? '' : dirname($_SERVER['SCRIPT_NAME']) ?>/timesheets" 
                           class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-check-circle me-2"></i>Submit Timesheet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
let weekIndex = 1;

// Calculate week total
function calculateWeekTotal(weekSection) {
    let total = 0;
    weekSection.querySelectorAll('.daily-hours').forEach(input => {
        total += parseFloat(input.value) || 0;
    });
    weekSection.querySelector('.week-total').textContent = total.toFixed(1) + ' hours';
    calculateMonthlyTotal();
}

// Calculate monthly total
function calculateMonthlyTotal() {
    let total = 0;
    document.querySelectorAll('.week-total').forEach(span => {
        total += parseFloat(span.textContent) || 0;
    });
    document.getElementById('monthlyTotal').textContent = total.toFixed(1) + ' hours';
}

// Update week date range display
function updateWeekDateRange(weekSection) {
    const startDate = weekSection.querySelector('.week-start-date').value;
    const endDate = weekSection.querySelector('.week-end-date').value;
    const rangeSpan = weekSection.querySelector('.week-date-range');
    
    if (startDate && endDate) {
        const start = new Date(startDate);
        const end = new Date(endDate);
        const startStr = start.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        const endStr = end.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        rangeSpan.textContent = `${startStr} - ${endStr}`;
    }
}

// Add week section
document.getElementById('addWeekBtn').addEventListener('click', function() {
    const container = document.getElementById('weeksContainer');
    const newWeek = document.querySelector('.week-section').cloneNode(true);
    
    newWeek.dataset.weekIndex = weekIndex;
    newWeek.querySelector('.week-number').textContent = weekIndex + 1;
    newWeek.querySelector('.week-date-range').textContent = '';
    
    // Update input names
    newWeek.querySelectorAll('input, textarea').forEach(input => {
        if (input.name) {
            input.name = input.name.replace(/weeks\[\d+\]/, `weeks[${weekIndex}]`);
            if (input.type === 'number') {
                input.value = '0';
            } else {
                input.value = '';
            }
        }
    });
    
    // Show remove button
    newWeek.querySelector('.remove-week').style.display = 'block';
    
    container.appendChild(newWeek);
    weekIndex++;
    
    // Re-attach event listeners
    attachWeekEventListeners(newWeek);
    calculateMonthlyTotal();
});

// Attach event listeners to a week section
function attachWeekEventListeners(weekSection) {
    // Calculate on input change
    weekSection.querySelectorAll('.daily-hours').forEach(input => {
        input.addEventListener('input', () => calculateWeekTotal(weekSection));
    });
    
    // Update date range
    weekSection.querySelector('.week-start-date').addEventListener('change', () => {
        updateWeekDateRange(weekSection);
        // Auto-set end date to 6 days after start
        const startDate = new Date(weekSection.querySelector('.week-start-date').value);
        if (startDate) {
            const endDate = new Date(startDate);
            endDate.setDate(endDate.getDate() + 6);
            weekSection.querySelector('.week-end-date').value = endDate.toISOString().split('T')[0];
            updateWeekDateRange(weekSection);
        }
    });
    
    weekSection.querySelector('.week-end-date').addEventListener('change', () => {
        updateWeekDateRange(weekSection);
    });
    
    // Remove week
    weekSection.querySelector('.remove-week').addEventListener('click', function() {
        if (document.querySelectorAll('.week-section').length > 1) {
            weekSection.remove();
            // Renumber weeks
            document.querySelectorAll('.week-section').forEach((section, index) => {
                section.querySelector('.week-number').textContent = index + 1;
            });
            calculateMonthlyTotal();
        }
    });
}

// Initialize first week
document.addEventListener('DOMContentLoaded', function() {
    const firstWeek = document.querySelector('.week-section');
    attachWeekEventListeners(firstWeek);
    
    // Set default week start to current Monday
    const today = new Date();
    const monday = new Date(today);
    monday.setDate(today.getDate() - today.getDay() + 1);
    firstWeek.querySelector('.week-start-date').value = monday.toISOString().split('T')[0];
    
    const sunday = new Date(monday);
    sunday.setDate(monday.getDate() + 6);
    firstWeek.querySelector('.week-end-date').value = sunday.toISOString().split('T')[0];
    
    updateWeekDateRange(firstWeek);
});
</script>

<style>
.week-section {
    transition: all 0.3s ease;
}

.week-section:hover {
    box-shadow: 0 0.5rem 1rem rgba(59, 130, 246, 0.15) !important;
}

.daily-hours:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
}

.card.border {
    transition: all 0.2s ease;
}

.card.border:hover {
    border-color: #3b82f6 !important;
    transform: translateY(-2px);
}
</style>
                                <label class="small text-muted">Monday</label>
                                <input type="number" class="form-control" name="hours[monday]" 
                                       min="0" max="24" step="0.5" value="8" required>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted">Tuesday</label>
                                <input type="number" class="form-control" name="hours[tuesday]" 
                                       min="0" max="24" step="0.5" value="8" required>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted">Wednesday</label>
                                <input type="number" class="form-control" name="hours[wednesday]" 
                                       min="0" max="24" step="0.5" value="8" required>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted">Thursday</label>
                                <input type="number" class="form-control" name="hours[thursday]" 
                                       min="0" max="24" step="0.5" value="8" required>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted">Friday</label>
                                <input type="number" class="form-control" name="hours[friday]" 
                                       min="0" max="24" step="0.5" value="8" required>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted">Saturday</label>
                                <input type="number" class="form-control" name="hours[saturday]" 
                                       min="0" max="24" step="0.5" value="0">
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted">Sunday</label>
                                <input type="number" class="form-control" name="hours[sunday]" 
                                       min="0" max="24" step="0.5" value="0">
                            </div>
                            <div class="col-md-6">
                                <label class="small fw-bold">Total Hours</label>
                                <input type="text" class="form-control fw-bold" id="totalHours" 
                                       value="40" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea id="notes" name="notes" style="display:none;"></textarea>
                        <div class="rich-editor" style="min-height: 150px;" data-placeholder="Add any notes about this week's work. You can format text and paste images if needed..."></div>
                    </div>

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        <strong>Note:</strong> Your timesheet will be submitted for manager approval. 
                        Ensure all hours are accurate and complete.
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="<?= dirname($_SERVER['SCRIPT_NAME']) === '/' ? '' : dirname($_SERVER['SCRIPT_NAME']) ?>/dashboard" class="btn btn-secondary">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Submit Timesheet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-light">
            <div class="card-body">
                <h6 class="card-title">⏱️ Timesheet Guidelines</h6>
                <ul class="small mb-0">
                    <li>Submit timesheets weekly</li>
                    <li>Record actual hours worked</li>
                    <li>Include overtime if applicable</li>
                    <li>Standard workweek is 40 hours</li>
                    <li>Manager approval required</li>
                </ul>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <h6 class="card-title">📋 Approval Process</h6>
                <div class="small">
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-secondary me-2">1</span>
                        <span>Draft</span>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-warning me-2">2</span>
                        <span>Manager Review</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-success me-2">3</span>
                        <span>Approved</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Calculate total hours
document.querySelectorAll('input[name^="hours"]').forEach(input => {
    input.addEventListener('input', function() {
        let total = 0;
        document.querySelectorAll('input[name^="hours"]').forEach(field => {
            total += parseFloat(field.value) || 0;
        });
        document.getElementById('totalHours').value = total.toFixed(1);
    });
});
</script>

<?php
$content = ob_get_clean();
$title = 'Log Timesheet';
require __DIR__ . '/../layout.php';
?>
