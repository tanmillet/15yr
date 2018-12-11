<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Game\base;
use App\Models\Model;
/**
 * Description of OrderGoodsphp
 *
 * @author 七彩P1
 */
class OrderGoods extends Model{
    //put your code here
    
    public $table ="order_goods";
    public  $connection="mysql_two";
    public $send_status_arr = [
                    "0"=>"没有发送",//没有发送
                    "1"=>"发送中",//发送中
                    "2"=>"发送成功",//发送成功
                    "3"=>"发送失败",//发送失败
            ];
}
