<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Game\base;
use App\Models\Model;
/**
 * Description of ScareBuy
 *
 * @author 七彩P1
 */
class ScareBuyLog extends Model{
    public $table="scare_buy_log";
    public  $connection="mysql_two";
//    public $type_arr = [1=>"循环夺宝",2=>"一次性夺宝"];
//    public $robot_num_type_arr = [0=>"无机器人",1=>"个数",2=>"百分比（100为单位）"];
}
