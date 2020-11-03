<?php
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

// DB利用

function get_item($db, $item_id){
  $sql = "
    SELECT
      item_id, 
      name,
      stock,
      price,
      image,
      status
    FROM
      items
    WHERE
      item_id = ?
  ";

  return fetch_query($db, $sql, array($item_id));
}

function get_items($db, $is_open = false){
  $sql = '
    SELECT
      item_id, 
      name,
      stock,
      price,
      image,
      status
    FROM
      items
  ';
  if($is_open === true){
    $sql .= '
      WHERE status = 1
    ';
  }

  return fetch_all_query($db, $sql);
}  

// 8件ずつ商品を表示する
function get_8_items($db, $is_open = false, $start=0, $one_page_items=ONE_PAGE_ITEMS){
  $sql = '
    SELECT
      item_id, 
      name,
      stock,
      price,
      image,
      status
    FROM
      items
  ';
  if($is_open === true){
    $sql .= '
      WHERE status = 1
    ';
  $sql .= '
    LIMIT
      ?, ?;
  ';
  }
  return fetch_all_query($db, $sql, array($start, $one_page_items));
}  

// 登録が新しい順
function get_new_items($db, $is_open = false, $start=0, $one_page_items=ONE_PAGE_ITEMS){
  $sql = '
    SELECT
      item_id, 
      name,
      stock,
      price,
      image,
      status
    FROM
      items
  ';
  if($is_open === true){
    $sql .= '
      WHERE status = 1
    ';
  }
  $sql .= '
    ORDER BY
      created DESC
    LIMIT
      ?, ?;
  ';
  return fetch_all_query($db, $sql, array($start, $one_page_items));
}

// 価格の安い順
function get_price_low_items($db, $is_open = false, $start=0, $one_page_items=ONE_PAGE_ITEMS){
  $sql = '
    SELECT
      item_id, 
      name,
      stock,
      price,
      image,
      status
    FROM
      items
  ';
  if($is_open === true){
    $sql .= '
      WHERE status = 1
    ';
  }
  $sql .= '
    ORDER BY
      price ASC
    LIMIT
      ?, ?;
  ';
  return fetch_all_query($db, $sql, array($start, $one_page_items));
}

// 価格の高い順
function get_price_high_items($db, $is_open = false, $start=0, $one_page_items=ONE_PAGE_ITEMS){
  $sql = '
    SELECT
      item_id, 
      name,
      stock,
      price,
      image,
      status
    FROM
      items
  ';
  if($is_open === true){
    $sql .= '
      WHERE status = 1
    ';
  }
  $sql .= '
    ORDER BY
      price DESC
    LIMIT
      ?, ?;
  ';
  return fetch_all_query($db, $sql, array($start, $one_page_items));
}

// itemsテーブルに入っているデータ件数を取得する
function get_pages_items($db, $is_open = false){
  $sql = '
    SELECT
      COUNT(*) AS item_count
    FROM
      items
  ';
  if($is_open === true){
    $sql .= '
      WHERE status = 1
    ';
  }
  return fetch_query($db, $sql);
}

// 購入数が多い順
function get_ranking_items($db, $is_open = false){
  $sql = '
    SELECT
      items.item_id, 
      items.name,
      items.stock,
      items.price,
      items.image,
      items.status,
      SUM(amount)
    FROM
      items
    JOIN
      order_details
    ON
      items.item_id = order_details.item_id
  ';
  if($is_open === true){
    $sql .= '
      WHERE status = 1
    ';
  }
  $sql .= '
    GROUP BY
      item_id
    ORDER BY
      SUM(amount) DESC
    LIMIT
      3;
  ';
  return fetch_all_query($db, $sql);
}

function get_all_items($db){
  return get_items($db);
}

function get_open_items($db){
  return get_items($db, true);
}

// 公開可の商品件数を取得する
function get_open_pages_items($db){
  return get_pages_items($db, true);
}

// 8件ずつ公開可の商品を表示する
function get_open_8_items($db, $start, $one_page_items){
  return get_8_items($db, true, $start, $one_page_items);
}

function get_open_new_items($db, $start, $one_page_items){
  return get_new_items($db, true, $start, $one_page_items);
}

