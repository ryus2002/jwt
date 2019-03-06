<?php
ini_set('display_errors', '1');

require 'vendor/autoload.php';
use \Firebase\JWT\JWT;

function verification($jwt)
{
	$key = '344'; //key要和签发的时候一样

	//$jwt = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC93d3cuaGVsbG93ZWJhLm5ldCIsImF1ZCI6Imh0dHA6XC9cL3d3dy5oZWxsb3dlYmEubmV0IiwiaWF0IjoxNTUxODM5NjMxLCJuYmYiOjE1NTE4Mzk2MzEsImV4cCI6MTU1MTg0NjgzMSwiZGF0YSI6eyJ1c2VyaWQiOjEsInVzZXJuYW1lIjoiXHU2NzRlXHU1YzBmXHU5ZjhkIn19.UNdnhotbPn0TZH0nlsQrbQk0-i46GQYMWdBsv-VNe8o"; //签发的Token
	try {
       		JWT::$leeway = 1;//当前时间减去60，把时间留点余地
       		$decoded = JWT::decode($jwt, $key, array('HS256')) ; //HS256方式，这里要和签发的时候对应
       		$arr = (array)$decoded;
       		print_r($arr);
    	} catch(\Firebase\JWT\SignatureInvalidException $e) {  //签名不正确
    		echo $e->getMessage();
    	}catch(\Firebase\JWT\BeforeValidException $e) {  // 签名在某个时间点之后才能用
    		echo $e->getMessage();
    	}catch(\Firebase\JWT\ExpiredException $e) {  // token过期
    		echo $e->getMessage();
   	}catch(Exception $e) {  //其他错误
    		echo $e->getMessage();
    }
    //Firebase定义了多个 throw new，我们可以捕获多个catch来定义问题，catch加入自己的业务，比如token过期可以用当前Token刷新一个新Token
}

$getToken = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC93d3cuaGVsbG93ZWJhLm5ldCIsImF1ZCI6Imh0dHA6XC9cL3d3dy5oZWxsb3dlYmEubmV0IiwiaWF0IjoxNTUxODM5OTgyLCJuYmYiOjE1NTE4Mzk5ODIsImV4cCI6MTU1MTgzOTk4MywiZGF0YSI6eyJ1c2VyaWQiOjEsInVzZXJuYW1lIjoiXHU2NzRlXHU1YzBmXHU5ZjhkIn19.cAuoyCpjBhdKwZoFLyPZKfmkFgwB88yvLHA4eAaQnXo';
verification($getToken);

?>
