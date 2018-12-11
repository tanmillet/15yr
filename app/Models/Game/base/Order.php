<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Game\base;
use App\Models\Model;
/**
 * Description of Order
 *
 * @author 七彩P1
 */
class Order extends Model{
    public $table="order";
    public  $connection="mysql_two";
    public  $order_status_arr = ["submit"=>"1","paying"=>"2","payed"=>"3","finish"=>"4","off"=>"5","sending"=>"6"];
    public  $order_status_name_arr = ["submit"=>"提交中","paying"=>"下单完成","payed"=>"支付完成","finish"=>"发货完成","off"=>"订单取消","sending"=>"发货中"];
    public  $obuy_ref_arr = ["1"=>"微信支付","2"=>"支付宝支付","3"=>"游戏内支付","4"=>"IOS","5"=>"三9","6"=>"应用宝","7"=>"vivo","8"=>"opopo","9"=>"微信公众号","10"=>"华为"];
    public  $obuy_type_arr = ["1"=>"人民币购买","2"=>"游戏B","3"=>"钻石","4"=>"彩券","5"=>"AppStore"];
    //游戏订单
    public  $game_buy_ref = ["3"];
    public  $pay_buy_ref =  ["1","2","4","5","6","7","8","9","10"];//支付订单
}
