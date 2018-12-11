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
class BrnnMoneyCount extends Model{
    //put your code here
    public $table = "brnn_count";
    public $timestamps =FALSE;
    //1玩家总上庄金额2玩家总下注金额3服务费4机器人上庄赢5机器人陪打赢
    public $type_arr = ["1"=>"玩家总上庄赢","7"=>"玩家上庄人数","2"=>"玩家总下注金额","6"=>"玩家下注人数","3"=>"服务费(含机器人)","4"=>"机器人上庄赢（已扣服务费）","5"=>"机器人陪打赢（已扣服务费）"];
    
    public $show_money_type_arr = ["3"=>"娱乐场服务费","4"=>"娱乐场机器人上庄赢","5"=>"娱乐场机器人陪打赢"];
}