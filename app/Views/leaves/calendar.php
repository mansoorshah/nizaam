<?php ob_start(); ?>

<div class="d-flex justify-content-end align-items-center mb-4">
    <div class="btn-group">
        <a href="<?= $this->getBaseUrl() ?>/leaves" class="btn btn-outline-secondary">
            <i class="bi bi-list"></i> List View
        </a>
        <a href="<?= $this->getBaseUrl() ?>/leaves/calendar" class="btn btn-primary">
            <i class="bi bi-calendar"></i> Calendar View
        </a>
        <a href="<?= $this->getBaseUrl() ?>/leaves/request" class="btn btn-outline-primary">
            <i class="bi bi-plus-lg"></i> Request Leave
        </a>
    </div>
</div>

<!-- Calendar -->
<div class="card shadow-sm">
    <div class="card-body">
        <div id="calendar"></div>
    </div>
</div>

<!-- Leave Type Legend -->
<div class="card mt-4">
    <div class="card-header">
        <h6 class="mb-0">Leave Types</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <?php foreach ($leaveTypes as $type): ?>
            <div class="col-md-3 mb-2">
                <span class="badge" style="background-color: <?= $type['color'] ?>;">
                    <?= htmlspecialchars($type['name']) ?>
                </span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- FullCalendar CSS & JS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listMonth'
        },
        events: <?= json_encode($calendarEvents) ?>,
        eventClick: function(info) {
            window.location.href = '<?= $this->getBaseUrl() ?>/work-items/' + info.event.id;
        },
        eventContent: function(arg) {
            return {
                html: '<div class="fc-event-title" style="padding: 2px 4px; font-size: 0.85em;">' + 
                      arg.event.title + '</div>'
            };
        },
        height: 'auto',
        aspectRatio: 1.8,
        eventTimeFormat: {
            hour: 'numeric',
            minute: '2-digit',
            meridiem: 'short'
        },
        displayEventTime: false
    });
    
    calendar.render();
});
</script>

<style>
.fc-event {
    cursor: pointer;
    border: none !important;
}
.fc-event-title {
    font-weight: 500;
}
.fc-daygrid-event {
    margin-bottom: 2px;
}
</style>

<?php
$content = ob_get_clean();
$title = 'Leave Calendar';
require __DIR__ . '/../layout.php';
?>
