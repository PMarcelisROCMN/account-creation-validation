<?php
require_once('../config.php');

// try to connect to the database
// try {
//     // create a new PDO object
//     $db = new PDO('mysql:host=localhost;dbname=taskdb;charset=utf8', 'root', '');
//     // set the PDO error mode to exception
//     $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// } catch (PDOException $ex) {
//     // if the connection fails, send a response with a 500 status code
//     $response = new Response();
//     $response->setSuccess(false);
//     $response->setHttpStatusCode(500);
//     $response->addMessage("Database connection error");
//     $response->send();
//     exit;
// }

// if the request method is not POST, send a response with a 405 status code
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response = new Response();
    $response->setSuccess(false);
    $response->setHttpStatusCode(405);
    $response->addMessage("Request method not allowed");
    $response->send();
    exit;
}

// if the content type is not JSON, send a response with a 400 status code
if ($_SERVER['CONTENT_TYPE'] !== 'application/json') {
    $response = new Response();
    $response->setSuccess(false);
    $response->setHttpStatusCode(400);
    $response->addMessage("Content type header not set to JSON");
    $response->send();
    exit;
}

// get the raw post data
$rawPostData = file_get_contents('php://input');

// if the raw post data is empty, send a response with a 400 status code
if (!$jsonData = json_decode($rawPostData)) {
    $response = new Response();
    $response->setSuccess(false);
    $response->setHttpStatusCode(400);
    $response->addMessage("Request body is not valid JSON");
    $response->send();
    exit;
}

// if the username or password is empty, send a response with a 400 status code
if (!isset($jsonData->username) || !isset($jsonData->password)) {
    $response = new Response();
    $response->setSuccess(false);
    $response->setHttpStatusCode(400);
    !isset($jsonData->username) ? $response->addMessage("Username not supplied") : false;
    !isset($jsonData->password) ? $response->addMessage("Password not supplied") : false;
    $response->send();
    exit;
}

// try to create a new account with this information
try {
    $account = new Account(null, $jsonData->username, $jsonData->password, $jsonData->age);

    $response = new Response();
    $response->setSuccess(true);
    $response->setHttpStatusCode(201);
    $response->addMessage("Account created");
    $response->send();
    exit;

}catch (AccountException $ex) {
    // if an error occurs, send a response with a 400 status code - which is invalid syntax
    $response = new Response();
    $response->setSuccess(false);
    $response->setHttpStatusCode(400);
    // this get message method is from the AccountException class
    // if you take a look at the Account.php file, you will see that an AccountException if some data is not valid
    $response->addMessage($ex->getMessage());
    $response->send();
    exit;
}
catch(PDOException $ex){
    // we can catch issues with adding a new account to the database
    if ($ex->getCode() == 23000) { // Duplicate entry error code - you can look these up online
        $response = new Response();
        $response->setSuccess(false);
        $response->setHttpStatusCode(409);
        $response->addMessage("Username already exists");
        $response->send();
        exit;
    } else {
        $response = new Response();
        $response->setSuccess(false);
        $response->setHttpStatusCode(500);
        $response->addMessage("There was an issue creating a user account - please try again");
        $response->send();
        exit;
    }
}