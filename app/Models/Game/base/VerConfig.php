<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Game\base;
use App\Models\Model;
/**
 * Description of VerConfig
 *
 * @author 七彩P1
 */
class VerConfig extends Model{
    //put your code here
    public  $table="ver_config";
    public  $connection="mysql_two";
    
    public  $typeArr = [0=>"不需强制",1=>"强制更新"];
    public  $uptTypeArr = [0=>"否",1=>"是"];
    public $timestamps = false;
}
