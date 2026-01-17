<?php ob_start(); ?>

<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= dirname($_SERVER['SCRIPT_NAME']) === '/' ? '' : dirname($_SERVER['SCRIPT_NAME']) ?>/dashboard">Dashboard</a></li>
        <li class="breadcrumb-item active">Log Timesheet</li>
    </ol>
</nav>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white">
                <h4 class="mb-0">Log Weekly Timesheet</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= dirname($_SERVER['SCRIPT_NAME']) === '/' ? '' : dirname($_SERVER['SCRIPT_NAME']) ?>/timesheets/store">
                    <input type="hidden" name="csrf_token" value="<?= Session::get('csrf_token') ?>">

                    <div class="mb-4">
                        <label for="week_ending" class="form-label">Week Ending <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="week_ending" name="week_ending" 
                               required>
                        <small class="text-muted">Select the Saturday/Sunday of the week you're logging</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Daily Hours <span class="text-danger">*</span></label>
                        <div class="row g-2">
                            <div class="col-md-6">
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
                        <textarea class="form-control" id="notes" name="notes" 
                                  rows="3" 
                                  placeholder="Add any notes about this week's work (optional)"></textarea>
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
