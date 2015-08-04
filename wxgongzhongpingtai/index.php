<!-- Built by Jiaxin Lin for Chidu China
 
              *********
             * === === *
            L*  *   *  *J
             *    0    *
              *********
                 -X-
                 / \
-->

<?
	session_start();
	if(isset($_SESSION['id']) && time() < $_SESSION['timeout']){
		header("location: homepage.php");
	}else {
		$_SESSION = array();
		session_destroy();
	}
?>

<!DOCTYPE html>

<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon64.png">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>尺度内部管理平台</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
  	<div class='container'>
    <h1>登陆界面</h1>
    <hr></hr>
    
    <form class="form-horizontal" id="loginform">
  <div class="form-group">
    <label for="username" class="col-sm-2 control-label">用户名</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="username" name="username" placeholder="Username" require>
    </div>
  </div>
  <div class="form-group">
    <label for="password" class="col-sm-2 control-label">密码</label>
    <div class="col-sm-10">
      <input type="password" class="form-control" id="password" name="password" placeholder="Password" require>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-default">登陆</button>
    </div>
  </div>
</form>
    
    </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    
    <script src="js/jquery-1.11.3.min.js"></script>
    
    <script src="js/indexapp.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>