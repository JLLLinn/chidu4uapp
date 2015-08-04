$(function(){
	//generate the days when coming in
	$("#month-select").trigger( 'change' );
});
$("#select-birth").on('click', "#submit-single-birthday", function(e){
	var $select_birth = $(e.delegateTarget);

	var timestr = "" + $select_birth.find("#year-select:first").val()
		+" " +$select_birth.find("#month-select:first").val()
		+" " +$select_birth.find("#day-select:first").val()
		+" " +$select_birth.find("#hour-select:first").val();
	$.get("./php/ballUtilOncall.php?func=strToTimestamp&timestr="+timestr, function(timestamp){
		console.log(timestamp);
		$.ajax("./php/ballUtilOncall.php?func=setBirthdaySession&timestamp="+timestamp).done( function(){
			console.log(timestamp);
			window.location.href = "./firststageresult-singlebirthday.php?birthdayTimestamp="+timestamp;
		});
		
	})
});


$("#select-birth").on('change', "#month-select", function(e){
	var $select_birth = $(e.delegateTarget);
	var year = $select_birth.find("#year-select:first").val();
	var month = parseInt($(this).val());
	var days = new Date(year, month, 0).getDate();
	var options="";
	for (i = 1; i <= days; i++) { 
	    options += "<option value='"+i+"'>" +i+ "</option>";
	}
	$select_birth.find("#day-select:first").html(options);
});
