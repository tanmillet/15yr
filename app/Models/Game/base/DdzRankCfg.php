<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Game\base;
use App\Models\Model;
/**
 * Description of DdzRankCfg
 *
 * @author 七彩P1
 */
class DdzRankCfg  extends Model{
    //put your code here
        public $table ="ddz_rank_cfg";
        public  $connection="mysql_two";
    public $groupArr = [
        "1"=>"青铜",
        "2"=>"白银",
        "3"=>"黄金",
        "4"=>"铂金",
        "5"=>"钻石",
        "6"=>"大师",
        "7"=>"王者",
        ];
    
    public $orderArr = [
        "1"=>"一阶",
        "2"=>"二阶",
        "3"=>"三阶",
        "4"=>"四阶",
        "5"=>"五阶",
        ];
}
