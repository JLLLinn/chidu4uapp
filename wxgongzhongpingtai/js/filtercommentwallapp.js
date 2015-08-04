var MODE_NORMAL = 0;
var MODE_SEARCH = 1;
var MODE_LIST_SELECTED_COMMENT = 2;
var mode = 0;
var user_info = {};
var user_ids=[];
//var endTime;
var REFRESH_INTERVAL = 5000; // in millisec
var TITLE = document.title;
var LOAD_SIZE = 20;
var INCOME_MSG = 1;
var OUTGO_MSG = 0;
var new_msg_count = 0;
var IMAGE_SIZE = "96";
var MY_NAME = "尺度";
var cur_page = 1;
var endTimeArr = [];
var TYPE_MATCH = {
    "text":"文字",
    "news":"图文",
 };
var COMMENT_WALL_URL_PREFIX="http://www.chidu4u.com/commentwall/?article=";
var search_input_text = "";
var searchTimeOut;


var article_str="";
var selected_comment_info_list=[];
var deleteCommentIds4Article=[];
var addCommentIds4Article=[];
$(function() {
    initendTime = Math.floor(Date.now() / 1000);
    getMessage.curEndTime = initendTime;

    getMessage(LOAD_SIZE, initendTime, 'text', 0);
    getNDisplayArticleList();

    setInterval(function(){
    	var curTime= Math.floor(Date.now()/1000);
    	countNAlertNewMsg(initendTime, curTime, 'text', 0, true);
 	}, REFRESH_INTERVAL);
	
});
function getNDisplayArticleList(){
    $.getJSON("./php/articleCommentUtilOncall.php?func=getArticleList",function(data){
        var $select = $("#article-list-select");
        var html = "";
        $.each(data,function(index, article_name){
            html += "<option value="+article_name+">"+article_name+"</option>";
        });
        $select.append(html);
    });
}

$("#article-list-select").on('change', function(e){
    $("input#article-name").val($(this).val());
});

$("#send_add_delete_msg_ids").on('click', function(e){
     var obj = {
        "article_str" : article_str,
        "add_id_arr" : JSON.stringify(addCommentIds4Article),
        "delete_id_arr": JSON.stringify(deleteCommentIds4Article),
    };
    $("#loading-modal").modal('show');
    $.post("./php/articleCommentUtilOncall.php?func=addDeleteMsgIdsByArticles",obj, function(data){
        location.reload();
    });
});


$("#generate-url-btn").on('click',function(e){
    selected_comment_info_list={};
    addCommentIds4Article=[];
    deleteCommentIds4Article=[];
    article_str="";
    $("#send_add_delete_msg_ids").prop("disabled",true);
    $("#list-selected-comment").prop("disabled",true);
    var str = $('#article-name:first').val();
    $.post("./php/articleCommentUtilOncall.php?func=generateUrlComponent", {'str':str}, function(data){
        article_str=data;
        $('#generated-url').val(COMMENT_WALL_URL_PREFIX+data);
        $.when(getMsgIdsByArticleIdStrNDisplay(article_str)).done(function(e){
            $("#send_add_delete_msg_ids").removeProp("disabled");
            $("#list-selected-comment").removeProp("disabled");
        });
        
    });
});

function getMsgIdsByArticleIdStrNDisplay(article_str){
    return $.post("./php/articleCommentUtilOncall.php?func=getMsgIdsByArticleIdStr",{"article_str":article_str},function(data){
        selected_comment_info_list=data;
        displayCommentInfo(selected_comment_info_list);
    },'json');
}

function displayCommentInfo(selected_comment_info_list){
    $("td.comment_select").each(function(i,td){
        var $td = $(td);
        var commentId = $td.data('msg-id');

        if(commentId in selected_comment_info_list){
            var reply_button_html="<button data-msg-id="+commentId+" class='btn btn-default official-reply-btn'>回复</button>";
            if(deleteCommentIds4Article.indexOf(commentId) != -1){
                $td.html("<i class='fa fa-square-o comment-select-box selected'></i> "+selected_comment_info_list[$td.data('msg-id')]["thumb_ups"]+" <i class='fa fa-thumbs-up'></i>"+reply_button_html);
            } else{
                $td.html("<i class='fa fa-check-square-o comment-select-box selected'></i> "+selected_comment_info_list[$td.data('msg-id')]["thumb_ups"]+" <i class='fa fa-thumbs-up'></i>"+reply_button_html);
            }
        } else{
             if(addCommentIds4Article.indexOf(commentId) != -1){
                $td.html("<i class='fa fa-check-square-o comment-select-box unselected'></i>");
            } else{
                $td.html("<i class='fa fa-square-o comment-select-box unselected'></i>");
            }

        }
    });
}

