<?php
require_once 'config.php';
if(is_logged_in())redirect('index.php');
if($_SERVER['REQUEST_METHOD']==='POST'){
 $email=trim($_POST['email']??'');$password=$_POST['password']??'';
 $st=$conn->prepare("SELECT id,name,email,password,role FROM users WHERE email=? AND status='active'");$st->bind_param("s",$email);$st->execute();$u=$st->get_result()->fetch_assoc();
 if($u && password_verify($password,$u['password'])){unset($u['password']);session_regenerate_id(true);$_SESSION['user']=$u;$_SESSION['flash']=['type'=>'success','message'=>'Login berhasil. Selamat datang!'];redirect('index.php');}
 $error="Email atau password salah.";
}
$page_title="Login";include 'partials/header.php';
?>
<div class="auth-wrap card border-0 shadow-sm p-4"><h1 class="h3 fw-bold">Selamat datang</h1><p class="text-secondary">Masuk ke akun PasarKita.</p><?php if(!empty($error)):?><div class="alert alert-danger"><?=e($error)?></div><?php endif;?>
<form method="post"><label class="form-label">Email</label><input class="form-control mb-3" type="email" name="email" required><label class="form-label">Password</label><input class="form-control mb-3" type="password" name="password" required><button class="btn btn-success w-100">Login</button></form><p class="mt-3 mb-0 text-center">Belum punya akun? <a href="register.php">Daftar</a></p></div>
<?php include 'partials/footer.php';?>