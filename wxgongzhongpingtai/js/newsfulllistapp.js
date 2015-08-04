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
	getNewsList(0,20);
});

/*
 * This is a freaking recursion? I don't know why I put it here to jam your browser.. I'm sorry...
 */
function getNewsList(offset,count) {
    $.getJSON("./php/getmediainfo.php?func=getNewsList&offset=" + offset+"&count="+count, function(data) {
    	var html = getNewsListHelper(data);
    	$(html).hide().appendTo('#newslist').fadeIn("slow");
        //$("#newslist").append(html);
        if(data['item_count']>0) {
        	getNewsList(offset+count,count);
        } else {
        	$("#loading").fadeOut();
        	return;
        }
    });
}

function getNewsListHelper(data) {
	getNewsListHelper.total_count =getNewsListHelper.total_count||1;
	var html="";
    	$.each(data['item'], function(i,item) {
    		var rowSpan = 0;
    		var firstRow = "";
    		var otherRows = "";
    		$.each(item['content']['news_item'], function(j,news_item){
    			itemUnique="<td>"+news_item['title']+"</td>"+"<td><a target='_blank' href='"+news_item['url']+"'> 点击</a></td>";
    			if(j == 0) {
    				firstRow = itemUnique;
    				
    			} else {
    				otherRows += "<tr>"+itemUnique + "</tr>";
    				
    			}
    			rowSpan += 1;
    		});
    		firstRow = "<tr><td rowspan='"+rowSpan+"'>"+getNewsListHelper.total_count+"</td>"+firstRow + "<td rowspan='"+rowSpan+"'>" + item['media_id'] + "</td></tr>";
    		getNewsListHelper.total_count += 1;
    		html += (firstRow + otherRows);
    	});
    	return html;
}