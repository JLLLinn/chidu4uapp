<?php
    session_start();
    header('Content-Type: text/html; charset=utf-8');
    defined("PERSONALCONSULTINGHTMLPATH") or define("PERSONALCONSULTINGHTMLPATH", "/home/chidsjwb/public_html/personalconsulting/");
    defined("GETACCESSTOKENPATH") or define("GETACCESSTOKENPATH", "/home/chidsjwb/wxgongzhongpingtai/getAccessToken.php");
    require_once(PERSONALCONSULTINGHTMLPATH.'php/DBvar.php');
    $conn = new mysqli(DBSERVERNAME, DBUSERNAME, DBPASSWORD,DBNAME);
    if ($conn ->connect_errno) {
      error_log("Connect failed: %s\n", $conn ->connect_error);
    }else {
    mysqli_set_charset ( $conn , 'utf8mb4' );
    }
    $script_insert="<script>var display_all_info=%s; var nickname='%s'; var sex=%s; var birthdaystr='%s' ; var birthdayTimestamp=%s</script>";
    if(isset($_SESSION['openid'])){
        defined("GETACCESSTOKENPATH") or define("GETACCESSTOKENPATH", "/home/chidsjwb/wxgongzhongpingtai/getAccessToken.php");
        require_once(GETACCESSTOKENPATH);
        require_once(PERSONALCONSULTINGHTMLPATH."ball/php/ballUserUtil.php");
        //trying to get from wechat with latest info
        if($user_array = BallUserUtil::getNSaveUserInformation($_SESSION['openid'],AccessToken::getAccessToken(), $conn)){
            if(isset($user_array['errcode'])){
                echo("<script>alert('呀，尺度似乎无法识别你，请通过尺度官方微信渠道进入本服务。')</script>");
                exit();
            } else if($user_array['subscribe'] ==0){
                echo("<script>alert('呀，您需要先关注尺度哟')</script>");
                exit();
            } else if($user_array['subscribe'] ==1){
                //ok so we know the person is real and subscribed, and is now in our DB.
                //but we need to check if he has a birthday already
                require_once(PERSONALCONSULTINGHTMLPATH."php/userUtil.php");
                if($row = UserUtil::getUserInfoFromDB($_SESSION['openid'], $conn)){
                    //$row['birth_timestamp']
                    if(!is_null($row['birth_timestamp']) && $row['birth_timestamp'] != 0){
                        //this guy has a birthday!
                        $_SESSION['birthdayTimestamp'] = $row['birth_timestamp'];
                        $script_insert = sprintf($script_insert, 'true', addslashes ($row['nickname']), $row['sex'], date("Y年n月j日G点", $row['birth_timestamp']), $row['birth_timestamp']);

                    } else {
                        //he doesn't have a birthday
                        $script_insert = sprintf($script_insert, 'true', addslashes ($row['nickname']), $row['sex'], "", 'false');
                    }
                }else {
                    trigger_error("Error in getUserInfoFromDB with openid: ".$_SESSION['openid']);
                }
            }
        } else{
            trigger_error("Can't perform getNSaveUserInformation with userid: ".$_SESSION['openid']);
        }
    } else {
        //no openid available
        echo("<script>alert('呀，尺度似乎无法识别你，请通过尺度官方微信渠道进入本服务。')</script>");
        $script_insert = sprintf($script_insert, 'false', "", "''", "", 'false');
        //echo("<script>alert('呀，尺度似乎无法识别你，请通过尺度官方微信渠道进入本服务。')</script>");
        //exit();
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
        <title>球形 &middot; 尺度</title>
        <!-- Bootstrap -->
        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <link href="../../../css/helperapp.css" rel="stylesheet">
        <link href="../css/font-awesome.min.css" rel="stylesheet">
        <link rel="icon" type="image/png" sizes="32x32" href="../../img/favicon64.png">
        <?php echo $script_insert?>
        

    </head>
    <body>
        <nav class="navbar navbar-default">
          <div class="container-fluid">
            <div class="navbar-header">
              <a class="navbar-brand" href="../subscribe-qrcode.html">
                <img alt="尺度" width="20" src="../../img/chidu_original.png">
              </a>
              <p class="navbar-text navbar-right">
               <a class="text-muted" href="../subscribe-qrcode.html"> 尺度</a> / <a class="text-muted" href="./ballIntro.html"> 球形</a> / <?php echo $user_array['nickname'] ?> <strong><span id="sex-icon"></span></strong>
              </p>
            </div>
          </div>
        </nav>
        <div class="container" >
            <div id="main-div">
            <div class="text-center"><h5 id="doc-title">球形解读</h5><small><small><mark>若生日错误请重新算球</mark></small></small></div>
            <div class="top-alert">
                
            </div>
            <div class='head'>
            </div>
            
            <section id="ball-content-sec">
                <hr>
                <h5><?php echo $user_array['nickname'] ?>的解读 <small><small><mark>点击标签看详细内容</mark></small></small></h5>
                <div class="list-group" id="ball-content-list">
                </div>
            </section>
            <div class="bottom-alert">
                
            </div>
            </div>

            <div id="detail-div" style="display:none">
                <div class="panel panel-default">
                  <div class="panel-heading text-center">
                    <h4 class="panel-title"></h4>
                  </div>
                  <div class="panel-body">
                    <div class="content-detail">
                    </div>
                    <div class="content-btn text-center">
                    </div>
                  </div>
                </div>
                <br>
                <div class="text-center">
                <button id="go-back-to-main" class="btn btn-default"><i class='fa fa-angle-double-left'></i> 返回主界面</button>
                </div>
                <br><br>
            </div>

            <hr>

            <div class="text-center">
                <small class="text-danger">持续关注尺度，获取更多解读内容</small>
            </div>
             <div class='text-center'>
                <small><small>本测试仅能通过在尺度微信后台回复“算个球”进入，其他进入方式很可能无效。如果您看不到结果，请直接回复尺度或识别下方二维码关注尺度</small></small><br>
                      <img src="../../img/qrcode_chidu_1_8.jpg" width="172px">
              </div>
            <small class="text-muted">
                
                    <br>1. 如果你觉得自己没有结果里说的那么好，那么很可能你还没有运用好自己的天赋。持续关注我们，一起挖掘出自己的更多潜能。
                    <br><br>2. 因为结果偏隐私，本结果页分享微信朋友圈后将不显示您的解答。建议邀请朋友一起做并用于朋友间交流。
                <div class="text-center">
                    <br><br>
                    <small>为保护您的隐私，请仅在由尺度网站chidu4u.com提供的服务中使用您的生日。
                        <br>&#169; 2015 尺度 | <a class="text-muted" href="./ballIntro.html"> 球形说明</a>
                    </small>
                </div> 
            </small>
            <br>
        </div>
        </div>
        </div>
        <script src="../js/jquery-1.11.3.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
        <script src="../js/Chart.min.js"></script>
        <!-- Hey, Velocity come to the rescue. To Improve My Pathetic Out-of-Date jQuery-->
        <!-- Well alright it's not that bad, it was just slow on mobile-->
        <script src="../js/velocity.min.js"></script>
        <script src="./js/self-portalapp.js"></script>
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