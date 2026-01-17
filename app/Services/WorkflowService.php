<?php

class WorkflowService
{
    private $workflowModel;
    private $workItemModel;
    private $notificationModel;
    private $auditLog;

    public function __construct()
    {
        $this->workflowModel = new Workflow();
        $this->workItemModel = new WorkItem();
        $this->notificationModel = new Notification();
        $this->auditLog = new AuditLog();
    }

    public function createWorkItem($type, $title, $description, $createdBy, $assignedTo = null, $priority = 'medium', $dueDate = null, $projectId = null, $metadata = null)
    {
        $workflow = $this->workflowModel->findByType($type);
        if (!$workflow) {
            throw new Exception("No active workflow found for type: $type");
        }

        $initialStatus = $this->workflowModel->getInitialStatus($workflow['id']);
        if (!$initialStatus) {
            throw new Exception("No initial status defined for workflow");
        }

        $workItemId = $this->workItemModel->create([
            'title' => $title,
            'description' => $description,
            'type' => $type,
            'created_by' => $createdBy,
            'assigned_to' => $assignedTo,
            'workflow_id' => $workflow['id'],
            'current_status_id' => $initialStatus['id'],
            'priority' => $priority,
            'due_date' => $dueDate,
            'project_id' => $projectId,
            'metadata' => $metadata ? json_encode($metadata) : null
        ]);

        // Add initial history
        $this->workItemModel->addHistory($workItemId, null, $initialStatus['id'], $createdBy, 'Work item created');

        // Notify assignee if assigned
        if ($assignedTo) {
            $this->notificationModel->createNotification(
                $assignedTo,
                'New Assignment',
                "You have been assigned: $title",
                'assignment',
                'work_item',
                $workItemId
            );
        }

        // Audit log
        $this->auditLog->log(
            Session::get('user')['id'],
            'create',
            'work_item',
            $workItemId,
            ['type' => $type, 'title' => $title]
        );

        return $workItemId;
    }

    public function transitionWorkItem($workItemId, $toStatusId, $employeeId, $comment = null)
    {
        $workItem = $this->workItemModel->find($workItemId);
        if (!$workItem) {
            throw new Exception("Work item not found");
        }

        // Check if transition is allowed
        if (!$this->workflowModel->canTransition($workItem['workflow_id'], $workItem['current_status_id'], $toStatusId)) {
            throw new Exception("Transition not allowed");
        }

        // Check if approval is required
        $transitionInfo = $this->workflowModel->requiresApproval($workItem['workflow_id'], $workItem['current_status_id'], $toStatusId);
        
        if ($transitionInfo && $transitionInfo['requires_approval']) {
            // TODO: Add approval check logic based on approver_role
            // For now, we'll allow the transition
        }

        // Update status
        $success = $this->workItemModel->updateStatus($workItemId, $toStatusId, $employeeId, $comment);

        if ($success) {
            // Notify creator and assignee
            if ($workItem['assigned_to'] && $workItem['assigned_to'] != $employeeId) {
                $this->notificationModel->createNotification(
                    $workItem['assigned_to'],
                    'Status Update',
                    "Status changed for: {$workItem['title']}",
                    'status_change',
                    'work_item',
                    $workItemId
                );
            }

            if ($workItem['created_by'] != $employeeId && $workItem['created_by'] != $workItem['assigned_to']) {
                $this->notificationModel->createNotification(
                    $workItem['created_by'],
                    'Status Update',
                    "Status changed for: {$workItem['title']}",
                    'status_change',
                    'work_item',
                    $workItemId
                );
            }

            // Audit log
            $this->auditLog->log(
                Session::get('user')['id'],
                'update_status',
                'work_item',
                $workItemId,
                ['from_status' => $workItem['current_status_id'], 'to_status' => $toStatusId]
            );
        }

        return $success;
    }

    public function getAvailableTransitions($workItemId)
    {
        $workItem = $this->workItemModel->find($workItemId);
        if (!$workItem) {
            return [];
        }

        return $this->workflowModel->getTransitions($workItem['workflow_id'], $workItem['current_status_id']);
    }
}
