<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Count\base;
use App\Models\Model;
/**
 * Description of ActiveCount
 *
 * @author 七彩P1
 */
class ActiveCount extends Model {
    //put your code here
    public $table = "active_count";
    public $timestamps =FALSE;
    
    public $active_type_arr = [
        "17"=>"中秋节兑换实物统计",
        "18"=>"中秋节灯笼统计",
        "7"=>"登录累计奖励统计",
        "20"=>"新手红包活动统计",
        "999"=>"登录奖励统计",
    ];
    
    public $type_arr = [
        "17"=>["1"=>"兑金币","2"=>"兑钻石","3"=>"兑宾王劵","4"=>"兑月饼","5"=>"兑音箱","6"=>"兑电视","1000"=>"活动总数","7"=>"送灯笼个数","8"=>"获得字个数"],
        "18"=>["1"=>"登录","2"=>"分享","3"=>"排位赛","4"=>"比赛","5"=>"金币场","1000"=>"活动总数"],
        "7"=>["1"=>"1天","2"=>"2天","3"=>"3天","4"=>"7天","5"=>"14天"],
        "20"=>["1"=>"第一天","2"=>"第二天","3"=>"第三天","4"=>"第四天","5"=>"第五天","6"=>"领取红包码人数","7"=>"领取红包人数"],
        "999"=>["7"=>"锁定800金币","8"=>"锁定1200金币","1"=>"800金币","2"=>"1200金币","3"=>"1600金币","4"=>"2000金币","5"=>"3000金币","6"=>"4000金币"],
    ];
}
