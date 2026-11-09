<?php
require_once __DIR__ . '/koneksi.php';

function h($value) {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}
function rupiah($value) {
    return 'Rp ' . number_format((float)$value, 0, ',', '.');
}
function scalar(PDO $pdo, string $sql, $default = 0) {
    try {
        $value = $pdo->query($sql)->fetchColumn();
        return $value === false || $value === null ? $default : $value;
    } catch (Throwable $e) {
        return $default;
    }
}
function rows(PDO $pdo, string $sql): array {
    try { return $pdo->query($sql)->fetchAll(); }
    catch (Throwable $e) { return []; }
}

$page = $_GET['page'] ?? 'dashboard';
$allowed = ['dashboard','produk','kategori','pelanggan','transaksi','laporan'];
if (!in_array($page, $allowed, true)) $page = 'dashboard';

$totalProduk = (int) scalar($pdo, "SELECT COUNT(*) FROM tbl_produk");
$totalPelanggan = (int) scalar($pdo, "SELECT COUNT(*) FROM tbl_pelanggan");
$totalTransaksi = (int) scalar($pdo, "SELECT COUNT(*) FROM tbl_transaksi");
$totalStok = (int) scalar($pdo, "SELECT COALESCE(SUM(stok),0) FROM tbl_produk");
$totalPenjualan = (float) scalar($pdo, "SELECT COALESCE(SUM(total_belanja),0) FROM tbl_transaksi");
$todaySales = (float) scalar($pdo, "SELECT COALESCE(SUM(total_belanja),0) FROM tbl_transaksi WHERE tanggal = CURDATE()");
$todayTransactions = (int) scalar($pdo, "SELECT COUNT(*) FROM tbl_transaksi WHERE tanggal = CURDATE()");

