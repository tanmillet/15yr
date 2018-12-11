<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Game\base;
use App\Models\Model;
/**
 * Description of UseGoodsLog
 *
 * @author 七彩P1
 */
class UseGoodsLog extends Model{
    //put your code here
    public $table="use_goods_log";
    public $connection="mysql_two";
    public $use_ref_arr =["1"=>"server操作","2"=>"","3"=>"订单使用","4"=>"定时使用道具","5"=>"活动来源","6"=>"任务使用","7"=>"抽奖使用","8"=>"用户在背包中使用","9"=>"充值赠送"];
    
    public $use_ref_arr_money_code=[//针对金币变动的
            "1"=>["serverUse"],
            "3"=>["goodsMake"],
            "4"=>["goodsMake"],
            //分享活动 邀请朋友 龙船竞赛 粽子兑换 纪念屈原 分享活动 登录活动 惊喜一夏 绑定公众号  11碎片兑换实物
            "5"=>[1=>"feed",2=>"invite",3=>"lzdh",4=>"zzdh",5=>"jjqy",6=>"feedtwo",7=>"loginPrize",8=>"jxyx","9"=>"bindGzhWechat","10"=>"bindGzhWechatWeek","11"=>"inventoryRealGood"],
            "6"=>["taskPrize"],
            "7"=>[1=>"feed",2=>"invite",3=>"lzdh",4=>"zzdh",5=>"jjqy",6=>"feedtwo",7=>"loginPrize",8=>"jxyx"],
            "8"=>["goodsMake"],
            "9"=>["bindGzhWechat"],
    ];
    
    
    public function getCode($ref,$search_ref){
        if(in_array($ref, array_keys($this->use_ref_arr_money_code))){
            $code = isset($this->use_ref_arr_money_code[$ref][$search_ref])?$this->use_ref_arr_money_code[$ref][$search_ref]:FALSE;
        }else{
            $code = FALSE;
        }
        return $code;
    }
}
