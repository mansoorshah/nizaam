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
