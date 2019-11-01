<?php
//ini_set('display_errors', '1');

require '../vendor/autoload.php';
include_once("class/jwt_tools.php");

$jwt = new jwt_tools();

$id = $_POST['id'];
$pw = $_POST['pw'];

//帳號密碼驗證成功，返回JWT token，Client取得token可存放於cookie或session中
if ($id == "abc" and $pw == "abc") {
    $token = $jwt->get_token();
    echo $token;
}
?>