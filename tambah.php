<?php
require_once "Buku.php";
$buku = new Buku();

$errors = [];
$success = false;

if (isset($_POST['submit'])) {
    $judul = trim($_POST['judul']);
    $penulis = trim($_POST['penulis']);
    $tahun = trim($_POST['tahun']);

    // File upload handling
    $fileName = null;
    if (!isset($_FILES['pdf']) || $_FILES['pdf']['error'] == UPLOAD_ERR_NO_FILE) {
        $errors[] = "Silakan pilih file PDF.";
    } else {
        $file = $_FILES['pdf'];
        $allowedMime = ['application/pdf'];
        $allowedExt = ['pdf'];
        $maxSize = 10 * 1024 * 1024; // 10 MB

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($mime, $allowedMime) || !in_array($ext, $allowedExt)) {
            $errors[] = "File harus berformat PDF.";
        }

        if ($file['size'] > $maxSize) {
            $errors[] = "Ukuran file maksimal 10 MB.";
        }

        if (empty($errors)) {
            // move uploaded file
            $uploadDir = __DIR__ . "/uploads/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $newName = time() . "_" . bin2hex(random_bytes(6)) . "." . $ext;
            $dest = $uploadDir . $newName;
            if (move_uploaded_file($file['tmp_name'], $dest)) {
                $fileName = $newName;
            } else {
                $errors[] = "Gagal menyimpan file.";
            }
        }
    }

    if (empty($judul) || empty($penulis) || empty($tahun)) {
        $errors[] = "Semua field harus diisi.";
    }

    if (empty($errors) && $fileName) {
        // save to DB via ORM
        $buku->create([
            "judul" => $judul,
            "penulis" => $penulis,
            "tahun" => $tahun,
            "file_pdf" => $fileName,
            "tanggal_upload" => date('Y-m-d H:i:s')
        ]);
        $success = true;
        // Redirect after successful upload
        header("Location: index.php?msg=added");
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Tambah Buku - LibroVault</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'header.php'; ?>

<div class="container">
    <h2>Tambah Buku</h2>

    <?php if (!empty($errors)): ?>
        <div class="error">
            <?php foreach($errors as $err) echo "<p>$err</p>"; ?>
        </div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        Judul:<br>
        <input type="text" name="judul" required><br><br>

        Penulis:<br>
        <input type="text" name="penulis" required><br><br>

        Tahun:<br>
        <input type="text" name="tahun" required><br><br>

        Upload PDF:<br>
        <input type="file" name="pdf" accept="application/pdf" required><br><br>

        <input type="submit" name="submit" value="Simpan">
        <a href="index.php" class="button">Kembali</a>
    </form>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
