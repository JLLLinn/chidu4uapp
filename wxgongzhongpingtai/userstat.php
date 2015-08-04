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
		header("location:http://wxadmin.chidu4u.com/");
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
    <title>用户数据分析 &middot; 尺度内部管理平台</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body>
    <div class="container">
      <div class="row">
        <h1>
          <div class="pull-left">用户数据分析 </div>
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
      <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#detailChange" aria-controls="detailChange" role="tab" data-toggle="tab">细节统计</a></li>
        <li role="presentation" ><a href="#userNumChange" aria-controls="userNumChange" role="tab" data-toggle="tab">用户数量统计</a></li>
      </ul>
      <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="detailChange">
          <div class="text-center">
            <h3>48小时用户新关注与取消关注统计
              <small> 
                总关注 <span id="total-subs" class="badge"></span> 
                总取关 <span id="total-unsubs" class="badge"></span>
              </small> 
            </h3>
            <canvas id="24hrsSubsChart" width="1000" height="400"></canvas>
            <h3>48小时用户信息数量统计
              <small> 
                总信息 <span id="total-msg" class="badge"></span> 
                总自动回复 <span id="total-ato-rep" class="badge"></span>
                总未自动回复 <span id="total-unato-rep" class="badge"></span>
              </small>
            </h3>
            <canvas id="24hrsMsgChart" width="1000" height="400"></canvas>
          </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="userNumChange">
          <table class='table table-bordered' >
            <thead id="userstatlistheader">
              <tr>
                <th>日期</th>
                <th>用户总量</th>
                <th>来源</th>
                <th>新增</th>
                <th>减少</th>
                <th>净增</th>
                <th></th>
              </tr>
            </thead>
            <tbody id="userstatlist">
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <script src="js/jquery-1.11.3.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/Chart.min.js"></script>
    <script src="js/userstatapp.js"></script>
    <script src="js/memberpagesapp.js"></script>
  </body>
</html>