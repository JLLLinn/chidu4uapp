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
<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
if(isset($_SESSION['openid'])){
  $openid=$_SESSION['openid'];
  } else{
    echo("<script>alert('呀，尺度似乎不认识你，请通过尺度官方微信渠道进入本服务。')</script>");
    exit();
  }
?>
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
        <script>var openid="<?php echo(htmlentities($openid)); ?>"</script>
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
            <div id="select-birth" class="text-center">
              <h4>输入生日<h4>
              <hr width='40%' align='center'>
              <div class="input-group">
                  <div class="input-group-addon">年</div>
                  <select class="form-control" id="year-select">
                      <option value='1951'>1951</option>
                      <option value='1952'>1952</option>
                      <option value='1953'>1953</option>
                      <option value='1954'>1954</option>
                      <option value='1955'>1955</option>
                      <option value='1956'>1956</option>
                      <option value='1957'>1957</option>
                      <option value='1958'>1958</option>
                      <option value='1959'>1959</option>
                      <option value='1960'>1960</option>
                      <option value='1961'>1961</option>
                      <option value='1962'>1962</option>
                      <option value='1963'>1963</option>
                      <option value='1964'>1964</option>
                      <option value='1965'>1965</option>
                      <option value='1966'>1966</option>
                      <option value='1967'>1967</option>
                      <option value='1968'>1968</option>
                      <option value='1969'>1969</option>
                      <option value='1970'>1970</option>
                      <option value='1971'>1971</option>
                      <option value='1972'>1972</option>
                      <option value='1973'>1973</option>
                      <option value='1974'>1974</option>
                      <option value='1975'>1975</option>
                      <option value='1976'>1976</option>
                      <option value='1977'>1977</option>
                      <option value='1978'>1978</option>
                      <option value='1979'>1979</option>
                      <option value='1980'>1980</option>
                      <option value='1981'>1981</option>
                      <option value='1982'>1982</option>
                      <option value='1983'>1983</option>
                      <option value='1984'>1984</option>
                      <option value='1985'>1985</option>
                      <option value='1986'>1986</option>
                      <option value='1987'>1987</option>
                      <option value='1988'>1988</option>
                      <option value='1989'>1989</option>
                      <option value='1990'>1990</option>
                      <option value='1991'>1991</option>
                      <option value='1992' selected>1992</option>
                      <option value='1993'>1993</option>
                      <option value='1994'>1994</option>
                      <option value='1995'>1995</option>
                      <option value='1996'>1996</option>
                      <option value='1997'>1997</option>
                      <option value='1998'>1998</option>
                      <option value='1999'>1999</option>
                      <option value='2000'>2000</option>
                      <option value='2001'>2001</option>
                      <option value='2002'>2002</option>
                      <option value='2003'>2003</option>
                      <option value='2004'>2004</option>
                      <option value='2005'>2005</option>
                      <option value='2006'>2006</option>
                      <option value='2007'>2007</option>
                      <option value='2008'>2008</option>
                      <option value='2009'>2009</option>
                      <option value='2010'>2010</option>
                  </select>
              </div>
              <br>
              <div class="input-group">
                  <div class="input-group-addon">月</div>
                  <select class="form-control" id="month-select">
                      <option value='1'>1</option>
                      <option value='2'>2</option>
                      <option value='3'>3</option>
                      <option value='4'>4</option>
                      <option value='5'>5</option>
                      <option value='6'>6</option>
                      <option value='7'>7</option>
                      <option value='8'>8</option>
                      <option value='9'>9</option>
                      <option value='10'>10</option>
                      <option value='11'>11</option>
                      <option value='12'>12</option>
                  </select>
              </div>
              <br>
              <div class="input-group">
                  <div class="input-group-addon">日</div>
                  <select class="form-control" id="day-select">
                    <option value='1'>1</option>
                  </select>
              </div>
              <br>
              <div class="input-group">
                  <div class="input-group-addon">时</div>
                  <select class="form-control" id="hour-select">
                      <option value='0'>0</option>
                      <option value='1'>1</option>
                      <option value='2'>2</option>
                      <option value='3'>3</option>
                      <option value='4'>4</option>
                      <option value='5'>5</option>
                      <option value='6'>6</option>
                      <option value='7'>7</option>
                      <option value='8'>8</option>
                      <option value='9'>9</option>
                      <option value='10'>10</option>
                      <option value='11'>11</option>
                      <option value='12'>12</option>
                      <option value='13'>13</option>
                      <option value='14'>14</option>
                      <option value='15'>15</option>
                      <option value='16'>16</option>
                      <option value='17'>17</option>
                      <option value='18'>18</option>
                      <option value='19'>19</option>
                      <option value='20'>20</option>
                      <option value='21'>21</option>
                      <option value='22'>22</option>
                      <option value='23'>23</option>
                  </select>
              </div>
              <br>
              <div class="text-center">
                <button id="submit-single-birthday" class="btn btn-default">确定</button>
              </div>
              <br><br>
              <small class="text-info">请先输入月份再输入日期，日期会根据月份自动调整。<br>时间输入小时单位上的数字，无需取整。如：晚上22:59分出生，则填写22点。</small>
            </div>
            <br><br>
            <div class="text-center"><small class="text-muted"><small>小编提醒：为保护您的隐私，请仅在由尺度网站chidu4u.com提供的服务中使用您的生日<br> &#169; 2015 尺度 | <a class="text-muted" href="./ballIntro.html"> 球形说明</a></small> </small></div><br>
        </div>
        </div>
        </div>
        <script src="../js/jquery-1.11.3.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
        <script src="./js/getbirthdayapp.js"></script>
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