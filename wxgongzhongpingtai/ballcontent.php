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
    <title>球形内容 &middot; 尺度内部管理平台</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/helperapp.css">
  </head>
  <body>
    <div class="container">
      <div class="row">
        <h1>
          <div class="pull-left">球形内容 </div>
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
        <div class="row">
            <div class="col-md-5">
                <input class=" form-control" type="text" id="tag-name-input" placeholder="选择或输入主题（TAG）">
          </div>
          <div class="col-md-5">
            <select class="form-control"  id="tag-name-select"></select>
          </div>
          
          <div class="col-md-2 ">
            <button class="form-control btn btn-default" id="submit-name-and-check">查看并编辑</button>
          </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-1">
                修改名称与Weight
            </div>
            <div class="col-md-4">
                <input class=" form-control" type="text" id="edit-tag-name-input" placeholder="输入需要修改的输入主题（TAG）">
          </div>
          <div class="col-md-4">
            <input class=" form-control" type="text" id="edit-tag-new-name-input" placeholder="新名称">
          </div>
          <div class="col-md-2">
            <input class=" form-control" type="number" id="edit-tag-weight-input" placeholder="Weight">
          </div>
          
          <div class="col-md-1">
            <button class="form-control btn btn-default" id="submit-edit-name-and-weight">修改</button>
          </div>
        </div>
        <br>
          <table class='table table-fixed-width table-bordered'>
            <thead>
                <tr>
                    <th class="col-md-1">标签</th>
                    <th class="col-md-1">类别号</th>
                    <th class="col-md-1">性别</th>
                    <th class="col-md-6">内容</th>
                    <th class="col-md-2">url</th>
                    <th class="col-md-1">使用中</th>
                    <th class="col-md-1">其他</th>
                </tr>
            </thead>
            <tbody id="ball-content-table">
                <tr class="ball-content-row" data-category="1" data-sex="1">
                    <td class="col-md-1 tag" rowspan="2"></td>
                    <td class="col-md-1" rowspan="2">1</td>
                    <th class="col-md-1" >男</th>
                    <td class="col-md-6 content"  contenteditable="true"></td>
                    <td class="col-md-2 url"  contenteditable="true"></td>
                    <td class="col-md-1 in-use"><input type="checkbox"></td>
                    <td class="col-md-1"></td>
                </tr>
                <tr class="ball-content-row" data-category="1" data-sex="2">
                    <th class="col-md-1" >女</th>
                    <td class="col-md-6 content"  contenteditable="true"></td>
                    <td class="col-md-2 url"  contenteditable="true"></td>
                    <td class="col-md-1 in-use"><input type="checkbox"></td>
                    <td class="col-md-1"></td>
                </tr>
                <tr class="ball-content-row" data-category="2" data-sex="1">
                    <td class="col-md-1 tag" rowspan="2"></td>
                    <td class="col-md-1" rowspan="2">2</td>
                    <th class="col-md-1" >男</th>
                    <td class="col-md-6 content"  contenteditable="true"></td>
                    <td class="col-md-2 url"  contenteditable="true"></td>
                    <td class="col-md-1 in-use"><input type="checkbox"></td>
                    <td class="col-md-1"></td>
                </tr>
                <tr class="ball-content-row" data-category="2" data-sex="2">
                    <th class="col-md-1" >女</th>
                    <td class="col-md-6 content"  contenteditable="true"></td>
                    <td class="col-md-2 url"  contenteditable="true"></td>
                    <td class="col-md-1 in-use"><input type="checkbox"></td>
                    <td class="col-md-1"></td>
                </tr>
                <tr class="ball-content-row" data-category="3" data-sex="1">
                    <td class="col-md-1 tag" rowspan="2"></td>
                    <td class="col-md-1" rowspan="2">3</td>
                    <th class="col-md-1" >男</th>
                    <td class="col-md-6 content"  contenteditable="true"></td>
                    <td class="col-md-2 url"  contenteditable="true"></td>
                    <td class="col-md-1 in-use"><input type="checkbox"></td>
                    <td class="col-md-1"></td>
                </tr>
                <tr class="ball-content-row" data-category="3" data-sex="2">
                    <th class="col-md-1" >女</th>
                    <td class="col-md-6 content"  contenteditable="true"></td>
                    <td class="col-md-2 url"  contenteditable="true"></td>
                    <td class="col-md-1 in-use"><input type="checkbox"></td>
                    <td class="col-md-1"></td>
                </tr>
                <tr class="ball-content-row" data-category="4" data-sex="1">
                    <td class="col-md-1 tag" rowspan="2"></td>
                    <td class="col-md-1" rowspan="2">4</td>
                    <th class="col-md-1" >男</th>
                    <td class="col-md-6 content"  contenteditable="true"></td>
                    <td class="col-md-2 url"  contenteditable="true"></td>
                    <td class="col-md-1 in-use"><input type="checkbox"></td>
                    <td class="col-md-1"></td>
                </tr>
                <tr class="ball-content-row" data-category="4" data-sex="2">
                    <th class="col-md-1" >女</th>
                    <td class="col-md-6 content"  contenteditable="true"></td>
                    <td class="col-md-2 url"  contenteditable="true"></td>
                    <td class="col-md-1 in-use"><input type="checkbox"></td>
                    <td class="col-md-1"></td>
                </tr>
                <tr class="ball-content-row" data-category="5" data-sex="1">
                    <td class="col-md-1 tag" rowspan="2"></td>
                    <td class="col-md-1" rowspan="2">5</td>
                    <th class="col-md-1" >男</th>
                    <td class="col-md-6 content"  contenteditable="true"></td>
                    <td class="col-md-2 url"  contenteditable="true"></td>
                    <td class="col-md-1 in-use"><input type="checkbox"></td>
                    <td class="col-md-1"></td>
                </tr>
                <tr class="ball-content-row" data-category="5" data-sex="2">
                    <th class="col-md-1" >女</th>
                    <td class="col-md-6 content"  contenteditable="true"></td>
                    <td class="col-md-2 url"  contenteditable="true"></td>
                    <td class="col-md-1 in-use"><input type="checkbox"></td>
                    <td class="col-md-1"></td>
                </tr>
                <tr class="ball-content-row" data-category="6" data-sex="1">
                    <td class="col-md-1 tag" rowspan="2"></td>
                    <td class="col-md-1" rowspan="2">6</td>
                    <th class="col-md-1" >男</th>
                    <td class="col-md-6 content"  contenteditable="true"></td>
                    <td class="col-md-2 url"  contenteditable="true"></td>
                    <td class="col-md-1 in-use"><input type="checkbox"></td>
                    <td class="col-md-1"></td>
                </tr>
                <tr class="ball-content-row" data-category="6" data-sex="2">
                    <th class="col-md-1" >女</th>
                    <td class="col-md-6 content"  contenteditable="true"></td>
                    <td class="col-md-2 url"  contenteditable="true"></td>
                    <td class="col-md-1 in-use"><input type="checkbox"></td>
                    <td class="col-md-1"></td>
                </tr>
                <tr class="ball-content-row" data-category="7" data-sex="1">
                    <td class="col-md-1 tag" rowspan="2"></td>
                    <td class="col-md-1" rowspan="2">7</td>
                    <th class="col-md-1" >男</th>
                    <td class="col-md-6 content"  contenteditable="true"></td>
                    <td class="col-md-2 url"  contenteditable="true"></td>
                    <td class="col-md-1 in-use"><input type="checkbox"></td>
                    <td class="col-md-1"></td>
                </tr>
                <tr class="ball-content-row" data-category="7" data-sex="2">
                    <th class="col-md-1" >女</th>
                    <td class="col-md-6 content"  contenteditable="true"></td>
                    <td class="col-md-2 url"  contenteditable="true"></td>
                    <td class="col-md-1 in-use"><input type="checkbox"></td>
                    <td class="col-md-1"></td>
                </tr>
                <tr class="ball-content-row" data-category="8" data-sex="1">
                    <td class="col-md-1 tag" rowspan="2"></td>
                    <td class="col-md-1" rowspan="2">8</td>
                    <th class="col-md-1" >男</th>
                    <td class="col-md-6 content"  contenteditable="true"></td>
                    <td class="col-md-2 url"  contenteditable="true"></td>
                    <td class="col-md-1 in-use"><input type="checkbox"></td>
                    <td class="col-md-1"></td>
                </tr>
                <tr class="ball-content-row" data-category="8" data-sex="2">
                    <th class="col-md-1" >女</th>
                    <td class="col-md-6 content"  contenteditable="true"></td>
                    <td class="col-md-2 url"  contenteditable="true"></td>
                    <td class="col-md-1 in-use"><input type="checkbox"></td>
                    <td class="col-md-1"></td>
                </tr>
                <tr class="ball-content-row" data-category="9" data-sex="1">
                    <td class="col-md-1 tag" rowspan="2"></td>
                    <td class="col-md-1" rowspan="2">9</td>
                    <th class="col-md-1" >男</th>
                    <td class="col-md-6 content"  contenteditable="true"></td>
                    <td class="col-md-2 url"  contenteditable="true"></td>
                    <td class="col-md-1 in-use"><input type="checkbox"></td>
                    <td class="col-md-1"></td>
                </tr>
                <tr class="ball-content-row" data-category="9" data-sex="2">
                    <th class="col-md-1" >女</th>
                    <td class="col-md-6 content"  contenteditable="true"></td>
                    <td class="col-md-2 url"  contenteditable="true"></td>
                    <td class="col-md-1 in-use"><input type="checkbox"></td>
                    <td class="col-md-1"></td>
                </tr>
                <tr class="ball-content-row" data-category="10" data-sex="1">
                    <td class="col-md-1 tag" rowspan="2"></td>
                    <td class="col-md-1" rowspan="2">10</td>
                    <th class="col-md-1" >男</th>
                    <td class="col-md-6 content"  contenteditable="true"></td>
                    <td class="col-md-2 url"  contenteditable="true"></td>
                    <td class="col-md-1 in-use"><input type="checkbox"></td>
                    <td class="col-md-1"></td>
                </tr>
                <tr class="ball-content-row" data-category="10" data-sex="2">
                    <th class="col-md-1" >女</th>
                    <td class="col-md-6 content"  contenteditable="true"></td>
                    <td class="col-md-2 url"  contenteditable="true"></td>
                    <td class="col-md-1 in-use"><input type="checkbox"></td>
                    <td class="col-md-1"></td>
                </tr>
                <tr class="ball-content-row" data-category="11" data-sex="1">
                    <td class="col-md-1 tag" rowspan="2"></td>
                    <td class="col-md-1" rowspan="2">11</td>
                    <th class="col-md-1" >男</th>
                    <td class="col-md-6 content"  contenteditable="true"></td>
                    <td class="col-md-2 url"  contenteditable="true"></td>
                    <td class="col-md-1 in-use"><input type="checkbox"></td>
                    <td class="col-md-1"></td>
                </tr>
                <tr class="ball-content-row" data-category="11" data-sex="2">
                    <th class="col-md-1" >女</th>
                    <td class="col-md-6 content"  contenteditable="true"></td>
                    <td class="col-md-2 url"  contenteditable="true"></td>
                    <td class="col-md-1 in-use"><input type="checkbox"></td>
                    <td class="col-md-1"></td>
                </tr>
                <tr class="ball-content-row" data-category="12" data-sex="1">
                    <td class="col-md-1 tag" rowspan="2"></td>
                    <td class="col-md-1" rowspan="2">12</td>
                    <th class="col-md-1" >男</th>
                    <td class="col-md-6 content"  contenteditable="true"></td>
                    <td class="col-md-2 url"  contenteditable="true"></td>
                    <td class="col-md-1 in-use"><input type="checkbox"></td>
                    <td class="col-md-1"></td>
                </tr>
                <tr class="ball-content-row" data-category="12" data-sex="2">
                    <th class="col-md-1" >女</th>
                    <td class="col-md-6 content"  contenteditable="true"></td>
                    <td class="col-md-2 url"  contenteditable="true"></td>
                    <td class="col-md-1 in-use"><input type="checkbox"></td>
                    <td class="col-md-1"></td>
                </tr>
                <tr class="ball-content-row" data-category="13" data-sex="1">
                    <td class="col-md-1 tag" rowspan="2"></td>
                    <td class="col-md-1" rowspan="2">13</td>
                    <th class="col-md-1" >男</th>
                    <td class="col-md-6 content"  contenteditable="true"></td>
                    <td class="col-md-2 url"  contenteditable="true"></td>
                    <td class="col-md-1 in-use"><input type="checkbox"></td>
                    <td class="col-md-1"></td>
                </tr>
                <tr class="ball-content-row" data-category="13" data-sex="2">
                    <th class="col-md-1" >女</th>
                    <td class="col-md-6 content"  contenteditable="true"></td>
                    <td class="col-md-2 url"  contenteditable="true"></td>
                    <td class="col-md-1 in-use"><input type="checkbox"></td>
                    <td class="col-md-1"></td>
                </tr>
                <tr class="ball-content-row" data-category="14" data-sex="1">
                    <td class="col-md-1 tag" rowspan="2"></td>
                    <td class="col-md-1" rowspan="2">14</td>
                    <th class="col-md-1" >男</th>
                    <td class="col-md-6 content"  contenteditable="true"></td>
                    <td class="col-md-2 url"  contenteditable="true"></td>
                    <td class="col-md-1 in-use"><input type="checkbox"></td>
                    <td class="col-md-1"></td>
                </tr>
                <tr class="ball-content-row" data-category="14" data-sex="2">
                    <th class="col-md-1" >女</th>
                    <td class="col-md-6 content"  contenteditable="true"></td>
                    <td class="col-md-2 url"  contenteditable="true"></td>
                    <td class="col-md-1 in-use"><input type="checkbox"></td>
                    <td class="col-md-1"></td>
                </tr>
            </tbody>
          </table>
          <button id="submit-ball-content" class="btn btn-default">提交</button>
        
    </div>
    <script src="js/jquery-1.11.3.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/ballcontentapp.js"></script>
    <script src="js/memberpagesapp.js"></script>
  </body>
</html>