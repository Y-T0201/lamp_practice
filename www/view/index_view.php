<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>

  <title>商品一覧</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'index.css'); ?>">
  <script type="text/javascript" src="<?php print(JAVASCRIPT_PATH . 'javascript.js'); ?>"></script>
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>

  <div class="container">
    <h1>商品一覧</h1>
    <?php include VIEW_PATH . 'templates/messages.php'; ?>
    <div class="right">
      <form id='submit_form' method='get' action='index.php'>
        <select id='submit_select' name='sort'>
          <option value='new'>新着順</option>
          <option value='price_low'>価格の安い順</option>
          <option value='price_high'>価格の高い順</option>
        </select>
        <input class="btn btn-secondary" type='submit' value='並び替え'> 
      </form>
    </div>
    <nav>
      <ul class="pagination">
        <li class="page-item">
          <?php if($page > 1) { ?>
            <a class="page-link" href="?page=<?php print($page - 1) ?>&sort=<?php print($sort) ?>">
              Previous
            </a>
          <?php } ?> 
        </li>
        <?php for ($x=1; $x<=$pagination; $x++) { ?>
          <?php if($x === $page) { ?>
            <li class="page-item active">
          <?php } ?>
            <a class="page-link" href="?page=<?php print($x) ?>&sort=<?php print($sort) ?>" >
              <?php print($x); ?>
            </a>
          </li>
        <?php } ?> 
        <li class="page-item">
          <?php if($page < $pagination) { ?>
            <a class="page-link" href="?page=<?php print($page + 1) ?>&sort=<?php print($sort) ?>"> 
              Next
            </a>
          <?php } ?> 
        </li>
      </ul>  
    </nav>
    <div class="card-deck">
      <div class="row">
      <?php foreach($items as $item){ ?>
        <div class="col-6 item">
          <div class="card h-100 text-center">
            <div class="card-header">
              <?php print(h($item['name'])); ?>
            </div>
            <figure class="card-body">
              <img class="card-img" src="<?php print(IMAGE_PATH . $item['image']); ?>">
              <figcaption>
                <?php print(h(number_format($item['price']))); ?>円
                <?php if($item['stock'] > 0){ ?>
                  <form action="index_add_cart.php" method="post">
                    <input type="hidden" name="token" value="<?php print($token); ?>">
                    <input type="submit" value="カートに追加" class="btn btn-primary btn-block">
                    <input type="hidden" name="item_id" value="<?php print($item['item_id']); ?>">
                  </form>
                <?php } else { ?>
                  <p class="text-danger">現在売り切れです。</p>
                <?php } ?>
              </figcaption>
            </figure>
          </div>
        </div>
      <?php } ?>
      </div>
    </div>
  </div>
</body>
</html>