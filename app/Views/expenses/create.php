<?php ob_start(); ?>

<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= dirname($_SERVER['SCRIPT_NAME']) === '/' ? '' : dirname($_SERVER['SCRIPT_NAME']) ?>/dashboard">Dashboard</a></li>
        <li class="breadcrumb-item active">Submit Expense</li>
    </ol>
</nav>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white">
                <h4 class="mb-0">Submit Expense Claim</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= dirname($_SERVER['SCRIPT_NAME']) === '/' ? '' : dirname($_SERVER['SCRIPT_NAME']) ?>/expenses/store">
                    <input type="hidden" name="csrf_token" value="<?= Session::get('csrf_token') ?>">

                    <div class="mb-3">
                        <label for="category" class="form-label">Expense Category <span class="text-danger">*</span></label>
                        <select class="form-select" id="category" name="category" required>
                            <option value="">Select category...</option>
                            <option value="Travel">Travel</option>
                            <option value="Meals">Meals & Entertainment</option>
                            <option value="Accommodation">Accommodation</option>
                            <option value="Supplies">Office Supplies</option>
                            <option value="Equipment">Equipment</option>
                            <option value="Training">Training & Development</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="amount" class="form-label">Amount <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="amount" name="amount" 
                                       step="0.01" min="0.01" required 
                                       placeholder="0.00">
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="date" class="form-label">Expense Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="date" name="date" 
                                   max="<?= date('Y-m-d') ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="description" name="description" 
                                  rows="4" required 
                                  placeholder="Provide details about this expense..."></textarea>
                    </div>

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        <strong>Note:</strong> Your expense will be submitted for manager approval. 
                        Please attach receipts if required by your company policy.
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="<?= dirname($_SERVER['SCRIPT_NAME']) === '/' ? '' : dirname($_SERVER['SCRIPT_NAME']) ?>/dashboard" class="btn btn-secondary">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Submit Expense
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-light">
            <div class="card-body">
                <h6 class="card-title">💡 Expense Guidelines</h6>
                <ul class="small mb-0">
                    <li>Keep all receipts for reimbursement</li>
                    <li>Submit expenses within 30 days</li>
                    <li>Provide clear descriptions</li>
                    <li>Follow company expense policy</li>
                    <li>Manager approval required for amounts over policy limits</li>
                </ul>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <h6 class="card-title">📋 Approval Process</h6>
                <div class="small">
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-warning me-2">1</span>
                        <span>Manager Review</span>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-warning me-2">2</span>
                        <span>Finance Review</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-success me-2">3</span>
                        <span>Approved & Processed</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = 'Submit Expense';
require __DIR__ . '/../layout.php';
?>
