<?php
/**
 * wechat php test
 */

//define your token
//define("TOKEN", "chidu4u");
$wechatObj = new wechatCallbackApi();
//$wechatObj->valid();
wechatCallbackApi::sendToBrowserNExit('');
define('HTMLPATH', "/home/chidsjwb/public_html/wxgongzhongpingtai/");
define("EXTERNALHTMLPATH","http://chidu4u.cloudapp.net/wxgongzhongpingtai/");
define("GETACCESSTOKENPATH","/home/chidsjwb/wxgongzhongpingtai/getAccessToken.php");



require_once(HTMLPATH.'DBvar.php');
$conn = new mysqli(DBSERVERNAME, DBUSERNAME, DBPASSWORD,DBNAME);
if ($conn ->connect_errno) {
    error_log("Connect failed: %s\n", $conn ->connect_error);
}else {
	mysqli_set_charset ( $conn , 'utf8mb4' );
}
$wechatObj->responseMsg($conn);

class wechatCallbackApi
{
    public function valid()
    {
        
        $echoStr = $_GET["echostr"];
        
        //valid signature , option
        if ($this->checkSignature()) {
            echo $echoStr;
            exit;
        }
    }
    
    public function responseMsg($conn)
    {
    	
    	
        //get post data, May be due to the different environments
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        //extract post data
        if (!empty($postStr)) {
            /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
            the best way is to check the validity of xml by yourself */
            libxml_disable_entity_loader(true);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $msgType = $postObj->MsgType;
            switch ($msgType) {
                case "text":
                    $this->textHandler($postObj,$conn);
                    exit;
                case "image":
                    $this->imageHandler($postObj,$conn);
                    exit;
                case "voice":
                    $this->voiceHandler($postObj,$conn);
                    exit;
                case "shortvideo":
                    $this->shortvideoHandler($postObj,$conn);
                    exit;
                case "event":
                    $this->eventHandler($postObj,$conn);
                    exit;
                default:
                    echo "";
                    exit;
            }
        } else {
            echo "";
            exit;
        }
    }
    
    
    public static function sendToBrowserNExit($str){
    	/*This piece of code will exceute first, then flush to browser*/
    	ob_end_clean();
    	header("Connection: close");
    	//ignore_user_abort(); // optional
    	ob_start();
    	echo ($str);
    	$size = ob_get_length();
    	header("Content-Length: $size");
    	ob_end_flush();     // Will not work
    	flush();            // Unless both are called !
    }
    
    /*
     * handles text messages
     param $postObj: an xml object parsed from the post string
     param $conn: the mysql connection
     */
    function textHandler($postObj,$conn)
    {
    	if($message_id = self::checkNSave2DB($postObj,$conn)){
    		require_once(HTMLPATH."php/replyWithRule.php");
    		$ret = ReplyWithRule::respondTextNEvent($postObj, $conn);
    		
    		if($ret){
    			//update the message and set rule_auto_replied to 1
    			$sql_texthandler = "UPDATE msg_incoming SET rule_auto_replied=%s WHERE id=%s";
    			$sql_texthandler = sprintf($sql_texthandler, 1,$message_id );
    			$conn -> query($sql_texthandler);
    		}
    	} else {
    	}
	
	
	return;
    
    }