$recent = rows($pdo, "
    SELECT t.id_transaksi, t.tanggal, t.total_belanja, p.nama_pelanggan
    FROM tbl_transaksi t
    LEFT JOIN tbl_pelanggan p ON p.id_pelanggan = t.id_pelanggan
    ORDER BY t.id_transaksi DESC LIMIT 7
");
$products = rows($pdo, "
    SELECT pr.id_produk, pr.nama_produk, pr.harga, pr.stok, k.nama_kategori
    FROM tbl_produk pr
    LEFT JOIN tbl_kategori k ON k.id_kategori = pr.id_kategori
    ORDER BY pr.id_produk DESC
");
$categories = rows($pdo, "SELECT id_kategori, nama_kategori FROM tbl_kategori ORDER BY id_kategori DESC");
$customers = rows($pdo, "SELECT id_pelanggan, nama_pelanggan, kota FROM tbl_pelanggan ORDER BY id_pelanggan DESC");
$transactions = rows($pdo, "
    SELECT t.id_transaksi, t.tanggal, t.total_belanja, p.nama_pelanggan
    FROM tbl_transaksi t
    LEFT JOIN tbl_pelanggan p ON p.id_pelanggan = t.id_pelanggan
    ORDER BY t.id_transaksi DESC
");
$lowStock = rows($pdo, "SELECT nama_produk, stok FROM tbl_produk WHERE stok <= 5 ORDER BY stok ASC LIMIT 5");

$chartRows = rows($pdo, "
    SELECT DATE_FORMAT(tanggal, '%Y-%m') AS bulan,
           DATE_FORMAT(tanggal, '%b %Y') AS label,
           SUM(total_belanja) AS total
    FROM tbl_transaksi
    WHERE tanggal >= DATE_SUB(CURDATE(), INTERVAL 5 MONTH)
    GROUP BY DATE_FORMAT(tanggal, '%Y-%m'), DATE_FORMAT(tanggal, '%b %Y')
    ORDER BY bulan ASC
");
$maxChart = 1;
foreach ($chartRows as $r) $maxChart = max($maxChart, (float)$r['total']);

$labels = [
    'dashboard' => ['Dashboard', 'Ringkasan aktivitas toko hari ini.'],
    'produk' => ['Produk', 'Kelola katalog dan persediaan produk toko.'],
    'kategori' => ['Kategori', 'Daftar kategori produk toko.'],
    'pelanggan' => ['Pelanggan', 'Informasi pelanggan yang terdaftar.'],
    'transaksi' => ['Transaksi', 'Riwayat transaksi penjualan toko.'],
    'laporan' => ['Laporan', 'Ringkasan dan detail laporan penjualan.']
];
[$title, $subtitle] = $labels[$page];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($title) ?> | TokoKu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="app-shell">
    <aside class="sidebar" id="sidebar">
        <a class="brand" href="index.php">
            <span class="brand-mark">T</span>
            <span><strong>TokoKu</strong><small>STORE MANAGEMENT</small></span>
        </a>
        <div class="side-label">MENU UTAMA</div>
        <nav class="nav">
            <a class="<?= $page==='dashboard'?'active':'' ?>" href="?page=dashboard"><span class="nav-icon">▦</span>Dashboard</a>
        </nav>
        <div class="side-label">MANAJEMEN TOKO</div>
        <nav class="nav">
            <a class="<?= $page==='produk'?'active':'' ?>" href="?page=produk"><span class="nav-icon">▣</span>Produk</a>
            <a class="<?= $page==='kategori'?'active':'' ?>" href="?page=kategori"><span class="nav-icon">◫</span>Kategori</a>
            <a class="<?= $page==='pelanggan'?'active':'' ?>" href="?page=pelanggan"><span class="nav-icon">♙</span>Pelanggan</a>
            <a class="<?= $page==='transaksi'?'active':'' ?>" href="?page=transaksi"><span class="nav-icon">⇄</span>Transaksi</a>
        </nav>
        <div class="side-label">ANALISIS</div>
        <nav class="nav">
            <a class="<?= $page==='laporan'?'active':'' ?>" href="?page=laporan"><span class="nav-icon">▤</span>Laporan</a>
        </nav>
        <div class="sidebar-bottom">
            <div class="help-card"><div class="help-icon">✦</div><strong>Kelola toko lebih mudah</strong><p>Pantau penjualan dan stok produk dalam satu tempat.</p></div>
            <div class="profile"><div class="avatar">A</div><div><strong>Administrator</strong><small>Admin Toko</small></div><span class="dots">•••</span></div>
        </div>
    </aside>

    <main class="main">
        <header class="topbar">
            <div class="top-left">
                <button class="menu-toggle" id="menuToggle" aria-label="Buka menu">☰</button>
                <div class="breadcrumb">Workspace <span>/</span> <strong><?= h($title) ?></strong></div>
            </div>
            <div class="top-actions">
                <div class="searchbox"><span>⌕</span><input id="globalSearch" type="search" placeholder="Cari data..." aria-label="Cari data"></div>
                <button class="icon-button" title="Notifikasi">♧<i></i></button>
                <div class="top-avatar">A</div>
            </div>
        </header>

        <section class="content">
            <div class="page-heading">
                <div><div class="eyebrow">TOKOKU WORKSPACE</div><h1><?= h($title) ?></h1><p><?= h($subtitle) ?></p></div>
                <div class="date-pill"><span>▦</span><?= date('d M Y') ?></div>
            </div>

            <?php if ($page === 'dashboard'): ?>
                <div class="welcome-banner">
                    <div><span class="welcome-tag">SELAMAT DATANG 👋</span><h2>Halo, Administrator!</h2><p>Ini ringkasan kondisi tokomu. Semoga harimu produktif.</p></div>
                    <div class="banner-art"><span class="art-circle">↗</span><span class="art-box">▣</span><span class="art-spark">✦</span></div>
                </div>
                <div class="stats-grid">
                    <article class="stat-card"><div class="stat-top"><span class="stat-icon purple">▣</span><span class="trend">Data toko</span></div><div class="stat-label">Total Produk</div><div class="stat-value"><?= number_format($totalProduk) ?></div><div class="stat-foot">Produk terdaftar di katalog</div></article>
                    <article class="stat-card"><div class="stat-top"><span class="stat-icon blue">♙</span><span class="trend">Pelanggan</span></div><div class="stat-label">Total Pelanggan</div><div class="stat-value"><?= number_format($totalPelanggan) ?></div><div class="stat-foot">Pelanggan terdaftar</div></article>
                    <article class="stat-card"><div class="stat-top"><span class="stat-icon orange">⇄</span><span class="trend">Transaksi</span></div><div class="stat-label">Total Transaksi</div><div class="stat-value"><?= number_format($totalTransaksi) ?></div><div class="stat-foot"><?= number_format($todayTransactions) ?> transaksi hari ini</div></article>
                    <article class="stat-card"><div class="stat-top"><span class="stat-icon green">Rp</span><span class="trend">Akumulasi</span></div><div class="stat-label">Total Penjualan</div><div class="stat-value money"><?= rupiah($totalPenjualan) ?></div><div class="stat-foot">Hari ini: <?= rupiah($todaySales) ?></div></article>
                </div>

                <div class="dashboard-grid">
                    <section class="panel chart-panel">
                        <div class="panel-heading"><div><h3>Grafik Penjualan</h3><p>Performa penjualan enam bulan terakhir</p></div><span class="period-chip">6 BULAN TERAKHIR</span></div>
                        <?php if ($chartRows): ?>
                            <div class="chart">
                                <div class="chart-y"><span>100%</span><span>75%</span><span>50%</span><span>25%</span><span>0%</span></div>
                                <div class="chart-area">
                                    <div class="grid-lines"><i></i><i></i><i></i><i></i><i></i></div>
                                    <div class="bars">
                                        <?php foreach ($chartRows as $r): $height = max(5, ((float)$r['total'] / $maxChart) * 100); ?>
                                            <div class="bar-group" title="<?= h($r['label']) ?>: <?= h(rupiah($r['total'])) ?>"><div class="bar" style="height:<?= $height ?>%"></div><span><?= h($r['label']) ?></span></div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="empty-chart"><div class="empty-icon">▥</div><strong>Belum ada data grafik</strong><span>Grafik akan muncul setelah tersedia transaksi penjualan.</span></div>
                        <?php endif; ?>
                        <div class="chart-summary"><span><i class="legend-dot"></i>Total penjualan tercatat</span><strong><?= rupiah($totalPenjualan) ?></strong></div>
                    </section>
                    <section class="panel stock-panel">
                        <div class="panel-heading"><div><h3>Ringkasan Stok</h3><p>Kondisi persediaan produk</p></div><span class="stock-icon">▤</span></div>
                        <div class="stock-number"><?= number_format($totalStok) ?><small>unit</small></div>
                        <div class="stock-progress"><span style="width:<?= $totalStok > 0 ? '72' : '0' ?>%"></span></div>
                        <div class="stock-caption"><span>Total stok tersedia</span><strong><?= number_format($totalProduk) ?> produk</strong></div>
                        <div class="mini-divider"></div>
                        <h4>Perlu diperhatikan</h4>
                        <?php if ($lowStock): foreach ($lowStock as $item): ?>
                            <div class="low-stock"><span class="low-dot"></span><span><?= h($item['nama_produk']) ?></span><strong><?= (int)$item['stok'] ?> tersisa</strong></div>
                        <?php endforeach; else: ?>
                            <div class="good-stock">✓ Tidak ada produk dengan stok kritis (≤ 5).</div>
                        <?php endif; ?>
                    </section>
                </div>

                <section class="panel table-panel">
                    <div class="panel-heading"><div><h3>Transaksi Terbaru</h3><p>Aktivitas penjualan yang baru tercatat</p></div><a class="text-link" href="?page=transaksi">Lihat semua <span>→</span></a></div>
                    <?php if ($recent): ?>
                    <div class="table-wrap"><table class="data-table searchable-table"><thead><tr><th>ID TRANSAKSI</th><th>PELANGGAN</th><th>TANGGAL</th><th>TOTAL BELANJA</th><th>STATUS</th></tr></thead><tbody>
                    <?php foreach ($recent as $r): ?><tr><td><span class="id-chip">#TRX-<?= str_pad((string)$r['id_transaksi'], 4, '0', STR_PAD_LEFT) ?></span></td><td><div class="customer-cell"><span class="customer-avatar"><?= h(strtoupper(substr($r['nama_pelanggan'] ?? 'P',0,1))) ?></span><strong><?= h($r['nama_pelanggan'] ?? 'Pelanggan tidak ditemukan') ?></strong></div></td><td><?= h($r['tanggal']) ?></td><td class="amount"><?= rupiah($r['total_belanja']) ?></td><td><span class="status-badge">Tercatat</span></td></tr><?php endforeach; ?>
                    </tbody></table></div>
                    <?php else: ?><div class="empty-state"><span>⇄</span><strong>Belum ada transaksi</strong><p>Data transaksi akan tampil di sini setelah penjualan dicatat.</p></div><?php endif; ?>
                </section>

            <?php elseif ($page === 'produk'): ?>
                <section class="panel table-panel">
                    <div class="panel-heading"><div><h3>Daftar Produk</h3><p><?= count($products) ?> produk ditemukan dari database</p></div><span class="period-chip">KATALOG PRODUK</span></div>
                    <?php if ($products): ?><div class="table-wrap"><table class="data-table searchable-table"><thead><tr><th>ID</th><th>NAMA PRODUK</th><th>KATEGORI</th><th>HARGA</th><th>STOK</th><th>KETERANGAN</th></tr></thead><tbody>
                    <?php foreach ($products as $r): ?><tr><td>#<?= (int)$r['id_produk'] ?></td><td><strong><?= h($r['nama_produk']) ?></strong></td><td><?= h($r['nama_kategori'] ?? '-') ?></td><td class="amount"><?= rupiah($r['harga']) ?></td><td><?= (int)$r['stok'] ?></td><td><span class="<?= (int)$r['stok'] <= 5 ? 'status-warning' : 'status-badge' ?>"><?= (int)$r['stok'] <= 5 ? 'Stok menipis' : 'Tersedia' ?></span></td></tr><?php endforeach; ?>
                    </tbody></table></div><?php else: ?><div class="empty-state"><span>▣</span><strong>Belum ada produk</strong><p>Tambahkan data produk melalui pengelolaan database.</p></div><?php endif; ?>
                </section>

            <?php elseif ($page === 'kategori'): ?>
                <section class="panel table-panel"><div class="panel-heading"><div><h3>Daftar Kategori</h3><p><?= count($categories) ?> kategori tersedia</p></div></div>
                <?php if ($categories): ?><div class="table-wrap"><table class="data-table searchable-table"><thead><tr><th>ID KATEGORI</th><th>NAMA KATEGORI</th><th>JENIS</th></tr></thead><tbody><?php foreach ($categories as $r): ?><tr><td>#<?= (int)$r['id_kategori'] ?></td><td><strong><?= h($r['nama_kategori']) ?></strong></td><td><span class="status-badge">Kategori produk</span></td></tr><?php endforeach; ?></tbody></table></div><?php else: ?><div class="empty-state">Belum ada kategori.</div><?php endif; ?></section>

            <?php elseif ($page === 'pelanggan'): ?>
                <section class="panel table-panel"><div class="panel-heading"><div><h3>Data Pelanggan</h3><p><?= count($customers) ?> pelanggan terdaftar</p></div></div>
                <?php if ($customers): ?><div class="table-wrap"><table class="data-table searchable-table"><thead><tr><th>ID</th><th>NAMA PELANGGAN</th><th>KOTA</th><th>STATUS</th></tr></thead><tbody><?php foreach ($customers as $r): ?><tr><td>#<?= (int)$r['id_pelanggan'] ?></td><td><div class="customer-cell"><span class="customer-avatar"><?= h(strtoupper(substr($r['nama_pelanggan'],0,1))) ?></span><strong><?= h($r['nama_pelanggan']) ?></strong></div></td><td><?= h($r['kota'] ?? '-') ?></td><td><span class="status-badge">Terdaftar</span></td></tr><?php endforeach; ?></tbody></table></div><?php else: ?><div class="empty-state">Belum ada pelanggan.</div><?php endif; ?></section>

            <?php elseif ($page === 'transaksi'): ?>
                <section class="panel table-panel"><div class="panel-heading"><div><h3>Riwayat Transaksi</h3><p><?= count($transactions) ?> transaksi ditemukan</p></div></div>
                <?php if ($transactions): ?><div class="table-wrap"><table class="data-table searchable-table"><thead><tr><th>ID TRANSAKSI</th><th>PELANGGAN</th><th>TANGGAL</th><th>TOTAL BELANJA</th><th>STATUS</th></tr></thead><tbody><?php foreach ($transactions as $r): ?><tr><td><span class="id-chip">#TRX-<?= str_pad((string)$r['id_transaksi'],4,'0',STR_PAD_LEFT) ?></span></td><td><strong><?= h($r['nama_pelanggan'] ?? 'Pelanggan tidak ditemukan') ?></strong></td><td><?= h($r['tanggal']) ?></td><td class="amount"><?= rupiah($r['total_belanja']) ?></td><td><span class="status-badge">Tercatat</span></td></tr><?php endforeach; ?></tbody></table></div><?php else: ?><div class="empty-state">Belum ada transaksi.</div><?php endif; ?></section>

            <?php elseif ($page === 'laporan'): ?>
                <div class="report-cards"><div class="report-card"><span>Total Penjualan</span><strong><?= rupiah($totalPenjualan) ?></strong><small>Akumulasi seluruh transaksi</small></div><div class="report-card"><span>Jumlah Transaksi</span><strong><?= number_format($totalTransaksi) ?></strong><small>Transaksi tercatat</small></div><div class="report-card"><span>Rata-rata Transaksi</span><strong><?= rupiah($totalTransaksi ? $totalPenjualan/$totalTransaksi : 0) ?></strong><small>Nilai rata-rata penjualan</small></div></div>
                <section class="panel table-panel"><div class="panel-heading"><div><h3>Laporan Penjualan</h3><p>Data transaksi dan pelanggan</p></div><button class="print-button" onclick="window.print()">Cetak laporan ↗</button></div>
                <?php if ($transactions): ?><div class="table-wrap"><table class="data-table searchable-table"><thead><tr><th>ID TRANSAKSI</th><th>PELANGGAN</th><th>TANGGAL</th><th>TOTAL BELANJA</th></tr></thead><tbody><?php foreach ($transactions as $r): ?><tr><td>#TRX-<?= str_pad((string)$r['id_transaksi'],4,'0',STR_PAD_LEFT) ?></td><td><?= h($r['nama_pelanggan'] ?? '-') ?></td><td><?= h($r['tanggal']) ?></td><td class="amount"><?= rupiah($r['total_belanja']) ?></td></tr><?php endforeach; ?></tbody></table></div><?php else: ?><div class="empty-state">Belum ada data laporan.</div><?php endif; ?></section>
            <?php endif; ?>
            <footer class="footer"><span>© <?= date('Y') ?> <strong>TokoKu</strong>. Sistem Informasi Penjualan Toko.</span><span>Made with care <b>♥</b></span></footer>
        </section>
    </main>
</div>
<script src="assets/app.js"></script>
</body>
</html>
