$('#whole-msg-send-btn').on('click', function(e){
	e.preventDefault();
	var content = $("#whole-msg-content:first").val();
	getAndSendMsg2UserList("",content,0);
});

function getAndSendMsg2UserList(next_openid, content,iteration){
	if(iteration == 0){
		var url="./php/userUtil.php?func=getSubsUserList";
	} else if( typeof next_openid !== 'undefined' && next_openid !== ""){
		console.log("a new next_openid:"+next_openid);
		var url="./php/userUtil.php?func=getSubsUserList&next_openid="+next_openid;
	} else {
		console.log("reach end");
		console.log(next_openid);
		console.log(iteration);
		return;
	}
	$.getJSON(url, function(data){
		var send = {
			'content':content,
		};
		send['openIDs'] = JSON.stringify(data['data']['openid']);
		console.log("Now sending iteration: "+iteration+"count of"+data['count']+"current next_id: "+ next_openid);
		$.post("./php/msgUtilOncall.php?func=sendGroupMessageByOpenIdList", send, function(d){
			console.log("Send iteration "+iteration+" done.");
		});
		getAndSendMsg2UserList(data['next_openid'], content, iteration+1);

	});
	
}