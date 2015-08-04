<?php
    session_start();
	header('Content-Type: text/html; charset=utf-8');
	defined("PERSONALCONSULTINGHTMLPATH") or define("PERSONALCONSULTINGHTMLPATH", "/home/chidsjwb/public_html/personalconsulting/");
	defined("GETACCESSTOKENPATH") or define("GETACCESSTOKENPATH", "/home/chidsjwb/wxgongzhongpingtai/getAccessToken.php");
	require_once(PERSONALCONSULTINGHTMLPATH.'php/DBvar.php');
	$conn = new mysqli(DBSERVERNAME, DBUSERNAME, DBPASSWORD,DBNAME);
	if ($conn ->connect_errno) {
	    trigger_error("Connect failed: %s\n", $conn ->connect_error);
	}else {
		mysqli_set_charset ( $conn , 'utf8mb4' );
	}
	require_once(GETACCESSTOKENPATH);
	require_once(PERSONALCONSULTINGHTMLPATH."ball/php/ballUserUtil.php");//Since I am sure this file will not be required, it is safe to use relative path
	if(isset($_SESSION['openid'])){
		if(isset($_SESSION['birthdayTimestamp'])){
				if(!$user_array = BallUserUtil::getNSaveUserInformationWithBirthday($_SESSION['openid'],AccessToken::getAccessToken(), $_SESSION['birthdayTimestamp'],  $conn)){
					echo("<script>alert('呀，尺度似乎不能识别你的生日，请通过尺度官方微信渠道进入本服务。')</script>");
					exit();
				}
				//now resolving the user_array, extacting only useful information for the javascript
				if(isset($user_array['errcode'])){
					echo("<script>alert('呀，尺度似乎不认识你，请通过尺度官方微信渠道进入本服务。')</script>");
					exit();
				} else if($user_array['subscribe'] == 0){
					echo("<script>alert('讨厌，要关注尺度微信才能体验我的服务啦~')</script>");
					exit();
				}
				$user_array_json=addslashes(json_encode(array(
					'nickname'=> $user_array['nickname'],
					'sex' => $user_array['sex'],
					),JSON_UNESCAPED_UNICODE ));

		}else{
			echo("<script>alert('呀，尺度似乎不知道你的生日，请通过尺度官方微信渠道进入本服务。')</script>");
			exit();
		}
	} else{
		echo("<script>alert('呀，尺度似乎不认识你，请通过尺度官方微信渠道进入本服务。')</script>");
		exit();
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
        <link href="../../../css/helperapp.css" rel="stylesheet">
        <link href="../css/font-awesome.min.css" rel="stylesheet">
        <link rel="icon" type="image/png" sizes="32x32" href="../../img/favicon64.png">
        <script>var user_info = JSON.parse('<?php echo ($user_array_json) ?>')</script>
        <script>var birthdayDate = new Date(<?php echo ($_SESSION['birthdayTimestamp']) ?> * 1000);</script>
        <script>var birthdayTimestamp = <?php echo ($_SESSION['birthdayTimestamp']) ?>;</script>

    </head>
    <body>
        <nav class="navbar navbar-default">
          <div class="container-fluid">
            <div class="navbar-header">
              <a class="navbar-brand" href="../subscribe-qrcode.html">
                <img alt="尺度" width="20" src="../../img/chidu_original.png">
              </a>
              <p class="navbar-text navbar-right">
               <a class="text-muted" href="../subscribe-qrcode.html"> 尺度</a> / <a class="text-muted" href="./ballIntro.html"> 球形</a> / 算个球 / <?php echo $user_array['nickname'] ?> <strong><span id="sex-icon"></span></strong>
              </p>
            </div>
          </div>
        </nav>
        <div class="container" >
            <div class="top-alert">
            </div>
            <div class="text-center bg-warning"><small>
            本次解读将自动识别你的微信账号的性别，为确保结果准确性，<span class="text-danger">请确认您在微信中设置的性别是否有误</span>
            </small></div>
            <div class='head'>
                <section id="chart-sec">
                    <div class="birth-chart text-center">
                    	<canvas id="myChart" width="275" height="275"></canvas>
                    </div>
                </section>

                

                <div class="text-center">
                    
                    <hr width='40%' align="center">
                    <a id="scroll-to-strength-sec" class="btn btn-circle btn-default"><i class="fa fa-angle-double-down  fa-1x"></i></a>
                    <br><small class="text-muted">下拉或点击查看详细解答</small>
                </div>
                <br>
                <br>
            </div>
            
            <section id="strength-sec">
                <hr>
                <h4>情感天赋 <small><small><mark>点击标签看详细内容</mark></small></small></h4>
                <div class="list-group" id="strength_list">
                </div>
            </section>
            
            <section id="weakness-sec">
                <hr>
                <h4>情感软肋 <small><small><mark>点击标签看详细内容</mark></small></small></h4>
                <div class="list-group" id="weakness_list">
                </div>
            </section>
            
            <div class="text-center"><a href="./self-portal.php" class="btn btn-default"><i class="fa fa-angle-double-left"></i> 返回我的解读</a></div>
            <br><br>
            <div class="text-center">
                <small class="text-danger">持续关注尺度，获取更多解读内容</small>
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
        <script src="./js/firststageresult-singlebirthdayapp.js"></script>
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