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
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title><?php echo (urldecode($_GET['article'])); ?> &middot; 评论墙</title>

    <!-- Bootstrap -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/font-awesome.min.css" rel="stylesheet">
    <link href="./css/emoji.css" rel="stylesheet">
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
          <div class="row">
        <a id="sticky-subs-btn" href="./subscribe-qrcode.html" class="btn btn-default btn-sm pull-right" >关注尺度</a>
        <div>
          <div class="text-center text-muted">

            <h4><?php echo (urldecode($_GET['article'])); ?> &middot; 评论墙</h4>
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


    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="../js/jquery-1.11.3.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="../js/bootstrap.min.js"></script>
    <script>
      var article_str = <?php echo ("'".$_GET['article']."'"); ?>;
    </script>
    <script type="text/javascript" src="./js/jquery.sticky.js"></script>
    <script>
      $(document).ready(function(){
        $("#sticky-subs-btn").sticky({topSpacing:50});
      });
    </script>
    <script src="./js/commentwallapp.js"></script>
  </body>
</html>