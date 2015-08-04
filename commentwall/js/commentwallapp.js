/*Built by Jiaxin Lin for Chidu China
 
              *********
             * === === *
            L*  *   *  *J
             *    0    *
              *********
                 -X-
                 / \
You found this? Good For You. See, above's my portrait.
Contact me at jiaxin.lin@chidu4u.com if you really want to for some reason.
*/
var loadMoreTimeOut;
var IMAGE_SIZE=96;
var LOAD_LIMIT_SIZE = 15;
var THUMBUP = 1;
var THUMBDWN = 0;
var loading = false;
$(function(){

	generateCommentHtmlWUserInfo.offset = 0
	generateCommentHtmlWUserInfo(LOAD_LIMIT_SIZE, generateCommentHtmlWUserInfo.offset, article_str);

	$(window).scroll(function() {   
		clearTimeout(loadMoreTimeOut);
		loadMoreTimeOut = setTimeout(function() {
			if($(window).scrollTop() + $(window).height() == $(document).height()) {
			       if(generateCommentHtmlWUserInfo.count == LOAD_LIMIT_SIZE && !loading){
			       		generateCommentHtmlWUserInfo(LOAD_LIMIT_SIZE, generateCommentHtmlWUserInfo.offset, article_str);
			       } else if(generateCommentHtmlWUserInfo.count < LOAD_LIMIT_SIZE){
			       		//loading = false;
			       		$('#loading-div:first').hide();
			       }
			   }
		}, 200)
	});

	//console.log("article_str: "+article_str);
	
	

	   
});

function setThumbsOnClick(curhtml){
	var currentCount = parseInt($(curhtml).prev(".thumb-count-num").text());
	var upOrDown = 0;
	if($(curhtml).hasClass("fa-thumbs-o-up")){
		//this is now adding thumb
		upOrDown = THUMBUP;
		$(curhtml).prev(".thumb-count-num").text(currentCount + 1);
	} else {
		upOrDown = THUMBDWN;
		$(curhtml).prev(".thumb-count-num").text(currentCount - 1);
	}
	$(curhtml).toggleClass("fa-thumbs-o-up fa-thumbs-up");
	var obj={
		'up_down':upOrDown,
		'msg_incoming_id' : $(curhtml).data("msg-id"),
		'article_str' : article_str,
	}
	$.post("./php/commentUtilOncall.php?func=thumbUpDown", obj, function(data){
		console.log(data);
	});
}

function generateCommentHtmlWUserInfo(limit_size, offset, article_string){
	loading = true;
	var obj={
		'limit_size':limit_size,
		'offset' : offset,
		'article_str' : article_string,
	}
	$.post("./php/commentUtilOncall.php?func=generateCommentHtml4ArticleBySize", obj, function(data){
		var $commentHtml = $(data['user_infos']);
		var userArray= JSON.stringify(data['user_ids']);
		generateCommentHtmlWUserInfo.offset = (generateCommentHtmlWUserInfo.offset+data['count']);
		//console.log("data count"+data['count']);
		//console.log("next offset"+generateCommentHtmlWUserInfo.offset);
		generateCommentHtmlWUserInfo.count = data['count'];
		$.post("./php/userUtilOncall.php?func=getBatchUserInformation", {"userids":userArray},function(user_info_list){
			//console.log(user_info_list);

			$commentHtml.hide().appendTo("#comments-body:first").fadeIn(1000);
			if("errcode" in user_info_list){
				$('#loading-div:first').hide();
			}else{
				$.each(user_info_list,function(openid, info){
					info['headimgurl'] = info['headimgurl'].slice(0, -1) + IMAGE_SIZE;
					$commentHtml.find(".comment-user-name#"+openid).html(info['nickname']);
					$commentHtml.find(".comment-headimg#"+openid).attr("src", info['headimgurl']);
				});
			}
			
			loading = false;
		},'json');
	},'json');
}


