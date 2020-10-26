<?php
// 定数ファイルを読み込み
require_once '../conf/const.php';
// 汎用関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';
// userデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'user.php';
// itemデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'item.php';
// ordersデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'orders.php';
// ログインチェックを行うため、セッションを開始する
session_start();

// ログインチェック用関数を利用
if(is_logined() === false){
  // ログインしていない場合はログインページにリダイレクト
  redirect_to(LOGIN_URL);
}

// PDOを取得
$db = get_db_connect();
// PDOを利用してログインユーザーのデータを取得
$user = get_login_user($db);
// 商品一覧用の商品データを取得
$items = get_open_items($db);

// ユーザーIDの取得
$user_id = $user['user_id'];
// 指定されたorder_idを取得
$order_id = get_post('order_id');

// 指定されたユーザーの購入履歴データを取得
if(is_admin($user)){
    $order_details = get_all_order_detail($db, $order_id);
// 全ての購入履歴データを取得
} else {
    $order_details = get_order_detail($db, $order_id, $user_id);
}

foreach($order_details as $order_detail){
  // 購入日
  $created = $order_detail['created'];
}

// 各購入履歴の合計金額を取得
$total_order = sum_order($order_details);

$token = get_post('token');
// トークンの照合
if(is_valid_csrf_token($token) === true) {
  unset($_SESSION['csrf_token']);

} else {
  set_error('トークンの照合に失敗しました。');
  redirect_to(ORDERS_URL);
}

// ビューの読み込み
include_once VIEW_PATH . 'orders_details_view.php';