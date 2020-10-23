<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';

session_start();

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();
$user = get_login_user($db);

$cart_id = get_post('cart_id');
$amount = get_post('amount');

$token = get_post('token');

// トークンの照合
if(is_valid_csrf_token($token) === true) {
  unset($_SESSION['csrf_token']);
  // カートの数量を変更
  if(update_cart_amount($db, $cart_id, $amount)){
    set_message('購入数を更新しました。' );
  } else {
    set_error('購入数の更新に失敗しました。');
  }
} else {
  set_error('トークンの照合に失敗しました。');
}

redirect_to(CART_URL);