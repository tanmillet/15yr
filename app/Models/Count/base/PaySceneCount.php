<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Count\base;
use App\Models\Model;
/**
 * Description of PayProfileCount
 *
 * @author 七彩P1
 */
class PaySceneCount extends Model{
    public $table = "pay_scene";
    public $timestamps =FALSE;
    
    public $count_type_arr = [3=>"天",6=>"周",4=>"月",5=>"年"];
    public $data_type_arr = ["pay_num"=>"支付数量","pay_pop_num"=>"支付人数","order_num"=>"订单数量"];
    
    public $type_arr =  [
        "1" =>"普通新手",
        "2" =>"普通初级",
        "3" =>"普通中级",
        "4" =>"普通高级",
        "101" =>"癞子新手",
        "102" =>"癞子初级",
        "103" =>"癞子中级",
        "104" =>"癞子高级",
        "999" =>"破产大厅",
    ];
}
