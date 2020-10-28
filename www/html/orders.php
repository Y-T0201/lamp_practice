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

// 全ての購入履歴データを取得
if(is_admin($user)){
  $orders = get_all_order($db);
// 指定されたユーザーの購入履歴データを取得
} else {
  $orders = get_order($db, $user_id);
}

// トークンの生成
$token = get_csrf_token();

// ビューの読み込み
include_once VIEW_PATH . 'orders_view.php';