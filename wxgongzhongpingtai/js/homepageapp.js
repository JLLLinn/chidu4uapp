/* Built by Jiaxin Lin for Chidu China
 
              *********
             * === === *
            L*  *   *  *J
             *    0    *
              *********
                 -X-
                 / \
*/
/**
 * Create by Jiaxin Lin for Chidu China
 * You Found This?? Adorable!
 */
$(document).ready(function() {
	getItemCounts();
});

function getItemCounts() {
    $.getJSON("./php/getmediainfo.php?func=getTotalCount", function(data) {
    	$("#newsfulllistbadge").html(data["news_count"]);
    	$("#imagefulllistbadge").html(data["image_count"]);
    });
}
