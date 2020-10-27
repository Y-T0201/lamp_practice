<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  
  <title>購入明細履歴</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'orders.css'); ?>">
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  
  <div class="container">
    <h1>購入明細履歴</h1>
    <?php include VIEW_PATH . 'templates/messages.php'; ?>
    <div class="shadow-none p-3 mb-5 bg-light rounded">
        <span class="margin50">注文番号：<?php print(h(number_format($order_id))); ?></span>
        <span class="margin50">購入日時：<?php print(h($created)); ?></span>
        <span class="margin50">合計金額：<?php print(h(number_format($total_order))); ?>円</span>
    </div>
    <table class="table table-hover">
        <thead>
            <tr>
            <th scope="col">商品名</th>
            <th scope="col">商品価格</th>
            <th scope="col">購入数</th>
            <th scope="col">小計</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($order_details as $order_detail){ ?>
                <tr>
                    <th scope="row">
                        <?php print(h($order_detail['name'])); ?>
                    </th>
                    <td>
                        <?php print(h(number_format($order_detail['price']))); ?>円
                    </td>
                    <td>
                        <?php print(h(number_format($order_detail['amount']))); ?>
                    </td>
                    <td>
                        <?php print(h(number_format($order_detail['price'] * $order_detail['amount']))); ?>円
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>  
</body>
</html>