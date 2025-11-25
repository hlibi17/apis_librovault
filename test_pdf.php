<?php
// Simple test to check PDF serving
$file = '1763438689_69a37b3792e1.pdf';
$filePath = __DIR__ . '/uploads/' . $file;

if (!file_exists($filePath)) {
    echo "File does not exist: $filePath";
    exit;
}

echo "File exists: $filePath<br>";
echo "File size: " . filesize($filePath) . " bytes<br>";
echo "File is readable: " . (is_readable($filePath) ? 'Yes' : 'No') . "<br>";

// Try to read first few bytes
$handle = fopen($filePath, 'rb');
if ($handle) {
    $data = fread($handle, 100);
    fclose($handle);
    echo "First 100 bytes (hex): " . bin2hex(substr($data, 0, 50)) . "...<br>";
    echo "Starts with PDF header: " . (substr($data, 0, 4) === '%PDF' ? 'Yes' : 'No') . "<br>";
} else {
    echo "Cannot open file<br>";
}
?>
