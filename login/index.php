<?php
define('BOT_USERNAME', 'BibleWiki_bot'); // place username of your bot here
function getTelegramUserData() {
  if (isset($_COOKIE['tg_user'])) {
    $auth_data_json = urldecode($_COOKIE['tg_user']);
    $auth_data = json_decode($auth_data_json, true);
    return $auth_data;
  }
  return false;
}
function getUserData() {
  if (isset($_COOKIE['user'])) {
    $user_data_json = urldecode($_COOKIE['user']);
    $user_data = json_decode($user_data_json, true);
    return $user_data;
  }
  return false;
}
if ($_GET['logout']) {
  setcookie('tg_user', '');
  header('Location: /login/');
}
$tg_user = getTelegramUserData();
$user = getUserData();

if ($tg_user !== false) {
  $username = htmlspecialchars($user['user_username']);
  $first_name = htmlspecialchars($tg_user['first_name']);
  $last_name = htmlspecialchars($tg_user['last_name']);

  if (isset($tg_user['username'])) {
    $tg_username = htmlspecialchars($tg_user['username']);
    $html = "<h1>Hello, <a href=\"https://t.me/{$tg_username}\">{$first_name} {$last_name} {$username}</a>!</h1>";
  } else {
    $html = "<h1>Hello, {$first_name} {$last_name} {$username}!</h1>";
  }
  if (isset($tg_user['photo_url'])) {
    $photo_url = htmlspecialchars($tg_user['photo_url']);
    $html .= "<img src=\"{$photo_url}\">";
  }
  $html .= "<p><a href=\"?logout=1\">Log out</a></p>";
} else {
  $bot_username = BOT_USERNAME;
  $html = <<<HTML
<h1>Hello, please LOGIN!</h1>
<script async src="https://telegram.org/js/telegram-widget.js?2" data-telegram-login="{$bot_username}" data-size="large" data-auth-url="check_authorization.php"></script>
HTML;
}
echo <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Login - BibleWiki</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap start -->
      <title>Login Biblewiki</title>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <!-- Bootstrap stop -->
    
    <!--link rel="stylesheet" type="text/css" media="screen" href="main.css">
    <script src="main.js"></script-->
</head>
<body>
  <div class="container">
    <h1>Login</h1>
    <p>Is being implemented</p>
    <center>{$html}</center>
    <center>
      <form action="check_mysql.php?web=1" method="post">
        <input type="text" name="username" placeholder="Username"><br><br>
        <input type="password" name="password" placeholder="Password"><br><br>
        <input type="submit" value="Login"><br>

      </form>   
    </center>
  </div>
</body>
</html>
HTML;
?>