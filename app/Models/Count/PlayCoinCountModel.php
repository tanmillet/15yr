<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Count;
use App\Models\Count\base\PlayCoinCount;
use App\Models\Game\base\QclandlordWinlog;
use App\Models\Game\base\QclandlordTablelog;
use App\Models\Game\base\CoinLog;
use App\Models\Game\base\userInfo;
use Illuminate\Support\Facades\DB;
use App\Models\Game\base\CloseRoomLog;
/**
 * Description of PlayCoinCountModel
 *
 * @author 七彩P1
 */
class PlayCoinCountModel extends PlayCoinCount{
    //put your code here
    
    public function arstianCount(){
        $sdate = date("Y-m-d",strtotime("-1day"));
        $stime = strtotime($sdate." 00:00:00");
        $ftime = strtotime($sdate." 23:59:59");
        $date = date("Ymd", strtotime("-1day"));
        $qlw = new QclandlordWinlog;
        $qTl = new QclandlordTablelog;
        $showgametypeArr = $this->showgametype;
        $room_type_arr = $this->room_type_arr;
        
        $coinLog = new CoinLog();
        $coinLog1 = $coinLog->setTable($coinLog->table . $date);
        $userInfo = new userInfo;
        
        $crl = new CloseRoomLog;
        foreach(config("game.game") as $vgame){
            $game = $vgame["value"];
            if(!isset($showgametypeArr[$game])){
                continue;
            }
            foreach($showgametypeArr[$game] as $play_type=>$value){
                list($gameid,$gametype) = explode("-", $value['key']);
                foreach($room_type_arr as $room_type=>$x){
                    $insert  =array();
                    $basecode = $room_type * 10 ;
                    $playCodeArr  = [$basecode+2,$basecode+3];
                    $countObj = $coinLog1->select(
                            DB::raw("count(DISTINCT uid) as renshu,game,gameid,gametype,code,sum(if(changecoin>0,changecoin,0)) as win_money,sum(if(changecoin<0,changecoin,0)) as lose_money")
                            )->where("game",$game)->where("gameid",$gameid)->where("gametype",$gametype)->where("uid",">=",$userInfo->aiUserId)
                            ->whereIn("code",$playCodeArr)->first();//获取打牌的信息
                    
                    $insert[0]["room_type"] = $room_type;
                    $insert[0]["play_type"] = $play_type;
                    $insert[0]["date"] = $date;
                    $insert[0]["num"] = (string)($countObj->renshu?$countObj->renshu:0);
                    $insert[0]["game"] = $game;
                    $insert[0]["count_type"] = 1;//人数
                    if(isset($value["room_id"]) && isset($value["room_id"][$room_type])){
                        $playObj = $qlw->select(DB::raw("count(DISTINCT tlid) as  playnum"))->where("wltime",">=",$stime)->where("wltime","<=",$ftime)->where("game",$game)
                            ->where("gameid",$gameid)->where("uid",">=",$userInfo->aiUserId)->where("gametype",$gametype)->where("room_id",$value["room_id"][$room_type])->first();
                        $playnum = $playObj->playnum?$playObj->playnum:0;
                        
                        $crlObj = $crl->select(DB::raw("count(id) as  hezhuonum"))->where("create_time",">=",$stime)->where("create_time","<=",$ftime)->where("game",$game)
                            ->where("gameid",$gameid)->where("uid",">=",$userInfo->aiUserId)->where("gametype",$gametype)->where("room_id",$value["room_id"][$room_type])->first();
                        $hezhuonum = $crlObj->hezhuonum?$crlObj->hezhuonum:0;
                    }else{
                        $hezhuonum = $playnum = 0;
                    }
                    
                    $insert[1]["room_type"] = $room_type;
                    $insert[1]["play_type"] = $play_type;
                    $insert[1]["date"] = $date;
                    $insert[1]["num"] = $playnum;
                    $insert[1]["game"] = $game;
                    $insert[1]["count_type"] = 2;//局数
                    
                    $insert[2]["room_type"] = $room_type;
                    $insert[2]["play_type"] = $play_type;
                    $insert[2]["date"] = $date;
                    $insert[2]["num"] = (string)($countObj->lose_money?$countObj->lose_money:0);
                    $insert[2]["game"] = $game;
                    $insert[2]["count_type"] = 3;//玩家累计输金币
                    
                    $insert[3]["room_type"] = $room_type;
                    $insert[3]["play_type"] = $play_type;
                    $insert[3]["date"] = $date;
                    $insert[3]["num"] =(string)($countObj->win_money?$countObj->win_money:0);
                    $insert[3]["game"] = $game;
                    $insert[3]["count_type"] = 4;//玩家累计赢金币
                    
                    $robot_money = $insert[2]["num"] + $insert[3]["num"]  ;//机器人赢得金币
                    $insert[2]["num"] = abs($insert[2]["num"]);//玩家累计输金币 要是整数
                    
                    $insert[4]["room_type"] = $room_type;
                    $insert[4]["play_type"] = $play_type;
                    $insert[4]["date"] = $date;
                    $insert[4]["num"] = (string)(0 -$robot_money);
                    $insert[4]["game"] = $game;
                    $insert[4]["count_type"] = 5;//机器人陪打赢
                    
                    $serverCode = $basecode+1;
                    $countObj2 = $coinLog1->select(
                            DB::raw("sum(changecoin) as money")
                            )->where("game",$game)->where("gameid",$gameid)->where("gametype",$gametype)
                            ->where("code",$serverCode)->first();//获取打牌的信息
                    $insert[5]["room_type"] = $room_type;
                    $insert[5]["play_type"] = $play_type;
                    $insert[5]["date"] = $date;
                    $insert[5]["num"] =(string) ($countObj2->money?0-$countObj2->money :0);
                    $insert[5]["game"] = $game;
                    $insert[5]["count_type"] = 6;//服务费
                    
                    
                    $insert[6]["room_type"] = $room_type;
                    $insert[6]["play_type"] = $play_type;
                    $insert[6]["date"] = $date;
                    $insert[6]["num"] =(string) ($hezhuonum);
                    $insert[6]["game"] = $game;
                    $insert[6]["count_type"] = 7;//合桌次数
                    
                    if(!empty($insert)){
                        $this->insert($insert);
                    }
                }
                
            }
            
        }
        
        
    }
}
