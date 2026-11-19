<?php
require_once 'config.php';
$q = trim($_GET['q'] ?? '');
$category_id = (int)($_GET['kategori'] ?? 0);

$sql = "SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON c.id=p.category_id WHERE p.status='active'";
$params = []; $types = '';
if ($q !== '') { $sql .= " AND (p.name LIKE ? OR p.description LIKE ?)"; $like = "%$q%"; $params[]=$like; $params[]=$like; $types.='ss'; }
if ($category_id > 0) { $sql .= " AND p.category_id=?"; $params[]=$category_id; $types.='i'; }
$sql .= " ORDER BY p.created_at DESC";
$stmt = $conn->prepare($sql);
if ($params) $stmt->bind_param($types, ...$params);
$stmt->execute(); $products = $stmt->get_result();
$categories = $conn->query("SELECT * FROM categories ORDER BY name");
$page_title = "Marketplace";
include 'partials/header.php';
?>
<section class="hero rounded-4 p-4 p-md-5 mb-4">
  <div class="row align-items-center">
    <div class="col-lg-7">
      <span class="badge text-bg-light mb-3">Belanja produk lokal</span>
      <h1 class="display-5 fw-bold">Pasar tradisional,<br>lebih dekat secara digital.</h1>
      <p class="lead mb-4">Temukan kebutuhan harian dan produk unggulan pedagang lokal dengan mudah.</p>
      <a href="#produk" class="btn btn-light btn-lg fw-semibold">Mulai Belanja</a>
    </div>
    <div class="col-lg-5 text-center d-none d-lg-block"><div class="hero-emoji">🧺🥬🍅</div></div>
  </div>
</section>
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3" id="produk">
  <div><h2 class="h3 fw-bold mb-1">Produk pilihan</h2><p class="text-secondary mb-0">Dukung pedagang dan produk lokal.</p></div>
  <form class="d-flex gap-2" method="get">
    <input class="form-control" type="search" name="q" placeholder="Cari produk..." value="<?=e($q)?>">
    <button class="btn btn-success">Cari</button>
  </form>
</div>
<div class="d-flex gap-2 flex-wrap mb-4">
  <a class="btn btn-sm <?= $category_id===0?'btn-success':'btn-outline-success' ?>" href="index.php">Semua</a>
  <?php while($cat=$categories->fetch_assoc()): ?>
    <a class="btn btn-sm <?= $category_id===(int)$cat['id']?'btn-success':'btn-outline-success' ?>" href="?kategori=<?=$cat['id']?>"><?=e($cat['name'])?></a>
  <?php endwhile; ?>
</div>
<div class="row g-4">
<?php if($products->num_rows): while($p=$products->fetch_assoc()): ?>
  <div class="col-6 col-md-4 col-lg-3">
    <div class="card product-card h-100">
      <a href="detail.php?id=<?=$p['id']?>" class="product-image">
        <?php if($p['image']): ?><img src="uploads/<?=e($p['image'])?>" alt="<?=e($p['name'])?>">
        <?php else: ?><div class="placeholder-image">🛍️</div><?php endif; ?>
      </a>
      <div class="card-body d-flex flex-column">
        <small class="text-secondary"><?=e($p['category_name'] ?? 'Umum')?></small>
        <a class="product-name" href="detail.php?id=<?=$p['id']?>"><?=e($p['name'])?></a>
        <div class="fw-bold text-success mt-2"><?=rupiah($p['price'])?></div>
        <small class="text-secondary mb-3">Stok: <?=$p['stock']?></small>
        <a href="detail.php?id=<?=$p['id']?>" class="btn btn-outline-success btn-sm mt-auto">Lihat Produk</a>
      </div>
    </div>
  </div>
<?php endwhile; else: ?>
  <div class="col-12"><div class="alert alert-info">Produk tidak ditemukan.</div></div>
<?php endif; ?>
</div>
<?php include 'partials/footer.php'; ?>
