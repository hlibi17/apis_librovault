<?php
require_once "Buku.php";
$buku = new Buku();

// Handle success messages
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';

// pagination
$limit = 5;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

$total = $buku->count();
$totalPages = (int) ceil($total / $limit);

$daftar = $buku->paginate($limit, $offset, "id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>LibroVault</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'header.php'; ?>

<div class="container">
    <h2>Daftar Buku</h2>

    <?php if ($msg === 'added'): ?>
        <div class="success-message" style="padding: 10px; margin-bottom: 20px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 5px;">
            <i class="fas fa-check-circle"></i> Buku berhasil ditambahkan!
        </div>
    <?php elseif ($msg === 'updated'): ?>
        <div class="success-message" style="padding: 10px; margin-bottom: 20px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 5px;">
            <i class="fas fa-check-circle"></i> Buku berhasil diupdate!
        </div>
    <?php elseif ($msg === 'deleted'): ?>
        <div class="success-message" style="padding: 10px; margin-bottom: 20px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 5px;">
            <i class="fas fa-check-circle"></i> Buku berhasil dihapus!
        </div>
    <?php endif; ?>

    <div class="top-actions">
        <a href="tambah.php" class="button primary">+ Tambah Buku</a>
    </div>

    <table class="table-list">
        <tr>
            <th>ID</th>
            <th>Judul</th>
            <th>Penulis</th>
            <th>Tahun</th>
            <th>PDF</th>
            <th style="width: 220px;">Aksi</th>
        </tr>

        <?php if (empty($daftar)): ?>
            <tr><td colspan="6" style="text-align:center;">Belum ada buku.</td></tr>
        <?php else: ?>
            <?php foreach ($daftar as $b): ?>
                <tr>
                    <td><?= htmlspecialchars($b['id']) ?></td>
                    <td><?= htmlspecialchars($b['judul']) ?></td>
                    <td><?= htmlspecialchars($b['penulis']) ?></td>
                    <td><?= htmlspecialchars($b['tahun']) ?></td>
                    <td>
                        <?php if ($b['file_pdf']): ?>
                            <a class="badge pdf" href="detail.php?id=<?= $b['id'] ?>" title="Baca PDF"><i class="fas fa-book-open"></i> Baca</a>
                        <?php else: ?>
                            <span class="badge empty">-</span>
                        <?php endif; ?>
                    </td>
                    <td class="actions">
                        <a href="detail.php?id=<?= $b['id'] ?>" class="btn small info">Detail</a>
                        <a href="edit.php?id=<?= $b['id'] ?>" class="btn small warning">Edit</a>
                        <a href="hapus.php?id=<?= $b['id'] ?>" class="btn small danger"
                           onclick="return confirm('Hapus buku ini?')">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>

    </table>

    <!-- pagination -->
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=<?= $page - 1 ?>" class="button">Prev</a>
        <?php endif; ?>

        <?php for ($p = 1; $p <= $totalPages; $p++): ?>
            <a href="?page=<?= $p ?>" 
               class="<?= $p == $page ? 'button current' : 'button' ?>">
               <?= $p ?>
            </a>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
            <a href="?page=<?= $page + 1 ?>" class="button">Next</a>
        <?php endif; ?>
    </div>

</div>

<?php include 'footer.php'; ?>
</body>
</html>
