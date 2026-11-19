<?php
require_once 'config.php';
if(is_logged_in())redirect('index.php');
if($_SERVER['REQUEST_METHOD']==='POST'){
 $name=trim($_POST['name']??'');$email=trim($_POST['email']??'');$password=$_POST['password']??'';
 if($name===''||!filter_var($email,FILTER_VALIDATE_EMAIL)||strlen($password)<6){$error="Lengkapi data dengan benar. Password minimal 6 karakter.";}
 else{$hash=password_hash($password,PASSWORD_DEFAULT);$st=$conn->prepare("INSERT INTO users(name,email,password,role) VALUES(?,?,?,'customer')");$st->bind_param("sss",$name,$email,$hash);
  if($st->execute()){$_SESSION['flash']=['type'=>'success','message'=>'Pendaftaran berhasil. Silakan login.'];redirect('login.php');}else{$error="Email sudah digunakan atau pendaftaran gagal.";}}
}
$page_title="Daftar";include 'partials/header.php';
?>
<div class="auth-wrap card border-0 shadow-sm p-4"><h1 class="h3 fw-bold">Buat akun</h1><p class="text-secondary">Daftar untuk mulai berbelanja.</p><?php if(!empty($error)):?><div class="alert alert-danger"><?=e($error)?></div><?php endif;?>
<form method="post"><label class="form-label">Nama lengkap</label><input class="form-control mb-3" name="name" required value="<?=e($_POST['name']??'')?>"><label class="form-label">Email</label><input class="form-control mb-3" type="email" name="email" required><label class="form-label">Password</label><input class="form-control mb-3" type="password" name="password" minlength="6" required><button class="btn btn-success w-100">Daftar</button></form><p class="mt-3 mb-0 text-center">Sudah punya akun? <a href="login.php">Login</a></p></div>
<?php include 'partials/footer.php';?>