<?php
// PDF viewer script to serve PDFs inline
if (!isset($_GET['file'])) {
    http_response_code(400);
    echo 'File parameter missing';
    exit;
}

$file = basename($_GET['file']); // Prevent directory traversal
$filePath = __DIR__ . '/uploads/' . $file;

// Check if file exists and is a PDF
if (!file_exists($filePath)) {
    http_response_code(404);
    echo 'File not found';
    exit;
}

if (!preg_match('/\.pdf$/i', $file)) {
    http_response_code(403);
    echo 'Invalid file type';
    exit;
}

// Clear any previous output and disable compression
while (ob_get_level()) {
    ob_end_clean();
}
if (ini_get('zlib.output_compression')) {
    ini_set('zlib.output_compression', 'Off');
}

// Set headers for iframe compatibility and proper PDF serving
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="' . $file . '"');
header('Cache-Control: public, max-age=31536000');
header('Access-Control-Allow-Origin: *');
header('X-Frame-Options: ALLOWALL');
header('Content-Security-Policy: frame-ancestors *');

// Output the file using readfile (more reliable for PDFs)
if (!readfile($filePath)) {
    http_response_code(500);
    echo 'Error reading file';
    exit;
}
exit;
?>
