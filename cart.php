<?php
require_once 'config.php';
if(isset($_POST['add'])){
 $id=(int)$_POST['product_id'];$qty=max(1,(int)($_POST['qty']??1));
 $st=$conn->prepare("SELECT stock FROM products WHERE id=? AND status='active'");$st->bind_param("i",$id);$st->execute();$prod=$st->get_result()->fetch_assoc();
 if(!$prod || $prod['stock']<1){$_SESSION['flash']=['type'=>'danger','message'=>'Produk tidak tersedia.'];}
 else{$qty=min($qty,(int)$prod['stock']);$_SESSION['cart'][$id]=min((int)($_SESSION['cart'][$id]??0)+$qty,(int)$prod['stock']);$_SESSION['flash']=['type'=>'success','message'=>'Produk ditambahkan ke keranjang.'];}
 redirect('cart.php');
}
if(isset($_POST['update'])){
 foreach(($_POST['qty']??[]) as $id=>$qty){$id=(int)$id;$qty=(int)$qty;if($qty<=0){unset($_SESSION['cart'][$id]);continue;}
  $st=$conn->prepare("SELECT stock FROM products WHERE id=?");$st->bind_param("i",$id);$st->execute();$r=$st->get_result()->fetch_assoc();
  if($r)$_SESSION['cart'][$id]=min($qty,(int)$r['stock']);}
 $_SESSION['flash']=['type'=>'success','message'=>'Keranjang diperbarui.'];redirect('cart.php');
}
if(isset($_GET['remove'])){unset($_SESSION['cart'][(int)$_GET['remove']]);redirect('cart.php');}
$items=[];$total=0;
foreach(($_SESSION['cart']??[]) as $id=>$qty){$st=$conn->prepare("SELECT * FROM products WHERE id=? AND status='active'");$st->bind_param("i",$id);$st->execute();$p=$st->get_result()->fetch_assoc();if(!$p){unset($_SESSION['cart'][$id]);continue;}$sub=$p['price']*$qty;$total+=$sub;$items[]=['p'=>$p,'qty'=>$qty,'sub'=>$sub];}
$page_title="Keranjang";include 'partials/header.php';
?>
<h1 class="h3 fw-bold mb-4">Keranjang Belanja</h1>
<?php if(!$items):?><div class="text-center bg-white rounded-4 p-5"><div class="display-3">🛒</div><h4>Keranjang masih kosong</h4><a href="index.php" class="btn btn-success mt-2">Cari Produk</a></div>
<?php else:?>
<form method="post"><div class="card border-0 shadow-sm"><div class="table-responsive"><table class="table mb-0"><thead><tr><th>Produk</th><th>Harga</th><th width="130">Jumlah</th><th>Subtotal</th><th></th></tr></thead><tbody>
<?php foreach($items as $it):$p=$it['p'];?><tr><td><?=e($p['name'])?></td><td><?=rupiah($p['price'])?></td><td><input class="form-control" type="number" min="0" max="<?=$p['stock']?>" name="qty[<?=$p['id']?>]" value="<?=$it['qty']?>"></td><td><?=rupiah($it['sub'])?></td><td><a class="btn btn-sm btn-outline-danger" href="?remove=<?=$p['id']?>">Hapus</a></td></tr><?php endforeach;?>
</tbody></table></div></div><div class="d-flex flex-wrap justify-content-between align-items-center mt-4 gap-3"><button class="btn btn-outline-success" name="update">Perbarui Keranjang</button><div class="text-end"><div class="text-secondary">Total belanja</div><div class="h4 fw-bold text-success"><?=rupiah($total)?></div><a class="btn btn-success" href="checkout.php">Lanjut Checkout</a></div></div></form>
<?php endif;include 'partials/footer.php';?>