$(document).on('click',".comment-select-box",function(e){
    var commentId = $(this).parent(".comment_select").data('msg-id');
    if($(this).hasClass("selected")){
        //this comment was originally selected
        if($(this).hasClass("fa-check-square-o")){
            //this is currelty selected, add to delete list
            deleteCommentIds4Article.push(commentId);
        } else{
            //this is currently not selected
            deleteCommentIds4Article.splice(deleteCommentIds4Article.indexOf(commentId),1);
        }
    } else{
        //this was originally unselected for this article
        if($(this).hasClass("fa-square-o")){
            //this is currelty unselected, add to add list
            addCommentIds4Article.push(commentId);
        } else{
            //this is currently selected，delete from add list
            addCommentIds4Article.splice(addCommentIds4Article.indexOf(commentId),1);
        }
    }
    $(this).toggleClass("fa-check-square-o fa-square-o");
});





$("#search-input").on('input',function(){
    clearTimeout(searchTimeOut);
    var $input = $(this);
      searchTimeOut = setTimeout(function() {
        search_input_text = $input.val();
        if(search_input_text === ""){
            reset_mode(MODE_NORMAL);
            getMessage.curEndTime = initendTime;
            getMessage(LOAD_SIZE, initendTime, 'text', 0);
        } else{
            reset_mode(MODE_SEARCH);
            searchAndPopulateResult.curEndTime = initendTime;
            searchAndPopulateResult(LOAD_SIZE, initendTime,search_input_text,0);
        }
        
      }, 1000);
});

$("#list-selected-comment").on('click',function(e){
    var LIST_ONLY_COMMENT_4_ARTICLE = 0;
    var LIST_ALL_COMMENT = 1;
    if($(this).data("mode") === LIST_ONLY_COMMENT_4_ARTICLE){
        $(this).prop("disabled",true);
        reset_mode(MODE_NORMAL);
        getMessage.curEndTime = initendTime;
        var $list_selected_comment = $(this);
        $.when(getMessage(LOAD_SIZE, initendTime, 'text', 0)).done(function(e){
            $list_selected_comment.removeProp("disabled");
            $list_selected_comment.text('只看当前文章选定评论');
        });
        $(this).data("mode",LIST_ALL_COMMENT);
    }else if($(this).data("mode") === LIST_ALL_COMMENT){
        $(this).prop("disabled",true);
        reset_mode(MODE_LIST_SELECTED_COMMENT);
        listAllSelectedComment4Article.curEndTime = initendTime;
        var $list_selected_comment = $(this);
        $.when(listAllSelectedComment4Article(LOAD_SIZE, initendTime,0)).done(function(e){
            $list_selected_comment.removeProp("disabled");
            $list_selected_comment.text('返回');
        });
        $(this).data("mode",LIST_ONLY_COMMENT_4_ARTICLE);
        
    } else{
        console.log("Button Mode Unreconigized");
    }
    
});

function reset_mode(newmode){
    mode=newmode;
    cur_page = 1;
    user_info = {};
    user_ids=[];
    cur_page = 1;
    endTimeArr = [];
    $('b.page-number').text(cur_page);
}
function getCurrentSelectedCommentIds(){
    var keyArray = Object.keys(selected_comment_info_list);
    return keyArray.concat(addCommentIds4Article);
}

function listAllSelectedComment4Article(limitSize, endTime,showAutoReplied){
    var idList=getCurrentSelectedCommentIds();
    //console.log(idList);
    var obj = {
        'limitSize': limitSize,
        'endTime': endTime,
        'idList':JSON.stringify(idList),
        'showAutoReplied':showAutoReplied
    };
    return $.post('./php/msgUtilOncall.php?func=getMsgByIdListBySize', obj, function(data){
        displayTextMsgToTable(data, false);
    },'json');
}

function searchAndPopulateResult(limitSize, endTime,keyword,showAutoReplied){
    var obj = {
        'limitSize': limitSize,
        'endTime': endTime,
        'keyword':keyword,
        'showAutoReplied':showAutoReplied
    };
    $.post('./php/msgUtilOncall.php?func=searchIncomeTextMessageKeywordBySize', obj, function(data){
        console.log(data);
        displayTextMsgToTable(data, false);
    },'json');
}

