
<?php
	require_once("wxgongzhongpingtai/php/msgUtil.php");
	$arr=array(
	    "touser"=>"o2NgxuPpy3coCBIb85vRwTk3vPVo",
	    "msgtype"=>"music",
	    "music"=>
	    array(
	      "title"=>"MUSIC_TITLE",
	      "description"=>"MUSIC_DESCRIPTION",
	      "musicurl"=>"http://www.chidu4u.com/PPL5A6HE-kmqLqi59UjmW6mL8JDVKHnccCnsXM9trrs6V_ECSXah93YCBV4D3H6M.amr",
	      "hqmusicurl"=>"http://www.chidu4u.com/PPL5A6HE-kmqLqi59UjmW6mL8JDVKHnccCnsXM9trrs6V_ECSXah93YCBV4D3H6M.amr",
	      "thumb_media_id"=>"oocr-2VvywSXLrNK824Vo0a3sLHKTRFYevFSF7SRLw0" 
	    )
	);
		$ret = MsgUtil::sendMsgWithCompiledStr(json_encode($arr, JSON_UNESCAPED_UNICODE));

		var_dump($ret);
?>