<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Server;
use App\Models\base\DrawGoods;
/**
 * Description of DrawChane
 *
 * @author 七彩P1
 */
class DrawChane {
    //put your code here
    public  $game = 1;
    
    
    public function __construct($game=1) {
       $this->game = $game;
    }
   
   
    public function __get($propertyName)
    {  
       $active = $propertyName;
       $ret = array();
       $drawGoodsObj = (new DrawChane)->where("game",$this->game)->where("active",$active)->where("is_delete",0)->get();
       if($drawGoodsObj){
           $ret = $drawGoodsObj->toArray();
       }
       return $ret;
    }
}
