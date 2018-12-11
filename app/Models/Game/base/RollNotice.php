<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Game\base;
use App\Models\Model;
/**
 * Description of RollNotice
 *
 * @author 七彩P1
 */
class RollNotice extends Model{
    //put your code here
    public $table="roll_notice";
    public  $connection="mysql_two";
    
    public $playTypeArr = ["1"=>"无限循环","2"=>"显示循环"];
    public $statusArr = ["1"=>"发布","2"=>"未发布"];
}
