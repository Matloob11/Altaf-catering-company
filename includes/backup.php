<?php
/**
 * Automated Backup System
 * Altaf Catering - Data Protection & Recovery
 */

/**
 * Create Full Backup
 */
function createFullBackup() {
    $backupDir = __DIR__ . '/../admin/backups/';
    
    // Create backups directory if not exists
    if (!file_exists($backupDir)) {
        mkdir($backupDir, 0755, true);
    }
    
    $timestamp = date('Y-m-d_H-i-s');
    $backupFile = $backupDir . 'backup_' . $timestamp . '.zip';
    
    // Directories and files to backup
    $dataDir = __DIR__ . '/../admin/data/';
    $uploadsDir = __DIR__ . '/../uploads/';
    
    // Create ZIP archive
    $zip = new ZipArchive();
    
    if ($zip->open($backupFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
        return ['success' => false, 'message' => 'Could not create backup file'];
    }
    
    // Add data files
    if (file_exists($dataDir)) {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dataDir),
            RecursiveIteratorIterator::LEAVES_ONLY
        );
        
        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = 'data/' . substr($filePath, strlen($dataDir));
                $zip->addFile($filePath, $relativePath);
            }
        }
    }
    
    // Add uploads if exists
    if (file_exists($uploadsDir)) {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($uploadsDir),
            RecursiveIteratorIterator::LEAVES_ONLY
        );
        
        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = 'uploads/' . substr($filePath, strlen($uploadsDir));
                $zip->addFile($filePath, $relativePath);
            }
        }
    }
    
    // Add backup info
    $backupInfo = [
        'timestamp' => $timestamp,
        'date' => date('Y-m-d H:i:s'),
        'type' => 'full',
        'files_count' => $zip->numFiles,
        'size' => 0
    ];
    
    $zip->addFromString('backup-info.json', json_encode($backupInfo, JSON_PRETTY_PRINT));
    $zip->close();
    
    // Get file size
    $fileSize = filesize($backupFile);
    $backupInfo['size'] = $fileSize;
    
    // Log backup
    logBackup($backupInfo);
    
    // Clean old backups (keep last 10)
    cleanOldBackups(10);
    
    return [
        'success' => true,
        'message' => 'Backup created successfully',
        'file' => basename($backupFile),
        'size' => formatBytes($fileSize),
        'timestamp' => $timestamp
    ];
}

/**
 * Create Quick Backup (Data only)
 */
function createQuickBackup() {
    $backupDir = __DIR__ . '/../admin/backups/';
    
    if (!file_exists($backupDir)) {
        mkdir($backupDir, 0755, true);
    }
    
    $timestamp = date('Y-m-d_H-i-s');
    $backupFile = $backupDir . 'quick_backup_' . $timestamp . '.zip';
    
    $dataDir = __DIR__ . '/../admin/data/';
    
    $zip = new ZipArchive();
    
    if ($zip->open($backupFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
        return ['success' => false, 'message' => 'Could not create backup file'];
    }
    
    // Add only data files
    if (file_exists($dataDir)) {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dataDir),
            RecursiveIteratorIterator::LEAVES_ONLY
        );
        
        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($dataDir));
                $zip->addFile($filePath, $relativePath);
            }
        }
    }
    
    $zip->close();
    
    $fileSize = filesize($backupFile);
    
    return [
        'success' => true,
        'message' => 'Quick backup created',
        'file' => basename($backupFile),
        'size' => formatBytes($fileSize)
    ];
}

/**
 * Restore Backup
 */
function restoreBackup($backupFile) {
    $backupPath = __DIR__ . '/../admin/backups/' . $backupFile;
    
    if (!file_exists($backupPath)) {
        return ['success' => false, 'message' => 'Backup file not found'];
    }
    
    $zip = new ZipArchive();
    
    if ($zip->open($backupPath) !== TRUE) {
        return ['success' => false, 'message' => 'Could not open backup file'];
    }
    
    // Extract to temp directory first
    $tempDir = __DIR__ . '/../admin/temp_restore/';
    if (!file_exists($tempDir)) {
        mkdir($tempDir, 0755, true);
    }
    
    $zip->extractTo($tempDir);
    $zip->close();
    
    // Restore data files
    $dataSource = $tempDir . 'data/';
    $dataTarget = __DIR__ . '/../admin/data/';
    
    if (file_exists($dataSource)) {
        // Backup current data first
        $currentBackup = createQuickBackup();
        
        // Copy restored files
        copyDirectory($dataSource, $dataTarget);
    }
    
    // Restore uploads if exists
    $uploadsSource = $tempDir . 'uploads/';
    $uploadsTarget = __DIR__ . '/../uploads/';
    
    if (file_exists($uploadsSource)) {
        copyDirectory($uploadsSource, $uploadsTarget);
    }
    
    // Clean temp directory
    deleteDirectory($tempDir);
    
    return [
        'success' => true,
        'message' => 'Backup restored successfully'
    ];
}

