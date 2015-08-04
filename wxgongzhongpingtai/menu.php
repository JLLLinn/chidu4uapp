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
    <title>自定义菜单 &middot; 尺度内部管理平台</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>


    <div class="modal fade" id="menu_modal" data-usage>
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 id='modal-title' class="modal-title"></h4>
        </div>
        <div id='modal-body' class="modal-body">
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
            <button id='modal-save-change-button' type="button" class="btn btn-primary" data-dismiss="modal">确认</button>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="container">







    <div class="row">
        <h1>
            <div class="pull-left">自定义菜单 </div>
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
        <h5>1. 自定义菜单最多包括3个一级菜单，每个一级菜单最多包含5个二级菜单。</h5>
        <h5>2. 一级菜单最多4个汉字<em>(实际可达到5个，4个最佳)</em>，二级菜单最多7个汉字，<em>(实际13个)</em>，多出来的部分将会以“...”代替<em>（测试中并未出现，设置人需实测）</em>。</h5>
        <h5>3. 创建自定义菜单后，由于微信客户端缓存，需要24小时微信客户端才会展现出来。测试时可以尝试取消关注公众账号后再次关注，则可以看到创建后的效果。</h5>
        <h5>4. 设置事件：每个菜单对应一个事件。推荐选择点击类别或跳转URL，其他暂不开放。</h5>
          <h5>5. 选择类别“点击”后，前往“设置简单规则”中的“事件消息”，“事件名称”中填写与本页“事件名称”相同的内容即可对应。</h5>
          <h5>6. 事件名称的命名推荐menu_a_b，a为一级菜单编号，b为二级菜单编号.</h5>
    </blockquote>

    <div class="row">
        <div class="col-md-4">
            <ul class="list-group" id="current-menu-list">
                <li class="list-group-item clearfix">
                    <label>
                        <input id="is_menu_open-checkbox"  type="checkbox"> 使用自定义菜单
                    </label>
                    <div class="btn-group pull-right" role="group" aria-label="...">
                        <button type="button" class="btn btn-sm btn-default" id="add_btn" >
                          <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                      </button>
                      <button id="sort-menu-btn" type="button" class="btn btn-sm btn-default " >
                          <span  class="span-icon-click-to-sort glyphicon glyphicon-sort" aria-hidden="true"></span>
                          <span  class="span-sort-text">点击排序</span>
                      </button>
                  </div>
              </li>

          </ul>
      </div>
      <div class="col-md-8" >
        <div class="button-detail" id=""><!--Here stores the unique id of the button -->
            <form class="form-horizontal" onsubmit="return false;">
                <div class="form-group">
                    <label for="form_event_type" class="col-sm-2 control-label">事件类别</label>
                    <div class="col-sm-10">
                       <select id="form_event_type" name="form_event_type" class="form-control">
                        <!--<option value="" ></option>-->
                        <!--<option value="news" disabled>图文（通过官网设置）</option>-->
                        <!--<option value="text" disabled>文字（通过官网设置）</option>-->
                        <option value="click" >点击</option>
                        <option value="view" >跳转URL</option>
                        <!--<option value="scancode_push" disabled>扫码推事件</option>-->
                        <!--<option value="scancode_waitmsg" disabled>扫码推事件且弹出“消息接收中”提示框</option>-->
                        <!--<option value="pic_sysphoto" disabled>弹出系统拍照发图</option>-->
                        <!--<option value="pic_photo_or_album" disabled>弹出拍照或者相册发图</option>-->
                        <!--<option value="pic_weixin" disabled>弹出微信相册发图器</option>-->
                        <!--<option value="location_select" disabled>弹出地理位置选择器</option>-->
                        <!--<option value="media_id" disabled>下发消息（除文本消息）</option>-->
                        <!--<option value="view_limited" disabled>跳转图文消息URL</option>-->
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label id="label-keyOrUrlOrMediaId" for="form-keyOrUrlOrMediaId" class="col-sm-2 control-label"></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="form-keyOrUrlOrMediaId" placeholder="">
                </div>
            </div>
            <div class="form-group">
                <label for="button-operation-group" class="col-sm-2 control-label">操作</label>
                <div class="col-sm-10">
                    <div id="button-operation-group" class="btn-group" role="group" >
                      <button id="open-rename-subbutton-modal" type="button" class="btn btn-default" disabled> 重命名 <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>
                      <button id="open-delete-subbutton-modal" type="button" class="btn btn-default" disabled> 删除 <span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>
                  </div>
              </div>
          </div>


      </form>


  </div>

</div>
</div>

<button id="big-submit-button" type="button" data-loading-text="正在处理一些事情" class="btn btn-default btn-lg btn-block">点击提交</button>




</div>
<script src="js/jquery-1.11.3.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>

<script src="js/jquery-sortable-min.js"></script>
<script src="js/menuapp.js"></script>
<script src="js/memberpagesapp.js"></script>
</body>
</html>