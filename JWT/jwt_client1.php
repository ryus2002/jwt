<html>
<head>
<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
<script>
$(function(){
  var thURL   = "jwt_server1.php";
  var thPara = "id=abc&pw=abc";
  $.ajax({  
    type: "POST",
    url: thURL,  
    data: thPara,  
    dataType: "json",
    success: function (data) {  
	    $('#cValue').val(data);
    }, 
    failure: function () {  
      $('#cValue').val('error');
    }  
  })
});

function getdata() {
  var thURL   = "jwt_server2.php";
  var thPara  = "";
  $.ajax({  
    type: "POST",
    url: thURL,  
    data: thPara,  
    dataType: "json",
    beforeSend: function (xhr) {   //Include the bearer token in header
        xhr.setRequestHeader("Authorization", 'Bearer '+ $('#cValue').val());
    },
    success: function (data) {  
      $('#result').val("");
      if ( typeof data['status'] !== "undefined"  ) {
        $('#result').val(data['status']+"\n"+data['name']+"\n"+data['tel']+"\n"+data['msg']);
      }
      else {
        $('#result').val(data);
      }
    
    }, 
    failure: function () {  
      //$('#cValue').val('error');
    }  
  })
}</script>
</head>
<body>
<input id="cValue" type="text" />
<input onclick="getdata();" type="button" value="驗證" />
<textarea rows="4" cols="50" id="result" ></textarea>
</body>
</html>
