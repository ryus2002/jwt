<html>
<head>
<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
<script>
//登入
$(function(){
  gettoken();
});


function gettoken() {
  if ( $('#cValue').val() == "" ) {
    var thURL   = "jwt_server4.php";
    var thPara = "id=abc&pw=abc";
    return $.ajax({  
      type: "POST",
      url: thURL,  
      data: thPara,  
      dataType: "json",
      async:false, //關閉同步
      success: function (data) {  
        var JSONArray = JSON.stringify(data);
        $('#cValue').val(JSONArray);
      }, 
      failure: function () {  
        $('#cValue').val('error');
      }  
    });
  }
  else {
    var thURL   = "jwt_server4.php";
    //var thPara  = "token="+$('#cValue').val();
    var thPara  = "";
    return $.ajax({  
      type: "POST",
      url: thURL,  
      data: thPara,  
      dataType: "json",
      async:false, //關閉同步
      beforeSend: function (xhr) {   //Include the bearer token in header
          xhr.setRequestHeader("Authorization", 'Bearer '+ $('#cValue').val());
      },
      success: function (data) {
        if ( typeof data['status'] !== "undefined"  ) {
        }
        else {
          var JSONArray = JSON.stringify(data);
          $('#cValue').val(JSONArray);
        }
      }, 
      failure: function () {  
        //$('#cValue').val('error');
      }  
    });
  }
}


function getdata() {
  var thURL   = "jwt_server5.php";
  //var thPara  = "token="+$('#cValue').val();
  var thPara  = "";
  return $.ajax({  
    type: "POST",
    url: thURL,  
    data: thPara,  
    dataType: "json",
    beforeSend: function (xhr) {   //Include the bearer token in header
        xhr.setRequestHeader("Authorization", 'Bearer '+ $('#cValue').val());
    },
    success: function (data) {
      //$('#cValue').val(data);
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
}

//取得資料
function send() {
  // gettoken().then(function(x) {
  //   getdata()
  // });

  $.when(
  ).then(function(x) {
    gettoken()
  }).then(function(x) {
    getdata()
  });


  // var thURL   = "jwt_server5.php";
  // var thPara  = "token="+$('#cValue').val();
  // $.ajax({  
  //   type: "POST",
  //   url: thURL,  
  //   data: thPara,  
  //   dataType: "json",
  //   success: function (data) {  
  //     if ( data['status'] != "success"  ) {
  //       $('#result').val(data['state']+"\n"+data['name']+"\n"+data['tel']);
  //     }
  //     else {
  //       $('#result').val(data);
  //     }
	  
  //   }, 
  //   failure: function () {  
  //     //$('#cValue').val('error');
  //   }  
  // })
}
</script>
</head>
<body>
<textarea rows="4" cols="50" id="cValue" ></textarea>
<input onclick="send();" type="button" value="驗證" />
<textarea rows="4" cols="50" id="result" ></textarea>
</body>
</html>
