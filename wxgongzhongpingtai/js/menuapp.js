/* Built by Jiaxin Lin for Chidu China
 
    *********
   * === === *
  L*  *   *  *J
   *    0    *
    *********
       -X-
       / \

    This code would reqiure heavy refactoring.
 */
var type_match = {
    "news": "图文（通过官网设置）",
    "text": "文字（通过官网设置）",
    "click": "点击",
    "view": "跳转URL",
    "scancode_push": "扫码推事件",
    "scancode_waitmsg": "扫码推事件且弹出“消息接收中”提示框",
    "pic_sysphoto": "弹出系统拍照发图",
    "pic_photo_or_album": "弹出拍照或者相册发图",
    "pic_weixin": "弹出微信相册发图器",
    "location_select": "弹出地理位置选择器",
    "media_id": "下发消息（除文本消息）",
    "view_limited": "跳转图文消息URL",
};
var event_assoc_match = {
    "news": "",
    "text": "",
    "click": "key",
    "view": "url",
    "scancode_push": "key",
    "scancode_waitmsg": "key",
    "pic_sysphoto": "key",
    "pic_photo_or_album": "key",
    "pic_weixin": "key",
    "location_select": "key",
    "media_id": "media_id",
    "view_limited": "media_id",
};

var assoc_chi = {
    "": "请更改类别",
    "key": "事件名称 Key",
    "url": "地址 URL",
    "media_id": "Media ID",
};
var menu_buttons = {};
var MAX_BUTTON_NUM = 3;
var MAX_SUB_BUTTON_NUM = 5;
$(document).ready(function() {
    $('#big-submit-button').button('loading');
    getCurrentMenu();
    $('#form_event_type').val("");
    //enable sorting first level btn list
    $('#current-menu-list').sortable({
        'nested': false,
        'itemSelector': "li.btn-li",
    });

    setSortingStatus(0);

    //$('ul.list_subbtn').sortable('disable');
});

//THis is the getJSON data, except that it has been processed and each entry has been added an id
var menu_object;

//this should be called whenever a new subbtn list is created
function initSubbtnListSortable() {
    //initializing the sortable
    //3 lines a function, that's called luxury
    $('ul.list_subbtn').sortable({
        'nested': false,
        'itemSelector': "li.subbtn-li",
    });
    setSortingStatus(3);
}
//0:disable both sorting
//1: sort first level list
//2: sort second level list
//3: get button state and set (recursive)
function setSortingStatus(indicator) {
    //this set the whole sorting status based on the status of the sorting button
    switch (indicator) {
        case 0:
            {
                $('#current-menu-list').sortable("disable");
                $('ul.list_subbtn').sortable('disable');
                $('.button-detail').fadeTo('slow', 1);
                break;
            }
        case 1:
            {
                $('#current-menu-list').sortable("enable");
                $('ul.list_subbtn').sortable('disable');
                $('.button-detail').fadeTo('slow', 0.3);
                break;
            }
        case 2:
            {
                $('#current-menu-list').sortable("disable");
                $('ul.list_subbtn').sortable('enable');
                $('.button-detail').fadeTo('slow', 0.3);
                break;
            }
        case 3:
            {
                var $btn_span = $('#sort-menu-btn').children('span.span-icon-click-to-sort');
                if ($btn_span.is('.glyphicon-sort')) {
                    setSortingStatus(0);
                } else if ($btn_span.is('.glyphicon-align-justify')) {
                    setSortingStatus(1);
                } else if ($btn_span.is('.glyphicon-list')) {
                    setSortingStatus(2);
                }
                break;
            }

        default:
            {
                alert("Unable to sort, contact Jiaxin");
                break;
            }
    }


}

