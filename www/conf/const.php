<?php

// ドキュメントルートは/var/www/htmlディレクトリ
// /var/www/model/ディレクトリを指定する
define('MODEL_PATH', $_SERVER['DOCUMENT_ROOT'] . '/../model/');
// /var/www/view/ディレクトリを指定する
define('VIEW_PATH', $_SERVER['DOCUMENT_ROOT'] . '/../view/');

// ディレクトリを指定する
define('IMAGE_PATH', '/assets/images/');
define('STYLESHEET_PATH', '/assets/css/');
// /var/www/assets/images/ディレクトリを指定する
define('IMAGE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/assets/images/' );

// データベースの接続情報
// MySQLのサーバー名
define('DB_HOST', 'mysql');
// MySQLのデータベース名
define('DB_NAME', 'sample');
// MySQLのユーザー名
define('DB_USER', 'testuser');
// MySQLのパスワード
define('DB_PASS', 'password');
// MySQLの文字コード
define('DB_CHARSET', 'utf8');

// ディレクトリを指定する
define('SIGNUP_URL', '/signup.php');
define('LOGIN_URL', '/login.php');
define('LOGOUT_URL', '/logout.php');
define('HOME_URL', '/index.php');
define('CART_URL', '/cart.php');
define('FINISH_URL', '/finish.php');
define('ADMIN_URL', '/admin.php');

define('REGEXP_ALPHANUMERIC', '/\A[0-9a-zA-Z]+\z/');
define('REGEXP_POSITIVE_INTEGER', '/\A([1-9][0-9]*|0)\z/');


define('USER_NAME_LENGTH_MIN', 6);
define('USER_NAME_LENGTH_MAX', 100);
define('USER_PASSWORD_LENGTH_MIN', 6);
define('USER_PASSWORD_LENGTH_MAX', 100);

define('USER_TYPE_ADMIN', 1);
define('USER_TYPE_NORMAL', 2);

define('ITEM_NAME_LENGTH_MIN', 1);
define('ITEM_NAME_LENGTH_MAX', 100);

define('ITEM_STATUS_OPEN', 1);
define('ITEM_STATUS_CLOSE', 0);

define('PERMITTED_ITEM_STATUSES', array(
  'open' => 1,
  'close' => 0,
));

define('PERMITTED_IMAGE_TYPES', array(
  IMAGETYPE_JPEG => 'jpg',
  IMAGETYPE_PNG => 'png',
));