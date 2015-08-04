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
var SUBSCRIPTION_RANGE=172800;
var SUBSCRIPTION_TIME_UNIT=7200;
var summaryStat = {};
var detailRows = {};
var user_source_match = [
    [0, "其他"],
    [35, "搜公众号名称"],
    [17, "名片分享"],
    [43, "图文页右上角菜单"],
    [3, "搜索微信号"],
    [30, "扫二维码"],
    [39, "查询微信公众帐号"]
];
//var userSubData={};
$(document).ready(function() {
    var date = new Date();
    getFullUserStat(date, 1);
    getSubsdataNDisplay(SUBSCRIPTION_RANGE, SUBSCRIPTION_TIME_UNIT);
    getMsgdataNDisplay(SUBSCRIPTION_RANGE, SUBSCRIPTION_TIME_UNIT);
    

});

/*
 * THis function gets the whole user data
 */
function getFullUserStat(end_date_obj, iteration) {
    console.log("ITER:"+iteration);
    if (iteration == 0) {
        console.log("DOne, return");
        return;
    }
    var date = end_date_obj;
    date.setDate(date.getDate() - 1);
    var end_date = (date.getFullYear()) + "-" + (date.getMonth() + 1) + "-" + (date.getDate());
    date.setDate(date.getDate() - 6);
    var begin_date = (date.getFullYear()) + "-" + (date.getMonth() + 1) + "-" + (date.getDate());
    console.log(end_date);
    console.log(begin_date);
    var getSummaryUrl = "./php/getUserStat.php?func=getUserStatFromWechat&startDate=" + begin_date + "&endDate=" + end_date + "&statType=summary";
    var getCumulateUrl = "./php/getUserStat.php?func=getUserStatFromWechat&startDate=" + begin_date + "&endDate=" + end_date + "&statType=cumulate";
    console.log("Now Execute");
    //canExcute = false;
    $.getJSON(getCumulateUrl, function(cumulateJSON) {
        console.log("Receiverd First data");
        cumulateJSON2Html(cumulateJSON);
        $.getJSON(getSummaryUrl, function(summaryJSON) {
            console.log("Receiverd Both data");
            var summaryStatSubset = convertSummaryJSON(summaryJSON);
            $.each(summaryStatSubset, function(ref_date, v1) {
                var new_user = 0;
                var cancel_user = 0;
                var html = "";
                $.each(user_source_match, function(index, v2) {
                    var source = v2[0];
                    var sourceName = v2[1];
                    if (v1[source]) {
                        sourceStat = v1[source];
                        new_user += sourceStat['new_user'];
                        cancel_user += sourceStat['cancel_user'];
                        var thisNet_user = sourceStat['new_user'] - sourceStat['cancel_user'];
                        html += "<tr><td> </td><td> </td><td>" + sourceName + "</td><td>" + sourceStat['new_user'] + "</td><td>" + sourceStat['cancel_user'] + "</td><td>" + thisNet_user + "</td><td></td></tr>";
                    } else {
                        html += "<tr><td> </td><td> </td><td>" + sourceName + "</td><td>" + 0 + "</td><td>" + 0 + "</td><td>" + 0 + "</td><td></td></tr>";

                    }
                });
                var $html = $(html).hide();
                var $dayDataRow = $('tr#' + ref_date);
                $dayDataRow.after($html);
                detailRows[ref_date] = $html;
                var net_user_total = new_user - cancel_user;
                var percet_net_user_total = ((net_user_total / summaryStat[ref_date]['cumulate']) * 1000).toFixed(2);
                $dayDataRow.children('.new').text(new_user);
                $dayDataRow.children('.cancel').text(cancel_user);
                $dayDataRow.children('.net').html(net_user_total + " " + percet_net_user_total + "&#8240");
            });
            $.extend(summaryStat, summaryStatSubset);

            getFullUserStat(date, iteration - 1)

        });
    });
}

function cumulateJSON2Html(cumulateJSON) {
    var html = "";
    $.each(cumulateJSON['list'], function(i, v) {
        html = "<tr class='datarow' id=" + v['ref_date'] + "><td>" + v['ref_date'] + "</td><td>" + v['cumulate_user'] + "</td><td> </td><td class='new'> </td><td class='cancel'> </td><td class='net'> </td><td class='opts text-center'><span class='glyphicon glyphicon-plus' ></span></td></tr>" + html;
        if (summaryStat[v['ref_date']]) {
            summaryStat[v['ref_date']]['cumulate'] = v['cumulate_user'];
        } else {
            summaryStat[v['ref_date']] = {};
            summaryStat[v['ref_date']]['cumulate'] = v['cumulate_user'];
        }
    });
    $('#userstatlist').append(html);
}
//convert to an object index by date, merge all sources on the same day together
function convertSummaryJSON(summaryJSON) {
    var summaryStatSubset = {};
    $.each(summaryJSON['list'], function(i, v) {
        if (summaryStatSubset[v['ref_date']]) {
            summaryStatSubset[v['ref_date']][v['user_source']] = v;
        } else {
            summaryStatSubset[v['ref_date']] = {};
            summaryStatSubset[v['ref_date']][v['user_source']] = v;
        }
    });
    return summaryStatSubset;
}

