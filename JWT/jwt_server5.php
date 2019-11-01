<?php
//ini_set('display_errors', '1');

require '../vendor/autoload.php';
include_once("class/jwt_tools.php");

$jwt = new jwt_tools();

$list = $jwt->getBearerToken();

//取得多個token
$token = json_decode($list,true);

$access_token = $token['access_token'];
$refresh_token = $token['refresh_token'];


$access = $jwt->verification($access_token);
$refresh = $jwt->verification($refresh_token);

// echo $access;
// echo $refresh;

//需重新登入
if ($access <> "success") {
	$return['status'] = "error";
	$array['msg'] = "驗證失敗";
	echo json_encode($return);
	exit();
}

//重新派發token
if ($access == "success" and $refresh == "success") { 
	$array['status'] = "success";
	$array['msg'] = "JWT通過驗證，傳回取得正式資料的JSON";
	$array['name'] = "Ryan";
	$array['tel'] = "09999999999";
	echo json_encode($array);
}
?>