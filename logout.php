<?php
require_once 'config.php';
$_SESSION=[];session_destroy();session_start();$_SESSION['flash']=['type'=>'success','message'=>'Anda berhasil logout.'];redirect('index.php');
