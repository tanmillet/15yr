<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of OnlineCountModel
 *
 * @author 七彩P1
 */
namespace App\Models\Count;
use App\Models\Count\base\OnlineCount;
use App\Models\Game\base\Online;
class OnlineCountModel {
    //put your code here
    
    public function arstianCount(){
        $baseOnline = new Online();
        $baseOnlineCount = new OnlineCount();
        $date = date("Ymd");
        $hour = date("Hi");
        foreach(config("game.game") as $vgame){
            $game = $vgame['value'];
            $where['game'] = $game;
            $onlineObj = $baseOnline->where($where)->get();
            $data = array();
            foreach($onlineObj as $online){
                if(!isset($data[$online->pfid][$online->usid])){
                    $data[$online->pfid][$online->usid]["time_num"] = 0;
                    $data[$online->pfid][$online->usid]["user_num"] = 0;
                }
                $data[$online->pfid][$online->usid]["time_num"] += $online->olsumtime;
                $data[$online->pfid][$online->usid]["user_num"] ++;
            }
            foreach($data as $pfid=>$dat){
                foreach($dat as $usid=>$da){
                    $insert["game"] = $game;
                    $insert["pfid"] = $pfid;
                    $insert["usid"] = $usid;
                    $insert["time_num"] = $da['time_num'];
                    $insert["user_num"] = $da['user_num'];
                    $insert["date"] = $date;
                    $insert["hour_min"] = $hour;
                    $baseOnlineCount->insert($insert);
                }
            }
        }
    }
    
    
    
    public function nowCount($game){
        $baseOnline = new Online();
        $date = date("Ymd");
        $hour = date("Hi");
        $where['game'] = $game;
        $onlineObj = $baseOnline->where($where)->get();
        $data = array();
        foreach($onlineObj as $online){
            if(!isset($data[$online->pfid][$online->usid])){
                $data[$online->pfid][$online->usid]["time_num"] = 0;
                $data[$online->pfid][$online->usid]["user_num"] = 0;
            }
            $data[$online->pfid][$online->usid]["time_num"] += $online->olsumtime;
            $data[$online->pfid][$online->usid]["user_num"] ++;
        }
        return $data;
    }
}
