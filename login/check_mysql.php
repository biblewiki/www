<?php

require_once('/home/mwepf1gm/www/biblewiki.one/config/biblewiki_userdb_connect.php');

$con = sql_connect();

if (isset($_COOKIE['tg_user'])) {
    $auth_data_json = urldecode($_COOKIE['tg_user']);
    $auth_data = json_decode($auth_data_json, true);
    
    $id_telegram = $auth_data['id'];
    
    $query = "SELECT * FROM users WHERE id_telegram='$id_telegram' LIMIT 1";
    $result = mysqli_query($con, $query)  or die("Could not connect database " .mysqli_error($con));
    $user_data = mysqli_fetch_array($result);
  
    $user_data_json = json_encode($user_data);
    setcookie('user', $user_data_json);
    
    header('Location: /login/');

}

?>