<?php
  defined("COMMENTWALLHTMLPATH") or define("COMMENTWALLHTMLPATH", "/home/chidsjwb/public_html/commentwall/");
  require_once(COMMENTWALLHTMLPATH.'php/commentUtil.php');
  require_once(COMMENTWALLHTMLPATH.'DBvar.php');
  $conn = new mysqli(DBSERVERNAME, DBUSERNAME, DBPASSWORD,DBNAME);
  if ($conn ->connect_errno) {
      error_log("Connect failed: %s\n", $conn ->connect_error);
  }else {
    mysqli_set_charset ( $conn , 'utf8mb4' );
  }
  $article_url = CommentUtil::getArticleUrl(urldecode($_GET['article']), $conn);
  
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
        <!-- baidu analytics-->
    <script>
      var _hmt = _hmt || [];
      (function() {
        var hm = document.createElement("script");
        hm.src = "//hm.baidu.com/hm.js?b12cf8d996bb7b02d2cbd0f968f1b24e";
        var s = document.getElementsByTagName("script")[0]; 
        s.parentNode.insertBefore(hm, s);
      })();
      </script>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo (urldecode($_GET['article'])); ?> &middot; 评论墙</title>

    <!-- GUESS WHAT, I am using bootstrap. Thank you Twitter. -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/font-awesome.min.css" rel="stylesheet">
    <!--<link href="./css/emoji.css" rel="stylesheet">
          Currently not converting emoji-->
    <link href="../css/helperapp.css" rel="stylesheet">
    <link href="./css/commentwallapp.css" rel="stylesheet">
    <link rel="icon" type="image/png" sizes="32x32" href="../img/favicon64.png">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>

        <div class="container-fluid">

          <div class="text-center text-muted">
            <h4><?php echo (urldecode($_GET['article'])); ?>  </h4>
            <h5><a href="./subscribe-qrcode.html">尺度</a> &middot; 评论墙</h5>

            <? 
            if(strcmp($article_url,"") != 0){
              $ret_to_article_btn = "<a class='btn btn-default btn-sm' href='%s'>返回文章</a>";
              $ret_to_article_btn = sprintf($ret_to_article_btn, $article_url);
              echo($ret_to_article_btn);
            }
            ?>
            
            <hr>
          </div>

          <div id="comments-body">

          </div>
          <div id="loading-div" class="text-center bottom-buffer ">
            <i class="fa fa-spinner fa-spin"></i>
          </div>
          
          


        </div>


    <script src="../js/jquery-1.11.3.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script>
      var article_str = <?php echo ("'".$_GET['article']."'"); ?>;
    </script>
    <script src="./js/commentwallapp.js"></script>
  </body>
</html>