    function eventHandler($postObj,$conn)
    {
        //error_log("handling event");
        if($message_id = self::checkNSave2DB($postObj,$conn)){
        //if(true){
            require_once(HTMLPATH."php/replyWithRule.php");
            $ret = ReplyWithRule::respondTextNEvent($postObj, $conn);
            if($ret){
                //update the message and set rule_auto_replied to 1
                $sql_texthandler = "UPDATE msg_incoming SET rule_auto_replied=%s WHERE id=%s";
                $sql_texthandler = sprintf($sql_texthandler, 1,$message_id );
                $conn -> query($sql_texthandler);
            }
        } else {
        }
    
    
    return;
    
    }
    /*THis function will return false if the message has already been handled(already exist in the database)
    	It will return the inserted id if this is the first time it was seen in the database and needs to be handled
    	It only checks for text for now
    */
    function checkNSave2DB($postObj,$conn){
    	 $fromUsername =(string) $postObj->FromUserName;
	   $createTime   = (string)$postObj-> CreateTime;
    	$sql_checkTextNSave2DB = "SELECT EXISTS(SELECT 1 FROM msg_incoming WHERE FromUserName='%s' AND CreateTime=%s )";
    	$sql_checkTextNSave2DB = sprintf($sql_checkTextNSave2DB ,$fromUsername,$createTime );
        //error_log("now checkNSave2DB, check sql: ".$sql_checkTextNSave2DB);
    	$result = $conn->query($sql_checkTextNSave2DB);
    	if($result) {
    		$row = $result->fetch_assoc();
    		if(intval(reset($row)) == 0) {//this means it doesn't exist yet

                switch($postObj->MsgType){
                    case 'text':{
                        $toUsername   = (string)$postObj->ToUserName;
                        $fromUsername =(string) $postObj->FromUserName;
                        $createTime   = $postObj-> CreateTime;
                        $msgType   = $postObj-> MsgType;
                        $content = $postObj-> Content;
                        $msgId   = $postObj-> MsgId;
                        $content_mysqlescaped = mysqli_real_escape_string($conn, $content );
                        //insert into db
                        $sql_checkTextNSave2DB = "INSERT INTO msg_incoming (ToUserName, FromUserName, CreateTime, MsgType,Content, MsgId) VALUES ('%s','%s',%s,'%s','%s',%s)";
                        $sql_checkTextNSave2DB = sprintf($sql_checkTextNSave2DB ,$toUsername,$fromUsername,$createTime,$msgType,$content_mysqlescaped,$msgId);
                        $conn->query($sql_checkTextNSave2DB);
                        //error_log('not found, inserted');
                        return $conn->insert_id;
                        break;
                    }
                    case 'event':{
                        $toUsername   = (string)$postObj->ToUserName;
                        $fromUsername =(string) $postObj->FromUserName;
                        $createTime   = $postObj-> CreateTime;
                        $msgType   = $postObj-> MsgType;
                        $event = strtolower ($postObj-> Event);//IDK Why the heck they dont keep the case consistent
                        $event_key   = $postObj-> EventKey;
                        $event_key_mysqlescaped = mysqli_real_escape_string($conn, $event_key );
                        //insert into db
                        $sql_checkTextNSave2DB = "INSERT INTO msg_incoming (ToUserName, FromUserName, CreateTime, MsgType,Event, EventKey) VALUES ('%s','%s',%s,'%s','%s','%s')";
                        $sql_checkTextNSave2DB = sprintf($sql_checkTextNSave2DB ,$toUsername,$fromUsername,$createTime,$msgType,$event,$event_key_mysqlescaped);
                        //error_log("now checkNSave2DB, inserting with sql:".$sql_checkTextNSave2DB);
                        $conn->query($sql_checkTextNSave2DB);
                        //error_log('not found, inserted');
                        return $conn->insert_id;
                        break;
                    }
                    default:{
                        break;
                    }
        			
                }
    			
    		} else {
    			//error_log('Found, not inserted');
    			return false;
    		}
    	} else {
    		trigger_error('No result inserting message into database, sql is: '.$sql_checkTextNSave2DB );
    	}
    	
    }
    
    
     /*5
     * handles image messages
     param $postObj: an xml object parsed from the post string
     param $conn: the mysql connection
     */
    function imageHandler($postObj,$conn)
    {
    	$toUsername   = (string)$postObj->ToUserName;
        $fromUsername =(string) $postObj->FromUserName;
        $createTime   = $postObj-> CreateTime;
        $msgType   = $postObj-> MsgType;
        $picUrl = $postObj-> PicUrl;
        $mediaId   = $postObj-> MediaId;
        $msgId   = $postObj-> MsgId;
        $str = var_export($fromUsername , true);
        //Now saving to database
        $picUrl_mysqlescaped = mysqli_real_escape_string($conn, $picUrl);
        
        $sql = "INSERT INTO msg_incoming (ToUserName, FromUserName, CreateTime, MsgType,PicUrl,MediaId, MsgId) VALUES ('".$toUsername."','".$fromUsername."',".$createTime.",'".$msgType."','".$picUrl_mysqlescaped."','".$mediaId."',".$msgId.")";
        $result = $conn->query($sql);
        
        
        $time         = time();
        $textTpl      = "<xml>
			<ToUserName><![CDATA[%s]]></ToUserName>
			<FromUserName><![CDATA[%s]]></FromUserName>
			<CreateTime>%s</CreateTime>
			<MsgType><![CDATA[%s]]></MsgType>
			<Content><![CDATA[%s]]></Content>
			<FuncFlag>0</FuncFlag>
			</xml>";
        if (!empty($picUrl)) {
            $msgType    = "text";
            $contentStr = "³ß¶È¾ýÐ»Ð»ÄãµÄÍ¼Í¼£¡£º£©";
            $resultStr  = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
            echo $resultStr;
        } else {
            echo "Input something...";
        }
    }
    
    
    /*
     * handles voice messages
     param $postObj: an xml object parsed from the post string
     param $conn: the mysql connection
     */
    function voiceHandler($postObj,$conn)
    {
    	$toUsername   = $postObj->ToUserName;
        $fromUsername = $postObj->FromUserName;
        $createTime   = $postObj-> CreateTime;
        $msgType   = $postObj-> MsgType;
        $format = $postObj-> Format;
        $mediaId   = $postObj-> MediaId;
        $msgId   = $postObj-> MsgId;
        
        //Now saving to database
        
        $sql = "INSERT INTO msg_incoming (ToUserName, FromUserName, CreateTime, MsgType,Format,MediaId, MsgId) VALUES ('".$toUsername."','".$fromUsername."',".$createTime.",'".$msgType."','".$format."','".$mediaId."',".$msgId.")";
        $result = $conn->query($sql);
        
        
        $time         = time();
        $textTpl      = "<xml>
			<ToUserName><![CDATA[%s]]></ToUserName>
			<FromUserName><![CDATA[%s]]></FromUserName>
			<CreateTime>%s</CreateTime>
			<MsgType><![CDATA[%s]]></MsgType>
			<Content><![CDATA[%s]]></Content>
			<FuncFlag>0</FuncFlag>
			</xml>";
        if (!empty($format)) {
            $msgType    = "text";
            $contentStr = "³ß¶È¾ý»áÀ´ÇãÌýÄãµÄÉùÒô£¡£º£©";
            $resultStr  = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
            echo $resultStr;
        } else {
            echo "Input something...";
        }
    }
    
