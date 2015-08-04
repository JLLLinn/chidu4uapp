
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
        <title>客服信息 &middot; 尺度内部管理平台</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/emoji.css" rel="stylesheet">
        <link rel="stylesheet" href="css/font-awesome.min.css">
        <link rel="stylesheet" href="css/custommessagereplyapp.css">
        <link rel="stylesheet" href="css/helperapp.css">
        <!--<link rel="stylesheet" href="./emojione/assets/css/emojione.min.css" />-->
        <!--<link rel="stylesheet" href="./emojione/assets/sprites/emojione.sprites.css"/>-->

        
    </head>
    <body>
        <div id="reply-message-modal" class="modal fade">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Modal title</h4>
              </div>
              <div class="modal-body">
              
                <textarea class="form-control" rows="3" id="reply_msg" placeholder="回复消息" maxlength="300" autofocus></textarea>
                <a id="emojiPopover" tabindex="0" class="btn btn-default pull-left" role="button" data-toggle="popover" title="插入表情">
                    <i class="fa fa-smile-o"></i>
                  </a>
             </div>

             <div class="modal-footer">
                  

                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" id="send-reply-btn" data-loading-text="处理点儿事儿" class="btn btn-primary">发送回复</button>
              </div>
              <hr>

               <div class="modal-body history_msg_div">
                <span class="checkbox">
                  <label>
                    <input id="showAutoRepliedHistoryCheckbox" type="checkbox"> 显示自动回复
                  </label>
                  <span id="load-history-spin"></span>
                </span>
                <i class="fa fa-bolt"></i> 表示自动回复或已被自动回复
                <div class="">
                <table class='table table-fixed-width'>
                <thead>
                    <tr>
                        <th class="col-md-1">时间</th>
                        <th class="col-md-2">用户</th>
                        <th class="col-md-1 hidden-xs">头像</th>
                        <th class="col-md-1 hidden-xs">类型</th>
                        <th class="col-md-6 col-xs-8">内容</th>
                        <th class="col-md-1 hidden-xs">其他</th>
                    </tr>
                </thead>
                <tbody id="history_msg_list">
                </tbody>
             </table>
           </div>
           </div>


              
            </div><!-- /.modal-content -->
          </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->



        <div class="container">
            <div class="row">
	  <h1>
	  <div class="pull-left">客服信息</div>
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
            <blockquote>
              <i class="fa fa-paper-plane"></i> 表示已回复，<i class="fa fa-reply"></i> 表示未回复。
            </blockquote>

            <div class="alert alert-success col-md-3" id="new_message_alert" style="display:none">
              <a class="close">&times;</a>
              <a class="btn new-msg-reload-page"><strong>提醒</strong> 有<span class="new-msg-count">0</span>条新信息</a>
            </div>


            <div class="input-group pull-right col-md-5">
               <span class="input-group-addon"><i class="fa fa-search"></i></span>
               <input id="search-input"type="text" class="form-control" placeholder="搜索">
            </div>
            <br><br>
            <table class='table table-striped table-fixed-width'>
                <thead>
                    <tr>
                        <th class="col-md-1">时间</th>
                        <th class="col-md-1">用户昵称</th>
                        <th class="col-md-1 hidden-xs">头像</th>
                        <th class="col-md-1 hidden-xs">关注时间</th>
                        <th class="col-md-6 col-xs-6">内容</th>
                        <th class="col-md-1">操作</th>
                        <th class="col-md-1">球形类别</th>
                    </tr>
                </thead>
                <tbody id="msglist">
                </tbody>
            </table>


          <nav>
            <ul class="pager">
              <li class="previous"><a class="prev-page btn btn-default" disabled><i class="fa fa-chevron-left"></i> 上页</a></li>
              <li ><b class="page-number">1</b></li>
              <li class="next"><a class="next-page btn btn-default">下页 <i class="fa fa-chevron-right"></i></a></li>
            </ul>
          </nav>


        </div>
        <!--<script src="./emojione/lib/js/emojione.min.js"></script>-->


        <script src="js/jquery-1.11.3.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="js/bootstrap.min.js"></script>
        <script src="js/custommessagereplyapp.js"></script>
        <script src="js/memberpagesapp.js"></script>
    </body>
</html>