<?php
//ini_set('display_errors', '1');

require '../vendor/autoload.php';
include_once("class/jwt_tools.php");

$jwt = new jwt_tools();

//帳號密碼驗證成功，返回JWT token，Client取得token可存放於cookie或session中
if ($id == "abc" and $pw == "abc") {
	$token = $jwt->authorizations();
	echo $token;
}

$headers = $jwt->getAuthorizationHeader();

if ( isset($headers) ) {

	$list = $jwt->getBearerToken();

	//取得多個token
	$token = json_decode($list,true);


print_r($token);exit;

	$access = $jwt->verification($token['access_token']);
	$refresh = $jwt->verification($token['refresh_token']);

	// echo $access;
	// echo $refresh;

	$refresh = "again";

	//需重新登入
	if ($access <> "success") {
		$return['status'] = "error";
		$array['msg'] = "驗證失敗";
		echo json_encode($return);
		exit();
	}

	//重新派發token
	if ($access == "success" and $refresh <> "success") { 

		$get_newrefresh = $jwt->refresh($refresh_token);
		$token["refresh_token"] = $get_newrefresh;

	    $jsonList = array(
	        'access_token'=>$token["access_token"],
	        'refresh_token'=>$token["refresh_token"],
	        'token_type'=>$token["token_type"] //token_type：表示令牌类型，该值大小写不敏感，这里用bearer
	    );
	    //Header("HTTP/1.1 201 Created");
		echo json_encode($jsonList);
		// $return = $jwt->authorizations();
		// echo json_encode($return); 
	}	

	if ($access == "success" and $refresh == "success") { 
		$return['status'] = "success";
		$array['msg'] = "驗證成功";
		echo json_encode($return);
	}


}

?>