$('#current-menu-list').on("click", "#sort-menu-btn", function(e) {
    $('#form-keyOrUrlOrMediaId').trigger('change');
    var $this = $(this);
    var $btn_span = $this.children('span.span-icon-click-to-sort');
    var $text_span = $btn_span.next();
    if ($btn_span.is('.glyphicon-sort')) {
        //change to first level sort
        $text_span.text("排序一级菜单");
        $btn_span.toggleClass("glyphicon-sort glyphicon-align-justify");
        setSortingStatus(1);
    } else if ($btn_span.is('.glyphicon-align-justify')) {
        $text_span.text("排序二级菜单");
        $btn_span.toggleClass("glyphicon-align-justify glyphicon-list");
        setSortingStatus(2);
    } else if ($btn_span.is('.glyphicon-list')) {
        $text_span.text("点击排序");
        $btn_span.toggleClass("glyphicon-sort glyphicon-list");
        setSortingStatus(0);
    }
});


function getCurrentMenu() {
    $.getJSON("./php/menuGetterSetter.php?func=get_current_selfmenu_info", function(data) {
        console.log(data);
        //set the is_menu_open check box
        if (data['is_menu_open'] == 1) {
            $('#is_menu_open-checkbox').prop('checked', true);
        }
        //now start create the name list of buttons
        var html = "";
        //going through the first level menu
        $.each(data['selfmenu_info']['button'], function(i, button) {

            html += processButton(button);
        });
        $("#current-menu-list").append(html);
        $('#big-submit-button').button('reset');
        initSubbtnListSortable();


    });
}
//This will process a first level button in the menu and return the html for thhis button 
//Note, it should ONLY be used for initial displaying all buttons and store in menu_buttons
function processButton(button) {
    processButton.buttonID = ++processButton.buttonID || 1; // f.count is undefined at first
    var html = "";
    var li_prefix = "<li class='btn-li list-group-item clearfix'>";
    var li_suffix = "</li>";
    var a_list_group_prefix = "<li  class='btn-li list-group-item btn btn-default menu-clickable clearfix'>";
    var a_list_subbtn_group_prefix = "<li  class='subbtn-li list-group-item btn btn-default menu-clickable clearfix'>";
    var a_list_group_suffix = "</li>";
    var ul_prefix = "<ul class='list_subbtn list-group'>";
    var ul_suffix = "</ul>";
    var h5_prefix = "<h5>";
    var h5_suffix = "</h5>";
    var badge_prefix = "<span class='badge'>";
    var badge_suffix = "</span>";
    var button_add_sub = "<span class='glyphicon glyphicon-plus btn btn-xs btn-default pull-right button_add_sub'  aria-hidden='true'></span>";

    if (button.hasOwnProperty('type')) {
        html += a_list_group_prefix + "<span class='button_span' data-subcount='0' id='" + processButton.buttonID + "'>" + button['name'] + "</span>" + button_add_sub + a_list_group_suffix;
        menu_buttons[processButton.buttonID] = button;
    } else {
        html += li_prefix + "<span class='button_span' data-subcount='" + button['sub_button']['list'].length + "' id='" + processButton.buttonID + "'>" + button['name'] + "</span>" + button_add_sub + ul_prefix;
        menu_buttons[processButton.buttonID] = {
            "name": button['name']
        };
        //drilling down to subbutton
        $.each(button['sub_button']['list'], function(j, sub_button) {
            processButton.buttonID = ++processButton.buttonID || 1;
            html += a_list_subbtn_group_prefix + "<span class='button_span' id='" + processButton.buttonID + "'>" + sub_button['name'] + "</span>" + a_list_group_suffix;
            menu_buttons[processButton.buttonID] = sub_button;
        });
        html += ul_suffix + li_suffix;
    }
    return html;
}

