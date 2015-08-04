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

var MODE_NORMAL = 0;
var MODE_SEARCH = 1;
var mode = 0;
var user_info = {};
var user_ids=[];
//var endTime;
var REFRESH_INTERVAL = 5000; // in millisec
var MAX_TIME_RANGE = 86400; //in second
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
var CHIDU_HEAD_URL="favicon64.png";
var search_input_text = "";
var searchTimeOut;
//var EMOJI_LIST="😄 😃 😀 😊 😉 😍 😘 😚 😗 😙 😜 😝 😛 😳 😁 😔 😌 😒 😞 😣 😢 😂 😭 😪 😥 😰 😅 😓 😩 😫 😨 😱 😠 😡 😤 😖 😆 😋 😷 😎 😴 😵 😲 😟 😦 😧 😈 👿 😮 😬 😐 😕 😯 😶 😇 😏 😑 👲 👳 👮 👷 💂 👶 👦 👧 👨 👩 👴 👵 👱 👼 👸";
$(function() {
    //setting up emojione
    /*emojione.imageType = 'png';
    emojione.ascii = true;
    emojione.imagePathPNG = './emojione/assets/png/';*/

    initendTime = Math.floor(Date.now() / 1000);
    getMessage.curEndTime = initendTime;
    //var initStartTime = endTime-MAX_TIME_RANGE;

    getMessage(LOAD_SIZE, initendTime, 'text', 0);
    initEmojiPopover();
    /*constructEmojiBtnGroup(EMOJI_LIST);
    $('#emojiPopover').popover({
        'container': '#reply-message-modal',
        'content':html,
        'html':true,
    });*/


    setInterval(function(){
    	var curTime= Math.floor(Date.now()/1000);
    	countNAlertNewMsg(initendTime, curTime, 'text', 0, true);
 	}, REFRESH_INTERVAL);
	
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

function reset_mode(newmode){
    mode=newmode;
    cur_page = 1;
    user_info = {};
    user_ids=[];
    cur_page = 1;
    endTimeArr = [];
    $('b.page-number').text(cur_page);
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

function initEmojiPopover(){
    $.get("./php/emojiUtilOncall.php?func=getEmojiBtnListHtml", function(data){
        var html = "<div class='btn-group'>";
        html += data;
        html += "</div>";
        $('#emojiPopover').popover({
            'container': '#reply-message-modal',
            'content':html,
            'html':true,
        });
        console.log("Emoji Popover initiated");
    },'html');
}

function constructEmojiBtnGroup(emojiliststr){
    html = "<div class='btn-group'>";
    var emojilist = emojiliststr.split(" ");
    $.each(emojilist,function(i,emoji){
        html+="<button class='emoji-insert-btn btn btn-default btn-xs'>"+emoji+"</button>";
    });
    html += "</div>";
    return html;
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


function getUserBallCategory(openid, html){
    $.post("../personalconsulting/php/userUtilOncall.php?func=getUserInfoFromDB",{"openid":openid},function(data){
        
        if(data){
            $.getJSON("../personalconsulting/ball/php/ballUtilOncall.php?func=calcBall&birthdayTimestamp="+data['birth_timestamp']+"&sex="+data['sex'],function(coor){
                console.log(coor);
                if(coor){
                    html.text(coor['category']);
                }else{
                    html.text("无法获取");
                }
            })
        } else {
            html.text("数据库中无");
        }
    },'json');
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
        var repliedBtn="<i class='fa fa-reply'></i>";
        if(row["replied_msg_id"] != 0){
            repliedBtn="<i class='fa fa-paper-plane'></i>";
        }
        var operationHtml = "<div class='btn-group'><button data-msg-id="+row['id']+" data-user-id='" + row['FromUserName'] + "' type='button' class='open-reply-modal-btn btn btn-default' aria-label='回复'>"+repliedBtn+" <span class='hidden-xs'>RE<span></button></div>";
        var html = $("<tr>  <td data-msg-id='" + row['id'] + "'><small>" + row['CreateTimeStr'] + "</small></td> <td id='" + row['FromUserName'] + "' class='nickname'>" + "<i class='fa fa-refresh fa-spin fa-lg'></i>" + "</td> <td id='" + row['FromUserName'] + "' class='headimg hidden-xs'>" + "<i class='fa fa-refresh fa-spin fa-lg'></i>" + "</td> <td id='" + row['FromUserName'] + "' class='subscribetimestr hidden-xs'>" + "<i class='fa fa-refresh fa-spin fa-lg'></i>" + "</td> <td>" + row['Content'] + "</td> <td>" + operationHtml + "</td> <td id='" + row['FromUserName'] + "' class='ball-category'></td></tr>");

        $msglist.append(html);
        //initiate the item for user_info
        user_info[row['FromUserName']]={};
        //user_ids.push(row['FromUserName']);//load all at once
        
        if (i == loadSize - 1) {
            if(mode==MODE_NORMAL){
                getMessage.nextEndTime = row['CreateTime'];
            }else if(mode == MODE_SEARCH){
                searchAndPopulateResult.nextEndTime = row['CreateTime'];
            }else {
                console.error("Current Mode Not declared");
            }
        }
    });
    //load all at once
    /*$.post("./php/userUtil.php?func=getAndSaveUserInfoOpenIDArray",{'openIDArr':JSON.stringify(user_ids)}, function(data) {
    	$.each(data,function(i,val){
    		val['headimgurl'] = val['headimgurl'].slice(0, -1) + IMAGE_SIZE; //change image quality
            //val['nickname'] = emojione.toImage(val['nickname']);//using the php library now
            user_info[val['openid']] = val;
            $msglist.find('.nickname#'+val['openid']).html(val['nickname']);
            $msglist.find('.headimg#'+val['openid']).html("<img src='" + val['headimgurl'] + "' width='46'>");
    	});
            
        },'json');*/
	 $.each(user_info,function(id, empty){
	 	$.getJSON("./php/userUtil.php?func=getAndSaveUserInfoOpenID&openID=" + id, function(data) {
            if(data['subscribe']==1){
                getUserBallCategory(id, $msglist.find('.ball-category#'+id));
                data['headimgurl'] = data['headimgurl'].slice(0, -1) + IMAGE_SIZE; //change image quality
                //data['nickname'] = emojione.toImage(data['nickname']);//using the php library now
                user_info[data['openid']] = data;
                $msglist.find('.subscribetimestr#'+id).html(data['subscribe_time_str']);
                $msglist.find('.nickname#'+id).html("<small>"+data['nickname']+"</small>");
                $msglist.find('.headimg#'+id).html("<img src='" + data['headimgurl'] + "' width='46'>");
            }else{
                console.log(data);
                $msglist.find('.subscribetimestr#'+id).html('<b>未关注</b>');
                $msglist.find('.nickname#'+id).html("<small>"+'<b>未关注</b>'+"</small>");
                $msglist.find('.headimg#'+id).html("<b>未关注</b>");
            }
        });

	 });
}

$(document).on('click', ".open-reply-modal-btn", function(e) {
    var $userid = $(this).data("user-id");
    var $msg_id = $(this).data("msg-id");
    if ($userid in user_info) {
        var $reply_message_modal = $('#reply-message-modal');
        $reply_message_modal.data("userid", $userid);
        $reply_message_modal.data("msg-id", $msg_id);
        if(typeof(user_info[$userid]['nickname']) === 'undefined'){
            $reply_message_modal.find("h4.modal-title").html("该用户未关注");
        }else{
            $reply_message_modal.find("h4.modal-title").html("与" + user_info[$userid]["nickname"] + "的对话");
        }
        
        $reply_message_modal.find("#showAutoRepliedHistoryCheckbox").data("user-id",$userid);
        //$reply_message_modal.find("#showAutoRepliedHistoryCheckbox").data("msg-id",$msg_id);
        $reply_message_modal.find("#showAutoRepliedHistoryCheckbox").prop("checked", false);
       
        
        populateHistoryMsg($userid,0);
        $reply_message_modal.modal('show');
    }

});

$('#reply-message-modal').on('click', "#send-reply-btn", function(e) {
    var $btn = $(this);
    $btn.button('loading');
    var $reply_message_modal = $(e.delegateTarget);
    if (typeof($reply_message_modal.data("userid")) === 'undefined') {
        alert("无用户选中");
        $reply_message_modal.modal('hide');
    }
    var msg = $reply_message_modal.find("textarea#reply_msg").val();
    $msg_obj = {
        "touser": $reply_message_modal.data("userid"),
        "msgtype": "text",
        "text": {
            "content": msg
        }
    };
    var json_str = JSON.stringify($msg_obj);
    $.post("./php/msgUtilOncall.php?func=sendMsgWithCompiledStr", {
        "json_str": json_str
    }, function(data) {
        console.log(data);
        if(data.errcode != 0){
            alert("呀发送失败了:(，该用户可能已经取关");

        }else{
            $.get("./php/msgUtilOncall.php?func=setMsgAsReplied&msg_id="+$reply_message_modal.data("msg-id")+"&replied_msg_id="+data['replied_msg_id'],function(data){
                console.log("msgid: "+$reply_message_modal.data("msg-id")+" set as replied"+data);
            });
            alert('同喜同喜，发送成功');
        }
        $btn.button('reset');
        $reply_message_modal.find("textarea#reply_msg").val("");
        $reply_message_modal.removeData("userid");
        $reply_message_modal.modal('hide');
        
    }, 'json');
});

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

$('#reply-message-modal').on('shown.bs.modal', function () {
    $(this).find('textarea#reply_msg').focus();
});

$('#reply-message-modal').on('hidden.bs.modal', function () {
    $(this).find('#emojiPopover').popover('hide');
});


//populate history msg to reply
function populateHistoryMsg(openID,showAutoReplied){
    $("#load-history-spin:first").html("<i class='fa fa-circle-o-notch fa-spin'></i>");
    html="";
        if(typeof(user_info[openID]['nickname']) === 'undefined'){
            var userNickName="未关注";
            var userImgHtml="未关注";
        }else{
            var userNickName=user_info[openID]['nickname'];
            var userImgHtml="<img src='" + user_info[openID]['headimgurl'] + "' width='46'>";
        }
        $.getJSON("./php/msgUtilOncall.php?func=getTextMessageHistoryOpenID&showAutoReplied=" + showAutoReplied+"&openID="+openID, function(data){
            //console.log(data);
            $.each(data,function(i,msg){
                var senderNickName;
                var senderImgHtml;
                if(msg['T']==INCOME_MSG){
                    senderNickName = "<b>"+userNickName+"</b>";
                    senderImgHtml = userImgHtml;

                }else{
                    senderNickName = MY_NAME;
                    senderImgHtml = "<img src='" + CHIDU_HEAD_URL + "' width='46'>";
                }
                if(msg['rule_auto_replied'] != 0){
                    auto_reply_indicate_str="<i class='fa fa-bolt'></i>";
                }else{
                    auto_reply_indicate_str="";
                }
                html+="<tr><td><small>"+msg['CreateTimeStr']+"</small></td><td><small>"+senderNickName+"</small></td><td class='hidden-xs'>"+senderImgHtml+"</td><td class='hidden-xs'>"+TYPE_MATCH[msg['MsgType']]+"</td><td>"+msg['Content']+"</td><td class='hidden-xs'>"+auto_reply_indicate_str+"</td></tr>";
            });
            $('#history_msg_list').html(html);
            $("#load-history-spin:first").html("");
        });
    
}

$('#showAutoRepliedHistoryCheckbox').change(function(){
    if(this.checked){
        populateHistoryMsg($(this).data("user-id"),1);
    }else{
        populateHistoryMsg($(this).data("user-id"),0);
    }
});

$('#reply-message-modal').on('click',".emoji-insert-btn", function(e){
    var emojichar = $(this).text();
    var $reply_message_modal = $(e.delegateTarget);
    $reply_message_modal.find("textarea#reply_msg").val($reply_message_modal.find("textarea#reply_msg").val()+$(this).data("emoji-char"));
    //$reply_message_modal.find('#emojiPopover').popover('hide');
});