/**
 * Get All Backups
 */
function getAllBackups() {
    $backupDir = __DIR__ . '/../admin/backups/';
    
    if (!file_exists($backupDir)) {
        return [];
    }
    
    $backups = [];
    $files = glob($backupDir . '*.zip');
    
    foreach ($files as $file) {
        $backups[] = [
            'filename' => basename($file),
            'size' => formatBytes(filesize($file)),
            'date' => date('Y-m-d H:i:s', filemtime($file)),
            'timestamp' => filemtime($file)
        ];
    }
    
    // Sort by date (newest first)
    usort($backups, function($a, $b) {
        return $b['timestamp'] - $a['timestamp'];
    });
    
    return $backups;
}

/**
 * Delete Backup
 */
function deleteBackup($filename) {
    $backupPath = __DIR__ . '/../admin/backups/' . $filename;
    
    if (!file_exists($backupPath)) {
        return ['success' => false, 'message' => 'Backup not found'];
    }
    
    if (unlink($backupPath)) {
        return ['success' => true, 'message' => 'Backup deleted'];
    }
    
    return ['success' => false, 'message' => 'Could not delete backup'];
}

/**
 * Download Backup
 */
function downloadBackup($filename) {
    $backupPath = __DIR__ . '/../admin/backups/' . $filename;
    
    if (!file_exists($backupPath)) {
        return false;
    }
    
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Content-Length: ' . filesize($backupPath));
    readfile($backupPath);
    exit;
}

/**
 * Log Backup
 */
function logBackup($info) {
    $logFile = __DIR__ . '/../admin/data/backup-log.json';
    
    $logs = [];
    if (file_exists($logFile)) {
        $logs = json_decode(file_get_contents($logFile), true) ?: [];
    }
    
    $logs[] = $info;
    
    // Keep last 100 logs
    if (count($logs) > 100) {
        $logs = array_slice($logs, -100);
    }
    
    file_put_contents($logFile, json_encode($logs, JSON_PRETTY_PRINT));
}

/**
 * Clean Old Backups
 */
function cleanOldBackups($keepCount = 10) {
    $backups = getAllBackups();
    
    if (count($backups) <= $keepCount) {
        return;
    }
    
    // Delete oldest backups
    $toDelete = array_slice($backups, $keepCount);
    
    foreach ($toDelete as $backup) {
        deleteBackup($backup['filename']);
    }
}

/**
 * Copy Directory Recursively
 */
function copyDirectory($source, $destination) {
    if (!file_exists($destination)) {
        mkdir($destination, 0755, true);
    }
    
    $dir = opendir($source);
    
    while (($file = readdir($dir)) !== false) {
        if ($file != '.' && $file != '..') {
            $srcPath = $source . '/' . $file;
            $destPath = $destination . '/' . $file;
            
            if (is_dir($srcPath)) {
                copyDirectory($srcPath, $destPath);
            } else {
                copy($srcPath, $destPath);
            }
        }
    }
    
    closedir($dir);
}

/**
 * Delete Directory Recursively
 */
function deleteDirectory($dir) {
    if (!file_exists($dir)) {
        return;
    }
    
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );
    
    foreach ($files as $file) {
        if ($file->isDir()) {
            rmdir($file->getRealPath());
        } else {
            unlink($file->getRealPath());
        }
    }
    
    rmdir($dir);
}

/**
 * Format Bytes
 */
function formatBytes($bytes, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB'];
    
    for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
        $bytes /= 1024;
    }
    
    return round($bytes, $precision) . ' ' . $units[$i];
}

/**
 * Get Backup Statistics
 */
function getBackupStats() {
    $backups = getAllBackups();
    
    $totalSize = 0;
    foreach ($backups as $backup) {
        $file = __DIR__ . '/../admin/backups/' . $backup['filename'];
        if (file_exists($file)) {
            $totalSize += filesize($file);
        }
    }
    
    return [
        'total_backups' => count($backups),
        'total_size' => formatBytes($totalSize),
        'latest_backup' => !empty($backups) ? $backups[0]['date'] : 'Never',
        'oldest_backup' => !empty($backups) ? end($backups)['date'] : 'Never'
    ];
}