//on add first level btn clicked
$('#add_btn').click(function(e) {
    $('#form-keyOrUrlOrMediaId').trigger('change');
    var first_level_btn_num = $('#current-menu-list').children('.btn-li').size();
    if (first_level_btn_num >= MAX_BUTTON_NUM) {
        alert("达到最大一级菜单数目");
        return;
    } else {
        //now adding a new first level btn
        //enable the buttons if it's the first time open
        $('#button-operation-group').children('button').removeAttr('disabled');
        // create object with default name, click type and empty key
        processButton.buttonID = ++processButton.buttonID || 1;
        var id = processButton.buttonID;
        menu_buttons[id] = {
            'name': "我还没名字呢",
            'type': 'click',
            'key': ''
        };
        //use the id from last and populate to div.button-detail and menu_buttons
        populate_button_detail(id);
        //create html for it
        $('.menu-clickable').removeClass("active");
        var a_list_group_prefix_active = "<li  class='btn-li list-group-item btn btn-default menu-clickable clearfix active'>";
        var a_list_group_suffix = "</li>";
        var button_add_sub = "<span class='glyphicon glyphicon-plus btn btn-xs btn-default pull-right button_add_sub'  aria-hidden='true'></span>";

        var html = a_list_group_prefix_active + "<span class='button_span' data-subcount='0' id='" + id + "'>" + menu_buttons[id]['name'] + "</span>" + button_add_sub + a_list_group_suffix;
        $('#current-menu-list').append(html);
    }
});



//on click  add subbutton
$('#current-menu-list').on('click', '.button_add_sub', function(e) {
    //get data-subcount, if 0 then pop up modal to ask to confirm, if more than MAX_SUB_BUTTON_NUM then popup tool tips and exit
    //alert("add sub button clicked");

    $('#form-keyOrUrlOrMediaId').trigger('change');
    var subcount = $(this).prev().data('subcount');
    if (subcount == 0) {
        $('#modal-title').html("请确认");
        var modal_body_html = "<p>如果添加将会导致当前设置失效，确认吗</p>";
        $('#modal-body').html(modal_body_html);
        //identifies the usage of the modal using name attr
        $('#menu_modal').data('usage', 'confirm-add-first-subbutton');
        //bind the clicked button to the clicked data assoc, pass it to modal to handle
        $('#menu_modal').data('clicked', $(this));
        $('#menu_modal').modal('show');
    } else if (subcount >= MAX_SUB_BUTTON_NUM) {
        alert("已达到最大二级菜单数目");
        return;
    } else if (isNaN(subcount)) {
        alert("出错，请联系开发者");
        return;
    } else {
        add_sub_button($(this));
    }

});

//this takes in the clicked button_add_sub and add the sub button
function add_sub_button($clicked) {
    //enable the buttons if it's the first time open
    $('#button-operation-group').children('button').removeAttr('disabled');
    //add 1 to data-subcount
    var subcount = $clicked.prev().data('subcount');
    $clicked.prev().data('subcount', subcount + 1);

    // create object with default name, click type and empty key
    processButton.buttonID = ++processButton.buttonID || 1;
    var id = processButton.buttonID;
    menu_buttons[id] = {
        'name': "我还没名字呢",
        'type': 'click',
        'key': ''
    };

    //use the id from last and populate to div.button-detail and menu_buttons
    populate_button_detail(id);

    //add to menu_button_list
    if (subcount == 0) {
        //it's the fisrt sub button
        var a_list_group_prefix_active = "<li  class='subbtn-li list-group-item btn btn-default menu-clickable clearfix active'>";
        var a_list_group_suffix = "</li>";
        var ul_prefix = "<ul class='list_subbtn list-group'>";
        var ul_suffix = "</ul>";
        var html = ul_prefix + a_list_group_prefix_active + "<span class='button_span' id='" + id + "'>" + menu_buttons[id]['name'] + "</span>" + a_list_group_suffix + ul_suffix;
        $(html).insertAfter($clicked);
        $clicked.parent().removeClass('active btn btn-default menu-clickable');

        initSubbtnListSortable();



    } else {
        $('.menu-clickable').removeClass("active");
        var a_list_group_prefix_active = "<li  class='subbtn-li list-group-item btn btn-default menu-clickable clearfix active'>";
        var a_list_group_suffix = "</li>";
        var html = a_list_group_prefix_active + "<span class='button_span' id='" + id + "'>" + menu_buttons[id]['name'] + "</span>" + a_list_group_suffix;

        //append one to the last
        $clicked.next().append(html);
    }
}

