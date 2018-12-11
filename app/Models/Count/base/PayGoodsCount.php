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
class PayGoodsCount extends Model{
    public $table = "pay_goods_count";
    public $timestamps =FALSE;
    
    public $count_type_arr = [3=>"天",4=>"月",6=>"周"];
    public $data_type_arr = ["new_pay_pop_num"=>"新增用户人数","pay_num"=>"支付数量","pay_pop_num"=>"支付人数","pay_price"=>"金额"];
}
