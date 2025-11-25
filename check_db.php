<?php
require_once "Buku.php";
$buku = new Buku();

$id = 1;
$data = $buku->find($id);
if ($data) {
    echo "Book ID: " . $data['id'] . "<br>";
    echo "Judul: " . $data['judul'] . "<br>";
    echo "Penulis: " . $data['penulis'] . "<br>";
    echo "Tahun: " . $data['tahun'] . "<br>";
    echo "File PDF: " . ($data['file_pdf'] ? $data['file_pdf'] : 'NULL') . "<br>";
    echo "Tanggal Upload: " . $data['tanggal_upload'] . "<br>";
} else {
    echo "No book found with ID: $id";
}
?>