//on select button
$('#current-menu-list').on('click', '.menu-clickable', function(e) {
    e.preventDefault();
    $('#form-keyOrUrlOrMediaId').trigger('change');
    $('#current-menu-list').find('.menu-clickable').removeClass('active');
    $(this).addClass('active');
    $('#button-operation-group').children('button').removeAttr('disabled');

    var id = $(this).children("span.button_span").attr("id");
    populate_button_detail(id);
});

//this takes in the id and populate the button detail to the div.button-detail
function populate_button_detail(id) {

    var button_type = menu_buttons[id]['type']; //I call it type here, by when accepting in coming message xml, it is called "event", it is "click", etc
    var button_assoc = event_assoc_match[button_type]; //this is either key/url/media_id or "" for now


    $("div.button-detail").attr("id", id); //set div id
    //$("#form_event_type option[value='"+button_type+"']").prop('selected', true); //set select option
    $('#form_event_type').val(button_type);
    $("#label-keyOrUrlOrMediaId").html(assoc_chi[button_assoc]); //set assoc label, ******Should this be handled by the onchange of the select?
    //$("#form-keyOrUrlOrMediaId").attr("placeholder",assoc_chi[button_assoc]);//set placeholder
    var $input = $("#form-keyOrUrlOrMediaId");
    if (button_assoc && button_assoc.localeCompare("") != 0) {
        $input.removeProp('disabled');
        var button_assoc_val = menu_buttons[id][button_assoc];
        $input.val(button_assoc_val);
        if (button_assoc.localeCompare("url") == 0) {
            $input.attr('type', 'url');
        } else {
            $input.attr('type', 'text');
        }
    } else {
        $input.val("");
        $input.prop('disabled', true);
    }
}
//on event type change
$('#form_event_type').change(function() {
    var id = parseInt($('div.button-detail').attr('id'));
    if (!isNaN(id)) {
        //change and get type in menu_buttons
        var $input = $("#form-keyOrUrlOrMediaId");
        $input.removeProp('disabled');
        var type = $(this).val();
        menu_buttons[id]['type'] = type;
        //use type to define assoc for menu_buttons, ADD assoc in menu_buttons (what if type was news of text)
        var button_assoc = event_assoc_match[type];
        var $input = $('#form-keyOrUrlOrMediaId');
        var button_assoc_content = $input.val();
        //change type to url to prevent input invalid url
        //console.log(button_assoc);
        if (button_assoc.localeCompare("url") == 0) {
            $input.attr('type', 'url');
        } else {
            $input.attr('type', 'text');
        }
        menu_buttons[id][button_assoc] = button_assoc_content;
        //change label
        $("#label-keyOrUrlOrMediaId").html(assoc_chi[button_assoc]);
        console.log(menu_buttons[id]);
    }

});

//on button key/url/mediaID change
$('#form-keyOrUrlOrMediaId').change(function() {
    var id = parseInt($('div.button-detail').attr('id'));
    if (!isNaN(id)) {
        var type = menu_buttons[id]['type'];
        var button_assoc = event_assoc_match[type];
        var button_assoc_content = $(this).val();
        menu_buttons[id][button_assoc] = button_assoc_content;
        console.log(menu_buttons[id]);
    }
});

//on clikc rename button, create and open rename modal 
$('#open-rename-subbutton-modal').click(function() {
    $('#form-keyOrUrlOrMediaId').trigger('change');
    var id = parseInt($('div.button-detail').attr('id'));

    $('#modal-title').html("重命名菜单按钮名称");
    var modal_body_html = "<input id='rename-input' class='form-control' value='" + menu_buttons[id]['name'] + "' type='text'>";
    $('#modal-body').html(modal_body_html);
    //identifies the usage of the modal using name attr
    $('#menu_modal').data('usage', 'rename');
    $('#menu_modal').modal('show');

});