$(document).on("click", ".datarow", function(e) {
    detailRows[$(this).attr('id')].toggle();
    var $indicator = $(this).children('.opts').children('span');
    $indicator.toggleClass('glyphicon-minus glyphicon-plus');
});




function getStartOfHourTimestamp(){
    var curTime = new Date;
    var secs = curTime.getSeconds();
    var mins = curTime.getMinutes();
    curTime = Math.floor(curTime.getTime()/1000 - secs - 60*mins);
    return curTime;
}
function getSubsdataNDisplay(length, interval){
    var curTime = getStartOfHourTimestamp();
    var startTime = curTime-length;
    console.log(startTime + " " + curTime);
    var interval = interval;
    var userSubData={};
    var subs_total = 0;
    var unsubs_total = 0;
    userSubData['end_time']=[];
    userSubData['subscribe']=[];
    userSubData['unsubscribe']=[];
    $.getJSON("./php/getUserStat.php?func=getSubscribeEventStat&startTime="+startTime+"&endTime="+curTime+"&interval="+interval,function(data){
        $.each(data,function(i,Subsdata){
            userSubData['end_time'].push(Subsdata['end_time']);
            var subscribe = parseInt(Subsdata['subscribe']);
            var unsubscribe = parseInt(Subsdata['unsubscribe']);
            subs_total += subscribe;
            unsubs_total += unsubscribe;
            userSubData['subscribe'].push(subscribe);
            userSubData['unsubscribe'].push(unsubscribe);
        });
        $('.badge#total-subs').text(subs_total);
        $('.badge#total-unsubs').text(unsubs_total);
        createSubsChart(userSubData);

    });
}
function createSubsChart(userSubData){
    
    var data = {
        labels : userSubData['end_time'],
        datasets: [
            {
                label: "新关注",
                fillColor: "rgba(220,220,220,0.2)",
                strokeColor: "rgba(220,220,220,1)",
                pointColor: "rgba(220,220,220,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(220,220,220,1)",
                data:userSubData['subscribe'],
            },
            {
                label: "取关",
                fillColor: "rgba(151,187,205,0.2)",
                strokeColor: "rgba(151,187,205,1)",
                pointColor: "rgba(151,187,205,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(151,187,205,1)",
                data: userSubData['unsubscribe']
            }
        ]
    };
    
    var ctx = document.getElementById("24hrsSubsChart").getContext("2d");
    var myLineChart = new Chart(ctx).Line(data, {
        multiTooltipTemplate: "<%= datasetLabel %> - <%= value %>",
    });
}


function getMsgdataNDisplay(length, interval){
    var curTime = getStartOfHourTimestamp();
    var startTime = curTime-length;
    console.log(startTime + " " + curTime);
    var interval = interval;
    var userMsgData={};
    var auto_replied_total = 0;
    var un_auto_replied_total = 0;
    var total_total = 0;
    userMsgData['auto_replied']=[];
    userMsgData['un_auto_replied']=[];
    userMsgData['total']=[];
    userMsgData['end_time']=[];
    $.getJSON("./php/getUserStat.php?func=getIncomeMsgCountStat&startTime="+startTime+"&endTime="+curTime+"&interval="+interval,function(data){
        $.each(data,function(i,Subsdata){
            
            var auto_replied =parseInt(Subsdata['auto_replied']);
            var un_auto_replied =parseInt(Subsdata['un_auto_replied']);
            var total =parseInt(Subsdata['total']);

            auto_replied_total += auto_replied;
            un_auto_replied_total += un_auto_replied;
            total_total += total;

            userMsgData['auto_replied'].push(auto_replied);
            userMsgData['un_auto_replied'].push(un_auto_replied);
            userMsgData['total'].push(total);
            userMsgData['end_time'].push(Subsdata['end_time']);
        });
        $('.badge#total-msg').text(total_total);
        $('.badge#total-ato-rep').text(auto_replied_total);
        $('.badge#total-unato-rep').text(un_auto_replied_total);
        createMsgChart(userMsgData);


    });
}
function createMsgChart(userMsgData){
    
    var data = {
        labels : userMsgData['end_time'],
        datasets: [
            {
                label: "总信息量",
                fillColor: "rgba(220,220,220,0.2)",
                strokeColor: "rgba(220,220,220,1)",
                pointColor: "rgba(220,220,220,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(220,220,220,1)",
                data:userMsgData['total'],
            },
            {
                label: "未自动回复",
                fillColor: "rgba(151,187,205,0.2)",
                strokeColor: "rgba(151,187,205,1)",
                pointColor: "rgba(151,187,205,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(151,187,205,1)",
                data: userMsgData['un_auto_replied']
            },
            {
                label: "自动回复",
                fillColor: "rgba(204, 102, 0,0.1)",
                strokeColor: "rgba(204, 102, 0,0.5)",
                pointColor: "rgba(204, 102, 0,0.5)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(204, 102, 0,0.5)",
                data: userMsgData['auto_replied']
            }
        ]
    };
    
    var ctx = document.getElementById("24hrsMsgChart").getContext("2d");
    var myLineChart = new Chart(ctx).Line(data, {
        multiTooltipTemplate: "<%= datasetLabel %> - <%= value %>",
    });
}