
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
        <title>选择评论墙回复 &middot; 尺度内部管理平台</title>
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
	  <div class="pull-left">选择评论墙回复</div>
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
            
<div id="loading-modal" class="modal fade">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title" id="mySmallModalLabel">加载中</h4>
        </div>
        <div class="modal-body">
          完成后将重新加载页面
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div>



  <div id="official-reply-modal" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title" id="mySmallModalLabel">回复当前评论</h4>
        </div>
        <div class="modal-body">
            当前回复：<div class="well" id="official-reply-current-response"></div>
            <textarea class="form-control" id="official-reply-response"></textarea>
        </div>
        <div class="modal-footer">
          <button class="btn btn-default" id="official-reply-submit">提交</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div>

<div id="set-article-url-modal" class="modal fade"  data-backdrop='static'>
    <div class="modal-dialog modal-lg">
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title" id="">为文章设置“返回文章”地址</h4>
        </div>
        <div class="modal-body form-horizontal" >
          <form id="article-url-form">
            <div class="form-group">
              <label for="article_str" class="col-md-2 control-label">文章名称或识别字符串</label>
              <div class="col-md-10">
                <div class=" input-group">
                <input class="form-control" id="article_str" name="article_str" placeholder="名称或字符串（就是那串看不懂的乱码）">
                <span class="input-group-btn">
                  <button class="btn btn-default" id="show-article-url">查看设置的返回文章url</button>
                </span>
              </div>
              </div>
            </div>
            <div class="form-group">
              <label for="url" class="col-md-2 control-label">返回文章URL地址</label>
              <div class="col-md-10">
                <input class="form-control" id="url" name="url" placeholder="返回文章URL地址">
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-offset-2 col-md-10">
                <button id="submit-article-url-form" class="btn btn-default">提交当前设置</button>
              </div>
            </div>
          </form>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div>


            
            <hr>
            <blockquote>
              <p>专用于选择信息放入评论墙</p>
            </blockquote>

            <div class="alert alert-success col-md-3" id="new_message_alert" style="display:none">
              <a class="close">&times;</a>
              <a class="btn new-msg-reload-page"><strong>提醒</strong> 有<span class="new-msg-count">0</span>条新信息</a>
            </div>

              <div class="input-group col-md-3">
                <select id="article-list-select" class="form-control">
                  <option value="">选择已有文章</option>
                </select> <span class="input-group-addon">或</span>
              </div> 
              <br>
            <div class="input-group col-md-12">
               <input id="article-name" type="text" class="form-control" placeholder="输入文章名称（为保证手机微信显示质量，中文字数最好不超9个字）">
               <span class="input-group-btn">
                  <button class="btn btn-default" id="generate-url-btn"><span class="hidden-xs">生成地址并为文章选择评论</span><span class="visible-xs"><small>生成地址</small></span></button>
                  <button class="btn btn-default" id="set-return2article-url"><span class="hidden-xs">设置“返回文章”URL</span><span class="visible-xs"><small>文章URL</small></span></button>
                </span>
            </div>

            <br>
            <div class="input-group col-md-12">
               <span class="input-group-addon hidden-xs">当前产生网址</span>
               <input id="generated-url" type="text" class="form-control" placeholder="" readonly>
               <span class="input-group-btn">
                  
                  <button data-mode='1' class="btn btn-default" id="list-selected-comment" disabled><span class="hidden-xs">只看当前文章选定评论</span><span class="visible-xs"><small>选定评论</small></span></button>
                  <button class="btn btn-default" id="send_add_delete_msg_ids" disabled><span class="hidden-xs">为当前文章提交选择评论</span><span class="visible-xs"><small>提交</small></span></button>
                </span>
            </div>
            <br>

            <div class="input-group col-md-6 pull-right">
               <span class="input-group-addon"><i class="fa fa-search"></i></span>
               <input id="search-input"type="text" class="form-control" placeholder="搜索">
               <span class="input-group-btn">
                  
                </span>
            </div>


            <br>
            <table class='table table-striped table-fixed-width'>
                <thead>
                    <tr>
                        <th class="col-md-1">时间</th>
                        <th class="col-md-2">昵称</th>
                        <th class="col-md-1 hidden-xs">头像</th>
                        <th class="col-md-1 hidden-xs">关注时间</th>
                        <th class="col-md-6">内容</th>
                        <th class="col-md-1">操作</th>
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


        <script src="js/jquery-1.11.3.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="js/bootstrap.min.js"></script>
        <script src="js/filtercommentwallapp.js"></script>
        <script src="js/memberpagesapp.js"></script>
    </body>
</html>