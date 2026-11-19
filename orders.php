<?php
require_once 'config.php';require_login();
$uid=(int)$_SESSION['user']['id'];
$st=$conn->prepare("SELECT * FROM orders WHERE user_id=? ORDER BY created_at DESC");$st->bind_param("i",$uid);$st->execute();$orders=$st->get_result();
$page_title="Pesanan Saya";include 'partials/header.php';
?>
<h1 class="h3 fw-bold mb-4">Pesanan Saya</h1>
<?php if(!$orders->num_rows):?><div class="alert alert-info">Belum ada pesanan. <a href="index.php">Mulai belanja</a>.</div><?php else:?>
<?php while($o=$orders->fetch_assoc()):?><div class="card border-0 shadow-sm mb-3"><div class="card-body"><div class="d-flex flex-wrap justify-content-between gap-2"><div><strong>Pesanan #<?=$o['id']?></strong><div class="small-muted"><?=date('d-m-Y H:i',strtotime($o['created_at']))?></div></div><span class="badge text-bg-<?=['pending'=>'warning','processed'=>'info','shipped'=>'primary','completed'=>'success','cancelled'=>'danger'][$o['status']]??'secondary'?>"><?=e(ucfirst($o['status']))?></span></div><div class="mt-3">Total: <strong class="text-success"><?=rupiah($o['total_amount'])?></strong></div><div class="small-muted mt-1"><?=e($o['address'])?></div><button class="btn btn-sm btn-outline-success mt-3" data-bs-toggle="collapse" data-bs-target="#order<?=$o['id']?>">Detail item</button><div class="collapse mt-3" id="order<?=$o['id']?>"><?php $oid=$o['id'];$it=$conn->prepare("SELECT * FROM order_items WHERE order_id=?");$it->bind_param("i",$oid);$it->execute();$items=$it->get_result();while($x=$items->fetch_assoc()):?><div class="d-flex justify-content-between border-bottom py-2"><span><?=e($x['product_name'])?> × <?=$x['quantity']?></span><span><?=rupiah($x['subtotal'])?></span></div><?php endwhile;?></div></div></div><?php endwhile;endif;?>
<?php include 'partials/footer.php';?>