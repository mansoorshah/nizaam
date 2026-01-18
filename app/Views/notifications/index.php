<?php ob_start(); ?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-down">
    <div>
        <h1 class="h3 mb-1">
            <i class="bi bi-bell-fill text-primary"></i>
            Notifications
        </h1>
        <p class="text-muted mb-0">Stay updated with your latest activities</p>
    </div>
    <div>
        <?php if ($unreadCount > 0): ?>
        <form action="<?= $this->getBaseUrl() ?>/notifications/read-all" method="POST" style="display: inline;">
            <button type="submit" class="btn btn-outline-primary">
                <i class="bi bi-check-all"></i> Mark All as Read
            </button>
        </form>
        <?php endif; ?>
    </div>
</div>

<!-- Notifications Stats -->
<div class="row mb-4" data-aos="fade-up">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: var(--nizaam-info);">
                <i class="bi bi-bell"></i>
            </div>
            <div class="stat-details">
                <span class="stat-value"><?= count($notifications) ?></span>
                <span class="stat-label">Total Notifications</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: var(--nizaam-warning);">
                <i class="bi bi-envelope-exclamation"></i>
            </div>
            <div class="stat-details">
                <span class="stat-value"><?= $unreadCount ?></span>
                <span class="stat-label">Unread</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: var(--nizaam-success);">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="stat-details">
                <span class="stat-value"><?= count($notifications) - $unreadCount ?></span>
                <span class="stat-label">Read</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: var(--nizaam-primary);">
                <i class="bi bi-calendar-day"></i>
            </div>
            <div class="stat-details">
                <span class="stat-value">
                    <?php
                    $today = date('Y-m-d');
                    $todayCount = 0;
                    foreach ($notifications as $notif) {
                        if (date('Y-m-d', strtotime($notif['created_at'])) === $today) {
                            $todayCount++;
                        }
                    }
                    echo $todayCount;
                    ?>
                </span>
                <span class="stat-label">Today</span>
            </div>
        </div>
    </div>
</div>

<!-- Notifications List -->
<div class="card shadow-sm" data-aos="fade-up" data-aos-delay="100">
    <div class="card-header bg-white border-bottom">
        <div class="d-flex align-items-center">
            <i class="bi bi-list-ul me-2 text-primary"></i>
            <h5 class="mb-0">All Notifications</h5>
        </div>
    </div>
    <div class="card-body p-0">
        <?php if (empty($notifications)): ?>
        <div class="text-center py-5">
            <i class="bi bi-bell-slash display-1 text-muted opacity-25"></i>
            <p class="text-muted mt-3 mb-0">No notifications yet</p>
            <p class="text-muted small">You'll see notifications here when there's activity</p>
        </div>
        <?php else: ?>
        <div class="list-group list-group-flush">
            <?php foreach ($notifications as $notification): ?>
            <div class="list-group-item list-group-item-action notification-item <?= $notification['is_read'] ? '' : 'notification-unread' ?>" 
                 onclick="markAsReadAndRedirect(<?= $notification['id'] ?>, '<?= $notification['link'] ?? '#' ?>')">
                <div class="d-flex w-100 align-items-start">
                    <div class="notification-icon me-3">
                        <?php
                        $iconClass = 'bi-bell';
                        $iconBg = 'var(--nizaam-primary)';
                        
                        if (strpos($notification['type'], 'work_item') !== false) {
                            $iconClass = 'bi-list-task';
                            $iconBg = 'var(--nizaam-info)';
                        } elseif (strpos($notification['type'], 'comment') !== false) {
                            $iconClass = 'bi-chat-dots';
                            $iconBg = 'var(--nizaam-success)';
                        } elseif (strpos($notification['type'], 'project') !== false) {
                            $iconClass = 'bi-folder';
                            $iconBg = 'var(--nizaam-secondary)';
                        } elseif (strpos($notification['type'], 'assignment') !== false) {
                            $iconClass = 'bi-person-check';
                            $iconBg = 'var(--nizaam-warning)';
                        }
                        ?>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 40px; height: 40px; background: <?= $iconBg ?>; color: white;">
                            <i class="bi <?= $iconClass ?>"></i>
                        </div>
                    </div>
                    
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <h6 class="mb-1 <?= $notification['is_read'] ? '' : 'fw-bold' ?>">
                                <?= htmlspecialchars($notification['title']) ?>
                            </h6>
                            <small class="text-muted ms-2">
                                <?php
                                $timestamp = strtotime($notification['created_at']);
                                $now = time();
                                $diff = $now - $timestamp;
                                
                                if ($diff < 60) {
                                    echo 'Just now';
                                } elseif ($diff < 3600) {
                                    echo floor($diff / 60) . ' min ago';
                                } elseif ($diff < 86400) {
                                    echo floor($diff / 3600) . ' hours ago';
                                } elseif ($diff < 604800) {
                                    echo floor($diff / 86400) . ' days ago';
                                } else {
                                    echo date('M d, Y', $timestamp);
                                }
                                ?>
                            </small>
                        </div>
                        <p class="mb-1 text-muted small"><?= htmlspecialchars($notification['message']) ?></p>
                        
                        <div class="d-flex align-items-center gap-3 mt-2">
                            <?php if (!$notification['is_read']): ?>
                            <span class="badge bg-primary">New</span>
                            <?php endif; ?>
                            
                            <?php if ($notification['link']): ?>
                            <span class="text-primary small">
                                <i class="bi bi-arrow-right"></i> View Details
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<style>
.notification-item {
    transition: var(--transition-base);
    cursor: pointer;
    border-left: 3px solid transparent;
}

.notification-item:hover {
    background: var(--bs-light);
    border-left-color: var(--nizaam-primary);
}

.notification-unread {
    background: rgba(59, 130, 246, 0.05);
    border-left-color: var(--nizaam-primary);
}

.notification-icon {
    flex-shrink: 0;
}
</style>

<script>
function markAsReadAndRedirect(notificationId, link) {
    // Mark as read via AJAX
    fetch('<?= $this->getBaseUrl() ?>/notifications/' + notificationId + '/read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        }
    }).then(() => {
        // Redirect if there's a link
        if (link && link !== '#') {
            window.location.href = link;
        } else {
            // Just reload to show updated status
            window.location.reload();
        }
    });
}
</script>

<?php
$content = ob_get_clean();
$title = 'Notifications';
require __DIR__ . '/../layout.php';
?>
