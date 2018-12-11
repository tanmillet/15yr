<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Game\base;
use App\Models\Model;
/**
 * Description of RealOrder
 *
 * @author 七彩P1
 */
class RealOrder extends Model{
    //put your code here
    public $table ="real_order";
    public  $connection="mysql_two";
    public $status_arr = ["0"=>"未填写","1"=>"地址已提交","2"=>"已发货","3"=>"发货完成"];
    public $fast_type_arr = ["1"=>"顺风","2"=>"圆通","3"=>"中通"];
}
