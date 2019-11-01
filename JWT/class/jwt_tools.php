<?php
//ini_set('display_errors', '1');

require '../vendor/autoload.php';
use \Firebase\JWT\JWT;

class jwt_tools extends JWT
{
    public function __construct() {
        $this->key = '344';
        $this->iss = 'http://house-e.nhg.tw';
    }

    //簽發token
    public function get_token()
    {
        $key = $this->key; //key
        $time = time(); //当前时间
        $token = array(
            'iss' => $this->iss, //签发者 可选
            'aud' => 'http://house-e.nhg.tw', //接收该JWT的一方，可选
            'iat' => $time, //签发时间
            //'nbf' => $time , //(Not Before)：某个时间点后才能访问，比如设置time+30，表示当前时间30秒后才能使用
            'exp' => $time+60 //过期时间,这里设置2个小时
            // 'data' => array( //自定义信息，不要定义敏感信息
            //     'userid' => 1,
            //     'username' => '李小龍'
            // )
        );
        $return = JWT::encode($token, $key, 'HS256'); //输出Token
        return json_encode($return);
    }

    //驗證token
    public function verification($token)
    {
        $key = $this->key; //key要和签发的时候一样

        // $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC93d3cuaGVsbG93ZWJhLm5ldCIsImF1ZCI6Imh0dHA6XC9cL3d3dy5oZWxsb3dlYmEubmV0IiwiaWF0IjoxNTI1MzQwMzE3LCJuYmYiOjE1MjUzNDAzMTcsImV4cCI6MTUyNTM0NzUxNywiZGF0YSI6eyJ1c2VyaWQiOjEsInVzZXJuYW1lIjoiXHU2NzRlXHU1YzBmXHU5Zjk5In19.Ukd7trwYMoQmahOAtvNynSA511mseA2ihejoZs7dxt0"; //签发的Token
        try {
            JWT::$leeway = 10;//当前时间减去60，把时间留点余地
            $decoded = JWT::decode($token, $key, array('HS256')); //HS256方式，这里要和签发的时候对应
            $arr = (array)$decoded;
            //return var_dump($arr);
            return "success";
        } catch(\Firebase\JWT\SignatureInvalidException $e) {  //签名不正确
            return $e->getMessage();
        }catch(\Firebase\JWT\BeforeValidException $e) {  // 签名在某个时间点之后才能用
            return $e->getMessage();
        }catch(\Firebase\JWT\ExpiredException $e) {  // token过期
            return $e->getMessage();
        }catch(Exception $e) {  //其他错误
            return $e->getMessage();
        }
        //Firebase定义了多个 throw new，我们可以捕获多个catch来定义问题，catch加入自己的业务，比如token过期可以用当前Token刷新一个新Token
    }

    public function authorizations()
    {
        $key = $this->key; //key
        $time = time(); //当前时间

        //公用信息
        $token = array(
            'iss' => $this->iss, //签发者 可选
            'aud' => 'http://house-e.nhg.tw', //接收该JWT的一方，可选
            'iat' => $time, //签发时间
            'data' => array( //自定义信息，不要定义敏感信息
                'userid' => 1,
            )
        );

        $access_token = $token;
        //$access_token['scopes'] = 'role_access'; //token标识，请求接口的token
        $access_token['exp'] = $time+(86400 * 30); //access_token过期时间,这里设置30天

        $refresh_token = $token;
        //$refresh_token['scopes'] = 'role_refresh'; //token标识，刷新access_token
        $refresh_token['exp'] = $time+60; //access_token过期时间,这里设置2个小时

        $jsonList = array(
            'access_token'=>JWT::encode($access_token,$key,'HS256'),
            'refresh_token'=>JWT::encode($refresh_token,$key,'HS256'),
            'token_type'=>'bearer' //token_type：表示令牌类型，该值大小写不敏感，这里用bearer
        );
        Header("HTTP/1.1 201 Created");
        return json_encode($jsonList); //返回给客户端token信息
    }

    //刷新token
    public function refresh($token) {
        $key = $this->key; //key
        try{
            JWT::$leeway = 60;
            $decoded = (array) JWT::decode($token, $key, array('HS256') );
            // TODO: test if token is blacklisted
            $decoded['iat'] = time();
            //$decoded['exp'] = time() + self::$offset;
            $decoded['exp'] = time()+60;
            return JWT::encode($decoded, $key);
            //TODO: do something if exception is not fired
        }catch ( \Firebase\JWT\ExpiredException $e ) {
            return $e->getMessage();
        }catch ( \Exception $e ){
            return $e->getMessage();
        }
    }

    public function getAuthorizationHeader(){
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        }
        else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }

    public function getBearerToken() {
        $headers = $this->getAuthorizationHeader();
        $this->check_headers($headers);
        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }

    public function check_headers($headers) {
        if ( !isset($headers) ){
            $array['status'] = "error";
            $array['msg'] = "不合法的連線";
            echo json_encode($array);
            exit(); //中斷程式
        }
    }

}
?>