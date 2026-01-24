<?php ob_start(); ?>

<div class="mb-4">
    <a href="<?= $this->getBaseUrl() ?>/work-items" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back to Work Items
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <!-- Work Item Details -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-dark fw-semibold">
                    <span class="badge bg-secondary me-2"><?= ucfirst(str_replace('_', ' ', $workItem['type'])) ?></span>
                    <?= htmlspecialchars($workItem['title']) ?>
                </h6>
                <span class="status-badge" style="background-color: <?= $workItem['status_color'] ?>20; color: <?= $workItem['status_color'] ?>;">
                    <?= htmlspecialchars($workItem['status_name']) ?>
                </span>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <h6>Description</h6>
                    <div class="comment-content">
                        <?php if (!empty($workItem['description'])): ?>
                            <?= $workItem['description'] ?>
                        <?php else: ?>
                            <p class="text-muted">No description provided</p>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if ($workItem['type'] === 'timesheet'): ?>
                <!-- Timesheet Hours Breakdown -->
                <?php 
                    $metadata = !empty($workItem['metadata']) ? json_decode($workItem['metadata'], true) : [];
                    $entries = $metadata['entries'] ?? [];
                    $totalHours = $metadata['total_hours'] ?? 0;
                    $weekPeriod = $metadata['week_period'] ?? 'N/A';
                    $weekStart = $metadata['week_start'] ?? '';
                    $weekEnd = $metadata['week_end'] ?? '';
                    
                    $projectModel = new Project();
                ?>
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h6 class="mb-0">
                            <i class="bi bi-calendar-week text-primary me-2"></i>
                            Timesheet - Week <?= htmlspecialchars($weekPeriod) ?>
                        </h6>
                        <div class="badge bg-primary fs-5 px-4 py-2">
                            <i class="bi bi-clock-history me-2"></i>Total: <?= number_format($totalHours, 1) ?> hours
                        </div>
                    </div>

                    <?php if ($weekStart && $weekEnd): ?>
                    <div class="alert alert-light border mb-3">
                        <strong><i class="bi bi-calendar-range me-2"></i>Period:</strong> 
                        <?= date('l, F d, Y', strtotime($weekStart)) ?> - <?= date('l, F d, Y', strtotime($weekEnd)) ?>
                    </div>
                    <?php endif; ?>

                    <?php if (empty($entries)): ?>
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            No timesheet data available.
                        </div>
                    <?php else: ?>
                        <?php foreach ($entries as $entryIndex => $entry): ?>
                            <?php
                                $projectId = $entry['project_id'] ?? 0;
                                $role = $entry['role'] ?? 'N/A';
                                $hours = $entry['hours'] ?? [];
                                $entryTotal = !empty($hours) ? array_sum($hours) : 0;
                                
                                $project = $projectId ? $projectModel->find($projectId) : null;
                                $projectName = $project ? $project['name'] : 'Unknown Project';
                            ?>
                            
                            <div class="card mb-4 border-primary shadow-sm">
                                <div class="card-header bg-primary bg-opacity-10 border-primary">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0 text-primary">
                                            <i class="bi bi-briefcase me-2"></i>
                                            <?= htmlspecialchars($projectName) ?>
                                        </h6>
                                        <div>
                                            <span class="badge bg-light text-dark border me-2">
                                                <i class="bi bi-person-badge me-1"></i>
                                                <?= htmlspecialchars($role) ?>
                                            </span>
                                            <span class="badge bg-primary">
                                                <?= number_format($entryTotal, 1) ?> hours
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-sm align-middle mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="ps-3" style="width: 50%;">
                                                        <i class="bi bi-calendar-day me-2 text-primary"></i>Day
                                                    </th>
                                                    <th class="text-center" style="width: 25%;">
                                                        <i class="bi bi-clock me-2 text-primary"></i>Hours
                                                    </th>
                                                    <th class="text-end pe-3" style="width: 25%;">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                $days = [
                                                    'sunday' => 'Sunday',
                                                    'monday' => 'Monday', 
                                                    'tuesday' => 'Tuesday', 
                                                    'wednesday' => 'Wednesday', 
                                                    'thursday' => 'Thursday', 
                                                    'friday' => 'Friday', 
                                                    'saturday' => 'Saturday'
                                                ];
                                                
                                                foreach ($days as $key => $label): 
                                                    $dayHours = isset($hours[$key]) ? floatval($hours[$key]) : 0;
                                                    
                                                    if ($dayHours == 0) {
                                                        $rowClass = 'table-light';
                                                        $hoursDisplay = '<span class="text-muted">—</span>';
                                                        $statusIcon = '<span class="badge bg-secondary">Off</span>';
                                                    } elseif ($dayHours > 8) {
                                                        $rowClass = 'table-warning bg-opacity-25';
                                                        $hoursDisplay = '<strong class="text-dark">' . number_format($dayHours, 1) . '</strong>';
                                                        $statusIcon = '<span class="badge bg-warning text-dark"><i class="bi bi-exclamation-triangle me-1"></i>Overtime</span>';
                                                    } else {
                                                        $rowClass = '';
                                                        $hoursDisplay = '<strong>' . number_format($dayHours, 1) . '</strong>';
                                                        $statusIcon = '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Normal</span>';
                                                    }
                                                ?>
                                                <tr class="<?= $rowClass ?>">
                                                    <td class="ps-3">
                                                        <i class="bi bi-circle-fill me-2" style="font-size: 8px; color: <?= $dayHours > 0 ? '#3b82f6' : '#d1d5db' ?>;"></i>
                                                        <strong><?= $label ?></strong>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="fs-5"><?= $hoursDisplay ?></span>
                                                    </td>
                                                    <td class="text-end pe-3"><?= $statusIcon ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                            <tfoot class="table-primary">
                                                <tr>
                                                    <td class="ps-3 fw-bold">
                                                        <i class="bi bi-calculator me-2"></i>Project Total
                                                    </td>
                                                    <td class="text-center fw-bold fs-5">
                                                        <?= number_format($entryTotal, 1) ?>
                                                    </td>
                                                    <td class="text-end pe-3">
                                                        <?php if ($entryTotal < 40): ?>
                                                            <span class="badge bg-warning text-dark">
                                                                <i class="bi bi-dash-circle me-1"></i>Under 40
                                                            </span>
                                                        <?php elseif ($entryTotal > 40): ?>
                                                            <span class="badge bg-info">
                                                                <i class="bi bi-plus-circle me-1"></i>Overtime
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="badge bg-success">
                                                                <i class="bi bi-check-circle me-1"></i>Full Week
                                                            </span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <!-- Weekly Summary -->
                        <div class="card border-primary shadow">
                            <div class="card-body bg-primary bg-opacity-10">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <h5 class="mb-2">
                                            <i class="bi bi-calendar-check text-primary me-2"></i>
                                            Weekly Summary
                                        </h5>
                                        <p class="mb-0 text-muted">
                                            <?= count($entries) ?> project<?= count($entries) > 1 ? 's' : '' ?> logged for this week
                                        </p>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <div class="display-6 text-primary fw-bold">
                                            <?= number_format($totalHours, 1) ?>
                                        </div>
                                        <small class="text-muted">Total Hours</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php 
                            $avgDailyHours = $totalHours / 7;
                            $expectedHours = 40;
                        ?>
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <div class="card border-0 bg-light">
                                    <div class="card-body text-center">
                                        <div class="text-primary fw-bold fs-4"><?= number_format($avgDailyHours, 1) ?></div>
                                        <small class="text-muted">Avg Daily Hours</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-0 bg-light">
                                    <div class="card-body text-center">
                                        <div class="text-primary fw-bold fs-4"><?= count($entries) ?></div>
                                        <small class="text-muted">Projects</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-0 bg-light">
                                    <div class="card-body text-center">
                                        <?php 
                                            $variance = $totalHours - $expectedHours;
                                            $varianceClass = $variance >= 0 ? 'text-success' : 'text-warning';
                                        ?>
                                        <div class="<?= $varianceClass ?> fw-bold fs-4">
                                            <?= $variance >= 0 ? '+' : '' ?><?= number_format($variance, 1) ?>
                                        </div>
                                        <small class="text-muted">vs. Expected (40h)</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>Created By:</strong><br>
                            <a href="<?= $this->getBaseUrl() ?>/employees/<?= $workItem['created_by'] ?>">
                                <?= htmlspecialchars($workItem['creator_name']) ?>
                            </a>
                        </div>
                        <?php if ($workItem['assigned_to']): ?>
                        <div class="mb-3">
                            <strong>Assigned To:</strong><br>
                            <a href="<?= $this->getBaseUrl() ?>/employees/<?= $workItem['assigned_to'] ?>">
                                <?= htmlspecialchars($workItem['assignee_name']) ?>
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>Priority:</strong><br>
                            <?php
                            $priorityColors = ['low' => 'info', 'medium' => 'primary', 'high' => 'warning', 'urgent' => 'danger'];
                            $color = $priorityColors[$workItem['priority']] ?? 'secondary';
                            ?>
                            <span class="badge bg-<?= $color ?>"><?= ucfirst($workItem['priority']) ?></span>
                        </div>
                        <?php if ($workItem['due_date']): ?>
                        <div class="mb-3">
                            <strong>Due Date:</strong><br>
                            <?= date('M d, Y', strtotime($workItem['due_date'])) ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if (!empty($transitions)): ?>
                <div class="mb-3">
                    <h6>Change Status</h6>
                    <form method="POST" action="<?= $this->getBaseUrl() ?>/work-items/<?= $workItem['id'] ?>/status" class="d-flex gap-2 flex-wrap">
                        <input type="hidden" name="csrf_token" value="<?= Session::csrfToken() ?>">
                        <?php foreach ($transitions as $transition): ?>
                        <button type="submit" name="to_status_id" value="<?= $transition['to_status_id'] ?>" 
                                class="btn btn-outline-primary btn-sm">
                            Move to <?= htmlspecialchars($transition['to_status_name']) ?>
                        </button>
                        <?php endforeach; ?>
                    </form>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Comments -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h6 class="mb-0 text-dark fw-semibold">
                    <i class="bi bi-chat-dots text-primary me-2"></i>
                    Comments (<?= count($comments) ?>)
                </h6>
            </div>
            <div class="card-body">
                <?php if (empty($comments)): ?>
                <div class="text-center py-4">
                    <i class="bi bi-chat display-1 text-muted opacity-25"></i>
                    <p class="text-muted mt-3 mb-0">No comments yet. Be the first to comment!</p>
                </div>
                <?php else: ?>
                <div class="comments-list">
                    <?php foreach ($comments as $comment): ?>
                    <div class="comment-item mb-4 pb-3 border-bottom" data-aos="fade-up">
                        <div class="d-flex gap-3">
                            <div class="user-avatar-small">
                                <?= strtoupper(substr($comment['author_name'], 0, 1)) ?>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <strong class="text-dark"><?= htmlspecialchars($comment['author_name']) ?></strong>
                                        <small class="text-muted ms-2">
                                            <i class="bi bi-clock"></i>
                                            <?php
                                            $time = strtotime($comment['created_at']);
                                            $diff = time() - $time;
                                            if ($diff < 60) echo 'Just now';
                                            elseif ($diff < 3600) echo floor($diff / 60) . ' min ago';
                                            elseif ($diff < 86400) echo floor($diff / 3600) . ' hours ago';
                                            else echo date('M d, Y H:i', $time);
                                            ?>
                                        </small>
                                    </div>
                                </div>
                                <div class="comment-content">
                                    <?= $comment['comment'] ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <div class="comment-form mt-4">
                    <form method="POST" action="<?= $this->getBaseUrl() ?>/work-items/<?= $workItem['id'] ?>/comment">
                        <input type="hidden" name="csrf_token" value="<?= Session::csrfToken() ?>">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Add a Comment</label>
                            <textarea name="comment" style="display:none;" data-required="true"></textarea>
                            <div class="rich-editor" style="min-height: 120px;" data-placeholder="Share your thoughts, ask questions, or provide updates..."></div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send"></i> Post Comment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-md-4">
        <!-- Status History -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h6 class="mb-0 text-dark fw-semibold">
                    <i class="bi bi-clock-history text-primary me-2"></i>
                    Status History
                </h6>
            </div>
            <div class="card-body">
                <?php if (empty($history)): ?>
                <p class="text-muted small">No history yet</p>
                <?php else: ?>
                <div class="timeline">
                    <?php foreach ($history as $entry): ?>
                    <div class="mb-3">
                        <div class="d-flex align-items-start">
                            <div class="me-2">
                                <i class="bi bi-arrow-right-circle" style="color: <?= $entry['to_status_color'] ?>;"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="small">
                                    <strong><?= htmlspecialchars($entry['changed_by_name']) ?></strong> changed status to
                                    <span class="badge" style="background-color: <?= $entry['to_status_color'] ?>;">
                                        <?= htmlspecialchars($entry['to_status_name']) ?>
                                    </span>
                                </div>
                                <?php if ($entry['comment']): ?>
                                <div class="small text-muted"><?= htmlspecialchars($entry['comment']) ?></div>
                                <?php endif; ?>
                                <div class="small text-muted"><?= date('M d, Y H:i', strtotime($entry['created_at'])) ?></div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Attachments -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h6 class="mb-0 text-dark fw-semibold">
                    <i class="bi bi-paperclip text-primary me-2"></i>
                    Attachments (<?= count($attachments) ?>)
                </h6>
            </div>
            <div class="card-body">
                <?php if (empty($attachments)): ?>
                <div class="text-center py-4">
                    <i class="bi bi-file-earmark display-1 text-muted opacity-25"></i>
                    <p class="text-muted mt-3 mb-0">No attachments yet</p>
                </div>
                <?php else: ?>
                <div class="list-group list-group-flush">
                    <?php foreach ($attachments as $attachment): ?>
                    <?php
                        $attachmentModel = new Attachment();
                        $icon = $attachmentModel->getFileIcon($attachment['mime_type']);
                        $size = $attachmentModel->formatFileSize($attachment['file_size']);
                    ?>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="flex-grow-1">
                            <i class="bi <?= $icon ?> text-primary me-2"></i>
                            <a href="<?= $this->getBaseUrl() ?>/attachments/<?= $attachment['id'] ?>/download" class="text-decoration-none">
                                <?= htmlspecialchars($attachment['original_filename']) ?>
                            </a>
                            <div class="small text-muted">
                                <?= $size ?> • Uploaded by <?= htmlspecialchars($attachment['uploaded_by_name']) ?> 
                                on <?= date('M d, Y', strtotime($attachment['created_at'])) ?>
                            </div>
                        </div>
                        <form method="POST" action="<?= $this->getBaseUrl() ?>/attachments/<?= $attachment['id'] ?>/delete" 
                              onsubmit="return confirm('Are you sure you want to delete this attachment?');" class="ms-2">
                            <input type="hidden" name="csrf_token" value="<?= Session::csrfToken() ?>">
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Upload Form -->
                <div class="mt-4">
                    <form method="POST" action="<?= $this->getBaseUrl() ?>/work-items/<?= $workItem['id'] ?>/attachment" 
                          enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?= Session::csrfToken() ?>">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Upload New Attachment</label>
                            <input type="file" name="attachment" class="form-control" required>
                            <div class="form-text">
                                Max size: 10MB. Allowed: Images, PDF, Word, Excel, Text, ZIP
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-upload"></i> Upload File
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = $workItem['title'];
require __DIR__ . '/../layout.php';
?>
