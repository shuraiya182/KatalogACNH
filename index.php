<?php
require __DIR__ . '/db.php';

$error = '';
try {
    // sesuaikan dengan kolom di table "item": no, kode, nama, img
    $stmt = $pdo->query("SELECT `no`, kode, nama, img FROM item ORDER BY `no` DESC");
    $rows = $stmt->fetchAll();
} catch (\Exception $e) {
    $rows = [];
    $error = $e->getMessage();
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>daftar katalog</title>
    <style>
        .gallery { display:flex; flex-wrap:wrap; gap:12px; }
        .item { width:200px; text-align:center; font-family: Arial, sans-serif; }
        .item img { max-width:100%; height:auto; display:block; border:1px solid #ccc; background:#fff; }
        .meta { margin-top:6px; color:#333; }
        .kode { font-weight:600; font-size:14px; }
        .nama { font-size:13px; color:#555; }
        .no-data { color:#666; text-align:center; margin:20px 0; }
    </style>
</head>
<body>
    <h1>daftar katalog</h1>

    <?php if (!empty($error)): ?>
        <div class="no-data">Error: <?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if (empty($rows)): ?>
        <div class="no-data">Tidak ada data gambar.</div>
    <?php else: ?>
        <div class="gallery">
        <?php foreach ($rows as $r):
            $imgPath = $r['img']; // kolom img sesuai database Anda
            // cek file di server (path relatif dari root project)
            $serverPath = __DIR__ . DIRECTORY_SEPARATOR . ltrim($imgPath, '/\\');
            if ($imgPath && file_exists($serverPath)) {
                $src = htmlspecialchars($imgPath);
            } else {
                // placeholder SVG untuk gambar hilang
                $src = 'data:image/svg+xml;utf8,' . rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" width="200" height="150"><rect fill="#eee" width="100%" height="100%"/><text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="#888" font-size="14">No image</text></svg>');
            }
        ?>
            <div class="item">
                <img src="<?php echo $src; ?>" alt="<?php echo htmlspecialchars($r['nama']); ?>">
                <div class="meta">
                    <div class="kode"><?php echo htmlspecialchars($r['kode']); ?></div>
                    <div class="nama"><?php echo htmlspecialchars($r['nama']); ?></div>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
    <?php endif; ?>
</body>
</html>