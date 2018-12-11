<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Count;
use App\Models\Count\base\MoneyCountInfo;
use App\Models\Game\base\CoinLog;
use App\Models\Game\base\userInfo;
use \Illuminate\Support\Facades\DB;
 
use App\Models\Game\base\UserDayLog;
use App\Models\Game\base\userGame;
use App\Models\Game\MoneyModel;
use App\Models\Game\base\StrongBox;
/**
 * Description of MoneyCountModel
 *
 * @author 七彩P1
 */
class MoneyCountModel {
    //put your code here
    public function arstianCount(){
        $date = date("Ymd", strtotime("-1day"));
        $futime = strtotime(date("Y-m-d 00:00:00")); 
        $sutime = $futime -24*3600; 
        $userInfo = new userInfo();
        $userGame = new userGame();
        
        $coinLog  = new CoinLog($date);
        $moneyCountInfo = new MoneyCountInfo();
        $uiTable = $userInfo->getTable();
        $ugTable = $userGame->getTable();
        
        $userDayLog = new UserDayLog();
        $udTable = $userDayLog->getTable();
        
        $moneyModel =  new MoneyModel();
        $strongBox = new StrongBox();
        $sbTable = $strongBox->getTable();
        foreach(config("game.game") as $game_value){
            $vgame = $game_value['value'];
            //获取机器人当天变动金币
            //["1"=>"金币总发放","2"=>"金币总消耗","3"=>"金币净消耗","4"=>"业务发放","5"=>"业务消耗",
                        //"6"=>"业务净消耗","7"=>"机器人净赢","8"=>"人均金币数","9"=>"金币总量","10"=>"活跃金币总量","11"=>"活跃用户人均金币"]
            //获取保险箱里面的金币
            $strongBoxCount =$userInfo->leftJoin( $sbTable,$uiTable.".uid","=",$sbTable.".uid")->where(DB::raw("{$uiTable}.game"),"=",$vgame)->where(DB::raw("{$uiTable}.uid"),">",$userInfo->aiUserId)->select(DB::raw(" sum(uchip) as uchip_all "))->first();
            if($strongBoxCount){
                $strongBoxCount = $strongBoxCount->toArray();
                $sbUchip = isset($strongBoxCount['uchip_all'])?$strongBoxCount['uchip_all']:0;
            }else{
                $sbUchip = 0;
            }
            //获取用户所以金币 加上保险箱
            $moneyAllCountObj = $userGame->leftJoin( $uiTable ,$uiTable.".uid","=",$ugTable.".uid")->where("game","=",$vgame)
                    ->select(DB::raw(" sum(uchip) as uchip_all ,sum(utombola) as utombola_all,sum(udiamond) as udiamond_all,sum(room_ticket) as room_ticket_all  "))->get();
            $moneyAllCountObj = $moneyAllCountObj->toArray();
            foreach($moneyAllCountObj as $moneyAllCount){
                $insert['game']  = $vgame;
                $insert['date']  = $date;
                $insert['code']  = -1;
                $insert['type']  = 9;
                foreach($moneyModel->type as $money_type=>$value){
                    //$insert['money_count']  = $moneyAllCount[$money_type];
                    $insert['money_count'] = (string)($value == 1? ($moneyAllCount[$money_type."_all"]+$sbUchip):$moneyAllCount[$money_type."_all"]);
                    $insert['money_type']  = $value;
                    $moneyCountInfo->insert($insert); 
                }
            }
            //获取用户人均金币 不包含机器人  加上保险箱
            $moneyAllCountObj1 = $userGame->leftJoin($uiTable,$uiTable.".uid","=",$ugTable.".uid")->where("game","=",$vgame)->where( DB::raw("{$uiTable}.uid"),">",$userInfo->aiUserId)->select(DB::raw("  sum(uchip) as uchip_all,sum(utombola) as utombola_all,sum(udiamond) as udiamond_all,sum(room_ticket) as room_ticket_all,count({$uiTable}.uid) as pnum "))->get();
            $moneyAllCountObj1 = $moneyAllCountObj1->toArray();
            foreach($moneyAllCountObj1 as $moneyAllCount){
                $insert['game']  = $vgame;
                $insert['date']  = $date;
                $insert['code']  = -1;
                $insert['type']  = 8;
                foreach($moneyModel->type as $money_type=>$value){
                    $money_type = $money_type."_all";
                    $moneyAllCount[$money_type] = $value == 1? ($moneyAllCount[$money_type]+$sbUchip):$moneyAllCount[$money_type];
                    $insert['money_count']  = (string)($moneyAllCount['pnum']?floor($moneyAllCount[$money_type] /  $moneyAllCount['pnum']):0);
                    $insert['money_type']  = $value;
                    $moneyCountInfo->insert($insert); 
                }
            }
            //获取活越用户金币  加上保险箱
            $hyMoneyAllCountObj = $userGame->leftJoin( $uiTable ,$uiTable.".uid","=",$ugTable.".uid")
                                            ->leftJoin( $sbTable,$uiTable.".uid","=",$sbTable.".uid")
                                            ->where("game","=",$vgame)->where("utime",">=",$sutime)->where("utime","<",$futime)
                                            ->where("urtime","<",$sutime)
                                            ->select(DB::raw(" sum({$ugTable}.uchip+ IFNULL({$sbTable}.uchip,0)) as uchip_all ,sum(utombola) as utombola_all,sum(udiamond) as udiamond_all,sum(room_ticket) as room_ticket_all,count({$uiTable}.uid) as pnum   "))->get();
            $hyMoneyAllCountObj = $hyMoneyAllCountObj->toArray();
            
            foreach($hyMoneyAllCountObj as $hyMoneyAllCount){
                $insert['game']  = $vgame;
                $insert['date']  = $date;
                $insert['code']  = -1;
                $insert['type']  = 10;
                
                $insert1['game']  = $vgame;
                $insert1['date']  = $date;
                $insert1['code']  = -1;
                $insert1['type']  = 11;
                foreach($moneyModel->type as $money_type=>$value){
                    $insert['money_count']  = $hyMoneyAllCount[$money_type."_all"] ;
                    $insert['money_type']  = $value;
                    $moneyCountInfo->insert($insert); 
                    
                    $insert1['money_count']  = (string)($hyMoneyAllCount['pnum']?(floor($insert['money_count'] /  $hyMoneyAllCount['pnum'])):0);
                    $insert1['money_type']  = $value;
                    $moneyCountInfo->insert($insert1); 
                }
            }
            
                        //获取当天注册用户人均数
            $hyMoneyAllCountObj = $userGame->leftJoin( $uiTable ,$uiTable.".uid","=",$ugTable.".uid")
                                            ->leftJoin( $sbTable,$uiTable.".uid","=",$sbTable.".uid")
                                            ->where("urtime",">=",$sutime)->where("urtime","<",$futime)
                                            ->select(DB::raw(" sum({$ugTable}.uchip+ IFNULL({$sbTable}.uchip,0)) as uchip_all ,sum(utombola) as utombola_all,sum(udiamond) as udiamond_all,sum(room_ticket) as room_ticket_all,count({$uiTable}.uid) as pnum   "))->get();
            $hyMoneyAllCountObj = $hyMoneyAllCountObj->toArray();
            
            foreach($hyMoneyAllCountObj as $hyMoneyAllCount){
                $insert['game']  = $vgame;
                $insert['date']  = $date;
                $insert['code']  = -1;
                $insert['type']  = 12;
                
                $insert1['game']  = $vgame;
                $insert1['date']  = $date;
                $insert1['code']  = -1;
                $insert1['type']  = 13;
                foreach($moneyModel->type as $money_type=>$value){
                    $insert['money_count']  = $hyMoneyAllCount[$money_type."_all"] ;
                    $insert['money_type']  = $value;
                    $moneyCountInfo->insert($insert); 
                    
                    $insert1['money_count']  =(string)( $hyMoneyAllCount['pnum']?floor($insert['money_count'] /  $hyMoneyAllCount['pnum']):0);
                    $insert1['money_type']  = $value;
                    $moneyCountInfo->insert($insert1); 
                }
            }
            
            //获取机器人打牌变动的金币  
            /*$AICountObj =$userInfo->leftJoin( $udTable,$uiTable.".uid","=",$udTable.".uid")->where(DB::raw("{$uiTable}.game"),"=",$vgame)
                        ->where(DB::raw("{$uiTable}.uid"),"<=",$userInfo->aiUserId)->select(DB::raw(" sum(card_money) as card_money_all "))->get();
            $AICountObj = $AICountObj->toArray();
            foreach($AICountObj as $AICount){
                $insert['game']  = $vgame;
                $insert['date']  = $date;
                $insert['code']  = -1;
                $insert['type']  = 7;
                $insert['money_count']  = $AICount['card_money_all']?$AICount['card_money_all']:0 ;
                $insert['money_type']  = 1;
                $moneyCountInfo->insert($insert); 
                //foreach($moneyModel->type as $money_type=>$value){
                    
                //}
            }*/
            
            //获取业务发放总数 跟消耗数
            
        }
    }
}