function countNAlertNewMsg(StartTime, endTime, type, showAutoReplied) {
	$.get("./php/msgUtilOncall.php?func=getIncomeMessageCount&startTime=" + StartTime + "&endTime=" + endTime + "&type=" + type + "&showAutoReplied=" + showAutoReplied, function(data) {
        //console.log(data);
        var count = parseInt(data);
        if(count>new_msg_count){
        	//console.log(data);
        	document.title="("+count+") "+TITLE;
        	$alert = $('div#new_message_alert');
        	$alert.find("span.new-msg-count").text(count);
        	$alert.show();
        	new_msg_count = count;
        }
    });
}



/*
 * showAutoReplied: True if want to show those  message that are auto replied, false if not
 * redisplayUserInfo: True if it is newly refreshed and needed to refresh
 */
function getMessage(loadSize, endTime, type, showAutoReplied) {
    $.getJSON("./php/msgUtilOncall.php?func=getIncomeMessageBySize&limitSize=" + loadSize + "&endTime=" + endTime + "&type=" + type + "&showAutoReplied=" + showAutoReplied, function(data) {
        if (type.localeCompare('text') == 0) {
            displayTextMsgToTable(data, true);
        }
    });
}

function displayTextMsgToTable(data, saveEndTime2GetMessage) {
    var $msglist = $('#msglist');
    $msglist.html("");
    var loadSize = data.length;
    $.each(data, function(i, row) {
        var html = $("<tr>  \
                <td data-msg-id='" + row['id'] + "'><small>"                           + row['CreateTimeStr']                          + "</small></td> \
                <td id='" + row['FromUserName'] + "' class='nickname'>"         + "<i class='fa fa-refresh fa-spin fa-lg'></i>" + "</td> \
                <td id='" + row['FromUserName'] + "' class='headimg hidden-xs'>"          + "<i class='fa fa-refresh fa-spin fa-lg'></i>" + "</td> \
                <td id='" + row['FromUserName'] + "' class='subscribetimestr hidden-xs'>" + "<i class='fa fa-refresh fa-spin fa-lg'></i>" + "</td> \
                <td>"                                                           + row['Content']                                + "</td> \
                <td data-msg-id='" + row['id']  + "' class=comment_select>"     + "<i class='fa fa-refresh fa-spin fa-lg'></i>" + "</td> \
                </tr>");

        $msglist.append(html);
        //initiate the item for user_info
        user_info[row['FromUserName']]={};
        //user_ids.push(row['FromUserName']);//load all at once
        
        if (i == loadSize - 1) {
            if(mode==MODE_NORMAL){
                getMessage.nextEndTime = row['CreateTime'];
            }else if(mode == MODE_SEARCH){
                searchAndPopulateResult.nextEndTime = row['CreateTime'];
            }else if(mode==MODE_LIST_SELECTED_COMMENT){
                listAllSelectedComment4Article.nextEndTime=row['CreateTime'];
            }else {
                console.error("Current Mode Not declared");
            }
        }
    });
    displayCommentInfo(selected_comment_info_list);
	 $.each(user_info,function(id, empty){
	 	$.getJSON("./php/userUtil.php?func=getAndSaveUserInfoOpenID&openID=" + id, function(data) {
            if(data['subscribe']==1){
                data['headimgurl'] = data['headimgurl'].slice(0, -1) + IMAGE_SIZE; //change image quality
                //data['nickname'] = emojione.toImage(data['nickname']);//using the php library now
                user_info[data['openid']] = data;
                $msglist.find('.subscribetimestr#'+id).html(data['subscribe_time_str']);
                $msglist.find('.nickname#'+id).html("<small>"+data['nickname']+"</small>");
                $msglist.find('.headimg#'+id).html("<img src='" + data['headimgurl'] + "' width='46'>");
            }else{
                $msglist.find('.subscribetimestr#'+id).html('<b>未关注</b>');
                $msglist.find('.nickname#'+id).html("<small>"+'<b>未关注</b>'+"<small>");
                $msglist.find('.headimg#'+id).html("<b>未关注</b>");
            }
        });

	 });
}



