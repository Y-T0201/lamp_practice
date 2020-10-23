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

// ユーザーのカートテーブルを出力
$carts = get_user_carts($db, $user['user_id']);

$user_id = $user['user_id'];

$token = get_post('token');
// トークンの照合
if(is_valid_csrf_token($token) === true) {
  unset($_SESSION['csrf_token']);
  // カート内に商品があるかの確認
  if(purchase_carts($db, $carts) === false){
    set_error('商品が購入できませんでした。');
    redirect_to(CART_URL);
  }
} else {
  set_error('トークンの照合に失敗しました。');
  redirect_to(CART_URL);
}

//トランザクション開始
$db->beginTransaction();

// カートの商品が購入できなければ、カート画面へ戻す
if(purchase_carts($db, $carts) === false){
  set_error('商品が購入できませんでした。');
}

//購入履歴にユーザーIDを登録 
if(insert_orders($db, $user_id) === false) {
  set_error('購入履歴にユーザーIDが登録できませんでした。');
} 
  
// order_idの取得
$order_id = get_order_id($db);
// var_dump($order_id);

foreach($carts as $cart){
  $item_id =  $cart['item_id']; 
  $price = $cart['price'];
  $amount = $cart['amount'];

  if(insert_order_products($db, $order_id, $item_id, $price) === false) {
    set_error('購入履歴に商品価格が登録できませんでした。');
  }
  if(insert_order_details($db, $order_id, $item_id, $amount) === false) {
    set_error('購入履歴に購入数量が登録できませんでした。');
  }
}

// エラーが無ければ、
if(has_error() === false){
  // コミット処理
  $db->commit();

// エラーがあったら、
} else {
  // ロールバック処理
  $db->rollback();
  redirect_to(CART_URL);
// トランザクション終了
}

$total_price = sum_carts($carts);

include_once '../view/finish_view.php';