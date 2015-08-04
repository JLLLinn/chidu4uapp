<?php
session_start();
  defined("PERSONALCONSULTINGHTMLPATH") or define("PERSONALCONSULTINGHTMLPATH", "/home/chidsjwb/public_html/personalconsulting/");
  defined("GETACCESSTOKENPATH") or define("GETACCESSTOKENPATH", "/home/chidsjwb/wxgongzhongpingtai/getAccessToken.php");
  require_once(PERSONALCONSULTINGHTMLPATH.'php/DBvar.php');
  $conn = new mysqli(DBSERVERNAME, DBUSERNAME, DBPASSWORD,DBNAME);
  if ($conn ->connect_errno) {
      error_log("Connect failed: %s\n", $conn ->connect_error);
  }else {
    mysqli_set_charset ( $conn , 'utf8mb4' );
  }
  if(isset($_SESSION['openid'])){
    require_once(PERSONALCONSULTINGHTMLPATH."php/userUtil.php");
    $scripthtml = "<div class='alert alert-warning alert-dismissible fade in' role='alert'>
      <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>×</span></button>
      <small><p>嗨%s，欢迎回来！尺度君记得%s上次输入的生日是%s～<br></p>%s可以:</small>
       <a href='./firststageresult-singlebirthday.php' type='button' class='btn btn-danger btn-xs'><small>载入球形</small></a> <small>或者</small> 
        <a href='./getbirthday.php' type='button' class='btn btn-default btn-xs'><small>重新算个球</small></a>
    </div>";
    if($row = UserUtil::getUserInfoFromDB($_SESSION['openid'], $conn)){
      $_SESSION['birthdayTimestamp'] = $row['birth_timestamp'];
      if(!is_null($row['birth_timestamp'])){
        if($row['sex'] == 2) {
          $sexStr = "妳";
        } else {
          $sexStr = "你";
        }
        $datestr = date("Y年n月j日G点", $row['birth_timestamp']);
        $scripthtml = sprintf($scripthtml, $row['nickname'], $sexStr, $datestr, $sexStr,$row['birth_timestamp']);
      } else {
        $scripthtml="";
      }
    }else {
      $scripthtml = "";
    }
  }
?>
<!-- Built by Jiaxin Lin for Chidu China
 
              *********
             * === === *
            L*  *   *  *J
             *    0    *
              *********
                 -X-
                 / \
You found this? Good For You. See, above's my portrait.
Contact me at jiaxin.lin@chidu4u.com if you really want to for some reason.
-->
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>算个球 &middot; 尺度</title>
    <!-- Bootstrap -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/font-awesome.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" sizes="32x32" href="../../img/favicon64.png">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <nav class="navbar navbar-default">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="../subscribe-qrcode.html">
            <img alt="尺度" width="20" src="../../img/chidu_original.png">
          </a>
          <p class="navbar-text navbar-right">
            <a class="text-muted" href="../subscribe-qrcode.html"> 尺度</a> / <a class="text-muted" href="./ballIntro.html"> 球形</a> / 算个球
          </p>
        </div>

      </div>
    </nav>
    <div class="container">

      
    <?php echo $scripthtml?>
      <div class="panel panel-default">
        <div class="panel-heading">
          <h5 class="panel-title text-center">算个球</h5>
        </div>
        <div class="panel-body">
          <p>
                这是一个直击内心的游戏，它比你更了解你自己。
            <br><br>抵达幸福的路比你想象中要更容易。
            <br><br>认识你自己，了解自己情感优势与劣势，通过扬长避短优化自己，一切将会化繁为简。
            <br><br>准备好了吗？来，我们一起， 给你算个球！
            <br><div class="text-center"><a href="./getbirthday.php" class="btn btn-success">点我开始 <strong><i class="fa fa-angle-double-right"></i></strong></a></div> 
            <br><br>
          </p>
        </div>
      </div>

       <div class='text-center'>
        <small><small>本测试仅能通过在尺度微信后台回复“算个球”进入，其他进入方式很可能无效。如果您看不到结果，请直接回复尺度或识别下方二维码关注尺度</small></small><br>
              <img src="../../img/qrcode_chidu_1_8.jpg" width="172px">
      </div>
      <br>
      <small class="text-muted"><div class="text-center"><small>为保护您的隐私，请仅在由尺度网站chidu4u.com提供的服务中使用您的生日。<br>&#169; 2015 尺度 | <a class="text-muted" href="./ballIntro.html"> 球形说明</a></small><div> </small><br>
        
      


    </div>




    <script src="../js/jquery-1.11.3.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="./js/indexapp.js"></script>
    <!-- Baidu Analytics-->
    <script>
        var _hmt = _hmt || [];
        (function() {
          var hm = document.createElement("script");
          hm.src = "//hm.baidu.com/hm.js?b12cf8d996bb7b02d2cbd0f968f1b24e";
          var s = document.getElementsByTagName("script")[0]; 
          s.parentNode.insertBefore(hm, s);
        })();
    </script>
  </body>
</html>