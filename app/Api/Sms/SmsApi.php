<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace App\Api\Sms;
use App\Api\Sms\SignatureHelper;
use App\Log\Facades\Logger;
/**
 * Description of newPHPClass
 *
 * @author 七彩P1
 */
class SmsApi {
    //put your code here
    public function __construct() {
 
    }
    //正常的发送短信的模板
    public function sendSms($mobile,$code,$templateCode){
        $mobile_arr = explode(",", $mobile);
        foreach($mobile_arr as $mobile){
            $templateParam = array("code"=>$code);
            $this->alySms($mobile, $templateParam, $templateCode);
        }
        return true;
    }
    

        //正常的发送短信的模板
    public function noticeSendSms($mobile,$msg,$templateCode){
        $mobile_arr = explode(",", $mobile);
        foreach($mobile_arr as $mobile){
            $templateParam = array("msg"=>$msg);
            $a =$this->alySms($mobile, $templateParam, $templateCode);
        }
        return true;
    }
    
    //发送实物通知
    public function realOrderSms($mobile,$goods_name,$hk,$password,$templateCode){
        $mobile_arr = explode(",", $mobile);
        $ret = array();
        foreach($mobile_arr as $mobile){
            $templateParam = array("goods_name"=>$goods_name,"user"=>$hk,"password"=>$password);
            $ret[] =$this->alySms($mobile, $templateParam, $templateCode);
        }
        return $ret;
    }
    
    public function alySms($mobile,$templateParam,$templateCode){
        $accessKeyId = env("ALYSMSACCESSKEYID","LTAIZpFE4laEjkx1");
        $accessKeySecret =env("ALYSMSACCESSKEYSECRET","d7LdXFUDxgmxGja0YkN8eqhKFCla1u") ;
        $params["PhoneNumbers"] = $mobile;
         // fixme 必填: 短信签名，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
        $params["SignName"] = env("ALYSMSSIGNNAME","阿里云短信测试专用");
        // fixme 必填: 短信模板Code，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
        $params["TemplateCode"] = $templateCode;
       // fixme 可选: 设置模板参数, 假如模板中存在变量需要替换则为必填项
        $params['TemplateParam'] = $templateParam;
        
            // *** 需用户填写部分结束, 以下代码若无必要无需更改 ***
        if(!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
            $params["TemplateParam"] = json_encode($params["TemplateParam"], JSON_UNESCAPED_UNICODE);
        }
            // 初始化SignatureHelper实例用于设置参数，签名以及发送请求
        $helper = new SignatureHelper();

        // 此处可能会抛出异常，注意catch
        $content = $helper->request(
            $accessKeyId,
            $accessKeySecret,
            "dysmsapi.aliyuncs.com",
            array_merge($params, array(
                "RegionId" => "cn-hangzhou",
                "Action" => "SendSms",
                "Version" => "2017-05-25",
            ))
            // fixme 选填: 启用https
            // ,true
        );
        return $content;
    }

    
    
    
    //钉钉通咋
    public function ddNotice($mobile,$msg){
        $mobile_arr = explode(",", $mobile);
        $messages=$msg;
        $data = array ('msgtype' => 'text','text' => array ('content' => $messages,),'at' => array ('atMobiles' => $mobile_arr,'isAtAll' => false,),);
        $data_string = json_encode($data);//echo $data_string;
        $urls= env("DDJQRNOTICE");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER,array('Content-Type: application/json; charset=utf-8'));
        curl_setopt($ch, CURLOPT_URL,$urls);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);curl_setopt($ch, CURLOPT_POST,true);  
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data_string); $retBase = curl_exec($ch);

        curl_close($ch);
        //echo $retBase;

        //根据返回值做出判断，不是成功直接抛出返回的JSON。
        $ret=json_decode($retBase,true);
        return $ret;
    }
}