    /*
     * handles shortvideo messages
     param $postObj: an xml object parsed from the post string
     param $conn: the mysql connection
     */
    function shortvideoHandler($postObj,$conn)
    {
    	$toUsername   = $postObj->ToUserName;
        $fromUsername = $postObj->FromUserName;
        $createTime   = $postObj-> CreateTime;
        $msgType   = $postObj-> MsgType;
        $thumbMediaId = $postObj-> ThumbMediaId;
        $mediaId   = $postObj-> MediaId;
        $msgId   = $postObj-> MsgId;
        
        //Now saving to database
        
        $sql = "INSERT INTO msg_incoming (ToUserName, FromUserName, CreateTime, MsgType,ThumbMediaId,MediaId, MsgId) VALUES ('".$toUsername."','".$fromUsername."',".$createTime.",'".$msgType."','".$thumbMediaId."','".$mediaId."',".$msgId.")";
        $result = $conn->query($sql);
        
        
        $time         = time();
        $textTpl      = "<xml>
			<ToUserName><![CDATA[%s]]></ToUserName>
			<FromUserName><![CDATA[%s]]></FromUserName>
			<CreateTime>%s</CreateTime>
			<MsgType><![CDATA[%s]]></MsgType>
			<Content><![CDATA[%s]]></Content>
			<FuncFlag>0</FuncFlag>
			</xml>";
        if (!empty($thumbMediaId)) {
            $msgType    = "text";
            $contentStr = "³ß¶È¾ýÐ»Ð»ÄãµÄÐ¡ÊÓÆµ£¡£º£©";
            $resultStr  = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
            echo $resultStr;
        } else {
            echo "Input something...";
        }
    }
    
    
    private function checkSignature()
    {
        // you must define TOKEN by yourself
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }
        
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce     = $_GET["nonce"];
        
        $token  = TOKEN;
        $tmpArr = array(
            $token,
            $timestamp,
            $nonce
        );
        // use SORT_STRING rule
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        
        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }
}


?>