//on click delete button, create and open delete modal
$('#open-delete-subbutton-modal').click(function() {
    $('#form-keyOrUrlOrMediaId').trigger('change');
    var id = parseInt($('div.button-detail').attr('id'));

    $('#modal-title').html("确认删除");
    var modal_body_html = "再问一遍，确认吗？";
    $('#modal-body').html(modal_body_html);
    //identifies the usage of the modal using name attr
    $('#menu_modal').data('usage', 'delete');
    $('#menu_modal').modal('show');

});

//on modal save clicked
$('#modal-save-change-button').click(function() {
    var id = parseInt($('div.button-detail').attr('id'));
    switch ($('#menu_modal').data('usage')) {
        case 'rename':
            {
                menu_buttons[id]['name'] = $('#rename-input').val();
                $("span.button_span#" + id).html(menu_buttons[id]['name']);
                break;
            }
        case 'delete':
            {
                //only get rid of it in the left side button list
                var $menu_button_list_item = $("span.button_span#" + id).parent(".menu-clickable");
                var $firstlevelbuttonspan = $("span.button_span#" + id).parent().parent().siblings('[data-subcount]');
                var outter_ul; //this is the outter ul, this would be false if if it is not the last one
                if ($menu_button_list_item.siblings().size() == 0) {
                    //this means it is the last one
                    outter_ul = $menu_button_list_item.parent();
                    $menu_button_list_item.parent().parent().addClass('menu-clickable btn btn-default');
                }
                $menu_button_list_item.slideUp("normal", function() {
                    $(this).remove();
                });
                if (outter_ul) {

                    outter_ul.remove();
                }
                $('#button-operation-group').children('button').prop('disabled', true);

                //reset data-subcount

                $firstlevelbuttonspan.data("subcount", $firstlevelbuttonspan.data("subcount") - 1);
                //alert($firstlevelbuttonspan.data("subcount"));
                break;
            }

        case 'confirm-add-first-subbutton':
            {
                add_sub_button($('#menu_modal').data('clicked'));
                break;
            }
        default:
            {
                break;
            }
    }
});

$('#big-submit-button').click(function(e) {
    $('#big-submit-button').button('loading');
    $('#form-keyOrUrlOrMediaId').trigger('change');
    $btn_array=[];
    $('#current-menu-list').children('li.btn-li').each(function(){
        //for each first level btn
        var $first_button_span = $(this).children("span.button_span");
        console.log($first_button_span.text());
        var id = $first_button_span.attr("id");
        var first_btn_obj = {'name':menu_buttons[id]['name']};//this is the contructed button object, will be pushed to the btn_array
        if($first_button_span.data("subcount") > 0) {
            //it has sub buttons
             var subbtn_array=[];

             $(this).children('ul').children('li').each(function(){
                var $sub_button_span = $(this).children("span.button_span");
                var sub_id = $sub_button_span.attr("id");
               
                var sub_type=menu_buttons[sub_id]['type'];
                var sub_button_assoc = event_assoc_match[sub_type];
                var sub_btn_obj = {
                    'name':menu_buttons[sub_id]['name'],
                    'type':sub_type,
                };
                sub_btn_obj[sub_button_assoc]=menu_buttons[sub_id][sub_button_assoc];
                subbtn_array.push(sub_btn_obj);
             });
              first_btn_obj['sub_button']=subbtn_array;

        } else {
            //it doesn't have sub buttons
            var type=menu_buttons[id]['type'];
            var button_assoc = event_assoc_match[type];
            first_btn_obj['type']=type;
            first_btn_obj[button_assoc] = menu_buttons[id][button_assoc];
        }
        $btn_array.push(first_btn_obj);
    });
    
    var ret_obj={"button":$btn_array};
    var str_json=JSON.stringify(ret_obj);
    //console.log(str_json);
    //$.post('./php/menuGetterSetter.php?func=set_test',str_json,function(data){console.log(data);},'json');
    $.post('./php/menuGetterSetter.php?func=set',{'str_json':str_json},function(data){
        $('#big-submit-button').button('reset');
        if(data['errcode'] == 0){
            alert("菜单更新成功");
        } else{
             alert("菜单更新失败，错误代码".data['errcode']);
        }
    },'json');

});