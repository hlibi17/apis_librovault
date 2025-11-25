<?php
require_once "Buku.php";
$buku = new Buku();

$errors = [];
$success = false;

// Pastikan ada ID
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = (int)$_GET['id'];
$book = $buku->find($id);

if (!$book) {
    header("Location: index.php");
    exit;
}

// Proses update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = trim($_POST['judul']);
    $penulis = trim($_POST['penulis']);
    $tahun = trim($_POST['tahun']);

    $pdf = $book['file_pdf']; // default PDF lama

    // Jika upload PDF baru
    if (!empty($_FILES['pdf']['name'])) {
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
        } elseif ($file['size'] > $maxSize) {
            $errors[] = "Ukuran file maksimal 10 MB.";
        } else {
            // Generate new filename
            $newName = time() . "_" . bin2hex(random_bytes(6)) . "." . $ext;
            $target = __DIR__ . "/uploads/" . $newName;

            if (move_uploaded_file($file['tmp_name'], $target)) {
                // Delete old file if exists
                if (!empty($book['file_pdf'])) {
                    $oldFile = __DIR__ . '/uploads/' . $book['file_pdf'];
                    if (file_exists($oldFile)) {
                        unlink($oldFile);
                    }
                }
                $pdf = $newName;
            } else {
                $errors[] = "Gagal mengupload file PDF baru.";
            }
        }
    }

    if (empty($judul) || empty($penulis) || empty($tahun)) {
        $errors[] = "Semua field harus diisi.";
    }

    if (empty($errors)) {
        // Update ke database
        if ($buku->update($id, [
            'judul' => $judul,
            'penulis' => $penulis,
            'tahun' => $tahun,
            'file_pdf' => $pdf
        ])) {
            $success = true;
            header("Location: index.php?msg=updated");
            exit;
        } else {
            $errors[] = "Gagal mengupdate data.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Buku</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include "header.php"; ?>

<div class="container">
    <h2><i class="fas fa-edit"></i> Edit Buku</h2>

    <div class="form-container">
        <form action="" method="POST" enctype="multipart/form-data" class="modern-form">
            <div class="form-group">
                <label for="judul"><i class="fas fa-book"></i> Judul Buku</label>
                <input type="text" id="judul" name="judul" value="<?= htmlspecialchars($book['judul']) ?>" required>
            </div>

            <div class="form-group">
                <label for="penulis"><i class="fas fa-user"></i> Penulis</label>
                <input type="text" id="penulis" name="penulis" value="<?= htmlspecialchars($book['penulis']) ?>" required>
            </div>

            <div class="form-group">
                <label for="tahun"><i class="fas fa-calendar"></i> Tahun Terbit</label>
                <input type="number" id="tahun" name="tahun" value="<?= htmlspecialchars($book['tahun']) ?>" required min="1000" max="2030">
            </div>

            <div class="form-group">
                <label for="pdf"><i class="fas fa-file-pdf"></i> PDF Baru (opsional)</label>
                <input type="file" id="pdf" name="pdf" accept="application/pdf">
                <small class="form-hint">Biarkan kosong jika tidak ingin mengubah PDF</small>
            </div>

            <?php if ($book['file_pdf']): ?>
            <div class="current-pdf">
                <h4><i class="fas fa-file-alt"></i> PDF Saat Ini</h4>
                <p>
                    <a href="pdf_viewer.php?file=<?= rawurlencode($book['file_pdf']) ?>" target="_blank" rel="noopener noreferrer" class="pdf-link">
                        <i class="fas fa-external-link-alt"></i> <?= htmlspecialchars($book['file_pdf']) ?>
                    </a>
                </p>
            </div>
            <?php endif; ?>

            <div class="form-actions">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save"></i> Update Buku
                </button>
                <a href="index.php" class="btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>

</body>
</html>
