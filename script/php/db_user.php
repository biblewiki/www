<?php
// User Datenbank Logindaten einbinden
require_once HOME_DIR . '/config/biblewiki/db_biblewiki_users.php';

// Datenbank Klasse einbinden
require_once SCRIPT_PATH . '/php/db.class.php';


// AJAX Input decodieren
$jsonTx = json_decode(file_get_contents("php://input"));

// Überprüfen ob eine Action gefordert wird
if ($jsonTx->action != "") {

    $function = $jsonTx->action;
    if (function_exists($function)) {
        echo $function($jsonTx->data); // Funktion ausführen
        exit;
    } else {
        $ret = array('error' => 'action_not_available');
        echo json_encode($ret);
        exit;
    }
    exit;
}


// Benutzerinfos abrufen
function GetData($userID, $db, $table, $columns = '', $join = '')
{
    try {
        // Datenbankverbindung herstellen
        $_db = new db(USER_DB_URL, USER_DB_USER, USER_DB_PW, USER_DB);
        $stmt = $_db->getDB()->stmt_init();


        if ($columns  === '') {
            $select = USER_DB . ".users.user_firstname,
            " . USER_DB . ".users.user_lastname,
            " . USER_DB . ".users.user_level";
        } else {
            $numCol = count($columns);
            $i = 0;

            foreach ($columns as $column) {
                $select .= $db . '.' . $table . '.' . $column;
                
                if (++$i < $numCol) {
                    $select .= ',';
                }
            }
        }
        
        // Select definieren
        $stmt = $_db->prepare(
            "SELECT
            " . $select . "
            FROM " . USER_DB . ".users
            " . $join . "
            WHERE " . USER_DB . ".users.user_ID = ?;"
        );


        $stmt->bind_param("i", $userID);

        $stmt->execute();

        $array = db::getTableAsArray($stmt);

        return $array[0];
    } catch (Exception $e) {
        return $e->getMessage(); // Fehler zurückgeben
    }
}
