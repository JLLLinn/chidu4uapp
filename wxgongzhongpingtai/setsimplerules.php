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
	<title>设置规则 &middot; 尺度内部管理平台</title>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/helperapp.css" rel="stylesheet">


</head>
<body>
	<div class="container">

		<div class="row">
			<h1>
				<div class="pull-left">设置简单回复规则 </div>
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

		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="active"><a href="#text-incoming-rule" aria-controls="text-incoming-rule" role="tab" data-toggle="tab">文字消息</a></li>
			<li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">事件消息</a></li>
		</ul>

		<div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="text-incoming-rule">
				<blockquote>
		            <h5>注意了注意了，本功能支持同个关键字多个规则，如果同时Match多个规则，将会一一执行</h5>
		            <footer>哇哈哈</footer>
		        </blockquote>
				<form id='addsimpletextruleform' class="top-buffer form-horizontal">
					<div class="form-group form-inline  ">
						<label for="formKeyword ">接收</label>
						<input type="text" class="form-control" id="formKeyword" name="formKeyword"  placeholder="关键词" required>
					</div>
					<div class="form-group form-inline ">
						<label for="formResponse ">发出</label>
						<select id="formSelect" name="formSelect" class="form-control" required>
							<option value="text">文字消息</option>
							<option value="news" selected>图文素材</option>
							<option value="image">图片</option>
						</select>
						<input type="text" class="form-control" id="formResponse" name="formResponse" placeholder="MediaId" required>
						<input type="text" class="form-control" id="formName" name="formName"  placeholder="规则名" required>
						<input type="text" class="form-control" id="formComment" name="formComment" placeholder="备注" >
						<input type="hidden" class="form-control" id="formAuthorId" name="formAuthorId" value=<?echo $_SESSION['id']?> >
						

					</div>
					<div class="form-group form-inline ">
					<label for="formConfirm ">确认</label>
					<button name="formConfirm" type="submit" class="btn btn-default">添加规则</button>
				</div>
					
				</form>
				<h2>已有规则</h2>

				<table  class="table table-fixed-width">
					<thead>
						<tr>
							<th class="col-md-1">#</th>
							<th class="col-md-1">关键词</th>
							<th class="col-md-1">回复类别</th>
							<th class="col-md-5">文字信息/MediaId</th>
							<th class="col-md-1">加入时间</th>
							<th class="col-md-1">规则名</th>
							<th class="col-md-1">备注</th>
							<th class="col-md-1">操作</th>
						</tr>
					</thead>
					<tbody id="textruleslist">
						<tr>
							<th scope="row">加载中</th>
							<td>加载中</td>
							<td>加载中</td>
							<td>加载中</td>
							<td>加载中</td>
							<td>加载中</td>
							<td>加载中</td>
							<td>加载中</td>

						</tr>
					</tbody>
				</table>
			</div>
			<div role="tabpanel" class="tab-pane" id="profile">
				<form id='addsimpleeventruleform' class="top-buffer">
					<div class="form-group form-inline  ">
						<label for="eventformType ">接收</label>
						<select id="eventformType" name="eventformType" class="form-control" required>

							<option value="click">点击</option>
							<option value="subscribe">关注</option>
						</select>
						<input type="text" class="form-control" id="eventformKey" name="eventformKey"  placeholder="事件名称" required>
					</div>


					<div class="form-group form-inline ">
						<label for="eventformSelect ">发出</label>
						<select id="eventformSelect" name="eventformSelect" class="form-control" required>
							<option value="text" >文字消息</option>
							<option value="news" selected>图文素材</option>
						</select>
						<input type='text' class='form-control' id='eventformResponse' name='eventformResponse' placeholder='MediaId' required>
						<input type="text" class="form-control" id="eventformName" name="eventformName"  placeholder="规则名" required>
						<input type="text" class="form-control" id="eventformComment" name="eventformComment" placeholder="备注" >
						<input type="hidden" class="form-control" id="eventformAuthorId" name="eventformAuthorId" value=<?echo $_SESSION['id']?> >
					</div>
					<div class="form-group form-inline ">
						<label for="eventformConfirm ">确认</label>
					<button name="eventformConfirm" type="submit" class="btn btn-default">添加规则</button>
				</div>
				</form>
				<h2>已有规则</h2>

				<table  class="table">
					<thead>
						<tr>
							<th>#</th>
							<th class="col-md-1">事件类别</th>
							<th class="col-md-1">事件名称</th>
							<th class="col-md-1">回复类别</th>
							<th>文字信息/MediaId</th>
							<th>加入时间</th>
							<th class="col-md-1">规则名</th>
							<th class="col-md-1">备注</th>
							<th>操作</th>
						</tr>
					</thead>
					<tbody id="eventruleslist">
						<tr>
							<th scope="row">加载中</th>
							<td>加载中</td>
							<td>加载中</td>
							<td>加载中</td>
							<td>加载中</td>
							<td>加载中</td>
							<td>加载中</td>
							<td>加载中</td>
							<td>加载中</td>

						</tr>
					</tbody>
				</table>

			</div>
		</div>

	</div>
	
	
	<script src="js/jquery-1.11.3.min.js"></script>
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="js/bootstrap.min.js"></script>
	<script src="js/setsimplerulesapp.js"></script>
	<script src="js/memberpagesapp.js"></script>

</body>
</html>
