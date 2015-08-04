$(function(){
	 populateTags();
})
$("#submit-edit-name-and-weight").on('click',function(e){
	var obj = {
		"tag":$('#edit-tag-name-input').val(),
		"newTag":$('#edit-tag-new-name-input').val(),
		"newWeight":$('#edit-tag-weight-input').val()
	}
	console.log(obj);
	$.post("./php/ballContentUtilOncall.php?func=updateTagNWeight", obj, function(d){

		if(d){
			alert("修改成功");
		}else{
			alert("可惜了没成功，跟管理员说说吧");
		}
		location.reload();
	})

})
$("#submit-ball-content").on("click", function(e){
	e.preventDefault();
	$(this).text("正在提交");
	$(this).prop("disabled",true);
	var ball_content_rows=$("tbody#ball-content-table").find("tr.ball-content-row");
	var categories = [];
	var contents = [];
	var urls = [];
	var in_uses = [];
	var sexes = [];
	$.each(ball_content_rows, function(index, ball_content_row){
		ball_content_row = $(ball_content_row);
		categories.push(ball_content_row.attr('data-category'));
		sexes.push(ball_content_row.attr('data-sex'));
		contents.push(ball_content_row.find("td.content").text());
		urls.push(ball_content_row.find("td.url").text());
		in_uses.push(ball_content_row.find("td.in-use:first input:first").prop( "checked" ));
	});
	var tag = $("#tag-name-input").val();
	var data={
		"tag": tag,
		"categories": JSON.stringify(categories),
		"contents": JSON.stringify(contents),
		"urls":JSON.stringify(urls),
		"in_uses":JSON.stringify(in_uses),
		"sexes":JSON.stringify(sexes)
	}
	console.log(data);
	$.post("./php/ballContentUtilOncall.php?func=addOrUpdateBallContents", data, function(d){

		if(d){
			alert("提交成功");
		}else{
			alert("可惜了没成功，跟管理员说说吧");
		}
		location.reload();
	})
});

$("#submit-name-and-check").on("click", function(e){
	e.preventDefault();
	var tag = $("#tag-name-input").val();
	if(tag == ""){
		alert("主题内容为空，请输入主题");
		return false;
	}
		
	$.post("./php/ballContentUtilOncall.php?func=getBallContentsForAllCategories",{"tag":tag}, function(data){
		console.log(data);

		var ball_content_rows=$("tbody#ball-content-table").find("tr.ball-content-row");

		$.each(ball_content_rows, function(index, ball_content_row){
			ball_content_row = $(ball_content_row);
			var category = ball_content_row.attr('data-category');
			var sex = ball_content_row.attr('data-sex');
			if(typeof data[category] !== "undefined" && typeof data[category][sex] !== "undefined"){
				var ball_content_row_data = data[category][sex];
				ball_content_row.find("td.content").text(ball_content_row_data['content']);
				ball_content_row.find("td.url").text(ball_content_row_data['url']);
				ball_content_row.find("td.tag").text(ball_content_row_data['tag']);
				if(ball_content_row_data['in_use'] == 1){
					ball_content_row.find("td.in-use:first input:first").prop( "checked", true);
				} else{
					ball_content_row.find("td.in-use:first input:first").prop( "checked", false);
				}
			}else{
				ball_content_row.find("td.content").text("");
				ball_content_row.find("td.url").text("");
				ball_content_row.find("td.tag").text(tag);
				ball_content_row.find("td.in-use:first input:first").prop( "checked", false);
			}
			
			
		});
		alert("加载完毕，现在正在编辑主题"+tag);
	},'json')
})

function populateTags(){
	$.getJSON("./php/ballContentUtilOncall.php?func=getAllCategories", function(data){
		console.log(data);
		html="";
		$.each(data, function(index, row){
			html += "<option value='"+row['tag']+"'>"+row['tag']+" - ("+row['in_uses']+") - "+row['weight']+" </option>"
		});
		$("#tag-name-select").html(html);
		$("#tag-name-select").trigger('change');
	})
}
$("#tag-name-select").on('change', function(e){
	e.preventDefault();
	$("#tag-name-input").val($(this).val());
	$("#submit-name-and-check").trigger('click');
})