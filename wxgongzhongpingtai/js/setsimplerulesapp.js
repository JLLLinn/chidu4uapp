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
 var reply_type_match = {
    "text":"文字消息",
    "news":"图文素材",
    "image":"图片",
 };
  var event_match = {
    "click":"点击",
    "subscribe":"关注",
 };
$(document).ready(function() {
    $.getJSON("./php/simpleRules.php?func=loadAllSimpleTextRules", function(data) {
        $("#textruleslist").html(createHtmlFromJSONForTextRuleRows(data));
    });
     $.getJSON("./php/simpleRules.php?func=loadAllSimpleEventRules", function(data) {
        $("#eventruleslist").html(createHtmlFromJSONForEventRuleRows(data));
    });
});

$('#eventformSelect').change(function(e){
    switch($('#eventformSelect').val()){
        case 'text':{
            $('#eventformResponse').replaceWith("<textarea class='form-control' id='eventformResponse' name='eventformResponse' placeholder='文字消息' cols='40' rows='1' ... required></textarea>");
            break;
        }
        case 'image':{
            $('#eventformResponse').replaceWith("<input type='text' class='form-control' id='eventformResponse' name='eventformResponse' placeholder='图片MediaId' required>");
            break;
        }
        case 'news':{
            $('#eventformResponse').replaceWith("<input type='text' class='form-control' id='eventformResponse' name='eventformResponse' placeholder='图文MediaId' required>");
            break;
        }
        default:{
            break;
        }

    }
    
});
$('#formSelect').change(function(e){
    switch($('#formSelect').val()){
        case 'text':{
            $('#formResponse').replaceWith("<textarea class='form-control' id='formResponse' name='formResponse' placeholder='文字消息' cols='40' rows='1' ... required></textarea>");
            break;
        }
        case 'image':{
            $('#formResponse').replaceWith("<input type='text' class='form-control' id='eventformResponse' name='formResponse' placeholder='图片MediaId' required>");
            break;
        }
        case 'news':{
            $('#formResponse').replaceWith("<input type='text' class='form-control' id='formResponse' name='formResponse' placeholder='MediaId' required>");
            break;
        }
        default:{
            break;
        }

    }
    
});

$('#addsimpletextruleform').submit(function(event) {
    // Stop the browser from submitting the form.
    event.preventDefault();
    $.post("./php/simpleRules.php?func=addSimpleTextRule", $('#addsimpletextruleform').serialize(), function(data) {
        //expect data as a json object, an array of rows (here it is only 1 row, the one that was added)
        data = JSON.parse(data)
        $(createHtmlFromJSONForTextRuleRows(data)).hide().prependTo("#textruleslist").fadeIn();
    });
});

/**
 * create the html rule row from a json object. No offense but don't even bother trying to find the structure for data, you can't. You can figure it out from my code
 * returns a piece of html
 */
function createHtmlFromJSONForTextRuleRows(data) {
	var items = [];
        $.each(data, function(key, val) {
            var rowHtml = "<tr><td>" + val['id'] + "</td><td>" + val['keyword'] + "</td><td>" + reply_type_match[val['reply_type']] + "</td><td>" + val['response'] + "</td><td>" + val['create_time'] + "</td><td>" + val['name'] + "</td><td>"+ val['comment'] + "</td>";
            rowHtml = rowHtml + "<td><button identifier='"+val['keyword']+"' id="+val['id']+" class='btn btn-default delete-record'><span class='glyphicon glyphicon-trash' aria-hidden='true'></span></button></td></tr>";
            items.push(rowHtml);
        });
    	return items.join("");
}

$('#addsimpleeventruleform').submit(function(event) {
    // Stop the browser from submitting the form.
    event.preventDefault();
    $.post("./php/simpleRules.php?func=addSimpleEventRule", $('#addsimpleeventruleform').serialize(), function(data) {
        //expect data as a json object, an array of rows (here it is only 1 row, the one that was added)
        data = JSON.parse(data)
        $(createHtmlFromJSONForEventRuleRows(data)).hide().prependTo("#eventruleslist").fadeIn();
    });
});

/**
 * create the html rule row from a json object. No offense but don't even bother trying to find the structure for data, you can't. You can figure it out from my code
 * returns a piece of html
 */
function createHtmlFromJSONForEventRuleRows(data) {
    var items = [];
        $.each(data, function(key, val) {
            var rowHtml = "<tr><td>" + val['id'] + "</td><td>" + event_match[val['event']] + "</td><td>" + val['event_key'] + "</td><td>" + reply_type_match[val['reply_type']] + "</td><td>" + val['response'] + "</td><td>" + val['create_time'] + "</td><td>" + val['name'] + "</td><td>"+ val['comment'] + "</td>";
            rowHtml = rowHtml + "<td><button identifier='"+val['event_key']+"' id="+val['id']+" class='btn btn-default delete-record'><span class='glyphicon glyphicon-trash' aria-hidden='true'></span></button></td></tr>";
            items.push(rowHtml);
        });
        return items.join("");
}





/*
 * This listener here is for those that are created after the DOM is created
 */
$(document).on("click", ".delete-record", function(e) {
        var identifier=($(this).attr("identifier"));
        var id = ($(this).attr("id"));
        var rowDiv=$(this).parent().parent();
        if(confirm('确认删除规则“'+identifier+'”?')){
        	$.get("./php/simpleRules.php?func=deleteSimpleRule&id="+id , function(data) {
	        	rowDiv.fadeOut();
	        });
        }
        
        
	
        
});