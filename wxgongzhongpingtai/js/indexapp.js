/* Built by Jiaxin Lin for Chidu China
 
              *********
             * === === *
            L*  *   *  *J
             *    0    *
              *********
                 -X-
                 / \
*/
$(document).ready(function() {
});
$('#loginform').submit(function(event) {
    // Stop the browser from submitting the form.
    event.preventDefault();
    $.post("./php/adminLogin.php?func=verifyAndLogin", $('#loginform').serialize(), function(data) {
        //expect data as a json object, an array of rows (here it is only 1 row, the one that was added)
        //alert(data);
        data = JSON.parse(data);
        
        if(data['status']==1) {
        	alert("登陆成功，欢迎回来，"+data['username']);
        	window.location = "./homepage.php";
        }else {
        	alert("登录失败");
        }
        //$(createHtmlFromJSONForRuleRows(data)).hide().prependTo("#ruleslist").fadeIn();
    });
});