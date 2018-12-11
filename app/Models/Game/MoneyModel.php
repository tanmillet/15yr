<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Game;
use App\Server\Socket;
use Illuminate\Support\Facades\Redis;
use App\Server\RedisKey;
use App\Models\Game\base\userInfo;
/**
 * Description of MoneyModel
 *
 * @author 七彩P1
 */
class MoneyModel {
    

    public $type = [
                "uchip"=>1,//金钱  
                "udiamond"=>2,//砖石  
                "utombola"=>3, //彩券
                "room_ticket"=>4 //房卡
            ];
    
    public $typeName = [
                "uchip"=>"金钱",//金钱  
                "udiamond"=>"钻石",//砖石  
                "utombola"=>"宾王卷", //宾王卷
                "room_ticket"=>"房卡", //房卡
            ];
    public $moneyFlagType =[
                "0" =>"增加",
                "1" =>"扣除",
    ];
    /*
     * 操作用户的金币
     * uid 用户id money需要加减的游戏b code操作的类型 type游戏B的类型
     * moneyFlag:0正1负
     */
    public function operateMoney($uid,$money,$code,$type,$moneyFlag=0){
        $code = config("socket.code_use.".$code);
        if(!$code || !isset($this->type[$type])){
            return FALSE;
        }
        if($money>0 && $moneyFlag==0){
            $moneyFlag=0;
        }else{
            $money = abs($money);
            $moneyFlag=1;
        }
         //获取用户
        $user = (new UserInfo())->where("uid",$uid)->first();
        if(!$user ){
            return FALSE;
        }
        
        //$sendStr = "uid:".$uid .",money:".$money.",code:".$code.",type:".$this->type[$type].",moneyFlag:".$moneyFlag;
        $sendArr['uid'] = (int)$uid;
        $sendArr['money'] = $money;
        $sendArr['code'] = $code;
        $sendArr['moneytype'] = $this->type[$type];
        $sendArr['moneyFlag'] = $moneyFlag;
        $sendArr['msgtype'] = 1;
        $sendArr['game'] = $user["game"];
        $sendJson = json_encode($sendArr);
        
       
        $userArr = $user->toArray();
        
        if($user["game"] ==4){
            $str = pack("n", config("socket.newServerCodeType.changeMoney"));
            $command = $str . $sendJson;
            $sendStr= pack("n", strlen($command)) . $command;
        }else{
            $len = strlen($sendJson);
            $head = str_pad($len,4,"0",STR_PAD_LEFT);//根sever协商发过去的前四个字节是字符串的长度
            $sendStr= $head.$sendJson ;
        }
        $i =0; 
        
        $configArr = config("socket.connect.hallServer".$user["game"]);
        !is_array($configArr) && empty($configArr) && $configArr=array();
        $socket = new Socket($configArr);
        while($i<3){
            $result=$socket->sendRequest($sendStr);
            if($result){
                $flag = $socket->waitForResponse();
                if($flag){
                    $flag= substr($flag,4);
                }
                if($flag){
                    $flag = json_decode($flag,true);
                    if(isset($flag['code']) && $flag['code']==0){
                        $redis = Redis::connection();
                        $redisKey = RedisKey::$Key;
                        $redis->rpush($redisKey["NOTICESERVERCHANGMONEY"],$uid);
                        return TRUE;
                    }
                }
            }
            $i++;
        }
        return FALSE;
    }
}