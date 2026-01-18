<?php

class Attachment extends Model
{
    protected $table = 'attachments';

    public function getForEntity($entityType, $entityId)
    {
        $sql = "SELECT a.*, e.full_name as uploader_name
                FROM {$this->table} a
                INNER JOIN employees e ON a.uploaded_by = e.id
                WHERE a.entity_type = ? AND a.entity_id = ?
                ORDER BY a.created_at DESC";
        return $this->db->fetchAll($sql, [$entityType, $entityId]);
    }

    public function createAttachment($entityType, $entityId, $fileName, $filePath, $fileSize, $fileType, $uploadedBy)
    {
        return $this->create([
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'file_name' => $fileName,
            'file_path' => $filePath,
            'file_size' => $fileSize,
            'file_type' => $fileType,
            'uploaded_by' => $uploadedBy
        ]);
    }

    public function deleteAttachment($id)
    {
        $attachment = $this->find($id);
        if ($attachment) {
            // Delete physical file
            $fullPath = __DIR__ . '/../public/uploads/' . $attachment['file_path'];
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
            // Delete database record
            return $this->delete($id);
        }
        return false;
    }

    public function getFileIcon($fileType)
    {
        if (strpos($fileType, 'image/') === 0) {
            return 'bi-file-image';
        } elseif (strpos($fileType, 'pdf') !== false) {
            return 'bi-file-pdf';
        } elseif (strpos($fileType, 'word') !== false || strpos($fileType, 'document') !== false) {
            return 'bi-file-word';
        } elseif (strpos($fileType, 'excel') !== false || strpos($fileType, 'spreadsheet') !== false) {
            return 'bi-file-excel';
        } elseif (strpos($fileType, 'zip') !== false || strpos($fileType, 'compressed') !== false) {
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
