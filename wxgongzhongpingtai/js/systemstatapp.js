$(document).ready(function() {
    getNDisplayMemUsage();
});
function getNDisplayMemUsage(){
	$.getJSON("./php/sysStatOncall.php?func=getSysMemUsageFromFile", function(data){
		console.log(data);
		var data = {
	        labels : data['labels'],
	        datasets: [
	            {
	                label: "Memery Usage Of The Server Over Last 24 Hrs",
	                fillColor: "rgba(151,187,205,0.2)",
	                strokeColor: "rgba(151,187,205,1)",
	                pointColor: "rgba(151,187,205,1)",
	                pointStrokeColor: "#fff",
	                pointHighlightFill: "#fff",
	                pointHighlightStroke: "rgba(151,187,205,1)",
	                data: data['memusages'],
	            },
	        ]
	    };
	    var ctx = document.getElementById("sys-mem-usage-chart").getContext("2d");
	    var myLineChart = new Chart(ctx).Line(data, {
	        scaleOverride : true,
	        scaleSteps : 10,
	        scaleStepWidth : 170,
	        scaleStartValue : 0,
	        scaleFontSize: 12,
	        showTooltips: true,
	        pointDot : false,
	    });
	});
}