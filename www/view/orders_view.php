<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  
  <title>購入履歴</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'orders.css'); ?>">
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  
  <div class="container">
    <h1>購入履歴</h1>
    <?php include VIEW_PATH . 'templates/messages.php'; ?>


    <table class="table table-hover">
        <thead>
            <tr>
            <th scope="col">注文番号</th>
            <th scope="col">購入日時</th>
            <th scope="col">合計金額</th>
            <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($orders as $order){ ?>
                <tr>
                    <th scope="row">
                        <?php print(h(number_format($order['order_id']))); ?>
                    </th>
                    <td>
                        <?php print(h($order['created'])); ?>
                    </td>
                    <td>
                        <?php print (h(number_format($order['total_price']))); ?>円
                    </td>
                    <td>
                        <form action="orders_details.php" method="post">
                            <input type="hidden" name="token" value="<?php print($token); ?>">
                            <input type="submit" value="購入明細へ" class="btn btn-primary btn-block">
                            <input type="hidden" name="order_id" value="<?php print($order['order_id']); ?>">
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>  
</body>
</html>