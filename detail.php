<?php
require_once "Buku.php";
$buku = new Buku();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$data = $buku->find($id);
if (!$data) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Detail Buku - <?= htmlspecialchars($data['judul']) ?></title>
    <link rel="stylesheet" href="style.css">
    <!-- PDF.js library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
</head>
<body>
<?php include 'header.php'; ?>

<div class="container">
    <h2>Detail Buku</h2>
    <p><strong>Judul:</strong> <?= htmlspecialchars($data['judul']) ?></p>
    <p><strong>Penulis:</strong> <?= htmlspecialchars($data['penulis']) ?></p>
    <p><strong>Tahun:</strong> <?= htmlspecialchars($data['tahun']) ?></p>
    <p><strong>Uploaded:</strong> <?= htmlspecialchars($data['tanggal_upload']) ?></p>

    <?php if ($data['file_pdf']): ?>
        <h3><i class="fas fa-book-open"></i> Baca Buku</h3>

        <!-- Enhanced PDF Viewer with Controls -->
        <div class="pdf-viewer-container">
            <!-- PDF Controls -->
            <div class="pdf-controls">
                <button id="prev-page" class="btn small" title="Halaman Sebelumnya">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <span id="page-info">Halaman <span id="page-num">1</span> dari <span id="page-count">0</span></span>
                <button id="next-page" class="btn small" title="Halaman Selanjutnya">
                    <i class="fas fa-chevron-right"></i>
                </button>

                <span class="control-separator">|</span>

                <button id="zoom-out" class="btn small" title="Zoom Out">
                    <i class="fas fa-search-minus"></i>
                </button>
                <span id="zoom-level">100%</span>
                <button id="zoom-in" class="btn small" title="Zoom In">
                    <i class="fas fa-search-plus"></i>
                </button>

                <span class="control-separator">|</span>

                <button id="fullscreen" class="btn small" title="Fullscreen">
                    <i class="fas fa-expand"></i>
                </button>

                <span class="control-separator">|</span>

                <a href="pdf_viewer.php?file=<?= rawurlencode($data['file_pdf']) ?>" target="_blank" class="btn small secondary" title="Buka di Tab Baru">
                    <i class="fas fa-external-link-alt"></i>
                </a>
                <a href="pdf_viewer.php?file=<?= rawurlencode($data['file_pdf']) ?>" download class="btn small primary" title="Download PDF">
                    <i class="fas fa-download"></i>
                </a>
            </div>

            <!-- PDF Canvas Container -->
            <div class="pdf-viewer">
                <canvas id="pdf-canvas"></canvas>
                <div id="pdf-loading" class="pdf-loading">
                    <i class="fas fa-spinner fa-spin"></i>
                    <p>Memuat PDF...</p>
                </div>
                <div id="pdf-error" class="pdf-error" style="display: none;">
                    <i class="fas fa-exclamation-triangle fa-2x"></i>
                    <p>Gagal memuat PDF</p>
                    <button id="retry-pdf" class="btn small">Coba Lagi</button>
                </div>
            </div>
        </div>

        <!-- PDF.js Script -->
        <script>
        // PDF.js configuration
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

        let pdfDoc = null;
        let pageNum = 1;
        let pageRendering = false;
        let pageNumPending = null;
        let scale = 1.0;
        const canvas = document.getElementById('pdf-canvas');
        const ctx = canvas.getContext('2d');

        // Load PDF
        function loadPDF(url) {
            document.getElementById('pdf-loading').style.display = 'flex';
            document.getElementById('pdf-error').style.display = 'none';

            pdfjsLib.getDocument(url).promise.then(function(pdf) {
                pdfDoc = pdf;
                document.getElementById('page-count').textContent = pdf.numPages;
                document.getElementById('pdf-loading').style.display = 'none';

                // Initial render
                renderPage(pageNum);
            }).catch(function(error) {
                console.error('Error loading PDF:', error);
                document.getElementById('pdf-loading').style.display = 'none';
                document.getElementById('pdf-error').style.display = 'flex';
            });
        }

        // Render page
        function renderPage(num) {
            pageRendering = true;

            pdfDoc.getPage(num).then(function(page) {
                const viewport = page.getViewport({scale: scale});
                canvas.height = viewport.height;
                canvas.width = viewport.width;

                const renderContext = {
                    canvasContext: ctx,
                    viewport: viewport
                };

                const renderTask = page.render(renderContext);

                renderTask.promise.then(function() {
                    pageRendering = false;
                    if (pageNumPending !== null) {
                        renderPage(pageNumPending);
                        pageNumPending = null;
                    }
                });
            });

            document.getElementById('page-num').textContent = num;
        }

        // Queue render page
        function queueRenderPage(num) {
            if (pageRendering) {
                pageNumPending = num;
            } else {
                renderPage(num);
            }
        }

        // Show previous page
        function showPrevPage() {
            if (pageNum <= 1) return;
            pageNum--;
            queueRenderPage(pageNum);
        }

        // Show next page
        function showNextPage() {
            if (pageNum >= pdfDoc.numPages) return;
            pageNum++;
            queueRenderPage(pageNum);
        }

        // Zoom functions
        function zoomIn() {
            if (scale >= 3.0) return;
            scale += 0.25;
            updateZoomDisplay();
            queueRenderPage(pageNum);
        }

        function zoomOut() {
            if (scale <= 0.5) return;
            scale -= 0.25;
            updateZoomDisplay();
            queueRenderPage(pageNum);
        }

        function updateZoomDisplay() {
            document.getElementById('zoom-level').textContent = Math.round(scale * 100) + '%';
        }

        // Fullscreen (pseudo-fullscreen for better scrolling support)
        function toggleFullscreen() {
            const container = document.querySelector('.pdf-viewer-container');
            if (container.classList.contains('fullscreen-active')) {
                // Exit fullscreen
                container.classList.remove('fullscreen-active');
            } else {
                // Enter fullscreen
                container.classList.add('fullscreen-active');
            }
        }

        // Event listeners
        document.getElementById('prev-page').addEventListener('click', showPrevPage);
        document.getElementById('next-page').addEventListener('click', showNextPage);
        document.getElementById('zoom-in').addEventListener('click', zoomIn);
        document.getElementById('zoom-out').addEventListener('click', zoomOut);
        document.getElementById('fullscreen').addEventListener('click', toggleFullscreen);
        document.getElementById('retry-pdf').addEventListener('click', function() {
            loadPDF('pdf_viewer.php?file=<?= rawurlencode($data['file_pdf']) ?>');
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.target.tagName.toLowerCase() === 'input') return;

            switch(e.key) {
                case 'ArrowLeft':
                    e.preventDefault();
                    showPrevPage();
                    break;
                case 'ArrowRight':
                    e.preventDefault();
                    showNextPage();
                    break;
                case '+':
                case '=':
                    e.preventDefault();
                    zoomIn();
                    break;
                case '-':
                    e.preventDefault();
                    zoomOut();
                    break;
                case 'f':
                case 'F':
                    e.preventDefault();
                    toggleFullscreen();
                    break;
            }
        });

        // Load PDF on page load
        window.addEventListener('load', function() {
            loadPDF('pdf_viewer.php?file=<?= rawurlencode($data['file_pdf']) ?>');
        });
        </script>
    <?php else: ?>
        <div class="no-pdf">
            <i class="fas fa-file-pdf fa-3x" style="color: #f5b700; opacity: 0.5;"></i>
            <p>Tidak ada file PDF tersedia.</p>
        </div>
    <?php endif; ?>

    <a href="index.php" class="button">Kembali</a>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
