<?php
//ini_set('display_errors', '1');

require '../vendor/autoload.php';
include_once("class/jwt_tools.php");

$jwt = new jwt_tools();

// $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC93d3cuaGVsbG93ZWJhLm5ldCIsImF1ZCI6Imh0dHA6XC9cL3d3dy5oZWxsb3dlYmEubmV0IiwiaWF0IjoxNTcyMzIwOTk1LCJuYmYiOjE1NzIzMjA5OTUsImV4cCI6MTU3MjMyODE5NSwiZGF0YSI6eyJ1c2VyaWQiOjEsInVzZXJuYW1lIjoiXHU2NzRlXHU1YzBmXHU5ZjhkIn19.zq-0vC_3plxYRZZQ_HHaYG0EeFPuE7xIu2hZy4FouqI";

//$token = $_POST['token'];

$token = null;

// if ( !isset($headers['Authorization']) ){
// 	$array['status'] = "error";
// 	$array['msg'] = "不合法的連線";
// 	echo json_encode($array);
// 	exit;
// }

$token = $jwt->getBearerToken();

//驗證Client端傳過來的JWT token
$return = $jwt->verification($token);

if ($return == "success") {
	$array['status'] = "success";
	$array['msg'] = "JWT通過驗證，傳回取得正式資料的JSON";
	$array['name'] = "Ryan";
	$array['tel'] = "09999999999";
	echo json_encode($array);
}
else {
	$return['status'] = "error";
	$array['msg'] = "驗證失敗";
	echo json_encode($return);
}



?>