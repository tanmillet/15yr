<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Count\base;

use App\Models\Model;
/**
 * Description of BwsCount
 *
 * @author 七彩P1
 */
class ZfbMoneyCount extends Model{
    //put your code here
    public $table = "zfb_count";
    public $timestamps =FALSE;
    public $type_arr = [
        "1"=>"新增用户","2"=>"活跃用户","3"=>"下注用户","4"=>"下注金币总量","5"=>"上庄用户","6"=>"上庄金币总量",
                        "11"=>"下庄金币总量","7"=>"玩家闲家服务费","8"=>"玩家庄家服务费","12"=>"机器人闲家服务费(全部游戏)","13"=>"机器人庄家服务费(全部游戏)",
                        "9"=>"总服务费","10"=>"机器人赢"];
    
}