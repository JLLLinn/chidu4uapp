
<!-- Built by Jiaxin Lin for Chidu China
 
              *********
             * === === *
            L*  *   *  *J
             *    0    *
              *********
                 -X-
                 / \
-->

<!-- Last Update: 6/5/2015 -->
<?
session_start();
if(!isset($_SESSION['id'])){
		header("location: http://wxadmin.chidu4u.com/");
		exit();
}else if (time() > $_SESSION['timeout']){
	require_once("php/adminLogin.php");
	Admin::logoutCurrentUser();
	header("location: http://wxadmin.chidu4u.com/");
	exit();
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
        <title>系统信息 &middot; 尺度内部管理平台</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/emoji.css" rel="stylesheet">
        <link rel="stylesheet" href="css/font-awesome.min.css">
        <link rel="stylesheet" href="css/helperapp.css">
        <!--<link rel="stylesheet" href="./emojione/assets/css/emojione.min.css" />-->
        <!--<link rel="stylesheet" href="./emojione/assets/sprites/emojione.sprites.css"/>-->

        
    </head>
    <body>



        <div class="container">
            <div class="row">
	         <h1>
            <div class="pull-left">系统信息</div>
	          <!-- dropdown is here -->
          	<div class="btn-group pull-right">
          	  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
          	    用户: <?echo $_SESSION['username']?> <span class="caret"></span>
          	  </button>
          	  <ul class="dropdown-menu" role="menu">
          	    <li><a id="logoutBtn">退出登录</a></li>
          	  </ul>
          	</div>
          	
          	</h1>
          	</div>
                      

            
            <hr>
             <div class="text-center">
              <h3>24小时内存使用, 最大单核为1678MB
              </h3>
              <canvas id="sys-mem-usage-chart" width="5000" height="400"></canvas>
            </div>
           


            



        </div>
        <!--<script src="./emojione/lib/js/emojione.min.js"></script>-->


        <script src="js/jquery-1.11.3.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="js/bootstrap.min.js"></script>
        <script src="js/Chart.min.js"></script>
        <script src="js/systemstatapp.js"></script>
        <script src="js/memberpagesapp.js"></script>
    </body>
</html>