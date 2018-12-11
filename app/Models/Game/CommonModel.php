<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Game;
use App\Models\Game\base\UseGoodsLog;
use App\Models\Game\base\ServerGoods;
use App\Models\Game\base\MacthRoomCfg;
use App\Models\Game\base\Active;
/**
 * Description of CommonModel
 *
 * @author 七彩P1
 */
class CommonModel {
    //put your code here
    public function getGoodsRefName($ref,$search_ref,$search_ref_sid,$game){
        $useGoodsLog = new UseGoodsLog;
        if(!isset($useGoodsLog->use_ref_arr[$ref])){
            return $ref;
        }
        
        $ret_name = $ref_name = $useGoodsLog->use_ref_arr[$ref];
        
        switch ($ref) {
            case 1: //server操作
                $ret_name = "服务操作";  
                if($search_ref_sid){
                    $server_goodsObj = (new ServerGoods)->where("id",$search_ref_sid)->first();
                    if(!$server_goodsObj){
                        break;
                    }
                    $server_goods = $server_goodsObj->toArray();
                    if($server_goods["type"]==1){//比赛场名次奖励
                        $match_room_cfg =(new MacthRoomCfg)->where("id",$server_goods['type_id'])->first();
                        $ret_name .="-".$match_room_cfg->match_name ."-排名第".$server_goods["sgoods_sid_type"];
                    }elseif($server_goods["type"]==2){//比赛场门票
                        $match_room_cfg = (new MacthRoomCfg)->where("id",$server_goods['type_id'])->first();
                        $ret_name .="-".$match_room_cfg->match_name;
                    }
                } elseif($search_ref) {
                    $match_room_cfg =(new MacthRoomCfg)->where("id",$search_ref)->first();
                    $ret_name .="-".$match_room_cfg->match_name;
                }
            break;
            case 2: 
                $ret_name =$ref_name ; 
            break;
            case 3://订单使用
                $ret_name = "订单使用"; 
            break;
            case 4: //定时使用道具
                $ret_name = "定时使用道具"; 
            break;
            case 5: //活动来源
                $ret_name = "活动来源"; 
                $active =(new Active())->where("active_type",$search_ref)->where("game",$game)->first();
                $ret_name .="-".$active->active_name ."-活动等级第".$search_ref_sid;
            break;
            case 6://任务使用
                $ret_name = "日常任务"; 
            break;
            case 7: //抽奖使用
                $ret_name = "抽奖使用"; 
            break;
            case 8: //用户在背包中使用
                $ret_name = "用户在背包中使用"; 
            break;
            case 9://充值赠送
               $ret_name = "充值赠送"; 
            break;
            default: 
                $ret_name =$ref_name ;
            break;
        }
        return $ret_name;
    }
    
    
    public function getDate($stime,$ftime){
        $min_time = $stime>$ftime?$ftime:$stime;
        $max_time = $stime>$ftime?$stime:$ftime;
        
        $min_date = date("Y-m-d 00:00:00",$min_time);
        $max_date = date("Y-m-d 00:00:00",$max_time);
        $data = array();
        if($min_date !=$max_date){
            $for_min_time = strtotime($min_date);
            $for_max_time = strtotime($max_date);
            for($i=$for_min_time;$i<=$for_max_time;$i+=24*3600){
                $data[] = date("Ymd",$i);
            }
        }else{
            $data = array(date("Ymd", strtotime($min_date)));
        }
        
        return $data;
    }
	
	    //获取牌局信息
    public function getCardInfo($uid,$gameid,$tllog){
        $tllogArr = json_decode($tllog,true);
        $ret= array();
        switch($gameid){
            case 3: //中发白
                //$ret_name =$ref_name ; 
                $retCard = $userPlayBet = array();
                $playArr = ["1"=>"中","2"=>"发","3"=>"白","4"=>"庄"];
                $card = config("game.card.".$gameid);
                foreach($tllogArr['playerBet'] as $playerBet){
                    if($playerBet["playerid"] == $uid){
                        isset($playerBet[1]) && $userPlayBet["中"] = $playerBet[1];
                        isset($playerBet[2]) && $userPlayBet["发"] = $playerBet[2];
                        isset($playerBet[3]) && $userPlayBet["白"] = $playerBet[3];
                        isset($playerBet[4]) && $userPlayBet["庄"] = $playerBet[4];
                    }
                }
                
                foreach($tllogArr["cards"] as $ke=>$cardContens){
                    $tllogArr["cards"][$ke] = isset($card[$cardContens])?$card[$cardContens]:$cardContens;
                }
                $cardArr = array_chunk($tllogArr["cards"],2);
                foreach($cardArr as $ke=>$cardContens){
                    $retCard[$playArr[$ke+1]] = join(",", $cardContens);
                }
                $ret["playerBet"] = $userPlayBet;
                $ret["retCard"] = $retCard;
                $ret["zhuangPlayerId"] = isset($tllogArr['zhuangPlayerId'])?$tllogArr['zhuangPlayerId']:0;
            break; 
            default: 
                $retStr =$tllog;
            break;
        }
        return $ret;
    }
}
