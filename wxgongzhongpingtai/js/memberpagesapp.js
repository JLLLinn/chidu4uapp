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
    $("#logoutBtn").click(function(){
    	//alert("clicked");
    	$.get("./php/adminLogin.php?func=logoutCurrentUser", function(data) {
	        window.location.href="index.php";
	    });
    	
    
    });
}); 