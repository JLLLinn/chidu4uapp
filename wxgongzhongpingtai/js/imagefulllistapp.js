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
	getImageList(0,20);
});

/*
 * This is a freaking recursion? I don't know why I put it here to jam your browser.. I'm sorry...
 */
function getImageList(offset,count) {
    $.getJSON("./php/getmediainfo.php?func=getImageList&offset=" + offset+"&count="+count, function(data) {
    	var html = getImageListHelper(data);
    	$(html).hide().appendTo('#imagelist').fadeIn("slow");
        //$("#imagelist").append(html);
        if(data['item_count']>0) {
        	getImageList(offset+count,count);
        } else {
        	$("#loading").fadeOut();
        	return;
        }
    });
}

function getImageListHelper(data) {
	getImageListHelper.total_count =getImageListHelper.total_count||1;
	var html="";
    	$.each(data['item'], function(i,item) {
    		html+="<tr><td>"+getImageListHelper.total_count+"</td><td>"+item['name'] + "</td><td><a class='openmodal' href='" + item['url'] + "'>预览</a></td><td>" + item['update_time'] + "</td><td>" + item['media_id']+"</td></tr>";
    		getImageListHelper.total_count += 1;
    	});
    	return html;
}

$(document).on("click", ".openmodal", function(event) {
	event.preventDefault();
	//var html = "<img class='img-responsive' src='" + $(this).attr('href') + "'></img>";
	//$('#imagemodalbody').html(html);
	
	$('#previewimage').attr('src',$(this).attr('href'));
	$('#downloadimage').attr('href',$(this).attr('href'));
	
	$('#imagemodal').modal('show')
});