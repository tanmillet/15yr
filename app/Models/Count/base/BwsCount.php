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
class BwsCount extends Model{
    //put your code here
    public $table = "bws_count";
    public $timestamps =FALSE;
//    1绑定2邀请3 10局数4 20局数5 充值 6购买 7  -- 1场  8 -2场 9---3场
    public $ref_arr = ["1"=>"绑定","2"=>"邀请","3"=>"10局数","4"=>"20局数","5"=>"充值赠送","6"=>"购买","7"=>"第一场","8"=>"第二场","9"=>"第三场"];
}
