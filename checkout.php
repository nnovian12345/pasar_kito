<?php
require_once 'config.php';require_login();
$cart=$_SESSION['cart']??[];if(!$cart){$_SESSION['flash']=['type'=>'warning','message'=>'Keranjang masih kosong.'];redirect('cart.php');}
$items=[];$total=0;
foreach($cart as $id=>$qty){$st=$conn->prepare("SELECT id,name,price,stock FROM products WHERE id=? AND status='active'");$st->bind_param("i",$id);$st->execute();$p=$st->get_result()->fetch_assoc();if(!$p||$qty>$p['stock']){$_SESSION['flash']=['type'=>'danger','message'=>'Stok salah satu produk berubah. Periksa keranjang.'];redirect('cart.php');}$items[]=['p'=>$p,'qty'=>$qty];$total+=$p['price']*$qty;}
if($_SERVER['REQUEST_METHOD']==='POST'){
 $address=trim($_POST['address']??'');$phone=trim($_POST['phone']??'');$notes=trim($_POST['notes']??'');
 if($address===''||$phone===''){$error="Alamat dan nomor telepon wajib diisi.";}
 else{
  $conn->begin_transaction();
  try{
   $uid=(int)$_SESSION['user']['id'];$st=$conn->prepare("INSERT INTO orders(user_id,total_amount,address,phone,notes,status) VALUES(?,?,?,?,?,'pending')");$st->bind_param("idsss",$uid,$total,$address,$phone,$notes);$st->execute();$order_id=$conn->insert_id;
   foreach($items as $it){$p=$it['p'];$qty=$it['qty'];$price=$p['price'];$pid=$p['id'];
    $ins=$conn->prepare("INSERT INTO order_items(order_id,product_id,product_name,price,quantity,subtotal) VALUES(?,?,?,?,?,?)");$sub=$price*$qty;$ins->bind_param("iisdid",$order_id,$pid,$p['name'],$price,$qty,$sub);$ins->execute();
    $upd=$conn->prepare("UPDATE products SET stock=stock-? WHERE id=? AND stock>=?");$upd->bind_param("iii",$qty,$pid,$qty);$upd->execute();if($upd->affected_rows!==1)throw new Exception("Stok produk tidak mencukupi.");
   }
   $conn->commit();unset($_SESSION['cart']);$_SESSION['flash']=['type'=>'success','message'=>"Pesanan #$order_id berhasil dibuat."];redirect('orders.php');
  }catch(Throwable $ex){$conn->rollback();$error="Pesanan gagal disimpan: ".$ex->getMessage();}
 }
}
$page_title="Checkout";include 'partials/header.php';
?>
<h1 class="h3 fw-bold mb-4">Checkout</h1><div class="row g-4"><div class="col-lg-7"><div class="card border-0 shadow-sm p-4"><h5>Alamat pengiriman</h5><?php if(!empty($error)):?><div class="alert alert-danger"><?=e($error)?></div><?php endif;?>
<form method="post"><label class="form-label mt-2">Alamat lengkap</label><textarea class="form-control mb-3" name="address" rows="3" required><?=e($_POST['address']??'')?></textarea><label class="form-label">Nomor telepon</label><input class="form-control mb-3" name="phone" required value="<?=e($_POST['phone']??'')?>"><label class="form-label">Catatan (opsional)</label><textarea class="form-control mb-3" name="notes" rows="2"><?=e($_POST['notes']??'')?></textarea><button class="btn btn-success">Buat Pesanan</button></form></div></div><div class="col-lg-5"><div class="card border-0 shadow-sm p-4"><h5>Ringkasan pesanan</h5><?php foreach($items as $it):?><div class="d-flex justify-content-between py-2 border-bottom"><span><?=e($it['p']['name'])?> × <?=$it['qty']?></span><strong><?=rupiah($it['p']['price']*$it['qty'])?></strong></div><?php endforeach;?><div class="d-flex justify-content-between mt-3"><strong>Total</strong><strong class="text-success h5"><?=rupiah($total)?></strong></div><small class="text-secondary">Pembayaran dilakukan sesuai kesepakatan dengan penjual/pengelola.</small></div></div></div>
<?php include 'partials/footer.php';?>