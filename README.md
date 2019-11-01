# jwt
JWT 可用於REST API中的驗證使用者, 防止被駭<br />
JWT套件網址:<br />
https://github.com/auth0/php-jwt-example<br />

API 安全性 ***很重要***
------------
1. Web Token
2. API KEY
3. SSL
4. 防火牆白名單
5. API Manager

Demo1 

JWT簡易版本

http://house.nhg.tw/admin/ryan/jwt_client1.php

Demo2 

JWT雙Token

access_token 30天有效，無效時則需重新登入

refresh_token 2小時有效，無效時若access_token仍有效時，將重新派發一個refresh_token給Client

http://house.nhg.tw/admin/ryan/jwt_client4.php
