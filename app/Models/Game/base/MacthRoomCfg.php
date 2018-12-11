<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Game\base;
use App\Models\Model;
/**
 * Description of MacthRoomCfg
 *
 * @author 七彩P1
 */
class MacthRoomCfg extends Model{
    //put your code here
    public $table ="match_room_cfg";
    public  $connection="mysql_two";
    
    
         //获取宾王赛的 id
    public function getBwsRoomId($game){
        $mrfArr = $this->where(array("matchtype"=>"2"))->whereIn("submatch",array(6,7,8))->pluck("match_name","id");
        if(!$mrfArr){
            return FALSE;
        }
        $mrfArr = $mrfArr->toArray();
        return $mrfArr;
    }
    
    
             //获取中秋赛的 id
    public function getZqsRoomId($game){
        $mrfArr = $this->where(array("matchtype"=>"2"))->whereIn("submatch",array(9,10,11,12))->pluck("match_name","id");
        if(!$mrfArr){
            return FALSE;
        }
        $mrfArr = $mrfArr->toArray();
        return $mrfArr;
    }
}
