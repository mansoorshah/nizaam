<?php

class Attachment extends Model
{
    protected $table = 'attachments';

    public function getForWorkItem($workItemId)
    {
        $sql = "SELECT a.*, e.full_name as uploader_name
                FROM {$this->table} a
                INNER JOIN employees e ON a.uploaded_by = e.id
                WHERE a.work_item_id = ?
                ORDER BY a.created_at DESC";
        return $this->db->fetchAll($sql, [$workItemId]);
    }

    public function createAttachment($workItemId, $filename, $originalFilename, $filePath, $fileSize, $mimeType, $uploadedBy)
    {
        return $this->create([
            'work_item_id' => $workItemId,
            'filename' => $filename,
            'original_filename' => $originalFilename,
            'file_path' => $filePath,
            'file_size' => $fileSize,
            'mime_type' => $mimeType,
            'uploaded_by' => $uploadedBy
        ]);
    }

    public function deleteAttachment($id)
    {
        $attachment = $this->find($id);
        if ($attachment) {
            // Delete physical file
            $fullPath = __DIR__ . '/../../storage/uploads/' . $attachment['file_path'];
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
            // Delete database record
            return $this->delete($id);
        }
        return false;
    }

    public function getFileIcon($mimeType)
    {
        if (strpos($mimeType, 'image/') === 0) {
            return 'bi-file-image';
        } elseif (strpos($mimeType, 'pdf') !== false) {
            return 'bi-file-pdf';
        } elseif (strpos($mimeType, 'word') !== false || strpos($mimeType, 'document') !== false) {
            return 'bi-file-word';
        } elseif (strpos($mimeType, 'excel') !== false || strpos($mimeType, 'spreadsheet') !== false) {
            return 'bi-file-excel';
        } elseif (strpos($mimeType, 'zip') !== false || strpos($mimeType, 'compressed') !== false) {
            return 'bi-file-zip';
        } else {
            return 'bi-file-earmark';
        }
    }

    public function formatFileSize($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }
}
