var analyze_result;
$(function(){
	if(user_info['sex'] == 1){
		//$('#weakness-sec').hide();
		$('#sex-icon').html("<i class='fa fa-male'></i>");
		$('#sex-icon').addClass('text-info');
	}else if(user_info['sex'] == 2) {
		$('#sex-icon').html("<i class='fa fa-female'></i>");
		$('#sex-icon').addClass('text-danger');
		
	}else {
		var html = "<div class='alert alert-info' role='alert'><strong>Heads up!</strong> 尺度似乎无法识别你的性别，请在微信中设置再重新算球</div>";
		$('.top-alert').html(html);
	}
	$.getJSON("./php/ballUtilOncall.php?func=calcBall&birthdayTimestamp=" + birthdayTimestamp+"&sex="+user_info['sex'], function(data){
		analyze_result = data;
		buildChart();
		addSentences();
	});
});

function addSentences(){
	 var list_item_div = "<div class='list-group-item'>";
	 var list_item_div_c = "</div>";
	 var list_header_div = "<h5 class='list-group-item-heading toggle-detail'><i class='fa fa-plus-square'></i> ";
	 var list_header_div_c = "</h5>";
	 var list_body_div = " <p class='list-group-item-text' style='display:none;'><small>";
	 var list_body_div_c= " </small></p>";
	 var html="";
	 $.each(analyze_result['strength_sentences'], function(index, strength){
	 	html+= list_item_div+list_header_div+strength['name']+list_header_div_c+list_body_div+strength['description']+list_body_div_c+list_item_div_c;
	 });

	 $('#strength_list').append(html);

	 html="";
	 $.each(analyze_result['weakness_sentences'], function(index, weakness){
	 	html+= list_item_div+list_header_div+weakness['name']+list_header_div_c+list_body_div+weakness['description']+list_body_div_c+list_item_div_c;
	 });

	 $('#weakness_list').append(html);
}
function buildChart(){
	var coords= analyze_result['birthdateNAssoc']['coords'];
	var options={
		scaleShowLine : false,
		showTooltips: false,
		pointDot : false,
		pointDotRadius : 1,
		datasetStrokeWidth : 1,
		angleLineWidth : 1,
	}
	var data = {
	    labels: ["温暖","王者","果敢","奇缘", "神秘","灵性", "亲和","浪漫"],
	    datasets: [
	        {
	            label: "My Second dataset",
	            fillColor: "rgba(151,187,205,0.2)",
	            strokeColor: "rgba(151,187,205,1)",
	            pointColor: "rgba(151,187,205,1)",
	            pointStrokeColor: "#fff",
	            pointHighlightFill: "#fff",
	            pointHighlightStroke: "rgba(151,187,205,1)",
	            data: [coords['up'], (coords['up'] + coords['right'])/2, coords['right'], (coords['down'] + coords['right'])/2,coords['down'], (coords['left'] + coords['down'])/2,coords['left'],(coords['up'] + coords['left'])/2]
	        }
	    ]
	};
	// Get context with jQuery - using jQuery's .get() method.
	var ctx = $("#myChart").get(0).getContext("2d");
	// This will get the first returned node in the jQuery collection.
	var myRadarChart = new Chart(ctx).Radar(data, options);
}

$("section").on('click', "h5.toggle-detail", function(e){
	$(this).next().slideToggle();
	$(this).find("i").toggleClass('fa-plus-square fa-minus-square');
})

$("#scroll-to-strength-sec").click(function(e) {
	e.preventDefault()
    $('html,body').animate({
        scrollTop: $("#strength-sec").offset().top},
        'slow');
    return false;
});