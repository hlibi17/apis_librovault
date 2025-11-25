<?php
require_once "Buku.php";
$buku = new Buku();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$data = $buku->find($id);
if ($data) {
    // hapus file fisik jika ada
    if (!empty($data['file_pdf'])) {
        $filePath = __DIR__ . '/uploads/' . $data['file_pdf'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
    $buku->delete($id);
    header("Location: index.php?msg=deleted");
    exit;
}
header("Location: index.php");
exit;
?>
