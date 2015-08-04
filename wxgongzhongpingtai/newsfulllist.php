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
}else if (time() > $_SESSION['timeout']){
	require_once("php/adminLogin.php");
	Admin::logoutCurrentUser();
	header("location: http://wxadmin.chidu4u.com/");
}else{
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" sizes="32x32" href="favicon64.png">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>图文素材 &middot; 尺度内部管理平台</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="container">
            <div class="row">
	  <h1>
	  <div class="pull-left">图文素材完全列表 </div>
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
            <table class='table table-bordered' >
                <thead>
                    <tr>
                    	<th>#</th>
                        <th>标题</th>
                        <th>URL</th>
                        <th>MediaId</th>
                    </tr>
                </thead>
                <tbody id="newslist">
                </tbody>
                <tbody id="loading">
                    <tr>
                    	<td>加载中..</td>
                        <td>加载中..</td>
                        <td>加载中..</td>
                        <td>加载中..</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <script src="js/jquery-1.11.3.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="js/bootstrap.min.js"></script>
        <script src="js/newsfulllistapp.js"></script>
        <script src="js/memberpagesapp.js"></script>
    </body>
</html>

<?}?>