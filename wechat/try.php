<?php



$wechatObj = new wechatCallbackapiTest();
$wechatObj->responseMsg();
class wechatCallbackapiTest{


    public function responseMsg(){

        $postStr = file_get_contents("php://input", 'r');
        if (!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $keyword = trim($postObj->Content);
            $time = time();
            $textTpl = "<xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[%s]]></MsgType>
            <Content><![CDATA[%s]]></Content>
            <FuncFlag>0<FuncFlag>
            </xml>";
            if(!empty($keyword)){
                if (preg_match('/\d{8}$/',strval($keyword))) {
                    if (preg_match('/[2][0][2][1]\d{4}$/',$keyword)){
                        $contentStr = "大一请输入小班（样例：A-21）!";
                    }else{
                        $contentStr = "你的学号是：".$keyword.";你的课表下载链接是：http://www.corsaircat.com/operationZ/index.php?userid=".$keyword."&ignoreLocal=owjs91 ；请复制链接在系统浏览器打开"; 
                    }
                }else{
                    if (preg_match('/[A,B,C,a,b,c][-][0-9]{2}$/',$keyword)){
                        $keyword = strtoupper($keyword);
                        $contentStr = "你的小班是：".$keyword.";你的课表下载链接是：http://www.corsaircat.com/operationZ/index-Y1.php?userid=".$keyword."&ignoreLocal=owjs91 ；请复制链接在系统浏览器打开"; 
                    }else{
                        if (preg_match('/[A,B,C,a,b,c][0-9]{2}$/',$keyword)){
                            $keyword = strtoupper($keyword);
                            $keywordNew = $keyword[0]."-".$keyword[1].$keyword[2];
                            $contentStr = "你的小班是：".$keywordNew.";你的课表下载链接是：http://www.corsaircat.com/operationZ/index-Y1.php?userid=".$keywordNew."&ignoreLocal=owjs91 ；请复制链接在系统浏览器打开"; 
                        }else{
                            $contentStr = "大二三四输入学号（样例：20124992），大一请输入小班（样例：A-21）";
                        }
                    }
                }
                $msgType = "text";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                echo $resultStr;
            }else{
                echo '大二三四输入学号（样例：20124992），大一请输入小班（样例：A-21）';
            }
        }else {
            echo '大二三四输入学号（样例：20124992），大一请输入小班（样例：A-21）';
            exit;
        }
    }
}