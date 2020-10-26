<?php 
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

// 購入時にユーザーIDを登録する
function insert_orders($db, $user_id){
  $sql = "
    INSERT INTO
      orders(
        user_id
      )
    VALUES(?)
  ";

  return execute_query($db, $sql, array($user_id));
}

// 購入時に商品の金額を登録する
function insert_order_products($db, $order_id, $item_id, $price){
  $sql = "
    INSERT INTO
      order_products(
        order_id,
        item_id,
        price
      )
    VALUES(?, ?, ?)
  ";

  return execute_query($db, $sql, array($order_id, $item_id, $price));
}

// 購入時に商品の購入数量を登録する
function insert_order_details($db, $order_id, $item_id, $amount){
  $sql = "
    INSERT INTO
      order_details(
        order_id,
        item_id,
        amount
      )
    VALUES(?, ?, ?)
  ";

  return execute_query($db, $sql, array($order_id, $item_id, $amount));
}

// 指定されたorder_idとuser_idの購入履歴テーブルのデータを表示
function get_order_detail($db, $order_id, $user_id){
    $sql = "
      SELECT
        orders.order_id,
        orders.user_id,
        orders.created,
        order_products.item_id,
        order_products.price,
        order_details.amount,
        items.name
      FROM
        orders
      JOIN
        order_products
      ON
        orders.order_id = order_products.order_id
      JOIN
        order_details
      ON
        order_products.order_id = order_details.order_id
      AND
        order_products.item_id = order_details.item_id  
      JOIN
        items
      ON
        order_products.item_id = items.item_id
      WHERE
        orders.order_id = ?
      AND
        orders.user_id = ?
    ";
    return fetch_all_query($db, $sql, array($order_id, $user_id));
}

// 指定されたorder_idのすべての購入履歴テーブルのデータを表示
function get_all_order_detail($db, $order_id){
  $sql = "
    SELECT
      orders.order_id,
      orders.user_id,
      orders.created,
      order_products.item_id,
      order_products.price,
      order_details.amount,
      items.name
    FROM
      orders
    JOIN
      order_products
    ON
      orders.order_id = order_products.order_id
    JOIN
      order_details
    ON
      order_products.order_id = order_details.order_id
    AND
      order_products.item_id = order_details.item_id  
    JOIN
      items
    ON
      order_products.item_id = items.item_id
    WHERE
      orders.order_id = ?
  ";
  return fetch_all_query($db, $sql, array($order_id));
}

// 指定されたユーザーの購入履歴を表示
function get_order($db, $user_id){
    $sql = "
      SELECT
        orders.order_id,
        orders.user_id,
        orders.created,
        SUM(order_products.price * order_details.amount) AS total_price
      FROM
        orders
      JOIN
        order_products
      ON
        orders.order_id = order_products.order_id
      JOIN
        order_details
      ON
        order_products.order_id = order_details.order_id
      AND
        order_products.item_id = order_details.item_id
      WHERE
        user_id = ?
      GROUP BY
        orders.order_id
      ORDER BY
        orders.order_id DESC;
    ";
    return fetch_all_query($db, $sql, array($user_id));
  }

// すべての購入履歴を表示
function get_all_order($db){
    $sql = "
      SELECT
        orders.order_id,
        orders.user_id,
        orders.created,
        SUM(order_products.price * order_details.amount) AS total_price
      FROM
        orders
      JOIN
        order_products
      ON
        orders.order_id = order_products.order_id
      JOIN
        order_details
      ON
        order_products.order_id = order_details.order_id
      AND
        order_products.item_id = order_details.item_id
      GROUP BY
        orders.order_id
      ORDER BY
        orders.order_id DESC;
    ";
    return fetch_all_query($db, $sql);
}

// order_idの取得
function get_order_id($db){
  $order_id=$db->lastInsertId();

  return $order_id;
}

// 合計金額
function sum_order($orders){
    $total_order = 0;
    foreach($orders as $order){
      $total_order += $order['price'] * $order['amount'];
    }
    return $total_order;
}
