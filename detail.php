<?php
require_once 'config.php';
$id=(int)($_GET['id']??0);
$stmt=$conn->prepare("SELECT p.*,c.name category_name FROM products p LEFT JOIN categories c ON c.id=p.category_id WHERE p.id=? AND p.status='active'");
$stmt->bind_param("i",$id);$stmt->execute();$p=$stmt->get_result()->fetch_assoc();
if(!$p){http_response_code(404);die("Produk tidak ditemukan. <a href='index.php'>Kembali</a>");}
$page_title=$p['name'];include 'partials/header.php';
?>
<div class="row g-4">
 <div class="col-md-6"><?php if($p['image']):?><img class="detail-image" src="uploads/<?=e($p['image'])?>" alt="<?=e($p['name'])?>"><?php else:?><div class="placeholder-image rounded-4 text-center p-5 bg-light">🛍️</div><?php endif;?></div>
 <div class="col-md-6">
  <span class="badge text-bg-success"><?=e($p['category_name']??'Umum')?></span>
  <h1 class="fw-bold mt-3"><?=e($p['name'])?></h1><div class="display-6 text-success fw-bold mb-3"><?=rupiah($p['price'])?></div>
  <p class="text-secondary"><?=nl2br(e($p['description']))?></p><p>Stok tersedia: <strong><?=$p['stock']?></strong></p>
  <?php if($p['stock']>0):?>
  <form method="post" action="cart.php" class="d-flex gap-2 align-items-center">
   <input type="hidden" name="product_id" value="<?=$p['id']?>">
   <input type="number" name="qty" class="form-control" style="max-width:100px" min="1" max="<?=$p['stock']?>" value="1">
   <button class="btn btn-success" name="add">+ Keranjang</button>
  </form>
  <?php else:?><button class="btn btn-secondary" disabled>Stok Habis</button><?php endif;?>
  <a href="index.php" class="btn btn-link mt-3">← Kembali belanja</a>
 </div>
</div>
<?php include 'partials/footer.php';?>