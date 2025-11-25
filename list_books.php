<?php
require_once "Buku.php";
$buku = new Buku();

$all = $buku->all();
echo "Total books: " . count($all) . "<br>";
foreach($all as $book) {
    echo "ID: " . $book['id'] . " - " . $book['judul'] . " - " . $book['file_pdf'] . "<br>";
}
?>
