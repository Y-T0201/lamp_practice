<?php
// 設定ファイルを読み込み
require_once '../conf/const.php';
// 関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';
// userデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'user.php';
// itemデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'item.php';

// ログインチェックをするため、セッションを開始する
session_start();
// ログインしていない場合はログインページへリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

// PDOを取得
$db = get_db_connect();

// PDOを利用してログインユーザーのデータを取得
$user = get_login_user($db);
// adminユーザーでない場合はログインページへリダイレクト
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}
// 全ての商品データを取得
$items = get_all_items($db);

// トークンの生成
get_csrf_token();

// ビューの読み込み
include_once VIEW_PATH . '/admin_view.php';
