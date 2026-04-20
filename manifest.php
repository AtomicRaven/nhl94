<?php
$dir = __DIR__ . '/uploads/gamesaves';
$out = [];

$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
foreach ($it as $file) {
    if ($file->isFile()) {
        $out[] = [
            'filename' => $file->getFilename(),
            'path' => $file->getPathname(),
            'modified' => date('Y-m-d H:i:s', $file->getMTime()),
            'mtime' => $file->getMTime(),
            'size' => $file->getSize(),
            'sha1' => sha1_file($file->getPathname()),
        ];
    }
}

usort($out, fn($a, $b) => $a['mtime'] <=> $b['mtime']);

header('Content-Type: application/json');
echo json_encode($out, JSON_PRETTY_PRINT);