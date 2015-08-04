
<?php
/**
 * wechat php test
 */

//define your token
define("TOKEN", "chidu4u");
$wechatObj = new wechatCallbackApi();
//$wechatObj->valid();

require_once('connectDB.php');
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
                default:
                    echo "";
                    exit;
            }
        } else {
            echo "";
            exit;
        }
    }
    
    /*
     * handles text messages
     param $postObj: an xml object parsed from the post string
     param $conn: the mysql connection
     */
    function textHandler($postObj,$conn)
    {
    	$toUsername   = $postObj->ToUserName;
        $fromUsername = $postObj->FromUserName;
        $createTime   = $postObj-> CreateTime;
        $msgType   = $postObj-> MsgType;
        $content   = trim($postObj-> Content);
        $msgId   = $postObj-> MsgId;
        
        //Now saving to database
        $content_mysqlescaped = mysqli_real_escape_string($conn, $content);
        
        $sql = "REPLACE INTO msg_incoming (ToUserName, FromUserName, CreateTime, MsgType,Content,MsgId) VALUES ('".$toUsername."','".$fromUsername."',".$createTime.",'".$msgType."','".$content."',".$msgId.")";
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
        if (!empty($content)) {
            $msgType    = "text";
            $contentStr = "尺度君谢谢你的消息！：）";
            $resultStr  = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
            echo $resultStr;
        } else {
            echo "Input something...";
        }
    }
     /*
     * handles image messages
     param $postObj: an xml object parsed from the post string
     param $conn: the mysql connection
     */
    function imageHandler($postObj,$conn)
    {
    	$toUsername   = $postObj->ToUserName;
        $fromUsername = $postObj->FromUserName;
        $createTime   = $postObj-> CreateTime;
        $msgType   = $postObj-> MsgType;
        $picUrl = $postObj-> PicUrl;
        $mediaId   = $postObj-> MediaId;
        $msgId   = $postObj-> MsgId;
        
        //Now saving to database
        $picUrl_mysqlescaped = mysqli_real_escape_string($conn, $picUrl);
        
        $sql = "REPLACE INTO msg_incoming (ToUserName, FromUserName, CreateTime, MsgType,PicUrl,MediaId, MsgId) VALUES ('".$toUsername."','".$fromUsername."',".$createTime.",'".$msgType."','".$picUrl_mysqlescaped."','".$mediaId."',".$msgId.")";
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
            $contentStr = "尺度君谢谢你的图图！：）";
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
        
        $sql = "REPLACE INTO msg_incoming (ToUserName, FromUserName, CreateTime, MsgType,Format,MediaId, MsgId) VALUES ('".$toUsername."','".$fromUsername."',".$createTime.",'".$msgType."','".$format."','".$mediaId."',".$msgId.")";
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
            $contentStr = "尺度君会来倾听你的声音！：）";
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
        
        $sql = "REPLACE INTO msg_incoming (ToUserName, FromUserName, CreateTime, MsgType,ThumbMediaId,MediaId, MsgId) VALUES ('".$toUsername."','".$fromUsername."',".$createTime.",'".$msgType."','".$thumbMediaId."','".$mediaId."',".$msgId.")";
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
            $contentStr = "尺度君谢谢你的小视频！：）";
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