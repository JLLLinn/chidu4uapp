analyze_result = [];
$(function(){
	if(display_all_info){
		if(sex == 1){
			//$('#weakness-sec').hide();
			$('#sex-icon').html("<i class='fa fa-male'></i>");
			$('#sex-icon').addClass('text-info');
		}else if(sex == 2) {
			$('#sex-icon').html("<i class='fa fa-female'></i>");
			$('#sex-icon').addClass('text-danger');
		}else {
			var html = "<div class='alert alert-info' role='alert'><strong>嗨!</strong> 尺度似乎无法识别你的性别，请在微信中设置再重新进入</div>";
			$('.top-alert').html(html);
			return;
		}
		if(birthdayTimestamp){
			//We have the birthday of this guy/gal
			$.post("./php/ballUtilOncall.php?func=getInUseBallContentByBirthTimestamp",{
					'birthdayTimestamp':birthdayTimestamp,
					'sex':sex,
				} ,function(data){
					if( typeof data !== "undefined" && data !== null){
						analyze_result = data;
					}
					$("#doc-title").text(nickname+" · "+birthdaystr+" · 球形解读");
					addSentences();

					insertSuccessSelfInfo();
				},'json');
		} else {
			//we don't have his birthday yet
			//go calc the ball first
			insertNoBirthdaySelfInfo();

		}
	} else {
		insertUnsuccessInfo();
	}
});

function addSentences(){
	 var list_button_item_div_prefix = "<button type='button' class='list-group-item detail-toggle-btn' data-detail-index='";
	 var list_button_item_div_disabled_prefix = "<div class='list-group-item text-muted'>";
	 var list_button_item_div_disabled_c="</div>";
	 var list_button_item_div_c = " <i class='fa fa-angle-double-right'></i></button>";
	 var html="";
	 $.each(analyze_result, function(index, content){
	 	if (content['content'] === "" && content['url'] === ""){
	 		html+=list_button_item_div_disabled_prefix + content['tag']+" （更新中，敬请期待！）"+list_button_item_div_disabled_c;
	 	} else {
			html+= list_button_item_div_prefix+index+"'>"+content['tag']+list_button_item_div_c;
		}
	 });

	 $('#ball-content-list').append(html);
}
function insertSuccessSelfInfo(){
	var html = "<hr><div class='alert alert-warning alert-dismissible fade in' role='alert'>\
                  <small><p>以上是尺度君根据你的球形为你做的解读。<br></p>你也可以:</small>\
                   <a href='./firststageresult-singlebirthday.php' type='button' class='btn btn-danger btn-xs'><small>载入球形</small></a> <small>或者</small> \
                    <a href='./getbirthday.php' type='button' class='btn btn-default btn-xs'><small>重新算球</small></a>\
                </div>";
    $(".bottom-alert").html(html);
}

function insertUnsuccessInfo(){
	var html = "<div class='alert alert-warning alert-dismissible fade in' role='alert'>\
                  <small>嗨！欢迎来到尺度。尺度君似乎不认识你，请从尺度官方微信公众平台进入。你也可以用下方二维码关注尺度。</small>\
                </div>";;
    $(".top-alert").html(html);
}

function insertNoBirthdaySelfInfo(){
	var html = "<div class='alert alert-warning alert-dismissible fade in' role='alert'>\
                  <small><p>嗨"+nickname+"，欢迎来到尺度！尺度君现在还对你一无所知，无法帮你做其他的解读，现在请先让尺度君读取你的先天情感特质吧！<br></p></small>不如我们先来算个球吧！点击: \
                    <a href='./getbirthday.php' type='button' class='btn btn-default btn-xs'>算个球</a>\
                </div>";
    $(".top-alert").html(html);
    $('#ball-content-list').append("<button type='button' class='list-group-item btn btn-default' disabled>尚未解锁</button>");
}


$("#go-back-to-main").on('click', function(e){
	$("div#detail-div:first").velocity("slideUp", { 
		duration: 1000,
		complete: function(elements) { 
			$("div#main-div:first").velocity("slideDown", { duration: 1000 });
		}
	});
	
})
$("section").on('click', "button.detail-toggle-btn", function(e){
	var index = $(this).attr("data-detail-index");
	var content = analyze_result[index];
	
	

	$("div#main-div:first").velocity("slideUp", { 
		duration: 1000,
		complete: function(elements) { 
			$("div#detail-div:first .panel-title").text(content['tag']);
			$("div#detail-div:first .panel-body:first .content-detail:first").html(content['content']);
			if(typeof content['url'] !== "undefined" && content['url'] !== ""){
			 		var button = "<a class='btn btn-default btn-xs' href='"+content['url']+"'>点击进入图文解答</a>"
			 		var html = "<hr>"+button;
			 		$("div#detail-div:first .panel-body:first .content-btn:first").html(html);
			}
			$("div#detail-div:first").velocity("slideDown", { duration: 1000 });
		}
	});
	
})