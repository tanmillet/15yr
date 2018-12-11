<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Game\base;
use App\Models\Model;
/**
 * Description of GoodsPrice
 *
 * @author 七彩P1
 */
class GoodsPrice  extends Model{
    //put your code here
    public $table ="goods_price";
    public  $connection="mysql_two"; 
    public $buy_type = ["1" => "人民币", "2" => "游戏币", "3" => "钻石", "4" => "彩券","5"=>"AppStore"];
}
