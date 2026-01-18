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
        <?php if (!empty($attachments)): ?>
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Attachments</h6>
            </div>
            <div class="card-body">
                <?php foreach ($attachments as $attachment): ?>
                <div class="mb-2">
                    <i class="bi bi-paperclip"></i>
                    <a href="<?= $this->getBaseUrl() ?>/storage/uploads/<?= htmlspecialchars($attachment['filename']) ?>">
                        <?= htmlspecialchars($attachment['original_filename']) ?>
                    </a>
                    <div class="small text-muted">
                        Uploaded by <?= htmlspecialchars($attachment['uploaded_by_name']) ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = $workItem['title'];
require __DIR__ . '/../layout.php';
?>
