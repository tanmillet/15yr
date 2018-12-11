<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Count\base;
use App\Models\Model;
/**
 * Description of MoneyCountInfo
 *
 * @author 七彩P1
 */
class MoneyCountInfo extends Model{
    //put your code here
    public $table = "money_count_info";
    public $timestamps =FALSE;
    
    public $type_arr = [
                        "1"=>"金币总发放","2"=>"金币总消耗","3"=>"金币净消耗","4"=>"业务发放","5"=>"业务消耗",
                        "6"=>"业务净消耗","7"=>"机器人净赢","8"=>"人均金币数","9"=>"金币总量","10"=>"活跃金币总量","11"=>"活跃用户人均金币","12"=>"注册金币总量","13"=>"注册用户人均金币"
                        ];
    //需要显示的 金币类型
    public $show_arr = ["9"=>"金币总量","1"=>"总送出金币","2"=>"总回收金币","3"=>"金币收益","10"=>"活跃金币总量","11"=>"活跃用户人均金币","12"=>"注册金币总量","13"=>"注册用户人均金币"];
    //统计的code 1 送出  2是回收
    public $countCode = [
            "117777"=>[2],
            "117778"=>[2],
            "117780"=>[2],
            "117779"=>[2],
            '300'=>[1],
            '301'=>[1],
            '302'=>[2],
            '303'=>[1],
            '304'=>[1],
            //'305'=>'保险箱',
            //'306'=>'比大小',

            //'306'=>'比大小',
            '500'=>[1,2],
            //'501'=>'连续登陆奖励',

            '502'=>[1,2],
            '503'=>[2],
            "504"=>[1],
            '506'=>[1],
            "505"=>[1],
            "507"=>[2],
            "508"=>[1],
            "510"=>[1],
        
            '600'=>[1],
            '601'=>[1],
            //'602'=>'龙船竞赛',
            //'603'=>'粽子兑换',
            //'604'=>'纪念屈原',
            '605'=>[1],
            '606'=>[1],
           // '607'=>'惊喜一夏',
            '608'=>[1],
            '611'=>[1],
            '612'=>[1],
            
    ];
    public $otherCode =[
                            "117777"=>"娱乐场服务费",
                            "117778"=>"娱乐场机器人陪打赢",
                            "117780"=>"牌局服务费",
                            "117779"=>"斗地主机器人陪打赢",
                        ];
    //服务费变动的code
    public $codeServer = [ '11', '21', '31', '41', '51', '61'];
    
    //牌局结束的变动code---包括排位赛的 ---根据 gameid 跟gametype区分
    public $codeCard = [ '12','13', '22','23', '32','33', '42','43', '52','53', '62','63'];
    //送出
    public $sendCodeArr =["17777"];
    
    public function moneyCode(){
        return config("socket.code") + $this->otherCode;
    }
    
    
    public function getCountCode(){
        $codeArr = $this->countCode;
        $ret = array();
        foreach($codeArr as $code=>$name){
            $ret[$code] = isset($this->countCode[$code])?$this->countCode[$code]:[1];
        }
        return $ret;
    }
}