$('a.next-page').click(function(ev){
	user_info = {};
	user_ids=[];
    if(mode == MODE_NORMAL){
        endTimeArr.push(getMessage.curEndTime);
        getMessage.curEndTime = getMessage.nextEndTime;
        getMessage(LOAD_SIZE, getMessage.curEndTime, 'text', 0);
    }else if(mode==MODE_SEARCH){
        endTimeArr.push(searchAndPopulateResult.curEndTime);
        searchAndPopulateResult.curEndTime = searchAndPopulateResult.nextEndTime;
        searchAndPopulateResult(LOAD_SIZE, searchAndPopulateResult.curEndTime,search_input_text,0);
    }else if(mode==MODE_LIST_SELECTED_COMMENT){
        endTimeArr.push(listAllSelectedComment4Article.curEndTime);
        listAllSelectedComment4Article.curEndTime = listAllSelectedComment4Article.nextEndTime;
        listAllSelectedComment4Article(LOAD_SIZE,listAllSelectedComment4Article.curEndTime,0);
    }else{
        console.log("Current Mode Undeclared");
    }
	
	cur_page = ++cur_page;
	if(cur_page > 1){
		$('a.prev-page').removeAttr('disabled');
	}
	$('b.page-number').text(cur_page);

});

$('a.prev-page').click(function(ev){
	user_info = {};
	user_ids=[];

	var curEndTime = endTimeArr.pop();
    if(mode == MODE_NORMAL){
        getMessage(LOAD_SIZE, curEndTime, 'text', 0);
    }else if(mode==MODE_SEARCH){
        searchAndPopulateResult(LOAD_SIZE, curEndTime,search_input_text,0);
    }else if(mode==MODE_LIST_SELECTED_COMMENT){
        listAllSelectedComment4Article(LOAD_SIZE, curEndTime, 0);
    }else{
        console.log("Current Mode Undeclared");
    }
	
	cur_page = --cur_page;
	if(cur_page <= 1){
		$(this).attr('disabled',true);
	}
	$('b.page-number').text(cur_page);
});

$('div#new_message_alert').on('click', '.close',function(e){
	$alert = $(e.delegateTarget);
	$alert.hide();
});

$('div#new_message_alert').on('click', 'a.new-msg-reload-page',function(e){
	location.reload();
});

$("#set-return2article-url").on('click', function(e){

    var $set_article_url_modal = $('#set-article-url-modal');
    $set_article_url_modal.modal('show');
});

$("#set-article-url-modal").on('click',"#show-article-url", function(e){
    e.preventDefault();
    //console.log("clicked");
    var $set_article_url_modal = $(e.delegateTarget);
    var article_string = $set_article_url_modal.find("#article_str:first").val();
    //console.log(article_string);
    $.post("./php/articleCommentUtilOncall.php?func=getArticleUrl", {"article_str":article_string}, function(data){
        $set_article_url_modal.find("#url:first").val(data);
        alert("完成，url显示在下框中");
        
        //console.log(data);
    });
});

$("#set-article-url-modal").on('click',"#submit-article-url-form", function(e){
    e.preventDefault();
    console.log("submitted");
    var $data = $(e.delegateTarget).find("#article-url-form").serialize();
    $.post("./php/articleCommentUtilOncall.php?func=setArticleUrl", $data, function(data){
        if(data==1){
            alert("设置成功");
        } else {
            alert("设置可能失败，请重新加载url试试");
        }
    });
});

$(document).on('click',".official-reply-btn", function(e){
    var obj={
        'article_str' : article_str,
        'msg_incoming_id': $(this).data("msg-id")
    }
    $.post("./php/articleCommentUtilOncall.php?func=getCommentOfficialResponse", obj,function(data){
        $("#official-reply-current-response:first").html(data);
        console.log(data);
    });
    var $official_reply_modal = $("#official-reply-modal");
    $official_reply_modal.data("msg-id",$(this).data("msg-id"));
    $official_reply_modal.modal('show');
});

$("#official-reply-modal").on('click',"#official-reply-submit", function(e){
    e.preventDefault();
    console.log("submitted");
    var $modal = $(e.delegateTarget);
    var $response_textarea = $modal.find("#official-reply-response");
    var reponse_string = $response_textarea.val();
    var obj={
        'article_str' : article_str,
        'msg_incoming_id': $modal.data("msg-id"),
        'reponse_string':reponse_string
    }

    $.post("./php/articleCommentUtilOncall.php?func=setCommentOfficialResponse", obj, function(data){
        if(data==1){
            alert("回复成功");
        } else {
            alert("回复可能失败，重新点击回复查看当前回复");
        }
    });
    $response_textarea.val("");
    $modal.modal('hide');
});


