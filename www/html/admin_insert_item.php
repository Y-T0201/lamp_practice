<?php
// 定数ファイルを読み込み
require_once '../conf/const.php';
// 関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';
// userデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'user.php';
// itemデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'item.php';

// ログインチェックを行うため、セッションを開始
session_start();
// ログインチェック用の関数を使用
if(is_logined() === false){
  // ログインしていない場合はログインページにリダイレクト
  redirect_to(LOGIN_URL);
}
// PDOを取得
$db = get_db_connect();
// PDOを利用してログインユーザーのデータを取得
$user = get_login_user($db);
// adminユーザーでない場合はログインページにリダイレクト
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}
// 商品追加された、商品名、価格、ステータス、在庫、画像データを取得
$name = get_post('name');
$price = get_post('price');
$status = get_post('status');
$stock = get_post('stock');

$image = get_file('image');

$token = get_post('token');
// トークンの照合
if(is_valid_csrf_token($token) === true) {
  unset($_SESSION['csrf_token']);
  // 商品の登録
  if(regist_item($db, $name, $price, $stock, $status, $image)){
    set_message('商品を登録しました。');
  }else {
    set_error('商品の登録に失敗しました。');
  }
} else {
  set_error('トークンの照合に失敗しました。');
}

redirect_to(ADMIN_URL);