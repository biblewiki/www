<?php
// User Datenbank Logindaten einbinden
require_once HOME_DIR . '/config/biblewiki/db_biblewiki_users.php';

function UserLog($userID = 0, $method, $action = 'undefined', $error = '')
{

    $hostname = gethostname();

    $_db = new db(USER_DB_URL, USER_DB_USER, USER_DB_PW, USER_DB);
    $stmt = $_db->getDB()->stmt_init();

    $stmt = $_db->prepare("INSERT INTO " . USER_DB . ".user_log (id_user, ip, hostname, browser, method, action, error) VALUES (?,?,?,?,?,?,?);");

    $stmt->bind_param("issssss", $userID, $_SERVER['REMOTE_ADDR'], $hostname, $_SERVER['HTTP_USER_AGENT'], $method, $action, $error);

    $stmt->execute();
}

?>