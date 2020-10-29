<?php
// 定数ファイルを読み込み
require_once '../conf/const.php';
// 汎用関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';
// userデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'user.php';
// itemデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'item.php';
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

// トークンの生成
$token = get_csrf_token();

// GETで現在のページ数を取得する
if (isset($_GET['page'])) {
  $page = (int)get_get('page');
} else {
  $page = 1;
}

// スタートのポジションを計算する
if ($page > 1) {
  $start = ($page * 8) -8;
} else {
  $start = 0;
}

// 商品件数を取得する
$page_num = get_open_pages_items($db, $start);

// ページネーションの数を取得する
$pagination = ceil($page_num['item_count'] / 8);


// 商品一覧用の商品データを取得
$items = get_open_new_items($db, $start);
// var_dump($items);
if(isset($_GET['sort'])) {
  $sort = get_get('sort');

  // 新着順
  if($sort === "new"){
    $items = get_open_new_items($db, $start);
  // 価格が安い順
  } else if($sort === "price_low"){
    $items = get_open_price_low_items($db, $start);
  // 価格が高い順
  } else if($sort === "price_high"){
    $items = get_open_price_high_items($db, $start);
  // 指定していないとき
  } else {
    $items = get_open_new_items($db, $start);
  }  
}
$ranking = 0;
$ranking_items = get_open_ranking_items($db);

// ビューの読み込み
include_once VIEW_PATH . 'index_view.php';