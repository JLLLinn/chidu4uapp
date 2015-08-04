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
        <title>尺度内部管理平台</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/newsfulllistapp.css" rel="stylesheet">
    </head>
    <body>
    
    	

        <div class="container">




        
	  		<div class="row">
			 	<h1>
					<div class="pull-left">尺度内部管理平台主界面</div>
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
	    
	    
	    
            <hr></hr>
            <div class="col-md-6">
	            <div class="list-group">

				  <a target="_blank" href="setsimplerules.php" class="list-group-item">设置简单规则</a>
				  <a target="_blank" href="newsfulllist.php" class="list-group-item">图文素材完全列表<span class="badge" id="newsfulllistbadge"></span></a>
				  <a target="_blank" href="imagefulllist.php" class="list-group-item">图片素材完全列表<span class="badge" id="imagefulllistbadge"></span></a>
				  <a target="_blank" href="userstat.php" class="list-group-item">用户数据分析</a>
				  <a target="_blank" href="menu.php" class="list-group-item">自定义菜单</a>
				  <a target="_blank" href="custommessagereply.php" class="list-group-item">客服信息</a>
				  <a target="_blank" href="filtercommentwall.php" class="list-group-item">评论墙</a>
				  <a target="_blank" href="ballcontent.php" class="list-group-item">球形内容</a>
			 	</div>

			 	<?php 
			 		if($_SESSION['type'] == "super"){
			 	?>
			 		<hr>
			 		<h4>仅对Super Admin</h4>
			 		<div class="list-group">
			 			<a target="_blank" href="systemstat.php" class="list-group-item">系统信息</a>
			 		</div>

			 	<?php
			 		}
			 	?>
			</div>
			

			<ul class="list-group col-md-6">
			  <li class="list-group-item list-group-item-info"><h4>公告</h4></li>
			  <li class="list-group-item ">2015/07/21 空白信息不再显示</li>
			  <li class="list-group-item ">2015/07/20 球形推出并测试</li>
			  <li class="list-group-item ">2015/07/08 增强评论墙与回复用户信息功能在手机端的体验</li>
			  <li class="list-group-item ">2015/07/07 使用更User Friendly的时间显示</li>
			  <li class="list-group-item ">2015/07/07 评论墙中加入<strong>查看过去添加文章</strong>功能。</li>
			  <li class="list-group-item ">2015/07/06 评论墙中加入<strong>回复用户</strong>按钮。此回复只会出现在评论墙，不会发送消息给用户</li>
			  <li class="list-group-item ">2015/07/05 评论墙中加入<strong>返回文章</strong>按钮，并可以在此后台评论墙中设置url.</li>
			  <li class="list-group-item ">2015/07/04 隆重推出<strong>评论墙</strong>功能！</li>
			  <li class="list-group-item ">2015/07/03 在客服信息中，新增显示某消息<strong>是否已经被回复</strong>的功能。</li>
			  <li class="list-group-item ">2015/07/01 在“用户数据分析”中，新增了显示用户的<strong>信息数据</strong>，时间扩大到过去的48小时。</li>
			  <li class="list-group-item ">2015/06/30 优化了用户数据的图表显示，时间从该整点显示起，更易观察。</li>
			  <li class="list-group-item ">2015/06/29 一个<strong>搜索过去消息</strong>的功能出现了,实时搜索所有数据库内过去消息记录（从平台开始使用15/6/22后的所有数据）。呵呵呵呵</li>
			  <li class="list-group-item ">2015/06/28 表情输入已经完成。如果有不能显示的跟我说，想要加其他表情的也告诉我</li>
			  <li class="list-group-item ">2015/06/28 现在回复消息时可以<strong>查看所有过去的历史纪录</strong>了。请大家不(wu)再(qing)使(pao)用(qi)微信官方平台，全面使用我们的平台进行回复。只有我们平台回复的才能进行纪录。</li>
			  <li class="list-group-item ">2015/06/27 MAC上输入<strong>表情</strong>的暂时解决办法：按control＋command＋空格即可跳出表情输入。中文颜文字可在中文输入法下使用shift＋6来发送</li>
			  <li class="list-group-item ">2015/06/27 客服中可以查看所有历史留言了。另外今天的后台发的消息挺好玩儿的</strong></li>
			  <li class="list-group-item ">2015/06/26 增强显示与回复表情功能。如果发现无法显示表情请告知管理员。</li>
			  <li class="list-group-item ">2015/06/25 现已在客服消息中新增回复功能，可以直接在页面进行回复。页面可自行接收新消息。注意编辑信息时，可以发送多个段落，即换行符有效。</li>
			  <li class="list-group-item ">2015/06/24 暂时开放基础客服消息功能，可以查看过去24小时用户未被规则自动回复的消息。仅用于浏览消息，暂时仍需回到官方网站进行查找与回复。<strong>特别请通过此确认是否有未添加的规则。</strong></li>
			  <li class="list-group-item ">2015/06/23 在“用户数据分析中”，新增“24小时用户新关注与取消关注统计”，可用于统计关注高峰期或文章等影响力</li>
			</ul>
			
        </div>


        <script src="js/jquery-1.11.3.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="js/bootstrap.min.js"></script>
        <script src="js/homepageapp.js"></script>
        <script src="js/memberpagesapp.js"></script>
    </body>
</html>