function get_open_price_low_items($db, $start, $one_page_items){
  return get_price_low_items($db, true, $start, $one_page_items);
}

function get_open_price_high_items($db, $start, $one_page_items){
  return get_price_high_items($db, true, $start, $one_page_items);
}

function get_open_ranking_items($db){
  return get_ranking_items($db, true);
}
function regist_item($db, $name, $price, $stock, $status, $image){
  $filename = get_upload_filename($image);
  if(validate_item($name, $price, $stock, $filename, $status) === false){
    return false;
  }
  return regist_item_transaction($db, $name, $price, $stock, $status, $image, $filename);
}

function regist_item_transaction($db, $name, $price, $stock, $status, $image, $filename){
  $db->beginTransaction();
  if(insert_item($db, $name, $price, $stock, $filename, $status) 
    && save_image($image, $filename)){
    $db->commit();
    return true;
  }
  $db->rollback();
  return false;
  
}

function insert_item($db, $name, $price, $stock, $filename, $status){
  $status_value = PERMITTED_ITEM_STATUSES[$status];
  $sql = "
    INSERT INTO
      items(
        name,
        price,
        stock,
        image,
        status
      )
    VALUES(?, ?, ?, ?, ?);
  ";

  return execute_query($db, $sql, array($name, $price, $stock, $filename, $status_value));
}

function update_item_status($db, $item_id, $status){
  $sql = "
    UPDATE
      items
    SET
      status = ?
    WHERE
      item_id = ?
    LIMIT 1
  ";
  
  return execute_query($db, $sql, array($status, $item_id));
}

function update_item_stock($db, $item_id, $stock){
  $sql = "
    UPDATE
      items
    SET
      stock = ?
    WHERE
      item_id = ?
    LIMIT 1
  ";
  
  return execute_query($db, $sql, array($stock, $item_id));
}

function destroy_item($db, $item_id){
  $item = get_item($db, $item_id);
  if($item === false){
    return false;
  }
  $db->beginTransaction();
  if(delete_item($db, $item['item_id'])
    && delete_image($item['image'])){
    $db->commit();
    return true;
  }
  $db->rollback();
  return false;
}

function delete_item($db, $item_id){
  $sql = "
    DELETE FROM
      items
    WHERE
      item_id = ?
    LIMIT 1
  ";
  
  return execute_query($db, $sql, array($item_id));
}


// 非DB
// ステータスが公開であるitemの値を返す
function is_open($item){
  return $item['status'] === 1;
}

function validate_item($name, $price, $stock, $filename, $status){
  $is_valid_item_name = is_valid_item_name($name);
  $is_valid_item_price = is_valid_item_price($price);
  $is_valid_item_stock = is_valid_item_stock($stock);
  $is_valid_item_filename = is_valid_item_filename($filename);
  $is_valid_item_status = is_valid_item_status($status);

  return $is_valid_item_name
    && $is_valid_item_price
    && $is_valid_item_stock
    && $is_valid_item_filename
    && $is_valid_item_status;
}

function is_valid_item_name($name){
  $is_valid = true;
  if(is_valid_length($name, ITEM_NAME_LENGTH_MIN, ITEM_NAME_LENGTH_MAX) === false){
    set_error('商品名は'. ITEM_NAME_LENGTH_MIN . '文字以上、' . ITEM_NAME_LENGTH_MAX . '文字以内にしてください。');
    $is_valid = false;
  }
  return $is_valid;
}

function is_valid_item_price($price){
  $is_valid = true;
  if(is_positive_integer($price) === false){
    set_error('価格は0以上の整数で入力してください。');
    $is_valid = false;
  }
  return $is_valid;
}

function is_valid_item_stock($stock){
  $is_valid = true;
  if(is_positive_integer($stock) === false){
    set_error('在庫数は0以上の整数で入力してください。');
    $is_valid = false;
  }
  return $is_valid;
}

function is_valid_item_filename($filename){
  $is_valid = true;
  if($filename === ''){
    $is_valid = false;
  }
  return $is_valid;
}

function is_valid_item_status($status){
  $is_valid = true;
  if(isset(PERMITTED_ITEM_STATUSES[$status]) === false){
    $is_valid = false;
  }
  return $is_valid;
}