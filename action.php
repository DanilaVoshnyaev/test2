<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
$response = [
    'status' => 'error',
    'msg' => 'Непредивденная ошибка',
    'discription' => '',
    'data' => [],
];

$data = file_get_contents("php://input");
$jsonData = json_decode($data, true);

if (empty($jsonData['action'])) {
    $response['msg'] = 'no action';
    pushJson();
}

try {
    $dbh = new PDO('mysql:host=localhost;dbname=test2', 'root', 'root');
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage();
    die();
}


$action = $jsonData['action'];

switch ($action) {
    case 'save_msg':
        //Insert
        if (empty($jsonData['login'])) {
            $response['msg'] = 'no login';
            pushJson();
        }
        if (empty($jsonData['message'])) {
            $response['emty message'];
            pushJson();
        }
        $login = htmlspecialchars($jsonData['login']);
        $message = htmlspecialchars($jsonData['message']);
        $query = "INSERT INTO `messages` (`login`, `message`) VALUES (:login, :message)";
        $params = [':login' => $login, ':message' => $message];
        $stmt = $dbh->prepare($query);
        $result = $stmt->execute($params);
        $newId = $dbh->lastInsertId();
        if (!$result) {
            $response['msg'] = 'insert error';
            pushJson();
        }
        $response['status'] = 'success';
        $response['data'] = ['id' => $newId];
        $response['msg'] = 'success insert';
        break;
    case 'delete_msg':
        if (empty($jsonData['id'])) {
            $response['emty id'];
            pushJson();
        }
        //Delete
        $query = "DELETE FROM `messages` WHERE `id`= :id";
        $params = ['id' => htmlspecialchars($jsonData['id'])];
        $stmt = $dbh->prepare($query);
        $result = $stmt->execute($params);
        if (!$result) {
            $response['msg'] = 'delete error';
            pushJson();
        }
        $response['status'] = 'success';
        $response['msg'] = 'success delete';
        //$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        break;
    case 'select_msg':
        $query = "SELECT * FROM messages WHERE 1";
        $stmt = $dbh->query($query);
        $result = $stmt->fetchAll();
        if (!$result) {
            $response['msg'] = 'select error';
            pushJson();
        }
        $response['status'] = 'success';
        $response['msg'] = 'success select';
        $response['data'] = json_encode($result);
        break;
    default:
        pushJson();
        $response['msg'] = 'no available action';
        break;
}




// $login = $jsonData['login'];
// $message = $jsonData['message'];
// //Insert

// $query = "INSERT INTO `messages` (`login`, `message`) VALUES (:login, :message)";
// $params = [':login' => $login, ':message' => $message];
// $stmt = $dbh->prepare($query);
// $result = $stmt->execute($params);
// //Select
// $selectQuery = "SELECT * FROM `messages`";
// $selectStmt = $dbh->prepare($selectQuery);
// $selectStmt->execute();
// $resultSet = $selectStmt->fetchAll(PDO::FETCH_ASSOC);
// //Delete

// $selectDel = "DELETE FROM `messages` WHERE id`= :id";
// $params = ['id'=>htmlspecialchars($jsonData['id'])];
// $selectStmt = $dbh->prepare($selectQuery);
// $selectStmt->execute();
// $resultDel = $selectStmt->fetchAll(PDO::FETCH_ASSOC);

if ($result) {
    $response['status'] = 'success';
} else {
    $response['msg'] = 'ERROR';
}

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

pushJson();




function pushJson()
{
    global $response;
    echo json_encode($response);
    exit();
}
